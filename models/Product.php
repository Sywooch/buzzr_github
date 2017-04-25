<?php

namespace app\models;

use Yii;
use yii\helpers\BaseArrayHelper as ArrayHelper;
use yii\db\ActiveRecord;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\helpers\Url;
use app\models\filters\ProductFilter;
use yii\db\Query;
use yii\behaviors\SluggableBehavior;
use app\behaviors\FullTextSearch;
use app\components\MediaLibrary;

class Product extends ActiveRecord {
	
	public $user_id;
	public $store_slug;
	public $sort;
	public $block_id;
	public $image, $image_list;
	public $type = Product::TYPE_ORDINARY;
	
	use \app\traits\MultiplePhotos;
	
	const TYPE_ORDINARY = 1;
	const TYPE_ADD_PRODUCT = 2;

	const SCENARIO_CREATE = 'create';
	const SCENARIO_UPDATE = 'update';

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_CREATE] = [
        	'description', 'title', 'active', 'blocked', 'amount', 'manufactures', 'category_type_id', 'slug', 'store_id', 'select'
    	];
        $scenarios[self::SCENARIO_UPDATE] = [
        	'description', 'title', 'active', 'blocked', 'amount', 'manufactures', 'category_type_id', 'slug', 'store_id', 'select'
    	];
        return $scenarios;
    }
	
	public function behaviors()
	{
	    return [
	        [
	            'class' => SluggableBehavior::className(),
	            'attribute' => 'title',
	            'slugAttribute' => 'slug',
	            'ensureUnique' => true
	        ],
            [
                'class' => FullTextSearch::className(),
                'fulltext_result_field' => 'search_text',
                'fulltext_source_fields' => ['title', 'keywords'],
            ],
	    ];
	}
	
	public function rules(){
		return [
            [['image'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg'],
			[['title', 'description'], 'required'],
			[['rating', 'select'], 'integer'],
			[['discount_amount', 'manufacturer_country', 'available', 'active',
				'unavailable_comment', '_custom_attributes', '_actions', '_filter_attribute', 'image_list',
				'manufactures', 'amount', 'pay_type', 'keywords', 'photo_link'], 'safe'],
			['photo_link', 'url'],
			['blocked', 'safe'],
			['_actions', 'defaultOnEmpty', 'skipOnEmpty'=>false],
			['_custom_attributes', 'defaultOnEmpty', 'skipOnEmpty'=>false],
			['image_list', 'validate_images', 'skipOnEmpty'=>false]
		];
	}

	public function attributeLabels(){
		return [
			'title' => 'Название товара',
			'description' => 'Описание',
			'discount_amount' => 'Цена со скидкой',
			'manufacturer_country' => 'Страна производитель',
			'available' => 'В наличии',
			'active' => 'Показывать в списке товаров и услуг',
			'blocked' => 'Заблокирован администратором',
			'unavailable_comment' => 'Комментарий для отсутствующего товара',
			'_custom_attributes' => 'Дополнительные характеристики',
			'_actions' => 'Акции',
			'amount' => 'Цена',
			'rating' => 'Оценка',
			'pay_type' => 'Единицы измерения',
			'photo_link' => 'Ссылка на фотогалерею',
			'keywords' => 'Ключевые слова',
			'manufactures' => 'Производитель',
			'slug' => 'Код адреса',
			'store_id' => 'магазин',
			'category_type_id' => 'Категория',
			'select' => 'Выделить товар',
			'block_id' => 'Блок'
			
		];
	}

	public static function getPayTypeList()
	{
	        $result = [
	                0 => 'час',
	                1 => 'день',
	                2 => 'за услугу',
	                3 => 'договорная',
	                4 => 'ед.',
	                5 => 'шт.',
	                6 => 'м2',
	                7 => 'м3',
	                8 => 'мп',
	                9 => 'сотка',
	                10 => 'га',
	        ];
	
	        return $result;
	}
	
	public function validate_images($attr){
		if(empty(JSON_decode($this->$attr, true)))
			$this->addError($attr, 'У товара должны быть фотографии');
	}
	
	public function defaultOnEmpty($attr){
		if(!isset($_POST['Product'][$attr]))
			$this->$attr = [];
	}
	
	public function getDestDir(){
		$store = $this->store_id;
		$user = $this->store->user_id;
		return $_SERVER['DOCUMENT_ROOT'] . "/uploads/user_$user/store_$store/image";
	}
	
	public function afterSave($insert, $p2){

		$this->multiPhotoAfterSave('product_photos', 'product_id', 4);
		
		return parent::afterSave($insert, $p2);
	}
	
	const imageRoute = 'upload/product_image';
	
	public static function tableName(){
		return 'products';
	}
	
	public function getProductAttributes(){
		$query = new Query;
		$query = $query->select('attributes_list.title as title, attribute_value.label as label')
					->from('product_attribute')
					->where(['product_attribute.product_id'=>$this->id])
					->leftJoin('attributes_list', 'attributes_list.id=product_attribute.attribute_id')
					->leftJoin('attribute_value', 'attribute_value.id=product_attribute.value_id');

		return $query->all();
	}
	
	public function getAllProductAttributes(){
		$retval = [];
		foreach($this->getProductAttributes() as $attr){
			$retval[] = ['key'=>$attr['title'], 'val'=>$attr['label']];
		}
		$custom = JSON_decode($this->custom_attributes, true);
		if(is_array($custom)){
			foreach($custom as $char){
				if(isset($char['name']) && isset($char['value']) && $char['name'])
					$retval[] = ['key'=>$char['name'], 'val'=>$char['value']];
			}
		}
		return $retval;
	}
	
	public static function findById($id){
		$filter = new ProductFilter;
		$filter->id = $id;
		$filter->active = null;
		$filter->edit_mode = true;
		return Product::jointQuery($filter)->one();
	}


	public function get_visit(){
		return CountVisit::find($this->id, CountVisit::TYPE_PRODUCT);
	}

	public function get_actions(){
		$query = new Query;
		$result = $query->select(['stock_product_id', 'description'])
			->from('stock_product')
			->where(['product_id'=>$this->id])->all();
			
		return $result;
	}

	public function set_actions($vals){
		if(!$this->id)
			return;
			
		Yii::$app->db->createCommand()->delete('stock_product', ['product_id'=>$this->id])->execute();
		foreach($vals as $data){
			if(isset($data['name']) && isset($data['description']))
				Yii::$app->db->createCommand()->insert('stock_product', ['product_id'=>$this->id, 'stock_product_id'=>$data['name'], 'description'=>$data['description']])->execute();
		}
	}

	
	public function get_custom_attributes(){
		return JSON_decode($this->custom_attributes, true);
	}

	public function set_custom_attributes($val){
		$this->custom_attributes = JSON_encode($val);
	}

	public function set_filter_attribute($vals){
		if(!$this->id)
			return false;
			
		Yii::$app->db->createCommand()->delete('product_attribute', ['product_id'=>$this->id])->execute();
		foreach($vals as $key=>$val){
			if($key && $val){
				if(is_array($val)){
					foreach($val as $multi_val){
						Yii::$app->db->createCommand()->insert('product_attribute', ['product_id'=>$this->id, 'attribute_id'=>$key, 'value_id'=>$multi_val])->execute();
					}
				} else {
					Yii::$app->db->createCommand()->insert('product_attribute', ['product_id'=>$this->id, 'attribute_id'=>$key, 'value_id'=>$val])->execute();
				}
			}
		}
	}
	
	public static function is_attribute_multiple($id){
		$query = new Query;
		return (1 == $query->select("multi")
			->from('attributes_list')
			->where(['id'=>$id])->scalar());
	}
	
	public function get_filter_attribute(){

		$query = new Query;
		$result = $query->select("group_concat(`attribute_id`, char(61), `value_id`) as col")
			->from('product_attribute')
			->where(['product_id'=>$this->id])->one();


		if(!$result)
			return [];
			
		$retval = [];
			
		foreach(preg_split('%,%', $result['col']) as $pair){
			$pair = preg_split('%=%', $pair);
			if(count($pair) < 2)
				continue;
				
			if(!isset($retval[$pair[0]])){
				$retval[$pair[0]] = $pair[1];
			}
			elseif(!is_array($retval[$pair[0]])) {
				$retval[$pair[0]] = [$retval[$pair[0]]];
				$retval[$pair[0]][] = $pair[1];
			} else {
				$retval[$pair[0]][] = $pair[1];
			}
		}
		return $retval;
	}
	
	public function getProductComments(){
		return $this->hasMany(ProductComment::className(), ['product_id'=>'id']);
	}
	
	public static function jointQuery($filter){

		$query = self::find();
		
		$query = $query
			->leftJoin('store', 'store.id = products.store_id')
			->leftJoin('product_attribute', 'products.id = product_attribute.product_id')
			->select(['count(products.id) as criteria', 'products.*',
				'(select group_concat(filename) from product_photos where product_id = products.id) as photos', 'store.user_id'])
			->groupBy('products.id');


		if(null !== $filter->has_sale){
			$query = $query->innerJoin('stock_product', 'stock_product.product_id = products.id');
		}

		if($product_slug = $filter->product_slug)
			$query = $query->andWhere(['products.slug'=>$product_slug]);

		if(null !== $filter->is_service)
			$query = $query->andWhere(['products.is_service'=>$filter->is_service]);

		if($store_id = $filter->store_id){
			$query = $query->andWhere(['store_id'=>$store_id]);
		}

		if($id = $filter->id){
			$query = $query->andWhere(['products.id'=>$id]);
		}

		if($date_min = $filter->date_min){
			$query = $query->andWhere("unix_timestamp(products.updated) >= $date_min");
		}

		if($exclude_id = $filter->exclude_id){
			$query = $query->andWhere(['not in', 'products.id', $exclude_id]);
		}
		
		if(!$filter->edit_mode){
			$query = $query ->andWhere('products.category_type_id in (select sub_categorie_id from store_sub_categories where store_id = store.id)');
		}

		if(null != $filter->active)
		{
			$query = $query->andWhere(['products.active'=>$filter->active, 'products.blocked'=>0]);
		}

		
		if($price_from = $filter->price_from){
			$query = $query->andWhere("amount >= $price_from");
		}

		if($price_to = $filter->price_to){
			$query = $query->andWhere("amount <= $price_to");
		}

		if($search_query = $filter->query){
			$query = $query->andWhere("search_text like '%$search_query%'");
		}
		
		if($filter->parent){
			$parent = $filter->parent;
			
			$parent = Category::andSubCategories($parent);
			
			$query = $query->andWhere(['category_type_id'=>$parent]);
		}
		
		if($filter->parent && $filter->filter_attribute){
			$where = ['or'];
			$criteria = 0;
			foreach($filter->filter_attribute as $attributeId => $attributeList){
				if(empty($attributeList) || ($attributeList[0] == '')) // ничего не делаем с пустыми фильтрами
					continue;

				$criteria++;
				$where[] = 
						['attribute_id'=>$attributeId, 'value_id'=>$attributeList];
						
			}
			if($criteria)
				$query = $query->andWhere($where)->having("criteria = $criteria");
				
		}
		
		$query = $query->groupBy('products.id');
		
		switch($filter->sort_order){
			case ProductFilter::DATE_SORT:
				$query = $query -> orderBy(['updated'=>SORT_DESC]);
				break;
			case ProductFilter::SORT_PRICE_ASC:
				$query = $query -> orderBy(['amount'=>SORT_ASC]);
				break;
			case ProductFilter::SORT_PRICE_DESC:
				$query = $query -> orderBy(['amount'=>SORT_DESC]);
				break;
			case ProductFilter::SORT_RATE:
				$query = $query -> orderBy(['rating'=>SORT_DESC]);
				break;
			case ProductFilter::SORT_POPULAR:
				$query = $query -> leftJoin('order_product', 'order_product.product_id = products.id');
				$query = $query -> orderBy(['count(order_product.id)'=>SORT_DESC]);
				break;
		}
		
		if($city = $filter->city){
			$stores_list = Store::getSituableStores(['city'=>$city]);
			$query = $query->andWhere(['store_id'=>$stores_list]);
		}
		
		return $query;
	}

	public function getPrice(){
		return $this->discount_amount ? $this->discount_amount : $this->amount;
	}

	public function getPriceMeasure(){
		
		$types = static::getPayTypeList();
		return isset($types[$this->pay_type]) ? ($types[$this->pay_type]) : '';
		
		return $this->discount_amount ? $this->discount_amount : $this->amount;
	}

	
	public function getCategoryPath(){
		return join($this->category->slugPath, '/');
	}
	
	public function getImages(){
		$result = [];
		
		if($this->photos)
		foreach(preg_split('%,%', $this->photos) as $photo){
			$result[] = [
				'name' => $photo,
				'size' => '',
				'url' => MediaLibrary::getByFilename($photo)->getUrl()
			];
			
		}
		return $result;
	}


	public function getCategory(){
		return $this->hasOne(Category::className(), ['id'=>'category_type_id']);
	}
	
	public static function path2parent($path){
		$pathArr = preg_split('%/%', $path);
		if(empty($pathArr))
			return null;
			
		$filter = new ProductFilter;
		$filter->active = null;
		$filter->product_slug = $pathArr[count($pathArr)-1];
		
		return static::jointQuery($filter)->one();
	}
	
	public static function getDataProvider($filter, $limit = 20, $addEdit = false){
		
		$query = self::jointQuery($filter);
		
		if($addEdit){
			$editModel = new Product();
			$editModel->type = Product::TYPE_ADD_PRODUCT;
			return new ArrayDataProvider([
			    'allModels' => array_merge([$editModel], $query->all()),
			    'sort' => [
			        'attributes' => ['id', 'username', 'email'],
			    ],
			    'pagination' => [
			        'pageSize' => $filter->limit
			    ],
			]);
		}
		
		if($filter->limit){
			return new ArrayDataProvider([
			    'allModels' => array_slice($query->all(), 0, $filter->limit),
			    'sort' => [
			        'attributes' => ['id', 'username', 'email'],
			    ],
			    'pagination' => [
			        'pageSize' => $filter->limit
			    ],
			]);
		}
		
		return new ActiveDataProvider([
		    'query' => $query,
		    'pagination' => [
	        'pageSize' => $limit,
		    ],
		]);
	}

	public static function getBlockProducts($filter, $limit = 20, $block, $addEdit = false){

        $query = Product::find();


        $query = $query
        ->innerJoin('block_product', 'products.id = block_product.product_id')
        ->leftJoin('store', 'store.id = products.store_id')
        ->leftJoin('product_attribute', 'products.id = product_attribute.product_id')
        ->select(['count(products.id) as criteria', 'products.*',
            '(select group_concat(filename) from product_photos where product_id = products.id) as photos',
            'store.user_id',
            'block_product.block_id as block_id', 'store.slug as store_slug'])
        ->groupBy('products.id');

        if($block){
            $query = $query->where(['block_product.block_id' => $block->id]);
        }

        if($addEdit){
            $editModel = new Product();
            $editModel->type = Product::TYPE_ADD_PRODUCT;
            return new ArrayDataProvider([
                'allModels' => array_merge([$editModel], $query->all()),
                'sort' => [
                    'attributes' => ['id', 'username', 'email'],
                ],
                'pagination' => [
                    'pageSize' => $filter->limit
                ],
            ]);
        }

        if($filter->limit){
            return new ArrayDataProvider([
                'allModels' => array_slice($query->all(), 0, $filter->limit),
                'sort' => [
                    'attributes' => ['id', 'username', 'email'],
                ],
                'pagination' => [
                    'pageSize' => $filter->limit
                ],
            ]);
        }

        return  new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => $limit,
            ],

        ]);
    }

	public function getStore(){
		return $this->hasOne(Store::className(), ['id'=>'store_id']);
	}

	
}