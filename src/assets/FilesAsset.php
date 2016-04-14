<?php

namespace DevGroup\MediaStorage\assets;

use yii\web\AssetBundle;

class FilesAsset extends AssetBundle
{
    public $sourcePath = '@app/modules/media/assets/res';

    public $css = [
        'css/files.css',
    ];
    public $js = [
        'js/dropzone.js',
        'js/files.js',
    ];
    public $depends = [
        'yii\web\JqueryAsset',
        'DevGroup\MediaStorage\assets\HelperAsset',
    ];
}
