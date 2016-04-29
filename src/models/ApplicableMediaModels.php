<?php

namespace DevGroup\MediaStorage\models;

use Yii;

/**
 * This is the model class for table "{{%applicable_media_models}}".
 *
 * @property integer $id
 * @property string $class_name
 * @property string $name
 */
class ApplicableMediaModels extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%applicable_media_models}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['class_name', 'name'], 'required'],
            [['class_name', 'name'], 'string', 'max' => 255],
            [['class_name'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'class_name' => Yii::t('app', 'Class name'),
            'name' => Yii::t('app', 'Name'),
        ];
    }
}
