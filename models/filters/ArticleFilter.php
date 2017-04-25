<?php

namespace app\models\filters;

use Yii;
use yii\base\Model;
use app\models\Category;

class ArticleFilter extends SessionSavedFilter {

	public $city, $store_id;
	public $id;
	
	public $search_query;
	
	public $category_id, $sort_order;
	
	public $published;

	public function rules(){
		return [
			[['category_id', 'sort_order'], 'safe'],
		];
	}
	
	const SORT_DATE = 1;
	const SORT_RATING = 2;
	
	public static function getSorts(){
		return [
			-1 => 'сортировать ...',
			self::SORT_DATE => 'по дате',
			self::SORT_RATING => 'по популярности',
		];
	}

	public static function getCategories($add_all = true){
		$retval = [];
		if($add_all)
			$retval = ['0'=>'Все разделы новостей'];
			
		foreach(Category::GetList(0) as $cat)
			$retval[$cat->id] = $cat->title;
			
		return $retval;
	}


}