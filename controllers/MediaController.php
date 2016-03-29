<?php

namespace app\modules\media\controllers;

class MediaController extends Controller
{
	public function actionIndex()
	{
        return $this->render('index');
    }
}
