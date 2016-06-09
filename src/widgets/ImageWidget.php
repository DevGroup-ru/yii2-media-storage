<?php


namespace DevGroup\MediaStorage\widgets;

use DevGroup\DataStructure\models\Property;
use DevGroup\MediaStorage\models\Media;
use League\Glide\ServerFactory;
use yii\base\Exception;
use yii\base\Widget;

class ImageWidget extends Widget
{
    const RETURN_SRC = 'src';
    const RETURN_BASE64 = 'base64';
    const RETURN_CONTENT = 'content';

    public $model = null;
    public $propertyId = null;
    public $response = self::RETURN_SRC;
    public $config = [];

    public function init()
    {
        if (is_null($this->model)) {
            throw new Exception('Set model');
        }
        if (is_null($this->propertyId)) {
            throw new Exception('Set property id');
        }
    }

    public function run()
    {
        $property = Property::findById($this->propertyId);
        $propValues = (array) $this->model->{$property->key};
        $imageMedias = Media::find()->where(['id' => $propValues])->andWhere(['like', 'mime', 'image'])->all();
        $server = ServerFactory::create(
            [
                'source' => $this->config['sourceFS'],
                'cache' => $this->config['cacheFS'],
            ]
        );
        foreach ($imageMedias as $imageMedia) {
            $path = $imageMedia->path;
            switch ($this->response) {
            }
        }
    }
}
