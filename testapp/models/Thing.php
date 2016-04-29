<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%thing}}".
 *
 * @property integer $id
 * @property string $prop
 *
 * @property ThingMedia[] $thingMedia
 * @property Media[] $media
 */
class Thing extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%thing}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['prop'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'prop' => Yii::t('app', 'Prop'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getThingMedia()
    {
        return $this->hasMany(ThingMedia::className(), ['model_id' => 'id'])->inverseOf('model');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMedia()
    {
        return $this->hasMany(Media::className(), ['id' => 'media_id'])->viaTable('{{%thing_media}}', ['model_id' => 'id']);
    }
}
