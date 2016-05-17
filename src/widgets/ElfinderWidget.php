<?php


namespace DevGroup\MediaStorage\widgets;

use mihaildev\elfinder\ElFinder;

class ElfinderWidget extends ElFinder
{
    public $customManagerOptions = [];

    public function init()
    {
        parent::init();
        if (count($this->customManagerOptions) > 0) {
            $this->frameOptions['src'] .= '&' . http_build_query(['managerOptions' => $this->customManagerOptions]);
        }
    }
}
