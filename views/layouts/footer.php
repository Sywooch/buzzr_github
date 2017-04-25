<?
use yii\widgets\Menu;
use yii\helpers\Url;
use app\models\Store;
use app\widgets\ChatWidget;
?>
<footer class="footer">
	<div class="container">
		<div class="row">
			<div class="col-sm-3">
				<? echo Menu::widget([
				    'items' => [
				    	['label'=>'О сайте', 'url'=> ['site/info', 'page'=>'about']],
				    	['label'=>'Пользовательское соглашение', 'url'=> ['site/info', 'page'=>'agreement']],
				    ],
				]);
				?>
			</div>
			<div class="col-sm-3">
				<? echo ChatWidget::widget([
					'receiver'=>1,
					'template'=>'/widgets/chat-admin-link'
				]); ?>
				<? echo Menu::widget([
				    'items' => [
				    	['label'=>'Помощь', 'url'=> ['site/info', 'page'=>'settings']],
				    ],
				]);
				?>
			</div>
			<div class="col-sm-3">
				<? echo Menu::widget([
				    'items' => [
				    	['label'=>'Карта сайта', 'url'=> Url::toRoute(['site/sitemap'])],
				    ],
				]);
				?>
			</div>
			<div class="col-sm-3">
				<? if(Yii::$app->user->getIsGuest() || !Yii::$app->user->identity->mystore) { ?>
						<div class="create-shop text-center">
							<div class="text">Создать свой магазин<br>прямо сейчас</div>
							<a href="<?=Url::toRoute(['site/addshop'])?>" class="orange-btn">СОЗДАТЬ</a>
						</div>
				<? } ?>
			</div>
		</div>
	</div>
</footer>
<div class="underfooter-copyright">
	<a href="/"><img src="/img/logo2.png" alt=""></a>
	<div>Buzzr Copyright © Buzzr.ru (Крымская торговая площадка) 2015-<?=date('Y')?> Все права защищены</div>
</div>
<? echo $this->render('_chat_modal'); ?>
<? echo $this->render('_crop_modal'); ?>