<?php

namespace app\modules\media\behaviors;

use Yii;
use yii\base\Behavior;
use yii\db\ActiveRecord;
use app\modules\media\models\MediaRelation;
use app\modules\media\helpers\MediaHelper;

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
