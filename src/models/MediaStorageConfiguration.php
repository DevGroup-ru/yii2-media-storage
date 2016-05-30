<?php


namespace DevGroup\MediaStorage\models;

use DevGroup\ExtensionsManager\models\BaseConfigurationModel;
use Yii;
use yii\helpers\ArrayHelper;

class MediaStorageConfiguration extends BaseConfigurationModel
{

    public $defaultThumbnailSize;
    public $noImageSrc;
    public $thumbnailsDirectory;
    public $useWatermark;
    public $watermarkDirectory;
    public $defaultComponents = [];
    public $defaultComponent;
    public $components = [];

    public function rules()
    {
        return [
            [['noImageSrc', 'defaultThumbnailSize', 'thumbnailsDirectory', 'watermarkDirectory'], 'string'],
            ['useWatermark', 'boolean'],
            [['components', 'defaultComponents'], 'isArray'],
        ];
    }

    public function isArray($attribute, $params)
    {
        if (!is_array($this->$attribute)) {
            $this->addError($attribute, "The $attribute must be array");
        }
    }

    public function attributeLabels()
    {
        return [
            'defaultThumbnailSize' => Yii::t('app', 'Default thumbnail size'),
            'noImageSrc' => Yii::t('app', 'No image src'),
            'thumbnailsDirectory' => Yii::t('app', 'Thumbnails directory'),
            'useWatermark' => Yii::t('app', 'Use watermark'),
            'watermarkDirectory' => Yii::t('app', 'Watermark directory'),
            'components' => Yii::t('app', 'Components'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function defaultValues()
    {
        $module = Yii::$app->getModule('image');
        $attributes = array_keys($this->getAttributes());
        foreach ($attributes as $attribute) {
            $this->{$attribute} = $module->{$attribute};
        }
        foreach ($this->defaultComponents as $name => $values) {
            $this->defaultComponents[$name]['name'] = $name;
        }
    }

    public function getAttributesForStateSaving()
    {
        $attributes = $this->getAttributes();
        if (isset($attributes['components'])) {
            foreach ($attributes['components'] as $name => $component) {
                if (isset($component['necessary']) && isset($component['necessary']['active']) && $component['necessary']['active'] == false) {
                    unset($attributes['components'][$name]);
                }
            }
        }
        if (isset($attributes['defaultComponents'])) {
            foreach ($attributes['defaultComponents'] as $component) {
                if (isset($component['necessary']) && isset($component['necessary']['active']) && $component['necessary']['active'] == true && !isset($attributes['components'][$component['name']])) {
                    $newData = $component;
                    unset($newData['name']);
                    $attributes['components'][$component['name']] = $newData;
                }
            }
            unset($attributes['defaultComponents']);
        }
        return $attributes;
    }

    /**
     * Returns array of key=>values for configuration.
     * @return mixed
     */
    public function keyValueAttributes()
    {
        return [];
    }


    /**
     * Returns array of module configuration that should be stored in application config.
     * Array should be ready to merge in app config.
     * Used for web only.
     *
     * @return array
     */
    public function webApplicationAttributes()
    {
        return [
            'modules' => [
                'media' => [
                    'class' => 'DevGroup\MediaStorage\MediaModule',
                ],
            ],
            'bootstrap' => ['media'],
        ];
    }

    /**
     * Returns array of module configuration that should be stored in application config.
     * Array should be ready to merge in app config.
     * Used for console only.
     *
     * @return array
     */
    public function consoleApplicationAttributes()
    {
        return [
            'controllerNamespace' => 'DevGroup\MediaStorage\commands',
        ];
    }

    /**
     * Returns array of module configuration that should be stored in application config.
     * Array should be ready to merge in app config.
     * Used both for web and console.
     *
     * @return array
     */
    public function commonApplicationAttributes()
    {
        $attributes = $this->getAttributesForStateSaving();
        $components = [];
        foreach ($this->components as $name => $component) {
            $necessary = ArrayHelper::getValue($component, 'necessary', []);
            $unnecessary = ArrayHelper::getValue($component, 'unnecessary', []);
            $active = ArrayHelper::remove($necessary, 'active', false);
            ArrayHelper::remove($necessary, 'srcAdapter');
            if ($active === true || $active === '1' || $name == 'fs') {
                foreach ($unnecessary as $confName => $confVal) {
                    if ($confVal === '') {
                        ArrayHelper::remove($unnecessary, $confName);
                    }
                }
                $components[$name] = ArrayHelper::merge($necessary, $unnecessary);
            }
        }

        return [
            'components' => [
                'protectedFilesystem' => [
                    'class' => 'creocoder\flysystem\LocalFilesystem',
                    'path' => '@app/media',
                ],
                'urlManager' => [
                    'excludeRoutes' => ['media/file/send', 'media/file/xsend'],
                ],
                $components,
            ],
            'modules' => [
                'image' => $attributes,
            ],
        ];
    }

    /**
     * Returns array of key=>values for configuration.
     *
     * @return mixed
     */
    public function appParams()
    {
        return [];
    }

    /**
     * Returns array of aliases that should be set in common config
     * @return array
     */
    public function aliases()
    {
        return ['@DevGroup/MediaStorage' => realpath(dirname(__DIR__)),];
    }

}