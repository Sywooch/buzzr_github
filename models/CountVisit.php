<?php

namespace app\models;

use Yii;
use yii\base\Model;

class CountVisit extends Model {
	
	public $today, $month, $total;
	
	const TYPE_STORE = 1;
	const TYPE_PRODUCT = 2;
	
	public static function count($object_id, $object_type = CountVisit::TYPE_STORE){
		if(!Yii::$app->session->get('session_id'))
			Yii::$app->session->set('session_id', time());
			
		if(false !== strpos(Yii::$app->request->userAgent, 'bot'))
			return false;
			
		$record = ['object_id'=>$object_id, 'object_type'=> $object_type, 'session_id'=>Yii::$app->session->get('session_id')];

		Yii::$app->db->createCommand()
			->delete('count_visit', $record)
			->execute();
		
		Yii::$app->db->createCommand()
			->insert('count_visit', $record)
			->execute();
		
		return true;
	}
	
	public static function find($store_id, $object_type = CountVisit::TYPE_STORE){
		$model = new CountVisit;
		
		$today_start = mktime ($hour = 0, $minute = 0, $second = 0, $month = date("n"), $day = date("j"), $year = date("Y"));
		$today_end = mktime ($hour = 0, $minute = 0, $second = 0, $month = date("n"), $day = date("j")+1, $year = date("Y"));
		
		$model->today = (new \yii\db\Query)
			->select('count(*)')
			->from('count_visit')
			->where(['object_id'=>$store_id, 'object_type'=>$object_type])
			->andWhere("session_id >= $today_start")
			->andWhere("session_id <= $today_end")
			->scalar();

		$month_start = mktime ($hour = 0, $minute = 0, $second = 0, $month = date("n"), $day = 0, $year = date("Y"));
		
		$model->month = (new \yii\db\Query)
			->select('count(*)')
			->from('count_visit')
			->where(['object_id'=>$store_id, 'object_type'=>$object_type])
			->andWhere("session_id >= $month_start")
			->andWhere("session_id <= $today_end")
			->scalar();

		$model->total = (new \yii\db\Query)
			->select('count(*)')
			->from('count_visit')
			->where(['object_id'=>$store_id, 'object_type'=>$object_type])
			->scalar();

		
		return $model;
	}
}