<?php

use yii\db\Migration;
use yii\db\Connection;

class m160329_140554_init_media_storage extends Migration
{
    public function up()
    {
        $table_options = ($this->db->driverName === 'mysql') ? 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB' : null;

        $this->createTable(
            'media-storage',
            [
                'id' => $this->primaryKey(),
                'group_id' => $this->integer()->defaultValue(1),
                'author_id' => $this->integer(),
                'path' => $this->string(),
                '_title' => $this->string(),
            ],
            $table_options
        );

        $this->insert(
            'media-storage',
            [
                'group_id' => 3,
                'author_id' => 101,
                'path' => '/media-storage/2016/04/B1rP8NhJmFk.jpg',
                '_title' => 'gow',
            ]
        );

        $this->createTable(
            'media-storage-groups',
            [
                'id' => $this->primaryKey(),
                'name' => $this->string(64)->unique(),
                'permissions' => $this->string(255),
            ],
            $table_options
        );
        $this->batchInsert(
            'media-storage-groups',
            ['name', 'permissions',],
            [['Main', null], ['Cats', '[\"media-admin\"]'], ['Books', '[\"random-item\"]']]
        );

        $this->createTable(
            'media-storage-groups-permissions',
            [
                'id' => $this->primaryKey(),
                'group_id' => $this->integer(),
                'name' => $this->string(64)->notNull(),
            ],
            $table_options
        );

        $this->createTable(
            'media-storage-relations',
            [
                'id' => $this->primaryKey(),
                'media_id' => $this->integer(),
                'object_id' => $this->integer(),
                'object_model' => $this->string(64),
            ],
            $table_options
        );

        $this->addForeignKey(
            'fk-media_id-media_group',
            'media-storage',
            'group_id',
            'media-storage-groups',
            'id',
            'CASCADE',
            'CASCADE'
        );
        $this->addForeignKey(
            'fk-media_group_permissions-media_group',
            'media-storage-groups-permissions',
            'group_id',
            'media-storage-groups',
            'id',
            'CASCADE',
            'CASCADE'
        );
        $this->addForeignKey(
            'fk-media_group_permissions-auth_item',
            'media-storage-groups-permissions',
            'name',
            'auth_item',
            'name',
            'CASCADE',
            'CASCADE'
        );
        $this->addForeignKey(
            'fk-media_id-object_id',
            'media-storage-relations',
            'media_id',
            'media-storage',
            'id',
            'CASCADE',
            'CASCADE'
        );
    }

    public function down()
    {
        $this->dropTable('media-storage-groups-permissions');
        $this->dropTable('media-storage-relations');
        $this->dropTable('media-storage-groups');
        $this->dropTable('media-storage');
    }
}
