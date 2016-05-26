<?php

use yii\db\Migration;
use yii\db\Connection;

class m160329_140554_init_media_storage extends Migration
{
    public function up()
    {
        $table_options = ($this->db->driverName === 'mysql') ? 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB' : null;

        $this->createTable(
            '{{%applicable_media_models}}',
            [
                'id' => $this->primaryKey(),
                'class_name' => $this->string(255)->notNull()->unique(),
                'name' => $this->string(255)->notNull(),
            ],
            $table_options
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
            '{{%media_group}}',
            [
                'id' => $this->primaryKey(),
                'name' => $this->string(255)->unique(),
            ],
            $table_options
        );
        $this->createTable(
            '{{%media_media_group}}',
            [
                'id' => $this->primaryKey(),
                'media_id' => $this->integer(),
                'media_group_id' => $this->integer(),
            ],
            $table_options
        );

        $this->addForeignKey(
            'fk-MmgMg',
            'media_media_group',
            'media_group_id',
            'media_group',
            'id',
            'CASCADE',
            'CASCADE'
        );
        $this->addForeignKey(
            'fk-MmgM',
            'media_media_group',
            'media_id',
            'media',
            'id',
            'CASCADE',
            'CASCADE'
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
    }

    public function down()
    {
        $this->dropTable('{{%media_route}}');
        $this->dropTable('{{%media_media_group}}');
        $this->dropTable('{{%media_group}}');
        $this->dropTable('{{%media}}');
        $this->dropTable('{{%applicable_media_models}}');
    }
}
