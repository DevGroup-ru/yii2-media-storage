<?php

namespace app\modules\media\behaviors;

use yii\base\Behavior;
use yii\db\ActiveRecord;

class RelationBehavior extends Behavior
{
    public function events()
    {
        return [
            ActiveRecord::EVENT_BEFORE_INSERT => 'saveRelation',
            ActiveRecord::EVENT_BEFORE_UPDATE => 'saveRelation',
        ];
    }

    public function saveRelation() {
        //
    }
}
