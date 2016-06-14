<?php


namespace DevGroup\MediaStorage\widgets;

use DevGroup\DataStructure\models\Property;
use DevGroup\MediaStorage\models\Media;
use League\Glide\ServerFactory;
use Yii;
use yii\base\Exception;
use yii\base\Widget;

class ImageWidget extends Widget
{
    const RETURN_SRC = 'src';
    const RETURN_BASE64 = 'base64';
    const RETURN_CONTENT = 'content';

    public $model = null;
    public $propertyId = null;
    public $config = [];

    /**
     * @var string
     */
    public $viewFile = self::RETURN_SRC;
    /**
     * @var string
     */
    public $noImageViewFile = 'noimage';
    /**
     * @var null|int
     */
    public $limit = null;
    /**
     * @var int
     */
    public $offset = 0;
    /**
     * @var bool if true and images array empty show "No image"
     */
    public $noImageOnEmptyImages = false;
    /** @var array $additional Additional data passed to view */
    public $additional = [];

    public function init()
    {
        if (is_null($this->model)) {
            throw new Exception(Yii::t('devgroup.media-storage', 'Set model'));
        }
        if (is_null($this->propertyId)) {
            throw new Exception(Yii::t('devgroup.media-storage', 'Set property id'));
        }
        //@todo default config merge
    }

    public function run()
    {
        $property = Property::findById($this->propertyId);
        $propValues = (array) $this->model->{$property->key};
        $imageMedias = Media::find()->where(['id' => $propValues])->andWhere(['like', 'mime', 'image'])->all();

        foreach ($imageMedias as $imageMedia) {
            // each media each server but cache server only one
            $server = ServerFactory::create(
                [
                    'source' => $this->config['sourceFS'],
                    'cache' => $this->config['cacheFS'],
                ]
            );
            $path = $imageMedia->path;

        }
    }
}
