<?php

use yii\db\Migration;

class m160329_140554_init_media_storage extends Migration
{
    public function up()
    {
        $this->createTable('media-storage', [
            'id'     => $this->primaryKey(),
            'author' => $this->integer(),
            'path'   => $this->string(),
            'title'  => $this->string(),
        ]);
    }

    public function down()
    {
        $this->dropTable('media-storage');
    }
}
