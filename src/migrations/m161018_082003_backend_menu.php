<?php

use app\models\BackendMenu;
use DevGroup\TagDependencyHelper\NamingHelper;
use yii\caching\TagDependency;
use yii\db\Migration;

class m161018_082003_backend_menu extends Migration
{
    public function up()
    {
        try {
            $this->insert(
                BackendMenu::tableName(),
                [
                    'parent_id' => 0,
                    'name' => 'Media',
                    'icon' => 'fa fa-file',
                    'sort_order' => 100,
                    'rbac_check' => 'backend-view',
                    'css_class' => '',
                    'route' => '',
                    'translation_category' => 'devgroup.media-storage',
                    'added_by_ext' => 'media-storage',
                ]
            );
            $menuId = $this->db->getLastInsertID();
            $this->batchInsert(
                BackendMenu::tableName(),
                [
                    'parent_id',
                    'name',
                    'icon',
                    'sort_order',
                    'rbac_check',
                    'css_class',
                    'route',
                    'translation_category',
                    'added_by_ext',
                ],
                [
                    [
                        $menuId,
                        'All files',
                        'fa fa-table',
                        0,
                        'backend-view',
                        '',
                        '/media/media/all-files',
                        'devgroup.media-storage',
                        'media-storage',
                    ],
                    [
                        $menuId,
                        'Media meta',
                        'fa fa-tags',
                        0,
                        'backend-view',
                        '',
                        '/media/media/media-meta',
                        'devgroup.media-storage',
                        'media-storage',
                    ],
                ]
            );
            TagDependency::invalidate(Yii::$app->cache, NamingHelper::getCommonTag(BackendMenu::class));
        } catch (\Exception $exception) {

        }
    }

    public function down()
    {
        try {
            $this->delete(
                BackendMenu::tableName(),
                ['name' => ['Media',]]
            );
            TagDependency::invalidate(Yii::$app->cache, NamingHelper::getCommonTag(BackendMenu::class));
        } catch (\Exception $exception) {

        }
    }

}
