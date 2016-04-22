<?php

namespace DevGroup\MediaStorage\models;

use Yii;

/**
 * This is the model class for table "{{%media}}".
 *
 * @property integer $id
 * @property string $path
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
            [['path'], 'required'],
            [['path'], 'string', 'max' => 255],
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
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMediaMediaGroups()
    {
        return $this->hasMany(MediaMediaGroup::className(), ['media_id' => 'id']);
    }

    public function getType()
    {
        return Yii::$app->fs->getMimetype($this->path);
    }

    public function isImage()
    {
        return substr($this->getType(), 0, 5) === 'image';
    }
}
