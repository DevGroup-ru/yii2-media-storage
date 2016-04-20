<?php

namespace DevGroup\MediaStorage\assets;

use yii\web\AssetBundle;

class WidgetAsset extends AssetBundle
{
    public $sourcePath = __DIR__ . DIRECTORY_SEPARATOR . 'res';

    public $css = [
        'css/widget.css',
    ];
    public $js = [
        'js/widget.js',
    ];
    public $depends = [
        'yii\web\JqueryAsset',
        'DevGroup\MediaStorage\assets\HelperAsset',
    ];
}
