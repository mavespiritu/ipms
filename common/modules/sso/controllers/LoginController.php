<?php

namespace common\modules\sso\controllers;

use Yii;

class LoginController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $module = $ssoModule = Yii::$app->get('sso');

        $request = Yii::$app->request;
        $session = Yii::$app->session;

        $state = Yii::$app->security->generateRandomString(40);
        $session->set('state', $state);

        $query = http_build_query([
            'client_id' => $module->client_id,
            'redirect_uri' => $module->redirect_uri,
            'response_type' => $module->response_type,
            'scope' => $module->scope,
            'state' => $session['state']
        ]);

        return $this->redirect($module->sso_host_uri."/oauth/authorize?" . $query);
    }

}
