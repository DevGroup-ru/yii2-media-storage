<?php

namespace app\modules\media\assets;

use yii\web\AssetBundle;

class UploadingAsset extends AssetBundle
{
    public $sourcePath = '@app/modules/media/assets/res';

    public $css = [
        'css/upload.css',
    ];
    public $js = [
        'js/dropzone.js',
        'js/upload.js',
    ];
    public $depends = [
        'yii\web\JqueryAsset',
        'app\modules\media\assets\HelperAsset',
    ];
}
