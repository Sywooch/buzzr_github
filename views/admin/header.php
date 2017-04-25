<?
use yii\widgets\Menu;
use yii\helpers\Url;
use app\models\User;

?>
<div class="submenu-area">
	<div class="cabinet-person">
		Панель администратора
	</div>
	<div class="submenu">
		<? echo Menu::widget([
			'activateItems' => true,
		    'items' => [
		    	['label'=>'Пользователи', 'url'=> (['admin/users/index'])],
		    	['label'=>'Магазины', 'url'=> (['admin/shops/index'])],
		    	['label'=>'Баннеры', 'url'=> (['admin/banners/index'])],
		    	['label'=>'Страницы', 'url'=> (['admin/pages/index'])],
		    	['label'=>'Категории', 'url'=> (['admin/categories/index'])],
		    	['label'=>'Блоки', 'url'=> (['admin/blocks/index'])],
		    ],
		]);
		?>
	
	</div>
</div>
