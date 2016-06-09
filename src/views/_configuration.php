<?php

use DevGroup\MediaStorage\helpers\MediaHelper;
use unclead\widgets\MultipleInput;
use unclead\widgets\MultipleInputColumn;
use yii\bootstrap\Tabs;
use yii\helpers\ArrayHelper;
use yii\helpers\VarDumper;
use yii\web\JsExpression;
use yii\web\View;

$activeComponents = [];

$this->registerJs('window.configTpl = ' . MediaHelper::getConfigurationTpl($form, $model), View::POS_HEAD);
if (count($model->activeFS) > 0) {
    foreach (array_keys($model->activeFS) as $array_key) {
        $this->registerJs(
            'window.configTpl.i' . $array_key . ' = ' . MediaHelper::getConfigurationTpl($form, $model, $array_key),
            View::POS_HEAD
        );
    }
}
$this->registerJs(
    /** @lang JavaScript */
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
};'
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
    "{{configInputs}}": window.configTpl[$(this).val()].strtr({
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
                    3 => 'Web acceptable',
                ],
            ],
        ],
    ]
);
$this->registerJs(
    /** @lang JavaScript */
    '$(\'#mediastorageconfiguration-activefs\').on(\'afterInit\', function () {
    $(\'#mediastorageconfiguration-activefs select\').each(function () {
        var $parentRow = $(this).closest(\'.multiple-input-list__item\');
        $parentRow.next(\'.jsable-row\').remove();
        var template = \'<tr class="jsable-row"><td colspan="{{cols}}">{{configInputs}}</td></tr>\';
        var number = $(this).attr(\'name\').split(\'[\')[2].slice(0, -1);
        $(template.strtr({
            "{{cols}}"        : 3,
            "{{configInputs}}": window.configTpl[\'i\' + number][$(this).val()].strtr({
                "{{number}}": number
            })
        })).insertAfter($parentRow);
    });
}).on(\'beforeDeleteRow\', function (e, item) {    
    item.next(\'.jsable-row\').remove();
});'
);
