<?php

namespace app\modules\media;

use Yii;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'app\modules\media\controllers';
    public $accessPermissions = ['@'];

    public function init()
    {
        parent::init();

        if (Yii::$app instanceof \yii\console\Application) {
            $this->controllerNamespace = 'app\modules\media\commands';
        }
    }

    public function registerTranslations()
    {
        //Yii::$app->i18n->translations['modules/media/*'] = [
            //'class' => '',
            //'sourceLanguage' => 'en_US',
            //'basePath' => '@app/modules/media',
        //];
    }
}
