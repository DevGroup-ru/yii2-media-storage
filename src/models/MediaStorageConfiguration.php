<?php


namespace DevGroup\MediaStorage\models;

use DevGroup\ExtensionsManager\models\BaseConfigurationModel;

class MediaStorageConfiguration extends BaseConfigurationModel
{
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
        return [
            'components' => [
                'protectedFilesystem' => [
                    'class' => 'creocoder\flysystem\LocalFilesystem',
                    'path' => '@app/media',
                ],
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