<?php


namespace DevGroup\MediaStorage\models;

use DevGroup\ExtensionsManager\models\BaseConfigurationModel;
use DevGroup\MediaStorage\MediaModule;
use yii\helpers\ArrayHelper;

class MediaStorageConfiguration extends BaseConfigurationModel
{
    public function getModuleClassName()
    {
        return MediaModule::className();
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['activeFS', 'checkConfigurations'],
        ];
    }

    public function checkConfigurations($attribute, $params)
    {
        $active = false;
        foreach ($this->$attribute as $index => $data) {
            if (empty($data['class'])) {
                $this->addError(
                    $attribute . '[' . $index . '][class]',
                    \Yii::t('devgroup.media-storage', 'The field cannot be blank')
                );
            }
            if (isset($data['options']) && is_array($data['options']) && in_array(1, $data['options'])) {
                $active = true;
            }
        }
        if (count($this->$attribute) > 0 && !$active) {
            $this->addError($attribute, \Yii::t('devgroup.media-storage', 'One or more file system must be an active'));
        }
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
        return ['modules' => ['gridview' => 'kartik\grid\Module']];
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
        return [];
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
        $attributes['activeFS'] = array_values($attributes['activeFS']);
        $allFsConfig = ArrayHelper::index(ArrayHelper::getValue($attributes, 'activeFS', []), 'name');

        $active = [];
        $cache = [];
        $allFs = [];
        foreach ($allFsConfig as $attribute) {
            $fs = $attribute;
            foreach (ArrayHelper::remove($fs, 'necessary', []) as $key => $item) {
                $fs[$key] = $item;
            }
            foreach (ArrayHelper::remove($fs, 'unnecessary', []) as $key => $item) {
                if ($item !== '') {
                    $fs[$key] = $item;
                }
            }
            $name = ArrayHelper::remove($fs, 'name');
            unset($fs['options']);
            unset($fs['urlRule']);
            $options = ArrayHelper::remove($attribute, 'options', []);
            if (array_search(1, $options) !== false) {
                if (array_search(2, $options) !== false) {
                    $cache[] = $name;
                } else {
                    $active[] = $name;
                }
            }
            $allFs[$name] = $fs;
        }
        return ArrayHelper::merge(
            [
                'components' => $allFs,
                'modules' => [
                    'media' => $attributes,
                ],
                'params' => [
                    'activeFsNames' => $active,
                    'glideCacheFsNames' => $cache,
                ],
            ],
            [
                'components' => [
//                    'urlManager' => [
//                        'excludeRoutes' => ['media/file/send', 'media/file/xsend'],
//                    ],
//                    @todo find error in UrlManager
                    'i18n' => [
                        'translations' => [
                            'devgroup.media-storage' => [
                                'class' => 'yii\i18n\PhpMessageSource',
                                'basePath' => dirname(__DIR__) . DIRECTORY_SEPARATOR . 'messages',
                            ],
                        ],
                    ],
                ],
                'modules' => ['media' => ['class' => MediaModule::class]],
            ]
        );
    }

    /**
     * Returns array of key=>values for configuration.
     *
     * @return mixed
     */
    public function appParams()
    {
        return [
            'flysystemDefaultConfigs' => [
                'fs' => [
                    'class' => 'creocoder\flysystem\LocalFilesystem',
                    'urlRule' => '/img/{{path}}',
                    'necessary' => [
                        'path' => '@app/media',
                    ],
                ],
                'awss3Fs' => [
                    'class' => 'creocoder\flysystem\AwsS3Filesystem',
                    'urlRule' => 'http://{{bucket}}.s3.amazonaws.com/{{path}}',
                    'necessary' => [
                        'key' => 'your-key',
                        'secret' => 'your-secret',
                        'bucket' => 'your-bucket',
                    ],
                    'unnecessary' => [
                        'region' => '',
                        'baseUrl' => '',
                        'prefix' => '',
                        'options' => '',
                    ],
                ],
                'ftpFs' => [
                    'class' => 'creocoder\flysystem\FtpFilesystem',
                    'urlRule' => '{{host}}/{{path}}',
                    'necessary' => [
                        'host' => 'ftp.example.com',
                    ],
                    'unnecessary' => [
                        'port' => '',
                        'username' => '',
                        'password' => '',
                        'ssl' => '',
                        'timeout' => '',
                        'root' => '',
                        'permPrivate' => '',
                        'permPublic' => '',
                        'passive' => '',
                        'transferMode' => '',
                    ],
                ],
                'sftpFs' => [
                    'class' => 'creocoder\flysystem\SftpFilesystem',
                    'urlRule' => '{{host}}/{{path}}',
                    'necessary' => [
                        'host' => 'sftp.example.com',
                        'username' => 'your-username',
                        'password' => 'your-password',
                    ],
                    'unnecessary' => [
                        'port' => '',
                        'privateKey' => '',
                        'timeout' => '',
                        'root' => '',
                        'permPrivate' => '',
                        'permPublic' => '',
                    ],
                ],
            ],
            'yii.migrations' => ['@vendor/devgroup/yii2-media-storage/src/migrations/'],
        ];
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
