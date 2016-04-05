<?php

namespace app\modules\media\widgets;

use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Modal;
use app\modules\media\models\Media;
use app\modules\media\assets\WidgetAsset;

class MediaLibrary extends Widget
{
    public $input = 'media-item-id';

    public function init()
    {
        WidgetAsset::register($this->getView());
    }

    public function run()
    {
        $media_library = Media::find()->orderBy(['id' => SORT_DESC])->all();

        ob_start();

        Modal::begin([
            'toggleButton' => ['label' => 'Add File'],
            'size'         => Modal::SIZE_LARGE,
            'id'           => 'media-storage-library-widget',
            'header'       => '<h3>Select File</h3>',
            'footer'       => '<button class="media-choise-confirm js-link btn btn-primary">Confirm</button>',
        ]);

        echo Html::beginTag('div', ['class' => 'media-storage-widget row']);

        foreach($media_library as $media) {
            echo Html::beginTag('div', ['class' => 'col-md-2']);

            echo Html::a(
                Html::img(['media/media/show-item', 'id' => $media->id], ['alt' => $media->title]),
                '#',
                ['class' => 'media-item thumbnail js-link', 'data-id' => $media->id]
            );

            echo Html::endTag('div');
        }

        echo Html::hiddenInput($this->input, 0, ['autocomplete' => 'off']);

        echo Html::endTag('div');

        Modal::end();

        return ob_get_clean();
    }
}
