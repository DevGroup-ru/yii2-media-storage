<?php

namespace app\modules\media\models;

use yii\db\ActiveRecord;
use app\modules\media\models\Media;
use app\modules\media\models\MediaPermission;

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
            ['name', 'required'],
            ['name', 'unique'],
        ];
    }

    /**
     * Relation with Media Items
     */
    public function getMedias()
    {
        return $this->hasMany(Media::classname(), ['group_id' => 'id']);
    }

    public function getItemsCount()
    {
        return $this->getMedias()->count();
    }

    /**
     * Relation with Group Permissions
     */
    public function getPermissions()
    {
        $permissions = $this->hasMany(MediaPermission::classname(), ['group_id' => 'id'])->select('name')->all();

        foreach ($permissions as &$p) {
            $p = $p->name;
        }

        return $permissions;
    }

    public function extraFields()
    {
        return ['permissions'];
    }
}
