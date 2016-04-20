<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\Thing;

class ThingController extends Controller
{
    public function actionIndex()
    {
        return $this->render('index', [
            'things' => Thing::find()->orderBy(['id' => SORT_DESC])->all(),
        ]);
    }

    public function actionNew()
    {
        return $this->render('new');
    }

    public function actionSave()
    {
        $request = Yii::$app->request;

        $thing = new Thing([
            'prop' => $request->post('thing-prop'),
        ]);
        $thing->save();

        return $this->redirect(['thing/index']);
    }
}
