<?php

namespace app\modules\media\models;

use yii\db\ActiveRecord;

class MediaRelation extends ActiveRecord
{
    public static function tableName()
    {
        return 'media-storage-relations';
    }

    public function rules()
    {
        return [
            [['media', 'object_id', 'object_model'], 'required'],
            [['media', 'object_id'], 'integer'],
            ['object_model', 'string', 'max' => 64],
        ];
    }
}
