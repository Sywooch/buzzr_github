<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\db\Query;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;
use app\models\Notification;

class Message extends ActiveRecord {
    
    public function rules(){
        return [
            [['receiver_id', 'text'], 'required']
        ];
    }
    
    public function attributeLabels(){
    	return [
    		'text'=>'Текст сообщения'
    	];
    }
    
    public function beforeSave($insert){
        $this->sender_id = Yii::$app->user->id;
        $this->timestamp = time();
        
        $to = User::findOne(['id'=>$this->receiver_id]);
        $link = Yii::$app->request->hostInfo . Url::toRoute(['cabinet/chat', 'receiver_id' => $this->receiver_id]);
        $notify = new Notification();
		$notify->setOptions($to->email, 'Новое сообщение');
		$notify->compose('message', ['link' => $link, 'from' => Yii::$app->user->identity->name]);
		
        return parent::beforeSave($insert);
    }
    
	public static function tableName(){
		return 'messages';
	}
	
	public function get_from(){
		return $this->hasOne(User::className(), ['id'=>'sender_id']);
	}
	public function get_to(){
		return $this->hasOne(User::className(), ['id'=>'receiver_id']);
	}
	public function get_date(){
		return date('d-m-y H:i:s', $this->timestamp);
	}
	
	public static function GetListQuery($receiver_id){
		if(Yii::$app->user->getIsGuest())
			return $this->goHome()->send();
			
		$me = Yii::$app->user->id;
		
		$messages = Message::find()->
			where(['OR',
				['sender_id'=>$me, 'receiver_id'=>$receiver_id],
				['sender_id'=>$receiver_id, 'receiver_id'=>$me]
			])->distinct()->orderBy(['timestamp'=>SORT_DESC]);
			
		return $messages;

	}
	
}