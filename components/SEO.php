<?
namespace app\components;

use Yii;
use yii\helpers\Url;
use app\models\Product;

class SEO {
	public function PlaceTags($controller, $data = []){
		
		$title = 'Крымская торговая интернет площадка | Buzzr.ru';
		$description = 'Buzzr.ru - является торговой площадкой с конструктором магазинов. Данная платформа связывает все предприятия и товары единым поисковым механизмом, позволяющим покупателю найти необходимый товар, не выходя из дома.';
		$keyword = 'товары, купить, заказать';
		$image = false;
		
		if( ($controller->id == 'store') && isset($data['store'])){
			$store = $data['store'];
			$descripton = $store->description;
			$keyword = "главная магазина, магазин, товары, купить, заказать";
			$title = "Магазин " . $store->title . ' | Главная | Buzzr.ru';
			
			if($controller->action->id == 'catalog')
				$title = "Магазин " . $store->title . ' | Каталог | Buzzr.ru';
			if($controller->action->id == 'about')
				$title = "Магазин " . $store->title . ' | О магазине | Buzzr.ru';
			if($controller->action->id == 'blog')
				$title = "Магазин " . $store->title . ' | Новости | Buzzr.ru';
				
			if($controller->action->id == 'productedit')
				$title = 'Редактирование товара';
			
		}
		
		if($controller->id == 'catalog'){
			$description = "Список товаров крымской торговой интернет площадки";
			$keywords = "товары, Крым, Севастополь, Симферополь";
			$title = 'Список товаров из Крыма | Севастополь | Симферополь | Buzzr.ru';
			
			if(($controller->action->id == 'product') && isset($data['product'])){
				$product = $data['product'];
				$title = $product->title;
				$description = mb_substr($product->description, 0, 100);
				
				$image = Yii::$app->request->hostInfo . $product->photoUrl;
				
			}
			
		}
		
		if($controller->id == 'stores'){
			if(isset($controller->actionParams['service']) && ($controller->actionParams['service'] == 'organizations')){
				$description = "Список организаций крымской торговой интернет площадки";
				$keywords = "организации, Крым, Севастополь, Симферополь";
				$title = 'Список организаций из Крыма | Севастополь | Симферополь | Buzzr.ru';
			} else {
				$description = "Список магазинов крымской торговой интернет площадки";
				$keywords = "магазины, Крым, Севастополь, Симферополь";
				$title = 'Список магазинов из Крыма | Севастополь | Симферополь | Buzzr.ru';
			}
		}
		
		if($controller->id == 'news'){
			$description = "Список новости крымской торговой интернет площадки";
			$keywords = "новости, Крым, Севастополь, Симферополь";
			$title = 'Новости из Крыма | Севастополь | Симферополь | Buzzr.ru';
			if( ($controller->action->id == 'view') && isset($data['model'])){
				$article = $data['model'];
				$title = $article->title;
				$image = Yii::$app->request->hostInfo . $article->photoUrl;
			}
		}

		
	    Yii::$app->view->title = $title;

		Yii::$app->view->registerMetaTag([
	        'name' => 'keyword',
	        'content' => $keyword
	    ]);
		Yii::$app->view->registerMetaTag([
	        'name' => 'description',
	        'content' => $description
	    ]);

		if($image)	    
		Yii::$app->view->registerMetaTag([
	        'name' => 'og:image',
	        'content' => $image
        ]);

	    
    
	}
}