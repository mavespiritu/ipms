<?php

namespace common\modules\dtr\controllers;

use Yii;
use common\modules\dtr\models\Dtr;
use common\modules\dtr\models\EmployeeDtrType;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\db\Transaction;

/**
 * FwaController implements the CRUD actions for Dtr model.
 */
class FwaController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index'],
                'rules' => [
                    [
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => ['fwa-index'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Dtr models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dtrType = EmployeeDtrType::findOne([
            'emp_id' => Yii::$app->user->identity->userinfo->EMP_N,
            'date' => date("Y-m-d"),
        ]) ? EmployeeDtrType::findOne([
            'emp_id' => Yii::$app->user->identity->userinfo->EMP_N,
            'date' => date("Y-m-d"),
        ]) : new EmployeeDtrType();

        $dtrType->emp_id = Yii::$app->user->identity->userinfo->EMP_N;
        $dtrType->date = date("Y-m-d");
        $dtrType->dtr_id = 'FWA';

        $am = Dtr::findOne([
                'emp_id' => Yii::$app->user->identity->userinfo->EMP_N,
                'date' => date("Y-m-d"),
                'time' => 'AM',
                'year' => date('Y'),
                'month' => date('F'),
            ]) ? Dtr::findOne([
                'emp_id' => Yii::$app->user->identity->userinfo->EMP_N,
                'date' => date("Y-m-d"),
                'time' => 'AM',
                'year' => date('Y'),
                'month' => date('F'),
            ]) : new Dtr();
        
        $am->emp_id = Yii::$app->user->identity->userinfo->EMP_N;
        $am->date = date("Y-m-d");
        $am->time = 'AM';
        $am->year = date('Y');
        $am->month = date('F');

        $pm = Dtr::findOne([
            'emp_id' => Yii::$app->user->identity->userinfo->EMP_N,
            'date' => date("Y-m-d"),
            'time' => 'PM',
            'year' => date('Y'),
            'month' => date('F'),
        ]) ? Dtr::findOne([
            'emp_id' => Yii::$app->user->identity->userinfo->EMP_N,
            'date' => date("Y-m-d"),
            'time' => 'PM',
            'year' => date('Y'),
            'month' => date('F'),
        ]) : new Dtr();
    
        $pm->emp_id = Yii::$app->user->identity->userinfo->EMP_N;
        $pm->date = date("Y-m-d");
        $pm->time = 'PM';
        $pm->year = date('Y');
        $pm->month = date('F');

        if(Yii::$app->request->post()){
            $transaction = Yii::$app->ipms->beginTransaction(Transaction::READ_COMMITTED);
                if($am->isNewRecord){
                    $am->time_in = date("Y-m-d H:i:s");
                    $am->time_out = '0001-01-01 00:00:00';
                    if($am->save(false)){
                        $dtrType->am_in = date("g:i:s A", strtotime($am->time_in));
                        $dtrType->save(false);
                        $transaction->commit();
                    }
                }else{
                    if($am->time_in != '0001-01-01 00:00:00' && $am->time_out != '0001-01-01 00:00:00'){
                        if($pm->isNewRecord){
                            $pm->time_in = date("Y-m-d H:i:s");
                            $pm->time_out = '0001-01-01 00:00:00';
                            if($pm->save(false)){
                                $dtrType->pm_in = date("g:i:s A", strtotime($pm->time_in));
                                $dtrType->save(false);
                                $transaction->commit();
                            }
                        }else{
                            if($pm->time_out == '0001-01-01 00:00:00')
                            {
                                $pm->time_out = date("Y-m-d H:i:s");
                                if($pm->save(false)){
                                    
                                    $diff = strtotime($pm->time_out) - strtotime($am->time_in);
                                    $diff -= 3600; // for 1 hour lunch break
                                    $hours = floor($diff/(60*60));
                                    $minutes = floor(($diff-($hours*60*60))/60);
                                    $seconds = floor($diff-($hours*60*60)-($minutes*60));

                                    $dtrType->pm_out = date("g:i:s A", strtotime($pm->time_out));
                                    $dtrType->total_with_out_pass_slip = sprintf("%02d",$hours) . ":" . sprintf("%02d",$minutes) . ":" . sprintf("%02d",$seconds);
                                    $dtrType->total_with_pass_slip = sprintf("%02d",$hours) . ":" . sprintf("%02d",$minutes) . ":" . sprintf("%02d",$seconds);
                                    $dtrType->save(false);

                                    $transaction->commit();
                                }
                            }
                        }
                    }else{
                        $am->time_out = date("Y-m-d H:i:s");
                        if($am->save(false)){
                            $dtrType->am_out = date("g:i:s A", strtotime($am->time_out));
                            $dtrType->save(false);

                            $transaction->commit();
                        }
                    }
                }
            try{

            }catch (\Exception $e) {
                $transaction->rollBack();
                Yii::$app->session->setFlash('error', 'An error occurred.');
                Yii::error($e->getMessage());
            }
        }

        return $this->render('index', [
            'am' => $am,
            'pm' => $pm,
        ]);
    }

    public function actionAmIn()
    {
        $dtr = Dtr::findOne([
            'emp_id' => Yii::$app->user->identity->userinfo->EMP_N,
            'date' => date("Y-m-d"),
            'time' => 'AM',
            'year' => date('Y'),
            'month' => date('F'),
        ]);

        return $dtr ? date("H:i:s", strtotime($dtr->time_in)) != '00:00:00' ? date("h:i:s A", strtotime($dtr->time_in)) : '' : '';
    }

    public function actionAmOut()
    {
        $dtr = Dtr::findOne([
            'emp_id' => Yii::$app->user->identity->userinfo->EMP_N,
            'date' => date("Y-m-d"),
            'time' => 'AM',
            'year' => date('Y'),
            'month' => date('F'),
        ]);

        return $dtr ? date("H:i:s", strtotime($dtr->time_out)) != '00:00:00' ? date("h:i:s A", strtotime($dtr->time_out)) : '' : '';
    }

    public function actionPmIn()
    {
        $dtr = Dtr::findOne([
            'emp_id' => Yii::$app->user->identity->userinfo->EMP_N,
            'date' => date("Y-m-d"),
            'time' => 'PM',
            'year' => date('Y'),
            'month' => date('F'),
        ]);

        return $dtr ? date("H:i:s", strtotime($dtr->time_in)) != '00:00:00' ? date("h:i:s A", strtotime($dtr->time_in)) : '' : '';
    }

    public function actionPmOut()
    {
        $dtr = Dtr::findOne([
            'emp_id' => Yii::$app->user->identity->userinfo->EMP_N,
            'date' => date("Y-m-d"),
            'time' => 'PM',
            'year' => date('Y'),
            'month' => date('F'),
        ]);

        return $dtr ? date("H:i:s", strtotime($dtr->time_out)) != '00:00:00' ? date("h:i:s A", strtotime($dtr->time_out)) : '' : '';
    }
}
