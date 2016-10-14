<?php


namespace DevGroup\MediaStorage\controllers;

use DevGroup\MediaStorage\helpers\MediaHelper;
use DevGroup\MediaStorage\helpers\MediaTableGenerator;
use Yii;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\web\JsExpression;

class MediaElfinderController extends BaseElfinderController
{
    public function init()
    {
        parent::init();
        $data = $this->getCustomData();
        if (count($data) > 0) {
            $ids = (new Query())->select('media_id')->from(
                (new MediaTableGenerator())->getMediaTableName($data['model'])
            )->where(['model_id' => $data['model_id']])->column();
            // @todo show folders
            $this->roots = MediaHelper::loadRoots($ids);
        }
        foreach ($this->roots as $name => $root) {
            $this->roots[$name]['name'] = $name . ' - available';
        }

    }
}
