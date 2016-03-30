<?php

namespace app\modules\media\assets;

use yii\web\AssetBundle;

class UploadingAsset extends AssetBundle
{
	public $sourcePath = '@app/modules/media/assets/res';

    public $css = [
        'css/uploading.css',
    ];
    public $js = [
		'js/dropzone.js',
		'js/media.js',
    ];
	public $depends = [
		'yii\web\JqueryAsset',
	];
}
