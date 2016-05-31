<?php

use kartik\date\DatePicker;
use unclead\widgets\MultipleInput;
use unclead\widgets\MultipleInputColumn;
use yii\bootstrap\Html;
use yii\bootstrap\Tabs;

$activeComponents = [];

echo $form->field($model, 'activeFS')->widget(
    MultipleInput::className(),
    [
        'min' => 0,
        'allowEmptyList' => true,
        'rowOptions' => function($model) {
            $options = [];
            if ($model['priority'] > 1) {
                $options['class'] = 'danger';
            }
            return $options;
        },
        'columns' => [
            [
                'name'  => 'user_id',
                'type'  => MultipleInputColumn::TYPE_DROPDOWN,
                'enableError' => true,
                'title' => 'User',
                'defaultValue' => 33,
                /* it can be an anonymous function
                'items' => function($data) {
                    return [
                        31 => 'item 31',
                        32 => 'item 32',
                        33 => 'item 33',
                        34 => 'item 34',
                        35 => 'item 35',
                        36 => 'item 36',
                    ];
                }
                */
                'items' =>  [
                    31 => 'item 31',
                    32 => 'item 32',
                    33 => 'item 33',
                    34 => 'item 34',
                    35 => 'item 35',
                    36 => 'item 36',
                ]
            ],
            [
                'name'  => 'day',
                'type'  => DatePicker::className(),
                'title' => 'Day',
                'value' => function($data) {
                    return $data['day'];
                },
                'items' => [
                    '0' => 'Saturday',
                    '1' => 'Monday'
                ],
                'options' => [
                    'pluginOptions' => [
                        'format' => 'dd.mm.yyyy',
                        'todayHighlight' => true
                    ]
                ],
                'headerOptions' => [
                    'style' => 'width: 250px;',
                    'class' => 'day-css-class'
                ]
            ],
            [
                'name'  => 'priority',
                'title' => 'Priority',
                'defaultValue' => 1,
                'enableError' => true,
                'options' => [
                    'class' => 'input-priority'
                ]
            ],
            [
                'name'  => 'comment',
                'type'  => MultipleInputColumn::TYPE_STATIC,
                'value' => function($data) {
                    return Html::tag('span', 'static content', ['class' => 'label label-info']);
                },
                'headerOptions' => [
                    'style' => 'width: 70px;',
                ]
            ],
            [
                'type' => MultipleInputColumn::TYPE_CHECKBOX_LIST,
                'name' => 'enable',
                'headerOptions' => [
                    'style' => 'width: 80px;',
                ],
                'items' => [
                    1 => 'Test 1',
                    2 => 'Test 2',
                    3 => 'Test 3',
                    4 => 'Test 4'
                ],
                'options' => [
                    // see checkboxList implementation in the BaseHtml helper for getting more detail
                    'unselect' => 2
                ]
            ]
        ]
    ]
);

foreach (array_keys($model->components) as $value) {
    $activeComponents[$value] = $value;
}
?>

<div id="fs-config">
    <div class="col-md-6 col-sm-12">

        <div class="clearfix"></div>
        <h2><?= Yii::t('app', 'Active Components') ?></h2>
        <?php
        foreach ($model['components'] as $componentName => $componentConf) {

            $necessaryContent = "";
            foreach ($componentConf['necessary'] as $necessaryConfName => $necessaryConfVal) {
                $content = $form->field(
                    $model,
                    "components[{$componentName}][necessary][{$necessaryConfName}]"
                )->label(
                    $necessaryConfName
                );
                if (is_bool($necessaryConfVal) === true || $necessaryConfName === 'active') {
                    $content = $content->widget(\kartik\widgets\SwitchInput::className());
                }
                $necessaryContent .= $content;
            }
            $unnecessaryContent = '';
            foreach ($componentConf['unnecessary'] as $unnecessaryConfName => $unnecessaryConfVal) {
                $unnecessaryContent .= $form->field(
                    $model,
                    "components[{$componentName}][unnecessary][{$unnecessaryConfName}]"
                )->label(
                    $unnecessaryConfName
                );
            }
            echo Tabs::widget(
                [
                    'items' => [
                        ['label' => Yii::t('app', 'necessary'), 'content' => $necessaryContent],
                        ['label' => Yii::t('app', 'unnecessary'), 'content' => $unnecessaryContent],
                    ],
                ]
            );

        }
        ?>


    </div>
    <div class="col-md-6 col-sm-12">
        <h2><?= Yii::t('app', 'Add new component') ?></h2>
        <?php
        foreach ($model['defaultComponents'] as $componentName => $componentConf) {

            $necessaryContent = $form->field(
                $model,
                "defaultComponents[{$componentName}][name]"
            )->label(
                'name'
            );
            foreach ($componentConf['necessary'] as $necessaryConfName => $necessaryConfVal) {
                $content = $form->field(
                    $model,
                    "defaultComponents[{$componentName}][necessary][{$necessaryConfName}]"
                )->label(
                    $necessaryConfName
                );
                if (is_bool($necessaryConfVal) === true || $necessaryConfName === 'active') {
                    $content = $content->widget(\kartik\widgets\SwitchInput::className());
                }
                $necessaryContent .= $content;
            }
            $unnecessaryContent = '';
            foreach ($componentConf['unnecessary'] as $unnecessaryConfName => $unnecessaryConfVal) {
                $unnecessaryContent .= $form->field(
                    $model,
                    "defaultComponents[{$componentName}][unnecessary][{$unnecessaryConfName}]"
                )->label(
                    $unnecessaryConfName
                );
            }
            echo Tabs::widget(
                [
                    'items' => [
                        ['label' => Yii::t('app', 'necessary'), 'content' => $necessaryContent],
                        ['label' => Yii::t('app', 'unnecessary'), 'content' => $unnecessaryContent],
                    ],
                ]
            );

        }
        ?>
    </div>