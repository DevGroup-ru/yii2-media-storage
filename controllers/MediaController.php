<?php

namespace app\modules\media\controllers;

use Yii;
use yii\web\Request;
use yii\web\Controller;
use yii\web\UploadedFile;
use yii\helpers\Json;
use yii\filters\AccessControl;
use app\modules\media\models\Media;

class MediaController extends Controller
{
	public function behaviors()
	{
		return [
			'access' => [
				'class' => AccessControl::className(),
				'rules' => [
					[
						'allow' => true,
						'roles' => $this->module->accessPermissions,
					]
				],
			],
		];
	}

	public function actionIndex()
	{
		$mediaLibrary = Media::find()->all();

		return $this->render('index', [
			'mediaLibrary' => $mediaLibrary,
		]);
    }

	public function actionShow()
	{
		return 'media item page';
	}

	public function actionSettings()
	{
        return $this->render('settings');
    }

	public function actionAdd()
	{
        return $this->render('upload-form');
    }

	public function actionUpload()
	{
        $file = UploadedFile::getInstanceByName('media-file');

        if ($file->hasError) {
            throw new HttpException(500, 'Upload error');
        }

		$request = Yii::$app->request;
		$title = $request->post('media-title', null);

		$uplDir = Yii::getAlias('@app/media-storage/');

		if (!file_exists($uplDir)) {
			mkdir($uplDir);
		}

		$filename = $file->name;
		$i = 1;

		while(file_exists($uplDir.$filename)) {
			$filename = $file->baseName . '_' . $i++ . $file->extension;
		}

        $file->saveAs($uplDir.$filename);

		$media = new Media([
			'path'  => $uplDir.$filename,
			'title' => $title,
		]);
		$media->save();

        return Json::encode(['status' => 'ok', 'file' => $filename]);
    }

	public function actionDelete($id)
	{
		$result = false;
		$media = Media::findOne($id);

		if ($media !== null) {
			$result = $media->delete();
		}

		return Json::encode(['result' => $result]);
	}
}
