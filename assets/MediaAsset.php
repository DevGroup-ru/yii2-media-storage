<?php

namespace app\modules\media\assets;

use yii\web\AssetBundle;

class MediaAsset extends AssetBundle
{
	public $sourcePath = '@app/modules/media/assets/res';

    public $css = [
        'css/media.css',
    ];
    public $js = [
		'js/dropzone.js',
		'js/media.js',
    ];
	public $depends = [
		'yii\web\JqueryAsset',
	];
}
