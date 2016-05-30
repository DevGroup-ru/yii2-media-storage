<?php

use yii\bootstrap\Tabs;

$activeComponents = [];
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