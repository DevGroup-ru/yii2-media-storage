<?php

namespace app\models;

use DevGroup\MediaStorage\behaviors\RelationBehavior;
use yii\db\ActiveRecord;


class Thing extends ActiveRecord
{
    public function behaviors()
    {
        return [
            'mediaRelation' => RelationBehavior::className(),
        ];
    }

    public static function tableName()
    {
        return 'thing';
    }
}
