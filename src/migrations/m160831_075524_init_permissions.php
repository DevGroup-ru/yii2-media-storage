<?php

use yii\db\Migration;

class m160831_075524_init_permissions extends Migration
{
    public function up()
    {
        $auth = Yii::$app->authManager;
        $admin = $auth->createRole('MediaStorageAdministrator');
        $admin->description = 'Media Storage Administration Role';
        $auth->add($admin);

        $mediaAdministrate = $auth->createPermission('mediastorage-administrate');
        $mediaAdministrate->description = 'Media Storage Administrate Permission';
        $auth->add($mediaAdministrate);

        $auth->addChild($admin, $mediaAdministrate);

    }

    public function down()
    {
        $auth = Yii::$app->authManager;
        $perm = $auth->getPermission('mediastorage-administrate');
        $auth->remove($perm);
        $role = $auth->getRole('MediaStorageAdministrator');
        $auth->remove($role);
    }
}
