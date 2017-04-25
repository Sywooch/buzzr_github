<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;
use yii\db\Query;
use yii\behaviors\SluggableBehavior;
use app\behaviors\FullTextSearch;
use app\models\filters\ArticleFilter;
use app\components\MediaLibrary;

class Article extends ActiveRecord {

	public $user_id;
	public $image, $image_list;
	
	public function attributeLabels(){
		return [
			'title' => 'Заголовок публикации',
			'text' => 'Текст',
			'category_id' => 'Категория публикации'
		];
	}
	
    public function rules()
    {
        return [
            [['image'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg'],
            [['image_list', 'category_id'], 'safe'],
            [['title', 'text'], 'required']
        ];
    }

	public function behaviors()
	{
	    return [
            [
                'class' => FullTextSearch::className(),
                'fulltext_result_field' => 'search_text',
                'fulltext_source_fields' => ['title', 'text'],
            ],
	    ];
	}

	use \app\traits\MultiplePhotos;
	use \app\traits\Likeable;
	
	public function getDestDir(){
		$user = $this->user_id;
		return $_SERVER['DOCUMENT_ROOT'] . "/uploads/user_$user/blog_post";
	}
	
	public function afterSave($insert, $changed){

		$this->multiPhotoAfterSave('store_blog_images', 'blog_post_id', 4);
		
		return parent::afterSave($insert, $changed);
	}
	
	public function getImages(){
		$result = [];
		
		if($this->photos)
		foreach(preg_split('%,%', $this->photos) as $photo){
			$result[] = [
				'name' => $photo,
				'size' => '',
				'url' => MediaLibrary::getByFilename($photo)->getUrl(),
			];
			
		}
		return $result;
	}
	
	const imageRoute = 'upload/blog_image';
	
	public static function tableName(){
		return 'store_blog';
	}
	
	public function likeTableName(){
		return 'store_blog_likes';
	}
	
	public function likeEntityName(){
		return 'blog_post_id';
	}
	
	public static function jointQuery($filter){

		$query = self::find();
		$query = $query->select("store_blog.*, store.user_id, (select group_concat(filename) from store_blog_images where blog_post_id = store_blog.id) as photos")
			->leftJoin('store', 'store.id = store_blog.store_id');
			
		if($filter->store_id)
			$query = $query -> andWhere(['store_id'=>$filter->store_id]);
		
		if($filter->id)
			$query = $query -> andWhere(['store_blog.id'=>$filter->id]);

		if(null !== $filter->published)
			$query = $query -> andWhere(['published'=>$filter->published]);

		if($filter->category_id)
			$query = $query -> andWhere(['store_blog.category_id'=>$filter->category_id]);
			
		if($filter->search_query)
			$query = $query -> andWhere("store_blog.search_text like '%{$filter->search_query}%'");
			
		if($filter->sort_order == ArticleFilter::SORT_RATING)
			$query = $query->orderBy(['likes'=>SORT_DESC]);
		else
			$query = $query->orderBy(['id'=>SORT_DESC]);
		
		return $query;

	}
	
	public function getAnnounce(){
		return mb_substr(strip_tags($this->text), 0, 300);
	}
	
	public function getStore(){
		return $this->hasOne(Store::className(), ['id'=>'store_id']);
	}
	
	
	public static function getDataProvider($filter, $limit = 10){
		
		$query = self::jointQuery($filter);
		
		return new ActiveDataProvider([
		    'query' => $query,
		    'pagination' => [
	        'pageSize' => $limit,
		    ],
		]);
	}

}