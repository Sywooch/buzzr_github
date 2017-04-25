<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use app\models\filters\SearchModel;
use yii\data\ActiveDataProvider;
use yii\behaviors\SluggableBehavior;

class Category extends ActiveRecord {
	
	public $count;
	
	const SERVICES_ID = 15;
	const SERVICES_SLUG = 'uslugi';
	
	public static function tableName(){
		return 'categories';
	}
	
	public function attributeLabels(){
		return [
			'title' => 'Название',
			'active' => 'Активность',
			'sort' => 'Порядок очередности отображения',
			'slug' => 'Код адреса',
			'parent_id' => 'Родительская категория'
		];
	}
	
	public function rules(){
		return [
			[['title', 'active', 'sort', 'slug', 'parent_id'], 'safe'],
		];
	}
		
	public function behaviors()
	{
	    return [
	        [
	            'class' => SluggableBehavior::className(),
	            'attribute' => 'title',
	            'slugAttribute' => 'slug',
	        ],
	    ];
	}
	
	public static function path2parent($path){
		
		$path_split = preg_split('/\//', $path);
		$parent_cat = null;
		$parent = 0;
		while(($path != '') && !empty($path_split)){
			$slug = array_shift($path_split);
			if(!$slug)continue;
			$parent_cat = Category::findOne(['parent_id'=>$parent, 'slug'=>$slug]);
			if(null == $parent_cat)
				break;
				
			$parent = $parent_cat->id;
		}
		return $parent_cat;

	}
	
	public static function isService($cat_id){
		$cat = Category::findOne(['id'=>$cat_id]);
		
		if(!$cat)return false;
		
		$path = $cat->getSlugPath();
		if(!empty($path) && ($path[0] == Category::SERVICES_SLUG))
			return true;
		else
			return false;
	}
	
	public static function GetList($parent){
		return Category::find()->where(['parent_id'=>$parent])->orderBy('sort')->all();
	}
	
	public function getChildren($count = false, $limit = false, $hide_empty = false){
		if(!$count){
			$query = Category::find()->where(['parent_id'=>$this->id])->orderBy('sort')->all();
			if($limit) $query = $query->limit($limit);
			return $query;
		}
		else {
			$search = new SearchModel;
			$search->loadFilter($_POST);
			$situableStores = Store::getSituableStores(['city'=>$search->city, 'active'=>1]);
			$query = Category::find()
					->select(['`categories`.*', 'count(`products`.`id`) as `count`'])
					->leftJoin('`categories` as `cc`', '`cc`.`parent_id` = `categories`.`id`')
					->leftJoin('`products`','
						(`products`.`category_type_id` = `categories`.`id`
							OR
						`products`.`category_type_id` = `cc`.`id`)
						AND `products`.`store_id` IN ('.join($situableStores, ',').')
						AND `products`.`active` = 1 
						AND `products`.`blocked` = 0
						AND `products`.`visible` = 1')
					->where(['`categories`.`parent_id`'	=> $this->id])
					->groupBy('`categories`.`id`')
					->orderBy(['`count`' => SORT_DESC]);
			if($limit) $query = $query->limit($limit);
			if($hide_empty) $query = $query->having('count > 0');
			
			return $query->all();
		}
	}
	
	public function getSlugPath(){
		$slugList = []; $infinite = 0;
		for($cat = $this->id; $cat != 0; $infinite++){
			$cat = Category::findOne(['id'=>$cat]);
			if(!$cat)
				throw new \yii\web\HttpException(500, 'No parent for '. $this->id);

			$slugList[] = $cat->slug;
			$cat = $cat->parent_id;
			if($infinite > 100)
				throw new \yii\web\HttpException(500, 'Infinite loop for category '. $this->id);

		}
		return array_reverse($slugList);
	}

	public function getBreadcrumbs(){
		$slugList = []; $infinite = 0;
		for($cat = $this->id; $cat != 0; $infinite++){
			$cat = Category::findOne(['id'=>$cat]);
			$slugList[] = ['title' => $cat->title, 'slug'=>$cat->slug, 'id'=> $cat->id];
			$cat = $cat->parent_id;
			if($infinite > 100)
				throw new \yii\web\HttpException(500, 'Infinite loop');

		}
		$slugList = array_reverse($slugList);

		$path = [];
		for($i = 0; $i < count($slugList); $i++){
			$path[] = $slugList[$i]['slug'];
			$slugList[$i]['path'] = join($path, '/');
		}
		
		return $slugList;
	}

	public static function andSubCategories($parent){
		
		if(!$parent)
			return $parent;
			
		if(!is_array($parent))
			$parent = [$parent];
		
		$latest_children = $parent;
		
		for($i = 0; ; $i++) {
			$new_children = Category::find()->where(['parent_id'=>$latest_children])->select('id')->column();

			if(!empty($new_children)){
				$parent = array_merge($parent, $new_children);
				$latest_children = $new_children;
			}
			else
				break;
				
			if($i > 10)
				throw new \yii\web\HttpException(500, 'Infinite cycle');
		}
		
		return $parent;
	}
	
	public function get_parent(){
		return $this->hasOne(Category::classname(), ['id'=>'parent_id']);
	}
	
	public static function getDataProvider($filter, $limit = 20, $addEdit = false){
		
		$query = self::find();
		
		if($filter)
			$query = $query->andWhere(['like', 'title', $filter]);
		
		return new ActiveDataProvider([
		    'query' => $query,
		    'pagination' => [
	        'pageSize' => $limit,
		    ],
		]);
	}
	
}