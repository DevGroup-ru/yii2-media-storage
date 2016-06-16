<?php

$params = require(__DIR__ . '/params.php');
$db = require(__DIR__ . '/db.php');

$config = [
    'id' => 'basic-console',
    'vendorPath' => '@app/../../vendor',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'DevGroup\MediaStorage\commands',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
        ],
        'protectedFilesystem' => [
            'class' => 'creocoder\flysystem\LocalFilesystem',
            'path' => '@app/media',
        ],
        'multilingual' => [
            'class' => 'DevGroup\Multilingual\Multilingual',
        ],
    ],
    'params' => $params,
];


return $config;
