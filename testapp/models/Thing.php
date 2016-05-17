<?php

namespace app\models;

use DevGroup\MediaStorage\models\Media;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%thing}}".
 *
 * @property integer $id
 * @property string $prop
 *
 * @property Media[] $media
 */
class Thing extends \yii\db\ActiveRecord
{
    use \DevGroup\DataStructure\traits\PropertiesTrait;
    use \DevGroup\TagDependencyHelper\TagDependencyTrait;

    public function behaviors()
    {
        return [
            // other behaviors
            'properties' => [
                'class' => '\DevGroup\DataStructure\behaviors\HasProperties',
                // 'class' => \DevGroup\DataStructure\behaviors\HasProperties::class, // Альтернативная версия
                'autoFetchProperties' => true,
            ],
            'CacheableActiveRecord' => [
                'class' => \DevGroup\TagDependencyHelper\CacheableActiveRecord::className(),
            ],
            // other behaviors
        ];
    }

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
        return ArrayHelper::merge(
            [
                [['prop'], 'string', 'max' => 255],
            ],
            $this->propertiesRules()
        );
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
    public function getMedia()
    {
        return $this->hasMany(Media::className(), ['id' => 'media_id'])->viaTable(
            '{{%thing_media}}',
            ['model_id' => 'id']
        );
    }

    public function setMedia($values)
    {
        //
        if (is_array($values) === false) {
            $values = (array) $values;
        }
        $mediaIds = $this->getMedia()->select(Media::primaryKey())->column();

        $skipIds = array_intersect($mediaIds, $values);
        foreach (array_diff($values, $skipIds) as $id) {
            $media = Media::findOne($id); //@todo change to find by id
            if (!empty($media)) {
                $this->link('media', $media);
            }
        }

        foreach (array_diff($mediaIds, $skipIds) as $id) {
            $media = Media::findOne($id); //@todo change to find by id
            if (!empty($media)) {
                $this->unlink('media', $media);
            }
        }

    }
}
