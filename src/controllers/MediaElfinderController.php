<?php


namespace DevGroup\MediaStorage\controllers;

use Yii;
use yii\helpers\ArrayHelper;

class MediaElfinderController extends BaseElfinderController
{
    public function beforeAction($action)
    {
        $filters = Yii::$app->request->get('mimes', '');
        if ($filters !== '' && is_array($filters)) {
            //            $modelClass = ArrayHelper::remove($_GET['mimes'], 'model');
            //            $modelId = ArrayHelper::remove($_GET['mimes'], 'model_id');
            if (empty($_GET['mimes'])) {
                //                unset($_GET['mimes']);
            }
        }
        
        return parent::beforeAction($action);
    }
}