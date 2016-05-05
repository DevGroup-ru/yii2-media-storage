<?php

use app\models\Thing;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

$params = require(__DIR__ . '/params.php');

$config = [
    'vendorPath' => '@app/../vendor',
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log', 'multilingual', 'media'],
    'modules' => [
        'gii' => [
            'class' => 'yii\gii\Module',
        ],
        'debug' => [
            'class' => 'yii\debug\Module',
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
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => require(__DIR__ . '/db.php'),
        'multilingual' => [
            'class' => \DevGroup\Multilingual\Multilingual::className(),
            'default_language_id' => 1,
            'handlers' => [
                [
                    'class' => \DevGroup\Multilingual\DefaultGeoProvider::className(),
                    'default' => [
                        'country' => [
                            'name' => 'England',
                            'iso' => 'en',
                        ],
                    ],
                ],
            ],
        ],
        'filedb' => [
            'class' => 'yii2tech\filedb\Connection',
            'path' => __DIR__ . '/data',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
//                'media' => 'media/media/all-files',
//                'media/groups' => 'media/media/all-groups',
//                'media/show/item/<id:\d+>' => 'media/media/show-item',
//                'media/show/group/<id:\d+>' => 'media/media/show-group',
//                'media/save/item/<id:\d+>' => 'media/media/save-item',
//                'media/save/group/<id:\d+>' => 'media/media/save-group',
//                'media/delete/item/<id:\d+>' => 'media/media/delete-item',
//                'media/delete/group/<id:\d+>' => 'media/media/delete-group',
                //'POST media/save/item<id:\d+>' => 'media/media/save-item',
                //'POST media/save/group<id:\d+>' => 'media/media/save-group',
                //'DELETE media/delete/item/<id:\d+>' => 'media/media/delete-item',
                //'DELETE media/delete/group/<id:\d+>' => 'media/media/delete-group',
            ],
        ],
        'protectedFilesystem' => [
            'class' => 'creocoder\flysystem\LocalFilesystem',
            'path' => '@app/media',
        ],
    ],
    'controllerMap' => [
        'elfinder' => [
            'class' => 'mihaildev\elfinder\Controller',
            'access' => ['@', '?'], //глобальный доступ к фаил менеджеру @ - для авторизорованных , ? - для гостей , чтоб открыть всем ['@', '?']
            'disabledCommands' => ['netmount'], //отключение ненужных команд https://github.com/Studio-42/elFinder/wiki/Client-configuration-options#commands
            'roots' => [
                [
                    'baseUrl'=>'@web',
                    'basePath'=>'@webroot',
                    'path' => 'files/global',
                    'name' => 'Global'
                ],
            ],
        ]
    ],
    'params' => $params,
];

if (YII_DEBUG) {
    $config['bootstrap'][] = 'debug';
}

return $config;
