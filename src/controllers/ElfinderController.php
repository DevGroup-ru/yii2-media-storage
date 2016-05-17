<?php

namespace DevGroup\MediaStorage\controllers;

use yii\helpers\ArrayHelper;

class ElfinderController extends BaseElfinderController
{
    public function init()
    {
        parent::init();
        $this->roots = ArrayHelper::merge(
            $this->roots,
            ['baseRoot' => ['options' => ['attributes' => static::getAllMedias()]]]
        );
    }
}