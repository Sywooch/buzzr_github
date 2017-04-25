<?php

namespace app\controllers\cabinet;

use Yii;
use app\controllers\cabinet\BaseCabinetController as Controller;
use app\models\ProductComment;
use app\models\UserNotifications;

class CommentsController extends Controller {
	public function actionIndex(){
		if(Yii::$app->user->getIsGuest())
			return $this->goHome();

		if(($pc = Yii::$app->request->post('ProductComment')) && (isset($pc['id']))){
			$id = $pc['id'];
			$comment = ProductComment::findOne(['id'=>$id]);
			$comment->scenario = 'answer';
			if($comment->load($_POST) && $comment->validate()){
				$comment->load($_POST);
				$comment->save(false);
			}
		}

		UserNotifications::shutup('last_comment');

		return $this->render('comments', ['commentsProvider'=>ProductComment::getDataProvider(Yii::$app->user->id)]);		
	}
	
}
