<?php

namespace app\modules\media\models;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\FileHelper;
use app\modules\media\models\MediaGroup;

class Media extends ActiveRecord
{
    public $type;
    public $size;
    public $width;
    public $height;

    /**
     * @return string the name of the table associated with this ActiveRecord class.
     */
    public static function tableName()
    {
        return 'media-storage';
    }

    public function rules()
    {
        return [
        ];
    }

    /**
     * Relation with Media Groups
     */
    public function getGroup()
    {
        return $this->hasOne(MediaGroup::classname(), ['id' => 'group_id']);
    }

    public function getType() {
        //return Yii::$app->fs->getWithMetadata($this->path, ['mimetype']);

        # Temporarily while issue not fixed
        # https://github.com/creocoder/yii2-flysystem/issues/19
        return Yii::$app->fs->getWithMetadata($this->path, ['mimetype'])['mimetype'];
    }

    public function isImage() {
        return substr($this->getType(), 0, 5) === 'image';
    }
}
