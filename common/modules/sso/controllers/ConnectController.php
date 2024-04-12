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

class ConnectController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $module = $ssoModule = Yii::$app->get('sso');

        $session = Yii::$app->session;

        // Retrieve access token from session
        $accessToken = $session->get('tokenResponse')['access_token'];

        // Make a GET request to the user endpoint
        $client = new Client();
        $response = $client->createRequest()
            ->setMethod('get')
            ->setHeaders([
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . $accessToken,
            ])
            ->setUrl($module->sso_host_uri.'/api/user')
            ->send();

        if ($response->isOk) {
            // Decode JSON response
            $ssoUser = $response->data;

            try {
                $email = $ssoUser['email'];
            } catch (\Throwable $th) {
                return $this->redirect(['/sso/login'])->with('error', 'Failed to get login information. Try again.');
            }

            // Check if user exists by email
            $user = User::findOne(['email' => $email]);

            if (!$user) {
                // Create a new user if not exists
                $user = new User([
                    'email' => $ssoUser['email'],
                    'username' => $ssoUser['email'],
                    'password' => Yii::$app->getSecurity()->generatePasswordHash(Yii::$app->security->generateRandomString(8)),
                    'confirmed_at' => strtotime(date("Y-m-d H:i:s")),
                    'registration_ip' => Yii::$app->request->getUserIP(),
                    'created_at' => strtotime(date("Y-m-d H:i:s")),
                    'updated_at' => strtotime(date("Y-m-d H:i:s")),
                    'flags' => 0
                ]);

                $division = Office::findOne(['abbreviation' => $ssoUser['division']]);

                if($user->save()){
                    $userinfo = UserInfo::findOne(['user_id' => $user->id]) ? 
                    UserInfo::findOne(['user_id' => $user->id]) : 
                    new UserInfo([
                        'user_id' => $user->id,
                        'EMP_N' => $ssoUser['ipms_id'],
                        'LAST_M' => $ssoUser['last_name'],
                        'FIRST_M' => $ssoUser['first_name'],
                        'MIDDLE_M' => $ssoUser['middle_name'],
                        'OFFICE_C' => $division->id
                    ]);

                    $userinfo->save(false);
                }
            }

            // Log in the user
            Yii::$app->user->login($user);

            return $this->redirect(['/']);
        } else {
            // Handle error
            Yii::error('Error in user request: ' . $response->content, __METHOD__);
            return $this->redirect(['/sso/login'])->with('error', 'Failed to retrieve user information. Try again.');
        }
    }

}
