<?php

namespace app\modules\media\models;

use yii\db\ActiveRecord;
use yii\helpers\FileHelper;
use app\modules\media\helpers\MediaHelper;

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

    public function getType() {
        return FileHelper::getMimeType(MediaHelper::getUploadPath() . $this->path);
    }

    public function isImage() {
        return substr($this->getType(), 0, 5) === 'image';
    }
}
