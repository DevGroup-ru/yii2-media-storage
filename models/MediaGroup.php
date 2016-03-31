<?php

namespace app\modules\media\models;

use yii\db\ActiveRecord;
use app\modules\media\models\Media;

class MediaGroup extends ActiveRecord
{
    /**
     * @return string the name of the table associated with this ActiveRecord class.
     */
    public static function tableName()
    {
        return 'media-storage-groups';
    }

    public function rules()
    {
        return [
        ];
    }

    /**
     * Relation with Media Items
     */
    public function getMedias()
    {
        return $this->hasMany(Media::classname(), ['group_id' => 'id']);
    }
}
