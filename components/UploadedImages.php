<?
namespace app\components;

use yii\web\UrlRuleInterface;
use yii\base\Object;

class UploadedImages extends Object implements UrlRuleInterface
{

    public function createUrl($manager, $route, $params)
    {
        if ($route === 'upload/product_image') {
        	
        	if(isset($params['store_id']) && isset($params['user_id']) && isset($params['filename'])){
        		$userid = $params['user_id'];
        		$storeid = $params['store_id'];
        		$size = isset($params['size']) ? $params['size'] : 'middle';
        		$filename = $params['filename'];
	        	return "uploads/user_$userid/store_$storeid/image/$size" . "_" . "$filename";
        	}
        	
        }
        if ($route === 'upload/blog_image') {
        	
        	if(isset($params['user_id']) && isset($params['filename'])){
        		$userid = $params['user_id'];
        		$size = isset($params['size']) ? $params['size'] : 'middle';
        		$filename = $params['filename'];
	        	return "uploads/user_$userid/blog_post/$size" . "_" . "$filename";
        	}
        	
        }
        if ($route === 'upload/store_image') {
        	
        	if(isset($params['store_id']) && isset($params['user_id']) && isset($params['filename'])){
        		$userid = $params['user_id'];
        		$storeid = $params['store_id'];
        		$size = isset($params['size']) ? $params['size'] : 'logo';
        		$filename = $params['filename'];
	        	return "uploads/user_$userid/store_$storeid/image/$size" . '_' . "$filename";
        	}
        	
        }
        if ($route === 'upload/store_banner') {
        	
        	if(isset($params['store_id']) && isset($params['user_id']) && isset($params['filename'])){
        		$userid = $params['user_id'];
        		$storeid = $params['store_id'];
        		$size = isset($params['size']) ? $params['size'] : 'banner';
        		$filename = $params['filename'];
	        	return "uploads/user_$userid/store_$storeid/banners/$size"."_$filename";
        	}
        	
        }
        return false;  // this rule does not apply
    }

    public function parseRequest($manager, $request)
    {
        return false;  // this rule does not apply
    }
}