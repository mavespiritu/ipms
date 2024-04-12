<?php

namespace common\modules\sso\controllers;

use Yii;
use yii\base\InvalidArgumentException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\httpclient\Client;
use markavespiritu\user\models\User;
use markavespiritu\user\models\UserInfo;
use markavespiritu\user\models\Office;
use yii\web\Response;

class LogoutController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $module = $ssoModule = Yii::$app->get('sso');

        $session = Yii::$app->session;

        // Retrieve access token from session
        $accessToken = $session->get('tokenResponse')['access_token'];

        $client = new Client();
        $response = $client->createRequest()
            ->setMethod('get')
            ->setHeaders([
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . $accessToken,
            ])
            ->setUrl($module->sso_host_uri.'/api/logout')
            ->send();

        if (Yii::$app->session->isActive) {
            Yii::$app->session->destroy();
            Yii::$app->session->regenerateID(true);
        }

       //die($response);

        Yii::$app->user->logout();

        return $this->redirect(['/']);
    }

}
