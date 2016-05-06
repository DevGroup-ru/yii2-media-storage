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
 * @property MediaMediaGroup[] $mediaMediaGroups
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
            'id' => Yii::t('app', 'ID'),
            'path' => Yii::t('app', 'Path'),
            'mime' => Yii::t('app', 'Mime'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMediaMediaGroups()
    {
        return $this->hasMany(MediaMediaGroup::className(), ['media_id' => 'id']);
    }

    public function isImage()
    {
        return substr($this->mime, 0, 5) === 'image';
    }
}
