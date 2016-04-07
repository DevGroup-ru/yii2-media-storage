<?php

namespace app\modules\media\models;

use yii\db\ActiveRecord;

class MediaPermission extends ActiveRecord
{
    public static function tableName()
    {
        return 'media-storage-groups-permissions';
    }

    public function rules()
    {
        return [
            [['group_id', 'name'], 'required'],
            ['group_id', 'integer'],
            ['name', 'string'],
        ];
    }
}
