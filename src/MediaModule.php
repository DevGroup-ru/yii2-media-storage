<?php

namespace DevGroup\MediaStorage;

use Yii;

class MediaModule extends \yii\base\Module
{
    public $controllerNamespace = 'DevGroup\MediaStorage\controllers';
    public $activeFS = [];

    /**
     * @return self Module instance in application
     */
    public static function module()
    {
        $module = Yii::$app->getModule('media');
        if ($module === null) {
            $module = $module = Yii::createObject(self::class, ['media']);
        }
        return $module;
    }
}
