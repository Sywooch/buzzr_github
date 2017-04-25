<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\db\Query;


class Contragent extends Model {
	
	public $me, $contragent, $last_message;
	
	public static function getContragents($me){
		
		$list = Yii::$app->db
			->createCommand(
				"(select receiver_id as contragent from messages where sender_id = $me)
					union
				(select sender_id as contragent from messages where receiver_id = $me)")
				->queryAll();
				
		$result = [];
		
		foreach($list as $c_result){
			$c_obj = new Contragent;
			$result[] = $c_obj;
			$c_obj->me = $me;
			$c_obj->contragent = User::findOne(['id'=>$c_result['contragent']]);
		}
				
		return $result;
		
	}
	
	public function getUnread(){
		$q = new Query;
		$ts = $q->select('ts')
			->from('dialogs_seen')
			->where(['owner_id'=>$this->me, 'sender_id'=>$this->contragent->id])->scalar();
			
		$q = new Query;
		$q = $q->select('count(*)')
				->from('messages')
				->where(['sender_id'=>$this->contragent->id, 'receiver_id'=>$this->me]);
				
		if($ts)
			$q = $q->andWhere("`timestamp`>=$ts");
				
		return $q->scalar();
	}
	
	public static function markRead($me, $contragent_id){
		Yii::$app->db->createCommand()->delete('dialogs_seen', ['owner_id'=>$me, 'sender_id'=>$contragent_id])->execute();
		Yii::$app->db->createCommand()->insert('dialogs_seen', ['owner_id'=>$me, 'sender_id'=>$contragent_id, 'ts'=>time()])->execute();
	}
	
}