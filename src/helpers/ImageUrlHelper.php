<?php


namespace DevGroup\MediaStorage\helpers;

use yii\helpers\Url;

class ImageUrlHelper
{


    public static function getImageUrlByPath($path, $config = [])
    {
        //$serverConfig = ArrayHelper::remove($config, 'server');

        return Url::to(['/media/file/' . $action, $config]);
    }
}
