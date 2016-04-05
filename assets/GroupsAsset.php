<?php

namespace app\modules\media\assets;

use yii\web\AssetBundle;

class GroupsAsset extends AssetBundle
{
    public $sourcePath = '@app/modules/media/assets/res';

    public $css = [
        'css/groups.css'
    ];
    public $js = [
        'js/groups.js',
    ];
    public $depends = [
        'yii\web\JqueryAsset',
        'app\modules\media\assets\HelperAsset',
    ];
}
