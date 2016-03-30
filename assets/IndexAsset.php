<?php

namespace app\modules\media\assets;

use yii\web\AssetBundle;

class IndexAsset extends AssetBundle
{
	public $sourcePath = '@app/modules/media/assets/res';

    public $css = [
        'css/index.css',
    ];
    public $js = [
		'js/index.js',
    ];
	public $depends = [
		'yii\web\JqueryAsset',
	];
}
