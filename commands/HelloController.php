<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use yii\console\Controller;
use yii\db\Query;
use app\models\CategoryAttribute;
use app\models\ProductPhoto;
use app\models\Product;
use app\models\Store;
use app\models\StoreBanner;
use app\models\ArticleImage;
use app\components\MediaLibrary;
use Yii;

/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class HelloController extends Controller
{
    /**
     * This command echoes what you have entered as the message.
     * @param string $message the message to be echoed.
     */

	public function init(){
    	Yii::setAlias('@webroot', __DIR__ . '/../web/');
		return parent::init();
	}

    public function actionIndex($message = 'hello world')
    {
        echo $message . "\n";
    }
    
    public function actionProductsmeasure(){
    	$uslugi = 0; $products = 0;
    	foreach(Product::find()->all() as $product){
    		if(!$product->category){
    			echo "no category for {$product->id}\n";
    			continue;
    		}
    		$path = $product->category->slugPath;
    		if($path[0] == 'uslugi'){
    			$uslugi++;
    		}
    		else {
    			$products++;
    			$product->pay_type = 4;
    			$product->save(false);
    		}
    	}
    	echo "$products $uslugi\n";
    }
    
    public function actionEmptyfile($filename){
    	$file = MediaLibrary::saveFromString(file_get_contents($filename));
    	var_dump($file);
    }
    
    public function actionImport(){
    	$path = '/var/www/Dropbox/sites/buzzr.real.ru/app/views/product/templates/search';
    	
    	$query = new Query;
		$templates = $query->select('*')->from('category_template')->all();
		foreach($templates as $template){
			$template_path = $path . '/' . $template['template_path'] . '.php';
			
			$category_attributes_set = [];
			
			if(!file_exists($template_path))continue;
			
			// читаем файл шаблона
			foreach(preg_split('%[\r\n]+%', file_get_contents($template_path)) as $line){

				if(preg_match('%"(Template|p)\[(.+)\.(.+)\]"%', $line, $matches)){
					$p = $matches[2];
					$v = $matches[3];
					$category_attributes_set[$p][] = $v;
				} elseif(preg_match('%"(Template|p)\[(.+)\]"%', $line, $matches)){
					$p = $matches[2];
					$category_attributes_set[$p] = [];
				}
			}
			
			// TODO: import text attributes
			
			//echo "template=";var_dump($template);var_dump($category_attributes_set);continue;
			if(!empty($category_attributes_set)){
				$category_id = $template['category_type_id'];
				#var_dump($category_attributes_set);
				$sort = 0;
				foreach($category_attributes_set as $attribute_key => $attribute_vals){
					$sort++;
					$category_attribute = new CategoryAttribute;
					$category_attribute->category_id = $category_id;
					$category_attribute->attribute_id = CategoryAttribute::findIdByName($attribute_key);
					$category_attribute->sort = $sort;
					if($category_attribute->validate())
						$category_attribute->save();
					else {
						echo "key `$attribute_key` not found\n";
						CategoryAttribute::addAttributeValues($attribute_key, $attribute_key, $attribute_vals);
					}
				}
			}
		}
    }
    
    public function actionImages(){
    	Yii::setAlias('@webroot', __DIR__ . '/../web/');
    	
    	$query = new Query;
    	
    	$query = $query->select(['product_photos.id as id', 'filename', 'user_id', 'store_id'])
    		->from('product_photos')
    		->leftJoin('products', 'products.id = product_photos.product_id')
    		->leftJoin('store', 'store.id = products.store_id')
    		->all();
    	
    	foreach($query as $photo){
    		$user = $photo['user_id'];
    		$store = $photo['store_id'];
    		$filename = $photo['filename'];
    		$file = "/root/img/user_$user/store_$store/image/full_$filename";
    		if(!file_exists($file))
    			continue;
    		$file = MediaLibrary::saveFromString(file_get_contents($file));
    		
    		$photo = ProductPhoto::findOne(['id'=>$photo['id']]);
    		$photo->filename = $file->filename;
    		
    		$photo->save();
    	}

    	$query = new Query;
    	
    	$query = $query->select(['store_banners.id as id', 'filename', 'user_id', 'store_id'])
    		->from('store_banners')
    		->leftJoin('store', 'store.id = store_banners.store_id')
    		->all();
    	
    	foreach($query as $photo){
    		$user = $photo['user_id'];
    		$store = $photo['store_id'];
    		$filename = $photo['filename'];
    		$file = "/root/img/user_$user/store_$store/banners/full_$filename";
    		if(!file_exists($file) || !is_file($file))
    			continue;
    		$file = MediaLibrary::saveFromString(file_get_contents($file));
    		var_dump($file->filename);
    		$photo = StoreBanner::findOne(['id'=>$photo['id']]);
    		$photo->filename = $file->filename;
    		
    		$photo->save();
    	}

    	$query = new Query;
    	
    	$query = $query->select(['store_blog_images.id as id', 'filename', 'user_id', 'store_id'])
    		->from('store_blog_images')
    		->leftJoin('store_blog', 'store_blog.id = store_blog_images.blog_post_id')
    		->leftJoin('store', 'store.id = store_blog.store_id')
    		->all();
    	
    	foreach($query as $photo){
    		$user = $photo['user_id'];
    		$store = $photo['store_id'];
    		$filename = $photo['filename'];
    		$file = "/root/img/user_$user/blog_post/full_$filename";
    		if(!file_exists($file) || !is_file($file))
    			continue;
    		$file = MediaLibrary::saveFromString(file_get_contents($file));
    		var_dump($file->filename);
    		$photo = ArticleImage::findOne(['id'=>$photo['id']]);
    		$photo->filename = $file->filename;
    		
    		$photo->save();
    	}
    }
}
