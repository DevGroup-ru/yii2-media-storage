<?php


namespace DevGroup\MediaStorage\controllers;

use DevGroup\MediaStorage\helpers\MediaTableGenerator;
use DevGroup\MediaStorage\models\ApplicableMediaModels;
use Yii;
use yii\db\Query;
use yii\helpers\ArrayHelper;

class MediaElfinderController extends BaseElfinderController
{
    public function init()
    {
        $filters = Yii::$app->request->get('mimes', '');
        if ($filters !== '' && is_array($filters)) {
            $modelClass = ArrayHelper::remove($_GET['mimes'], 'model', null);
            $modelId = ArrayHelper::remove($_GET['mimes'], 'model_id', null);

            if (is_null($modelClass) === false && is_null($modelId) === false) {
                //if applicable show only available files else show all
                if (ApplicableMediaModels::find()->where(['class_name' => $modelClass])->count() == 1) {
                    $this->roots['baseRoot']['name'] = 'available';
                    $ids = (new Query())->select('media_id')->from(
                        (new MediaTableGenerator())->getMediaTableName($modelClass)
                    )->where(['model_id' => $modelId])->column();
                    //@todo show folders
                    $this->roots = ArrayHelper::merge(
                        $this->roots,
                        ['baseRoot' => ['options' => ['attributes' => static::getAllMedias($ids)]]]
                    );
                } else {
                    $this->roots = ArrayHelper::merge(
                        $this->roots,
                        ['baseRoot' => ['options' => ['attributes' => static::getAllMedias()]]]
                    );
                }

            }
        }
        parent::init();
    }

}