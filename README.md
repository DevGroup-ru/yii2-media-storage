Media Storage module for Yii2 
===================

### Installing

1. Media Storage required [creocoder/yii2-flysystem](https://github.com/creocoder/yii2-flysystem). It will be installed as dependency, but you still need to [configure](https://github.com/creocoder/yii2-flysystem#configuring) that module.

2. Add routes to UrlManager:
 ```php
 'urlManager' => [
     'enablePrettyUrl' => true,
     'showScriptName' => false,
     'rules' => [
         'media' => 'media/media/index',
         'media/item/<id:\d+>' => 'media/media/show-item',
         'media/group/<id:\d+>' => 'media/media/show-group',
         'GET media/add/item' => 'media/media/new-item-form',
         'GET media/add/group' => 'media/media/new-group-form',
         'POST media/add/item' => 'media/media/add-item',
         'POST media/add/group' => 'media/media/add-group',
         'DELETE media/delete/item/<id:\d+>' => 'media/media/delete-item',
         'DELETE media/delete/group/<id:\d+>' => 'media/media/delete-group',
     ],
 ],
 ```

3. Run migrations:
 ```bash
 ./yii migrate --migrationPath='modules/media/migrations'
 ```

### Version
0.1
