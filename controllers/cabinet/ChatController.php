<?php

namespace app\controllers\cabinet;

use Yii;
use app\controllers\cabinet\BaseCabinetController as Controller;
use app\models\Message;
use app\models\Contragent;
use app\models\User;
use app\models\UserNotifications;
use yii\helpers\Url;

class ChatController extends Controller {

	public function actionIndex($receiver_id = null, $ajax = false){

		if(Yii::$app->user->getIsGuest())
			return $this->goHome();

		$contragents = Contragent::getContragents(Yii::$app->user->id);

		if(!$receiver_id)
			return $this->render('contragents-not-selected', ['contragents'=>$contragents]);
			
		if($receiver_id && !User::findOne(['id'=>$receiver_id])){
			return $this->goHome();
		}
		
		if(Yii::$app->user->getIsGuest())
			return $this->goHome();
			
		$me = Yii::$app->user->id;
			
		$message = new Message;
		$message->receiver_id = $receiver_id;
		
		if($message->load($_POST) && $message->validate()){
			$message->save();
			$message->text = '';
		}

		$messages = Message::GetListQuery($receiver_id);
		
		Contragent::markRead($me, $receiver_id);
		
		if($ajax)
			return $this->renderAjax('chat-modal-inner', ['messages'=>$messages, 'message'=>$message, 'me'=>$me]);
		else{
			Url::remember();
			return $this->render('messages', ['messages'=>$messages, 'message'=>$message, 'me'=>$me, 'contragents'=>$contragents]);
		}
	}
	
	public function actionDelete($id){
		
		$message = Message::findOne(['id'=>$id]);
		if($message && !Yii::$app->user->getIsGuest() && ($message->sender_id == Yii::$app->user->id)){
			$message->delete();
		}
		
		return $this->goBack();
	}
}