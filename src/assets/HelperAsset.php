<?php

namespace DevGroup\MediaStorage\assets;

use yii\web\AssetBundle;

class HelperAsset extends AssetBundle
{
    public $sourcePath = __DIR__ . DIRECTORY_SEPARATOR . 'res';

    public $css = [
        'css/helper.css',
    ];
    public $js = [
        'js/helper.js',
    ];
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}
