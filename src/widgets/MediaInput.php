<?php


namespace DevGroup\MediaStorage\widgets;

use mihaildev\elfinder\AssetsCallBack;
use mihaildev\elfinder\InputFile;
use yii\helpers\Html;
use yii\helpers\Json;

class MediaInput extends InputFile
{
    public function init()
    {
        parent::init();
    }

    public function run()
    {
        if ($this->hasModel()) {
            //$replace['{input}'] = Html::activeTextInput($this->model, $this->attribute, $this->options);
            $replace['{input}'] = '';
            $inputName = Html::getInputName($this->model, $this->attribute) . '[]';
            $inputId = Html::getInputId($this->model, $this->attribute);
        } else {
            $replace['{input}'] = Html::textInput($this->name, $this->value, $this->options);
            $inputName = $this->name;
            $inputId = $this->name;
        }


        $replace['{button}'] = Html::tag($this->buttonTag, $this->buttonName, $this->buttonOptions);


        echo strtr($this->template, $replace);

        AssetsCallBack::register($this->getView());

        if ($this->multiple === true) {

            $this->getView()->registerJs(

                strtr(
                /** @lang JavaScript */
                    "mihaildev.elFinder.register(optId, function (files, id) {
                        var _f = [];
                        for (var i in files) {
                            _f.push(files[i].hash);
                        }
                        $.getJSON('/media/elfinder/connect', {
                            cmd    : \"info\",
                            targets: _f
                        }, function (data) {

                            data.files.every(function (i) {
                                if ($('.multi-media input[value=\"' + i.id + '\"]').length === 0) {
                                    $('.multi-media').append('<input type=\"hidden\" id=\"inputId-' + i.id + '\" class=\"form-control\" name=\"inputName\" value=\"' + i.id + '\">');
                                }
                                return true;
                            });
                        });

                        return true;
                    });

                    $(document).on('click', '#buttonId', function () {
                        mihaildev.elFinder.openManager(managerOpts);
                    });",
                    [
                        'optId' => Json::encode($this->options['id']),
                        'buttonId' => $this->buttonOptions['id'],
                        'managerOpts' => Json::encode($this->_managerOptions),
                        'inputName' => $inputName,
                        'inputId' => $inputId,
                    ]
                )
            );
        } else {
            $this->getView()->registerJs(

                strtr(
                /** @lang JavaScript */
                    "mihaildev.elFinder.register(optId, function (file, id) {
                            var _f = [];
                            _f.push(file.hash);
                            $.getJSON('/media/elfinder/connect', {
                                cmd    : \"info\",
                                targets: _f
                            }, function (data) {
                                data.files.every(function (i) {
                                    $('#' + id).val(i.id).trigger('change', [file, id]);
                                    return true;
                                });
                            });
                            $('#' + id).val(file.url).trigger('change', [file, id]);

                            return true;
                        }
                    );
                    $(document).on('click', '#buttonId', function () {
                        mihaildev.elFinder.openManager(managerOpts);
                    });",
                    [
                        'optId' => Json::encode($this->options['id']),
                        'buttonId' => $this->buttonOptions['id'],
                        'managerOpts' => Json::encode($this->_managerOptions),
                        'inputName' => $inputName,
                    ]
                )
            );
        }
    }
}
