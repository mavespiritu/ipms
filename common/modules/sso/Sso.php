<?php

namespace common\modules\sso;

/**
 * sso module definition class
 */
class Sso extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'common\modules\sso\controllers';
    public $client_id;
    public $client_secret;
    public $redirect_uri;
    public $response_type;
    public $scope;
    public $sso_host_uri;

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }
}
