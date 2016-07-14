<?php

use mihaildev\elfinder\Controller as ElfinderController;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

$params = require(__DIR__ . '/params.php');

$config = [
    'vendorPath' => '@app/../../vendor',
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log', 'multilingual', 'media', 'properties', 'adminUtils'],
    'modules' => [
        'gii' => [
            'class' => 'yii\gii\Module',
        ],
        'adminUtils' => [
            'class' => DevGroup\AdminUtils\AdminModule::class,
        ],
        'properties' => [
            'class' => 'DevGroup\DataStructure\Properties\Module',
        ],
        'media' => [
            'class' => 'DevGroup\MediaStorage\MediaModule',
        ],
    ],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'Nxc7OMuD0jBmtoxLmLENwmGw_1hVENsq',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
            'as lazy' => [
                'class' => 'DevGroup\TagDependencyHelper\LazyCache',
            ],
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
        //        'log' => [
        //            'traceLevel' => YII_DEBUG ? 3 : 0,
        //            'targets' => [
        //                [
        //                    'class' => 'yii\log\FileTarget',
        //                    'levels' => ['error', 'warning'],
        //                ],
        //            ],
        //        ],
        'db' => require(__DIR__ . '/db.php'),
        'filedb' => [
            'class' => 'yii2tech\filedb\Connection',
            'path' => __DIR__ . '/languages',
        ],
        'multilingual' => [
            'class' => \DevGroup\Multilingual\Multilingual::class,
            'language_id' => 1,
            'context_id' => 1,
            'handlers' => [
                [
                    'class' => \DevGroup\Multilingual\DefaultGeoProvider::class,
                    'default' => [
                        'country' => [
                            'name' => 'England',
                            'iso' => 'en',
                        ],
                    ],
                ],
            ],
        ],
        'urlManager' => [
            'class' => \DevGroup\Multilingual\components\UrlManager::class,
            'excludeRoutes' => false,

        ],
        'protectedFilesystem' => [
            'class' => 'creocoder\flysystem\LocalFilesystem',
            'path' => '@app/media',
        ],
        'i18n' => [
            'translations' => [
                'devgroup.media-storage' => [
                    'class' => 'yii\\i18n\\PhpMessageSource',
                    'basePath' => '@app/../../src/messages',
                ],
            ],
        ],
    ],
    'controllerMap' => [

    ],
    'params' => $params,
];


return $config;
