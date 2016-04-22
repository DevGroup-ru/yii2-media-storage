Media Storage module for Yii2 
===================

**WARNING:** This extension is under active development. Don't use it in production!


### Installing

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist devgroup/yii2-media-storage "*"
```

or add

```
"devgroup/yii2-media-storage": "*"
```

to the require section of your `composer.json` file.

1. Media Storage required [creocoder/yii2-flysystem](https://github.com/creocoder/yii2-flysystem). It will be installed as dependency, but you still need to [configure](https://github.com/creocoder/yii2-flysystem#configuring) that module.

2. Add routes to UrlManager:
 ```php
 'urlManager' => [
     'enablePrettyUrl' => true,
     'showScriptName' => false,
     'rules' => [
         'media' => 'media/media/index',
         'media/show/item/<id:\d+>' => 'media/media/show-item',
         'media/edit/item/<id:\d+>' => 'media/media/edit-item',
         'media/edit/group/<id:\d+>' => 'media/media/edit-group',
         'GET media/add/item' => 'media/media/new-item-form',
         'GET media/add/group' => 'media/media/new-group-form',
         'POST media/add/item' => 'media/media/add-item',
         'POST media/add/group' => 'media/media/add-group',
         'DELETE media/delete/item/<id:\d+>' => 'media/media/delete-item',
         'DELETE media/delete/group/<id:\d+>' => 'media/media/delete-group',
     ],
 ],
 ```

3. Add media module:
 ```php
 'modules' => [
     'media' => [
         'class' => 'app\modules\media\Module',
         'accessPermissions' => ['@'],
     ],
 ],
 ```
4. Run migrations:
 ```bash
 ./yii migrate --migrationPath='modules/media/migrations'
 ```

5. Put this lines to VirtualHost section of your domain in Apache configurations files:
 ```apacheconf
 XSendFile on
 XSendFilePath "/absolute-path-to-application-root/runtime/media-storage"
 ```

### Clean runtime folder
This module will store temporarily files in Yii runtime folder. For clean it, add module in console.php and run:
 ```bash
 ./yii media/clean
 ```
You can also create task for cron.


