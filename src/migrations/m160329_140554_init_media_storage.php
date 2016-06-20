<?php

use DevGroup\DataStructure\models\PropertyHandlers;
use DevGroup\DataStructure\models\PropertyStorage;
use DevGroup\MediaStorage\properties\MediaHandler;
use DevGroup\MediaStorage\properties\MediaStorage;
use yii\db\Migration;

class m160329_140554_init_media_storage extends Migration
{
    public function up()
    {
        $table_options = ($this->db->driverName === 'mysql') ? 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB' : null;

        Yii::$app->runAction(
            'migrate/up',
            [
                'interactive' => 0,
                'migrationPath' => '@DevGroup/DataStructure/migrations',
                'migrationTable' => 'migrations_data',
            ]
        );

        $this->createTable(
            '{{%media}}',
            [
                'id' => $this->primaryKey(),
                'path' => $this->string()->notNull()->unique(),
                'mime' => $this->string()->notNull(),
            ],
            $table_options
        );
        $this->createTable(
            '{{%media_route}}',
            [
                'id' => $this->primaryKey(),
                'media_id' => $this->integer(),
                'url' => $this->string(255)->unique(),
                'params' => $this->text(),
            ],
            $table_options
        );

        $this->insert(
            PropertyHandlers::tableName(),
            [
                'name' => 'Media property',
                'class_name' => MediaHandler::class,
                'sort_order' => 4,
                'packed_json_default_config' => '{}',
            ]
        );
        $this->insert(
            PropertyStorage::tableName(),
            [
                'name' => 'Media property',
                'class_name' => MediaStorage::class,
                'sort_order' => 4,
            ]
        );
        Yii::$app->cache->flush();
    }

    public function down()
    {
        $this->delete(
            PropertyHandlers::tableName(),
            [
                'name' => 'Media property',
                'class_name' => MediaHandler::class,
                'sort_order' => 4,
            ]
        );
        $this->delete(
            PropertyStorage::tableName(),
            [
                'name' => 'Media property',
                'class_name' => MediaStorage::class,
                'sort_order' => 4,
            ]
        );
        $this->dropTable('{{%media_route}}');
        $this->dropTable('{{%media}}');
        Yii::$app->cache->flush();

        Yii::$app->runAction(
            'migrate/down',
            [
                'interactive' => 0,
                'migrationPath' => '@DevGroup/DataStructure/migrations',
                'migrationTable' => 'migrations_data',
            ]
        );
    }
}
