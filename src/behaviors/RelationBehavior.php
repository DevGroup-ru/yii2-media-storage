<?php

namespace DevGroup\MediaStorage\behaviors;

use Yii;
use yii\base\Behavior;
use yii\db\ActiveRecord;
use DevGroup\MediaStorage\models\MediaRelation;
use DevGroup\MediaStorage\helpers\MediaHelper;

class RelationBehavior extends Behavior
{
    public function events()
    {
        return [
            ActiveRecord::EVENT_AFTER_INSERT => 'saveRelation',
            ActiveRecord::EVENT_AFTER_UPDATE => 'saveRelation',
        ];
    }

    public function saveRelation($event)
    {
        $request = Yii::$app->request;

        $relation = new MediaRelation([
            'media_id'     => $request->post(MediaHelper::getWidgetInputName()),
            'object_id'    => $event->sender->id,
            'object_model' => $event->sender->classname(),
        ]);
        $relation->save();
    }
}
