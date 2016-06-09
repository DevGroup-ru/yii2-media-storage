<?php
return [
    [
        'sectionName' => Yii::t('devgroup.media-storage', 'Yii2 media storage settings'),
        'configurationView' => 'src/views/_configuration.php',
        'configurationModel' => DevGroup\MediaStorage\models\MediaStorageConfiguration::class,
    ],
];
