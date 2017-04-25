<?php

namespace app\models\filters;

use Yii;
use yii\base\Model;
use yii\db\Query;

class SearchModel extends SessionSavedFilter {
	
	public $search_query;
	public $search_in;
	public $city;
	
	const ST_PRODUCTS = 1;
	const ST_SHOPS = 2;
	const ST_NEWS = 3;
	const ST_SERVICES = 4;
	const ST_ORGS = 5;
	
	public function rules(){
		return [
			[['search_query', 'search_in', 'city'], 'safe']
		];
	}
	
	public static function getTypes(){
		return [
			self::ST_PRODUCTS => 'Товары',
			self::ST_SHOPS => 'Магазины',
			self::ST_NEWS => 'Новости',
			self::ST_SERVICES => 'Услуги',
			self::ST_ORGS => 'Организации'
		];
	}
	
	public static function getCities(){
		$query = new Query;
		$query = $query->select('*')
			->from('cities')
			->all();
			
		$retval = [];
		foreach($query as $city){
			$retval[$city['id']] = $city['title'];
		}
		unset($retval[-2]);
		return $retval;
	}
	

}