<?php

namespace DevGroup\MediaStorage\models;

use Yii;

/**
 * This is the model class for table "{{%media}}".
 *
 * @property integer $id
 * @property string $path
 * @property string $mime
 *
 */
class Media extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%media}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['path', 'mime'], 'required'],
            [['path', 'mime'], 'string', 'max' => 255],
            [['path'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('devgroup.media-storage', 'ID'),
            'path' => Yii::t('devgroup.media-storage', 'Path'),
            'mime' => Yii::t('devgroup.media-storage', 'Mime'),
        ];
    }

    public function isImage()
    {
        return substr($this->mime, 0, 5) === 'image';
    }
}
