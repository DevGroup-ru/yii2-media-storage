Media Storage module for Yii2 
===================

> **WARNING:** This extension is under active development. Don't use it in production!


### Installing

The preferred way to install this extension is through [extension manager](https://github.com/DevGroup-ru/yii2-extensions-manager)

Another way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist devgroup/yii2-media-storage "*"
```

or add

```
"devgroup/yii2-media-storage": "*"
```

to the require section of your `composer.json` file.


### Configure

If extension installed throw [extension manager](https://github.com/DevGroup-ru/yii2-extensions-manager) simple go to [config page](http://demo.com/extensions-manager/extensions/config) and select `media storage` section.

If extension installed throw  [composer](http://getcomposer.org/download/) you need to add to configuration

```php
[    
    'bootstrap' => ['media', 'properties'],
    'modules' => [       
        'properties' => [
            'class' => 'DevGroup\DataStructure\Properties\Module',
        ],
        'media' => [
            'class' => 'DevGroup\MediaStorage\MediaModule',
        ],
    ],
    'components' => [
       
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

        'protectedFilesystem' => [
            'class' => 'creocoder\flysystem\LocalFilesystem',
            'path' => '@app/media',
        ],
    ],
];
```