<?php

namespace app\modules\media\assets;

use yii\web\AssetBundle;

class WidgetAsset extends AssetBundle
{
    public $sourcePath = '@app/modules/media/assets/res';

    public $css = [
        'css/widget.css',
    ];
    public $js = [
        'js/widget.js',
    ];
    public $depends = [
        'yii\web\JqueryAsset',
        'app\modules\media\assets\HelperAsset',
    ];
}
