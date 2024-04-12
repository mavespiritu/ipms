<?php

namespace common\modules\sso\controllers;

use Yii;
use yii\base\InvalidArgumentException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\httpclient\Client;
use yii\web\Response;

class CallbackController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $module = $ssoModule = Yii::$app->get('sso');

        $request = Yii::$app->request;
        $session = Yii::$app->session;

        $state = $session->get('state');

        if(!(strlen($state) > 0 && $state == $request->get('state'))){
            throw new BadRequestHttpException('Invalid state.');
        }

        // Make a POST request to the token endpoint
        $client = new Client();
        $response = $client->createRequest()
            ->setMethod('post')
            ->setUrl($module->sso_host_uri.'/oauth/token')
            ->setData([
                'grant_type' => 'authorization_code',
                'client_id' => $module->client_id,
                'client_secret' => $module->client_secret,
                'redirect_uri' => $module->redirect_uri,
                'code' => $request->get('code'),
            ])
            ->send();

        if ($response->isOk) {
            // Put the token response in session
            $session->set('tokenResponse', $response->data);

            return $this->redirect(['/sso/connect']);
        } else {
            // Handle error
            Yii::error('Error in token request: ' . $response->content, __METHOD__);
            throw new BadRequestHttpException('Error in token request.');
        }
    }

}
