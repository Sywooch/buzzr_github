<?php

namespace app\models\filters;

use Yii;
use yii\base\Model;

class ProductFilter extends SessionSavedFilter {
	public $parent;
	
	public $id, $exclude_id;
	
	public $filter_attribute;
	public $price_from, $price_to, $sort_order, $store_id;
	
	public $active = 1;
	
	public $limit;
	
	public $has_sale;
	
	public $edit_mode = false;
	
	public $product_slug;
	
	public $display_type;
	
	public $city;
	
	public $query;
	
	public $is_service;
	
	public $date_min;
	
	const NO_SORT = 0;
	const DATE_SORT = 1;
	const SORT_PRICE_ASC = 2;
	const SORT_PRICE_DESC = 3;
	const SORT_RATE = 4;
	const SORT_POPULAR = 5;
	
	public static function getSorts(){
		return [
			self::NO_SORT => 'Сортировать по',
			self::DATE_SORT => 'по дате',
			self::SORT_PRICE_ASC => 'от дешевых к дорогим',
			self::SORT_PRICE_DESC => 'от дорогих к дешевым',
			self::SORT_RATE => 'по рейтингу'
			];
	}
	
	public function attributeLabels(){
		return [
			'sort_order' => 'Порядок сортировки',
			'price_from' => 'цена мин',
			'price_to' => 'цена макс'
		];
	}
	
	public function rules(){
		return [
			[['filter_attribute', 'display_type'], 'safe'],
			[['price_from', 'price_to', 'sort_order'], 'integer']
		];
	}
	
	function setPath($path){
		if($path){
			$parent = Category::path2parent($path);
			if($parent)
				$this->parent = $parent->id;
		}
	}
	
	public function session_key(){
		return "filter_category_" . $this->parent;
	}
	
}