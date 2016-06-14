<?php


namespace DevGroup\MediaStorage\helpers;

use Yii;
use yii\base\Object;

class GlideServerConfigurator extends Object
{
    public $config = [];

    public function init()
    {
        if (empty($this->config['cache'])) {
            $this->config['cache'] = $this->config['source'];
        }
        if ($this->config['source'] == $this->config['cache'] && empty($this->config['cache_path_prefix'])) {
            $this->config['cache_path_prefix'] = '.cache';
        }
    }
}
