<?php

namespace DevGroup\MediaStorage;

use Yii;

class MediaModule extends \yii\base\Module
{
    public $controllerNamespace = 'DevGroup\MediaStorage\controllers';

    public function init()
    {
        parent::init();
        
        if (Yii::$app instanceof \yii\console\Application) {
            $this->controllerNamespace = 'DevGroup\MediaStorage\commands';
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
