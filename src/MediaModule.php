<?php

namespace DevGroup\MediaStorage;

use DevGroup\MediaStorage\components\MediaRule;
use Yii;

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

    public function init()
    {
        parent::init();

        Yii::$classMap['creocoder\flysystem\Filesystem'] = __DIR__ . '/../Filesystem.php';

        if (Yii::$app instanceof \yii\console\Application) {
            $this->controllerNamespace = 'DevGroup\MediaStorage\commands';
        } elseif (Yii::$app instanceof \yii\web\Application) {
            Yii::$app->getUrlManager()->addRules(
                [
                    [
                        'class' => MediaRule::class,
                    ],
                ],
                false
            );
        }
    }

}
