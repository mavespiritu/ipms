<?php
namespace frontend\controllers;

use frontend\models\ResendVerificationEmailForm;
use frontend\models\VerifyEmailForm;
use Yii;
use yii\base\InvalidArgumentException;
use yii\web\BadRequestHttpException;
use yii\helpers\Json;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;
use common\modules\rpmes\models\Plan;
use common\modules\rpmes\models\EventImage;
use common\modules\rpmes\models\ProjectRegion;
use common\modules\rpmes\models\ProjectProvince;
use common\modules\rpmes\models\Project;
use common\modules\rpmes\models\Submission;
use common\modules\rpmes\models\Agency;
use common\modules\rpmes\models\Category;
use common\modules\rpmes\models\Sector;
use common\modules\rpmes\models\SubSector;
use common\modules\rpmes\models\FundSource;
use common\modules\rpmes\models\ProjectCategory;
use common\modules\rpmes\models\Accomplishment;
use common\modules\rpmes\models\ProjectTarget;
use common\modules\rpmes\models\PhysicalAccomplishment;
use common\modules\rpmes\models\FinancialAccomplishment;
use common\modules\rpmes\models\BeneficiariesAccomplishment;
use common\modules\rpmes\models\PersonEmployedAccomplishment;
use common\models\Region;
use common\models\Province;
use common\models\Citymun;
use common\modules\rpmes\models\ProjectCitymun;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

/**
 * Site controller
 */
class SiteController extends \yii\web\Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $model = new Submission();

        $model->year = date("Y");
        $quarters = ['Q1' => '1st Quarter', 'Q2' => '2nd Quarter', 'Q3' => '3rd Quarter', 'Q4' => '4th Quarter'];

        //$status = ['C' => 'Completed', 'O' => 'On-going', 'N' => 'Not Yet Started'];

        $years = Project::find()->select(['distinct(year) as year'])->asArray()->all();
        $years = [date("Y") => date("Y")] + ArrayHelper::map($years, 'year', 'year');
        array_unique($years);

        $agencies = Agency::find()->select(['id', 'code as title']);
        $agencies = Yii::$app->user->can('AgencyUser') ? $agencies->andWhere(['id' => Yii::$app->user->identity->userinfo->AGENCY_C]) : $agencies;
        $agencies = $agencies->orderBy(['code' => SORT_ASC])->asArray()->all();
        $agencies = ArrayHelper::map($agencies, 'id', 'title');

        $categories = Category::find()->all();
        $categories = ArrayHelper::map($categories, 'id', 'title');

        $sectors = Sector::find()->all();
        $sectors = ArrayHelper::map($sectors, 'id', 'title');

        $subSectors = [];

        $fundSources = FundSource::find()->select(['id', 'concat(title," (",code,")") as title'])->asArray()->all();
        $fundSources = ArrayHelper::map($fundSources, 'id', 'title');

        $provinces = Province::find()->where(['region_c' => 01])->orderBy(['province_m' => SORT_ASC])->all();
        $provinces = ArrayHelper::map($provinces, 'province_c', 'province_m');

        $fundSources = FundSource::find()->select(['id', 'concat(title," (",code,")") as title'])->orderBy(['title' => SORT_ASC])->asArray()->all();
        $fundSources = ArrayHelper::map($fundSources, 'id', 'title');

        return $this->render('index', [
            'model' => $model,
            'years' => $years,
            'quarters' => $quarters,
            'agencies' => $agencies,
            'sectors' => $sectors,
            'subSectors' => $subSectors,
            'categories' => $categories,
            'provinces' => $provinces,
            'fundSources' => $fundSources,
        ]);
    }

    public function actionProfilePicture()
    {
        $imageUrl = 'http://58.69.112.182/npis/pages/get_picture.php?ID=' . Yii::$app->user->identity->userinfo->EMP_N;
        //$imageContent = file_get_contents($imageUrl);

        // Fallback to a default MIME type
        $defaultContentType = 'image/jpeg';
        header("Content-Type: $defaultContentType");

        // Output the image content
        return readfile($imageUrl);
    }

}
