<?php

namespace DevGroup\MediaStorage\widgets;

use Yii;
use yii\base\Exception;
use yii\base\Widget;
use yii\db\ActiveRecord;
use yii\widgets\ActiveForm;

class MediaForm extends Widget
{
    /**
     * @var ActiveRecord
     */
    public $model = null;
    /**
     * @var ActiveForm
     */
    public $form = null;
    /**
     * @var string
     */
    public $viewFile = 'media-form';

    /**
     * @inheritdoc
     */
    public function init()
    {
        if (is_null($this->model)) {
            throw new Exception(Yii::t('app', 'Set model'));
        }
        if (is_null($this->form)) {
            throw new Exception(Yii::t('app', 'Set form'));
        }
        //@todo check instance
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        return $this->render($this->viewFile, ['form' => $this->form, 'model' => $this->model]);
    }
}
