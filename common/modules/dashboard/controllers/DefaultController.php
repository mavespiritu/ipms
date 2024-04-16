<?php

namespace common\modules\dashboard\controllers;

use Yii;
use common\modules\dtr\models\Dtr;
use common\modules\dtr\models\EmployeeDtrType;
use common\models\Employee;
use common\models\Holiday;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\db\Transaction;
use yii\db\Expression;
use yii\db\Query;
/**
 * Default controller for the `dashboard` module
 */
class DefaultController extends Controller
{
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
                'rules' => [
                    [
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        $am = Dtr::findOne([
                'emp_id' => Yii::$app->user->identity->userinfo->EMP_N,
                'date' => date("Y-m-d"),
                'time' => 'AM',
                'year' => date('Y'),
                'month' => date('F'),
            ]);
        
        $pm = Dtr::findOne([
            'emp_id' => Yii::$app->user->identity->userinfo->EMP_N,
            'date' => date("Y-m-d"),
            'time' => 'PM',
            'year' => date('Y'),
            'month' => date('F'),
        ]);
        
        $celebrants = Employee::find()
                    ->where([
                        'MONTH(birth_date)' => date("m"),
                        'work_status' => 'Active'
                    ])
                    ->orderBy(['DAY(birth_date)' => SORT_ASC])
                    ->all();

        $holidays = Holiday::find()
                    ->where(['>=', new Expression('MONTH(holiday_date)'), date('m')])
                    ->orderBy(['MONTH(holiday_date)' => SORT_ASC, 'DAY(holiday_date)' => SORT_ASC])
                    ->all();

        $connection = Yii::$app->ipms;

        $params = [
            ':emp_id' => Yii::$app->user->identity->userinfo->EMP_N,
            ':date' => date("Y-m-d"),
        ];

        $weeklyDtrSql = "
            select emp_id, dtr_id, date,
            DATE_FORMAT(date, '%W') as days,
            total_with_out_pass_slip,
            total_with_pass_slip,
            total_tardy,
            total_UT,
            total_pass_slip,
            am_in,
            am_out,
            pm_in,
            pm_out,
            total_OT,
            multiplied_total_OT,
            ((TIME_TO_SEC(total_with_pass_slip) / 60)/60) as total_with_pass_slip_dbl
            from tblemp_dtr_type
            where emp_id = :emp_id
            and date >=(
            select distinct(week_lower_limit) from tblweekly_dtr_summary
            where :date between week_lower_limit and DATE_ADD(week_upper_limit,INTERVAL 2 DAY))
            and date <=(
            select distinct(DATE_ADD(week_upper_limit,INTERVAL 2 DAY)) from tblweekly_dtr_summary
            where :date between week_lower_limit and DATE_ADD(week_upper_limit,INTERVAL 2 DAY))
            order by date
        ";

        $weeklyDtrs = $connection->createCommand($weeklyDtrSql)
            ->bindValues($params)
            ->queryAll();

        $total = 0;

        $hrsToRendersql = "select distinct(total_hours) as total_hours
                                from tblweekly_dtr_summary
                                where week_lower_limit <= :date and week_upper_limit >= :date";

        $hrsToRender = $connection->createCommand($hrsToRendersql)
            ->bindValues($params)
            ->queryAll();

        $hrsToRenderInHrsSql = "select SEC_TO_TIME('".(($hrsToRender[0]['total_hours'] * 60)*60)."') as total";
        $hrsToRenderInHrsQuery = $connection->createCommand($hrsToRenderInHrsSql)
            ->queryAll();

        $hrsToRenderInHrs = $hrsToRenderInHrsQuery[0]['total'];

        $hrsToGo = $hrsToRender[0]['total_hours'];
        $totalInHrs = "00:00:00";
        $hrsToGoInHrs = "00:00:00";

        if(!empty($weeklyDtrs)){
            foreach($weeklyDtrs as $dtr){
                $total += $dtr['total_with_pass_slip_dbl'];
            }
        }

        $totalInHrsSql = "select SEC_TO_TIME('".(($total * 60)*60)."') as total";
        $totalInHrsQuery = $connection->createCommand($totalInHrsSql)
            ->queryAll();
        
        $totalInHrs = $totalInHrsQuery[0]['total'];

        if($total < $hrsToRender[0]['total_hours']){
            $hrsToGo -= $total;
        }

        $hrsToGoSql = "select SEC_TO_TIME('".(($hrsToGo * 60)*60)."') as total";
        $hrsToGoQuery = $connection->createCommand($hrsToGoSql)
            ->queryAll();

        $hrsToGoInHours = $hrsToGoQuery[0]['total'];

        return $this->render('index', [
            'am' => $am,
            'pm' => $pm,
            'celebrants' => $celebrants,
            'holidays' => $holidays,
            'weeklyDtrs' => $weeklyDtrs,
            'hrsToRender' => $hrsToRender,
            'hrsToRenderInHrs' => $hrsToRenderInHrs,
            'total' => $total,
            'totalInHrs' => $totalInHrs,
            'hrsToGo' => $hrsToGo,
            'hrsToGoInHours' => $hrsToGoInHours,
        ]);
    }
}
