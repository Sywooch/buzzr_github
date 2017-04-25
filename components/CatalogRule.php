<?
namespace app\components;

use yii\web\UrlRuleInterface;
use yii\base\Object;
use app\models\Category;

class CatalogRule extends Object implements UrlRuleInterface
{

    public function createUrl($manager, $route, $params)
    {
        if (in_array($route, ['catalog/index', 'catalog/list', 'catalog/product'])) {

			if(isset($params['id'])){
				$cat = new Category;
				$cat->id = $params['id'];
				$params['path'] = join($cat->slugPath, '/');
				unset($params['id']);
			}
        	
        	if(!isset($params['path']))
	        	return 'catalog';
	        	
	        $path = $params['path'];
	        unset($params['path']);
	        
	        if(isset($params['product_code'])){
	        	$product_code = $params['product_code'];
	        	unset($params['product_code']);
	        	$path = $path . '/' . $product_code;
	        }
	        
	        if(empty($params))
        		return 'catalog/' . $path;
	        
	        $_t = [];
	        foreach($params as $k=>$v)
	        	$_t[] = "$k=$v";

        	return 'catalog/' . $path . '?' . join($_t, '&');
        }
        return false;  // this rule does not apply
    }

    public function parseRequest($manager, $request)
    {
        $pathInfo = $request->getPathInfo();
        if (preg_match('%^catalog\/(.*)$%', $pathInfo, $matches)) {
        	
        	$path = preg_split('/\//', $matches[1]);
        	
        	if(count($path) <= 1)
	        	return ['catalog/index', ['path'=>$matches[1]]];
	        elseif(count($path) <= 3)
	        	return ['catalog/list', ['path'=>$matches[1]]];
	        else
	        	return ['catalog/product', ['path'=>$matches[1]]];
        }
        return false;  // this rule does not apply
    }
}