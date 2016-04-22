<?php

namespace DevGroup\MediaStorage\models;

use Yii;

/**
 * This is the model class for table "{{%media_media_group}}".
 *
 * @property integer $id
 * @property integer $media_id
 * @property integer $media_group_id
 *
 * @property Media $media
 * @property MediaGroup $mediaGroup
 */
class MediaMediaGroup extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%media_media_group}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['media_id', 'media_group_id'], 'integer'],
            [['media_id'], 'exist', 'skipOnError' => true, 'targetClass' => Media::className(), 'targetAttribute' => ['media_id' => 'id']],
            [['media_group_id'], 'exist', 'skipOnError' => true, 'targetClass' => MediaGroup::className(), 'targetAttribute' => ['media_group_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'media_id' => Yii::t('app', 'Media ID'),
            'media_group_id' => Yii::t('app', 'Media Group ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMedia()
    {
        return $this->hasOne(Media::className(), ['id' => 'media_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMediaGroup()
    {
        return $this->hasOne(MediaGroup::className(), ['id' => 'media_group_id']);
    }
}
