<?php

use yii\db\Migration;

class m160405_161651_add_relations_table extends Migration
{
    public function up()
    {
        $this->createTable('media-storage-relations', [
            'id'           => $this->primaryKey(),
            'media_id'     => $this->integer(),
            'object_id'    => $this->integer(),
            'object_model' => $this->string(24),
        ]);

        $this->addForeignKey('fk-media_id-object_id', 'media-storage-relations', 'media_id', 'media-storage', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->dropForeignKey('fk-media_id-object_id', 'media-storage-relations');
        $this->dropTable('media-storage-relations');
    }
}
