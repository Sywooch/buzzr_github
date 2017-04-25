<?
use yii\widgets\Menu;
use app\components\MediaLibrary;
use app\models\UserNotifications;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\Pjax;
use app\models\User;

//$cart = isset($this->params['cartModel']) ? $this->params['cartModel'] : null;
$cartCount = isset($this->params['cartCount']) ? $this->params['cartCount'] : null;

?>
<span class="text-left">
	<div class="cabinet">
	<!-- cabinet ajax -->
		<? if(!Yii::$app->user->getIsGuest()) : $user = Yii::$app->user->identity;?>
			<? $notifications = UserNotifications::getNotifications(); ?>
			<? if(Yii::$app->user->getIsGuest() || !Yii::$app->user->identity->mystore) { ?>
				<a data-pjax="0" href="<?=Url::toRoute(['site/addshop'])?>" class="btn btn-default cabinet-btn">Создать магазин</a>
			<? } else { ?>
				<a data-pjax="0" href="<?=Url::toRoute(['store/about'])?>" class="btn btn-default cabinet-btn">Мой магазин</a>
			<? } ?>
	
			<? if($cartCount) : ?>
			<a data-pjax="0" href="<?=Url::toRoute(['cabinet/cart'])?>" class="cart-link"><i class="fa fa-shopping-basket"></i> <?=$cartCount?></a>
			<? endif ?>
			<span class="pulldn-trigger">
				<div class="avatar <?= join(' ', array_keys($notifications)); ?>">
					<div class="message notification blink-animation">
						<i class="fa fa-envelope"></i>
					</div>
				<? if($user->avatar) : ?>
					<div class="avatar-round"><img src="<?=MediaLibrary::getByFilename($user->avatar)->getResizedUrl($user->avatar_crop . ' -resize 55x55')?>" alt=""></div>
				<? else : ?>
					<div class="nophoto"><i class="fa fa-camera"></i></div>
				<? endif ?>
				</div>
				<i class="fa fa-chevron-down"></i>
			</span>
			<div class="cabinet-dropdown">
				<div class="ugolok"></div>
				<div class="email"><?=Html::encode($user->email)?></div>
				<? if($user->avatar) : ?>
					<div class="avatar-larger">
						<img src="<?=MediaLibrary::getByFilename($user->avatar)->getResizedUrl('-resize 200x200')?>?>" alt="">
					</div>
				<? endif ?>
				<div class="person"><?=Html::encode($user->name)?></div>
				<ul class="menu">
					<li><a data-pjax="0" href="<?=Url::toRoute(['cabinet/chat'])?>"><i class="fa fa-envelope"></i> Новые сообщения
						<? if(isset($notifications['messages'])) echo '<span class="count">(', $notifications['messages'], ')</span>'; ?>
					</a></li>
					<li><a data-pjax="0" href="<?=Url::toRoute(['cabinet/comments'])?>"><i class="fa fa-commenting"></i> Отзывы
						<? if(isset($notifications['comments'])) echo '<span class="count">(', $notifications['comments'], ')</span>'; ?>
					</a></li>
					<li><a data-pjax="0" href="<?=Url::toRoute(['cabinet/cart'])?>"><i class="fa fa-shopping-cart"></i> Корзина</a></li>
					<li><a data-pjax="0" href="<?=Url::toRoute(['cabinet/user'])?>"><i class="fa fa-lock"></i> Личный кабинет</a></li>
				</ul>
				<hr>
				<? if(null !== $user->mystore) : ?>
				<ul class="menu">
					<li>
						<a data-pjax="0" href="<?=Url::toRoute(['store/aboutedit'])?>">Мой магазин</a>
					</li>
					<li>
						<a data-pjax="0" href="<?=Url::toRoute(['store/orders'])?>"><i class="fa fa-list"></i> Заказы
						<? if(isset($notifications['orders'])) echo '<span class="count">(', $notifications['orders'], ')</span>'; ?>
						</a>
					</li>
				</ul>
				<hr>
				<? endif ?>
				<? if(User::isAdmin()) : ?>
			   	<a data-pjax="0" href="<?=Url::toRoute(['admin/users/index']);?>">Панель администратора</a>
			   	<hr>
			   	<? endif ?>
				<a data-pjax="0" href="<?=Url::toRoute(['user/logout'])?>"><i class="fa fa-sign-out"></i> Выход</a>
				
			</div>
		<? else : ?>
			<div class="not-logged">
				<a href="<?=Url::toRoute(['user/login', 'ajax'=>1])?>" data-toggle="modal" data-target="#login_modal">Вход</a>
				<a href="<?=Url::toRoute(['user/register', 'ajax'=>1])?>" data-toggle="modal" data-target="#register_modal">Регистрация</a>
			</div>
		<? endif ?>
		
	<!-- cabinet ajax end -->
	</div>
	
	<div class="modal fade" tabindex="-1" role="dialog" id="login_modal">
	  <div class="modal-dialog" role="document">
	    <div class="modal-content">
	    </div>
	  </div>
	</div>
	<div class="modal fade" tabindex="-1" role="dialog" id="register_modal">
	  <div class="modal-dialog" role="document">
	    <div class="modal-content">
	    </div>
	  </div>
	</div>
</span>