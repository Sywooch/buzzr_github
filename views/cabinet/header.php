<?
use yii\widgets\Menu;
use yii\helpers\Url;
use app\models\User;

?>
<div class="submenu-area">
	<div class="cabinet-person">
		<?=$user->name?>
	</div>
	<div class="submenu">
		<? echo Menu::widget([
			'activateItems' => true,
		    'items' => [
		    	['label'=>'Диалоги', 'url'=> (['cabinet/chat/index'])],
		    	['label'=>'Комментарии', 'url'=> (['cabinet/comments/index'])],
		    	['label'=>'Корзина', 'url'=> (['cabinet/cart/index'])],
		    	['label'=>'История заказов', 'url'=> (['cabinet/history/index'])],
		    	['label'=>'Подписки', 'url'=> (['cabinet/subscriptions/index'])],
		    	['label'=>'Пользователь', 'url'=> (['cabinet/user/index'])],
		    ],
		]);
		?>
	
	</div>
</div>
