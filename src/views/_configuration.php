<?php

use DevGroup\MediaStorage\helpers\MediaHelper;
use unclead\widgets\MultipleInput;
use unclead\widgets\MultipleInputColumn;
use yii\bootstrap\Tabs;
use yii\helpers\ArrayHelper;
use yii\web\JsExpression;
use yii\web\View;

$activeComponents = [];

$this->registerJs('window.cofgTpl = ' . MediaHelper::getConfigurationTpl($form, $model), View::POS_HEAD);
$this->registerJs(
    'String.prototype.strtr = function (replacePairs) {
    "use strict";
    var str = this.toString(), key, re;
    for (key in replacePairs) {
        if (replacePairs.hasOwnProperty(key)) {
            re = new RegExp(key, "g");
            str = str.replace(re, replacePairs[key]);
        }
    }
    return str;
}'
);
echo $form->field(
    $model,
    'activeFS',
    [
        'horizontalCssClasses' => [
            'label' => 'col-sm-12',
            'offset' => '',
            'wrapper' => 'col-sm-12',
            'error' => '',
            'hint' => '',
        ],
    ]
)->widget(
    MultipleInput::className(),
    [
        'min' => 0,
        'allowEmptyList' => true,
        'columns' => [
            [
                'name' => 'class',
                'type' => MultipleInputColumn::TYPE_DROPDOWN,
                'enableError' => true,
                'title' => 'FS',
                'items' => function ($data) {
                    return ArrayHelper::merge([0 => ''], MediaHelper::getFsCfgDropdown());
                },
                'options' => [
                    'onchange' => new JsExpression(
                    /** @lang JavaScript */
                        'var $parentRow = $(this).closest(\'.multiple-input-list__item\');
$parentRow.next(\'.jsable-row\').remove();
var template = \'<tr class="jsable-row"><td colspan="{{cols}}">{{configInputs}}</td></tr>\';
$(template.strtr({
    "{{cols}}"        : 3,
    "{{configInputs}}": window.cofgTpl[$(this).val()].strtr({
        "{{number}}": $(this).attr(\'name\').split(\'[\')[2].slice(0, -1)
    })
})).insertAfter($parentRow);'
                    ),
                ],
            ],
            [
                'name' => 'name',
                'title' => 'name',
                'defaultValue' => 'fs',
                'enableError' => true,
            ],
            [
                'type' => MultipleInputColumn::TYPE_CHECKBOX_LIST,
                'name' => 'options',
                'headerOptions' => [
                    'style' => 'width: 80px;',
                ],
                'items' => [
                    1 => 'active',
                    2 => 'use as Glide cache',
                ],
            ],
        ],
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