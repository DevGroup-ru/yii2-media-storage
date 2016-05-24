<?php

namespace DevGroup\MediaStorage\models;

use Yii;

/**
 * This is the model class for table "{{%media_route}}".
 *
 * @property integer $id
 * @property integer $media_id
 * @property string $url
 * @property string $params
 *
 * @property Media $media
 */
class MediaRoute extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%media_route}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['media_id', 'url', 'params'], 'required'],
            [['media_id'], 'integer'],
            [['params'], 'string'],
            [['url'], 'string', 'max' => 255],
            [['url'], 'unique'],
            [['media_id'], 'exist', 'skipOnError' => true, 'targetClass' => Media::className(), 'targetAttribute' => ['media_id' => 'id']],
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
            'url' => Yii::t('app', 'Url'),
            'params' => Yii::t('app', 'Params'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMedia()
    {
        return $this->hasOne(Media::className(), ['id' => 'media_id'])->inverseOf('mediaRoutes');
    }
}
