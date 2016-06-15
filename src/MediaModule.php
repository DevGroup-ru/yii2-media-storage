<?php

namespace DevGroup\MediaStorage;

use DevGroup\MediaStorage\components\MediaRule;
use Yii;
use yii\base\Application;
use yii\base\BootstrapInterface;

class MediaModule extends \yii\base\Module
{
    public $controllerNamespace = 'DevGroup\MediaStorage\controllers';
    public $activeFS = [];

    public static function getModuleInstance()
    {
        $module = Yii::$app->getModule('media');
        if ($module === null) {
            $module = new self('media');
        }
        return $module;
    }
}
