<?php

namespace app\controllers\cabinet;

use Yii;
use app\controllers\BaseController as Controller;
use yii\web\UploadedFile;

class UserController extends Controller {
	
	public function actionIndex(){
		if(Yii::$app->user->isGuest)
			return $this->goHome();
			
		$user = Yii::$app->user->identity;
		
		if($user->load($_POST) && $user->validate())
			$user->save();
		
		return $this->render('edit', ['user'=>$user]);
	}
	
	public function actionAvatar()
	{
		$model = Yii::$app->user->identity;
	    $model->image = UploadedFile::getInstance($model, 'avatar_tmp');
		if($result = $model->upload()){
			return JSON_encode($result);
		}
	    return '';
	}

}
