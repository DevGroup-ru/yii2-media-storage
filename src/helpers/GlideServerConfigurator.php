<?php


namespace DevGroup\MediaStorage\helpers;


use Yii;
use yii\base\Object;
use yii\helpers\ArrayHelper;

class GlideServerConfigurator extends Object
{
    public $config = [];

    public function init()
    {
        //@todo load defaults from app conf
        $defaults = [
            'source' => Yii::$app->protectedFilesystem->getFilesystem(),
            'cache' => Yii::$app->protectedFilesystem->getFilesystem(),
            'cache_path_prefix' => '',
        ];
        $this->config = ArrayHelper::merge($defaults, $this->config);
        if ($this->config['source'] == $this->config['cache'] && empty($this->config['cache_path_prefix'])) {
            $this->config['cache_path_prefix'] = 'cache';
        }
    }
}