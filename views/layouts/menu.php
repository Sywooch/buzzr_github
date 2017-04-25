<?
use yii\widgets\Menu;
use yii\helpers\Url;

?>
<section class="menu">
	<div class="container">
		<? echo Menu::widget([
			'activateItems' => true,
		    'items' => [
		    	
		    	['label'=>'Главная', 'url'=> (['site/index'])],
		    	['label'=>'Каталог', 'url'=> (['catalog/index'])],
		    	['label'=>'Магазины', 'url'=> (['stores/index', 'service'=>'stores'])],
		    	['label'=>'Организации', 'url'=> (['stores/index', 'service'=>'organizations'])],
		    	['label'=>'Карта', 'url'=> (['site/map'])],
		    	['label'=>'Новости', 'url'=> (['news/index'])],
		    ],
		]);
		?>

	</div>
</section>
