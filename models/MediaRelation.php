<?php

namespace app\modules\media\models;

use yii\db\ActiveRecord;

class MediaRelation extends ActiveRecord
{
    public static function tableName()
    {
        return 'media-storage-relations';
    }
}
