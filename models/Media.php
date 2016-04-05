<?php

namespace app\modules\media\models;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\FileHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use branchonline\lightbox\Lightbox;
use app\modules\media\models\MediaGroup;

class Media extends ActiveRecord
{
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

    /**
     * Relation with Media Groups
     */
    public function getGroup()
    {
        return $this->hasOne(MediaGroup::classname(), ['id' => 'group_id']);
    }

    public function show()
    {
        echo Html::img(
            Url::to(['media/show-item', 'id' => $this->id]),
            ['alt' => $this->title]
        );
    }

    public function showThumb($use_lightbox = true)
    {
        if ($use_lightbox) {
            echo Lightbox::widget([
                'files' => [
                    [
                        'thumb'    => Url::to(['media/show-item', 'id' => $this->id, 'size' => 'thumb']),
                        'original' => Url::to(['media/show-item', 'id' => $this->id]),
                        'title'    => $this->title ? : "File #{$this->id}",
                    ]
                ],
            ]);
        } else {
            echo Html::img(
                Url::to(['media/show-item', 'id' => $this->id, 'size' => 'thumb']),
                ['alt' => $this->title, 'class' => 'media-item']
            );
        }
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
