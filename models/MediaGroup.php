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

    /**
     * Return all media groups for using as dropdown items
     *
     * @return array Dropdown items format
     */
    static public function getForDropdown() {
        $media_groups = [];

        foreach(self::find()->select(['id', 'name'])->all() as $group) {
            $media_groups[] = [
                'label'       => $group->name,
                'url'         => '#',
                'linkOptions' => ['data-id' => $group->id],
            ];
        }

        return $media_groups;
    }
}
