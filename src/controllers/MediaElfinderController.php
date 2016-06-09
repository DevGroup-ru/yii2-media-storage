<?php


namespace DevGroup\MediaStorage\controllers;

use DevGroup\MediaStorage\helpers\MediaHelper;
use DevGroup\MediaStorage\helpers\MediaTableGenerator;
use Yii;
use yii\db\Query;
use yii\helpers\ArrayHelper;

class MediaElfinderController extends BaseElfinderController
{
    public function init()
    {

        $modelId = Yii::$app->request->get('model_id', null);
        $modelClass = Yii::$app->request->get('model', null);

        if (is_null($modelClass) === false && is_null($modelId) === false) {
            $this->roots['baseRoot']['name'] = 'available';
            $ids = (new Query())->select('media_id')->from(
                (new MediaTableGenerator())->getMediaTableName($modelClass)
            )->where(['model_id' => $modelId])->column();
            // @todo show folders
            $this->roots = ArrayHelper::merge(
                $this->roots,
                ['baseRoot' => ['options' => ['attributes' => MediaHelper::loadMediasAttrs($ids)]]]
            );
        }

        parent::init();
    }
}
