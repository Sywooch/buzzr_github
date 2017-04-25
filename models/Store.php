<?php

namespace app\models;

use Yii;
use yii\helpers\BaseArrayHelper as ArrayHelper;
use yii\db\ActiveRecord;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;
use yii\db\Query;
use yii\behaviors\SluggableBehavior;

use app\components\MediaLibrary;


class Store extends ActiveRecord {
    
    use \app\traits\Likeable;
    use \app\traits\Subscribable;
    use \app\traits\MultiplePhotos;
    
    public $image, $image_list, $logo_tmp, $update_logo;
    public $x1, $y1, $width, $height, $ratio;

    public function behaviors()
    {
        return [
            [
                'class' => SluggableBehavior::className(),
                'attribute' => 'title',
                'slugAttribute' => 'slug',
                'ensureUnique' => true
            ]
        ];
    }
    
    public function rules()
    {
        return [
            [['image', 'logo_tmp'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg'],
            [['x1', 'y1', 'width', 'height', 'ratio'], 'safe'],
            [['x1', 'y1', 'width', 'height'], 'integer'],
            [['image_list', 'title', 'description', 'address', 'region_id', 'update_logo',
                'lat', 'lng', 'selected_categories', 'is_service', 'active', 'sell_deliver', 'delivery_text', 'slug', 'category_id'], 'safe'],
            ['title', 'string', 'length' => [4, 100]],
        ];
    }
    
    public function attributeLabels(){
        return [
            'title' => 'Название магазина',
            'description' => 'Описание магазина',
            'active' => 'Статус магазина (включен - товары магазина видны пользователям)',
            'sell_deliver' => 'Доставка',
            'delivery_text' => 'Условия доставки',
            'slug' => 'Именованная ссылка магазина',
            'phone' => 'Телефон',
            'address' => 'Адрес',
            'city_id' => 'Город',
            'region_id' => 'Район',
            'category_id' => 'Профиль магазина'
        ];
    }
    
    public function can_edit(){
        if(Yii::$app->user->getIsGuest())
            return false;
            
        $user = Yii::$app->user->identity;
        
        if($user->type == 'admin')
            return true;
            
        if($this->user_id == $user->id)
            return true;
            
        return false;
    }
    
    public static function getCities(){
        $query = new Query;
        $query = $query->select('*')
            ->from('cities')
            ->all();
            
        $retval = [];
        foreach($query as $city){
            if($city['id'])
                $retval[$city['id']] = $city['title'];
        }
        return $retval;
    }
    
    public function getUrl(){
        return Url::toRoute(['store/main', 'code'=>$this->slug]);
    }

    public function getUser(){
        return $this->hasOne(User::className(), ['id'=>'user_id']);
    }
    
    public function setSelected_categories($val){
        $keys = array_keys($val);
        StoreSubCategories::updateForStore($this->id, $keys);
    }

    public function getSelected_categories(){
        return;
    }


    public function getCity_id(){
        $region = Region::findOne(['id'=>$this->region_id]);
        
        return $region ? $region->city_id : null;
    }

    public function beforeSave($insert){
        
        if(!$this->title)
            $this->title = 'Новый магазин '.time();
            
        if(!$this->slug)
            $this->slug = 'novij-magazin-'.time();
        
        if($this->update_logo){
            $this->logo = $this->update_logo;
        }
        
        if($this->update_logo && $this->ratio){
            $x1 = round($this->x1 / $this->ratio);
            $y1 = round($this->y1 / $this->ratio);      
            $height = round($this->height / $this->ratio);      
            $width = round($this->width / $this->ratio);
            $this->crop = "-crop {$width}x{$height}+{$x1}+{$y1} -gravity NorthWest";
        }

        return parent::beforeSave($insert);
    }

    public function afterSave($insert, $changed){
        
        if($this->image_list){
            $this->multiPhotoAfterSave('store_banners', 'store_id');
        }
        
        return parent::afterSave($insert, $changed);
    }
    
    public function likeTableName(){
        return 'store_likes';
    }
    
    public function likeEntityName(){
        return 'store_id';
    }

    public function subscribeTableName(){
        return 'store_subscriptions';
    }
    
    public function subscribeEntityName(){
        return 'store_id';
    }
    
    public function getProductsCount(){
        return Product::find()->where(['store_id' => $this->id, 'active'=>1])
            ->andWhere('products.category_type_id in (select sub_categorie_id from store_sub_categories where store_id = products.store_id)')
            ->count();
    }

    public function checkBanned(){
        if($this->blocked){
            $this->active = false;
            $this->save(false, ['active']);
            return false;
        }
        return true;
    }
    
    public function check(){
        $count = $this->getProductsCount();
        if($count < 5){
            if($this->active){
                $this->active = false;
                $this->save(false, ['active']);
            }
            return false;
        }
        return true;
    }

    public function getIsMyStore(){
        if(Yii::$app->user->getIsGuest())
            return false;
            
        $myStore = Yii::$app->user->identity->mystore;
        if($myStore)
            $myStore = $myStore->id;
        
        return($this->id == $myStore);
        
    }

    public function get_visits(){
        return CountVisit::find($this->id);
    }
    
    public function getHasNews(){
        return (0 != Article::find()->where(['store_id'=>$this->id, 'published'=>1])->count());
    }
    
    public function GetCategoryTreeList($markSelected = false, $showAll = false){

        $tree = [
            0 => ['id' => 0, 'title' => 'Все категории', 'slug'=> '', 'subcat'=> [] ]
        ];
        
        $query = Category::find()
            ->select('categories.title as title, categories.slug as slug, categories.id as id, categories.parent_id as parent_id,
            count(products.id) as cnt, sum(products.active=1 and products.blocked=0) as act_cnt')
            ->leftJoin('products', 'products.category_type_id = categories.id AND products.store_id = '.$this->id)
            ->orderBy('categories.sort');
            
        foreach($query ->groupBy('categories.id') ->asArray() ->all() as $cat){
            $id = 0 + $cat['id'];
            if(!isset($tree[$id]))
                $tree[$id] = [];
                
                $tree[$id]['title'] = $cat['title'];
                $tree[$id]['slug'] = $cat['slug'];
                $tree[$id]['id'] = $id;
                $tree[$id]['cnt'] = 0 + $cat['cnt'];
                $tree[$id]['act_cnt'] = 0 + $cat['act_cnt'];

                $parent_id = 0 + $cat['parent_id'];
                if(!isset($tree[$parent_id]))
                    $tree[$parent_id] = [];
                    
                if(!isset($tree[$parent_id]['subcat']))
                    $tree[$parent_id]['subcat'] = [];
                    
                $tree[$parent_id]['subcat'][] = $id;
            }
        if($markSelected){
            $checkInThis = [];
            foreach($this->getStoreSubs()->all() as $sub)
                $checkInThis[] = $sub->sub_categorie_id;

            if($showAll)
                foreach($this->getStoreCategories(false, false) as $sub)
                    $checkInThis[] = $sub['id'];
                
            $this->_markSelectedInternal($tree, 0, $checkInThis);
            $this->_countActiveInternal($tree, 0);
        }
        return $tree;
    }
    
    public function _countActiveInternal(&$tree, $id){
        $sum = (isset($tree[$id]['act_cnt']) && $tree[$id]['has_selected']) ? $tree[$id]['act_cnt'] : 0;

        if(isset($tree[$id]['subcat']))
            foreach($tree[$id]['subcat'] as $subcat){
                $sum += $this->_countActiveInternal($tree, $subcat);
            }

        return $tree[$id]['act_cnt'] = $sum;
    }

    public function _markSelectedInternal(&$tree, $id, &$checkInThis){
        $hasSelected = false;
        
        if(isset($tree[$id]['subcat']))
            foreach($tree[$id]['subcat'] as $subcat){
                $hasSelected = $this->_markSelectedInternal($tree, $subcat, $checkInThis) || $hasSelected;
            }

        //echo "id=";var_dump($id);var_dump($checkInThis);
        return $tree[$id]['has_selected'] = $hasSelected || in_array($id, $checkInThis);
        
    }

    
    public function getStoreSubs(){
        return $this->hasMany(StoreSubCategories::className(), ['store_id'=>'id']);
    }

    public function getBanners(){
        return $this->hasMany(StoreBanner::className(), ['store_id' => 'id'])->andWhere(['active'=>'1']);
    }
    
    public function getImages(){
        $result = [];
        
        if($this->banners)
        foreach($this->banners as $banner){
            $result[] = [
                'name' => $banner['filename'],
                'size' => '',
                'crop' => $banner['crop'],
                'url' => MediaLibrary::getByFilename($banner['filename'])
                ->getResizedUrl($banner['crop'] . ' -resize 120x45')
            ];
            
        }
        return $result;
    }
    
    public static function getSituableStores($filter){
        
        $situableStores = 
            ArrayHelper::getColumn(
                self::jointQuery($filter)
                    ->asArray()
                    ->all(),
                'id');
                
        if(empty($situableStores))
            $situableStores[] = 0;
            
        return $situableStores;
    }
    
    public static function jointQuery($filter){
        $query = self::find();
        
        if(isset($filter['city']) && ($filter['city'] != 0))
            $query = $query
                ->leftJoin('regions', 'store.region_id = regions.id')
                ->andWhere(['regions.city_id'=>$filter['city']]);
                
        if(isset($filter['is_service']))
            $query = $query->andWhere(['is_service'=>$filter['is_service']]);
            
        if(isset($filter['active'])){
            $query = $query->andWhere(['active'=>$filter['active']]);
        }
            
        if(isset($filter['query']) && $filter['query']){
            $squery = $filter['query'];
            $query = $query->andWhere("store.title like '%$squery%'");
        }
        
        if(isset($filter['subscribed_by'])){
            $query = $query -> innerJoin('store_subscriptions', 'store_subscriptions.store_id = store.id')
                ->andWhere(['store_subscriptions.user_id'=>$filter['subscribed_by']]);
        }

        $query = $query->andWhere(['blocked'=>0]);

        return $query;
    }
    
    public function getLogoUrl(){
        if(!$this->logo)
            return null;
            
        return MediaLibrary::getByFilename($this->logo)->getResizedUrl($this->crop . ' -resize 202x140');
    }
    
    public function getStoreCategories($limit = false, $onlychecked = true){
        $query = new Query();
        $query = $query -> select('category_type_id as id, categories.title, count(*) as cnt')
            ->from('products')
            ->leftJoin('categories', 'categories.id = products.category_type_id')
            ->groupBy('id')
            ->orderBy('cnt desc')
            ->where(['store_id'=>$this->id, 'products.active'=>1, 'products.blocked'=>0]);
            
        if($onlychecked)
            $query = $query->andWhere('category_type_id in (select sub_categorie_id from store_sub_categories where store_id = products.store_id)');

            
        $query = $query -> having('cnt > 0');

        if($limit)
            $query = $query -> limit($limit);
            
        return $query -> all();
    }
    
    public static function getDataProvider($filter = []){
        
        $query = self::jointQuery($filter);
        
        return new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
            'pageSize' => 20,
            ],
        ]);
    }
    
}
