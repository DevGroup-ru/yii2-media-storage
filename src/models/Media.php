<?php

namespace DevGroup\MediaStorage\models;

use DevGroup\TagDependencyHelper\CacheableActiveRecord;
use DevGroup\TagDependencyHelper\TagDependencyTrait;
use Yii;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "{{%media}}".
 *
 * @property integer $id
 * @property string $path
 * @property string $mime
 * @property string $title
 * @property string $alt
 *
 */
class Media extends \yii\db\ActiveRecord
{
    const SCENARIO_SEARCH = 'search';
    use TagDependencyTrait;

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'CacheableActiveRecord' => [
                'class' => CacheableActiveRecord::className(),
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%media}}';
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_SEARCH] = [];
        return $scenarios;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['path', 'mime'], 'required'],
            [['path', 'mime', 'alt', 'title'], 'string', 'max' => 255],
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

    public function search($params = [])
    {
        $query = static::find();
        $dataProvider = new ActiveDataProvider(
            [
                'query' => $query,
            ]
        );
        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }
        return $dataProvider;
    }
}
