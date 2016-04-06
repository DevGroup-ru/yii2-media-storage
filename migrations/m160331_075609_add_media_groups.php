<?php

use yii\db\Migration;

class m160331_075609_add_media_groups extends Migration
{
    public function up()
    {
        $this->createTable('media-storage-groups', [
            'id'   => $this->primaryKey(),
            'name' => $this->string(64),
        ]);

        $this->addColumn('media-storage', 'group_id', 'integer');
        $this->addForeignKey('fk-media_id-media_group', 'media-storage', 'group_id', 'media-storage-groups', 'id', 'CASCADE', 'CASCADE');

        $this->insert('media-storage-groups', [
            'name' => 'Main',
        ]);
    }

    public function down()
    {
        $this->dropForeignKey('fk-media_id-media_group', 'media-storage');
        $this->dropColumn('media-storage', 'group_id');

        $this->dropTable('media-storage-groups');
    }
}
