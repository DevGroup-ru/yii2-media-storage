Media Storage module for Yii2 
===================

### Installing
Add routes to UrlManager:
```php
'media' => 'media/media/index',
'media/<id:\d+>' => 'media/media/show',
'media/add' => 'media/media/add',
'media/settings' => 'media/media/settings',
'POST media/upload' => 'media/media/upload',
'DELETE media/delete' => 'media/media/delete',
```

### Version
0.1
