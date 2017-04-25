<?
use app\widgets\LikeWidget;
use app\widgets\ShareWidget;
use yii\helpers\Url;
use yii\widgets\ListView;
use yii\widgets\Pjax;

$url = Url::toRoute(['stores/view', 'id'=>$store->id]);
?>
<? Pjax::begin(); ?>
<div class="subpage-content">
		<? if($saleDataProvider->totalCount) : ?>
			<div class="bordered-title"><a href="<?=Url::current(['salelimit'=>0]);?>">Акция</a></div>
			<div class="catalog-products">
				<?= ListView::widget([
				        'dataProvider' => $saleDataProvider,
						'options' => ['class' => 'products-list row'],
				        'itemOptions' => ['class' => 'col-sm-3'],
						'layout' => "{items}\n<div class=\"col-xs-12\">{pager}</div>",
				        'itemView' => '/catalog/_product_list_item',
	        			'viewParams' => ['path'=>'any/any/any', 'store'=>$store, 'parent' => isset($parent) ? $parent : null, 'editable'=>$editable]
				]) ?>
			</div>
		<? endif ?>
		<? if($newDataProvider->totalCount) : ?>
			<div class="bordered-title"><a href="<?=Url::current(['newlimit'=>0]);?>">Новые поступления</a></div>
			<div class="catalog-products">
				<?= ListView::widget([
				        'dataProvider' => $newDataProvider,
						'options' => ['class' => 'products-list row'],
				        'itemOptions' => ['class' => 'col-sm-3'],
						'layout' => "{items}\n<div class=\"col-xs-12\">{pager}</div>",
				        'itemView' => '/catalog/_product_list_item',
	        			'viewParams' => ['path'=>'any/any/any', 'store'=>$store, 'parent' => isset($parent) ? $parent : null, 'editable'=>$editable]
				]) ?>
			</div>
		<? endif ?>
		<? if($popularDataProvider->totalCount) : ?>
			<div class="bordered-title"><a href="<?=Url::current(['poplimit'=>0]);?>">Популярные</a></div>
			<div class="catalog-products">
				<?= ListView::widget([
				        'dataProvider' => $popularDataProvider,
						'options' => ['class' => 'products-list row'],
				        'itemOptions' => ['class' => 'col-sm-3'],
						'layout' => "{items}\n<div class=\"col-xs-12\">{pager}</div>",
				        'itemView' => '/catalog/_product_list_item',
	        			'viewParams' => ['path'=>'any/any/any', 'store'=>$store, 'parent' => isset($parent) ? $parent : null, 'editable'=>$editable]
				]) ?>
			</div>
		<? endif ?>
		<? if(!$popularDataProvider->totalCount && !$newDataProvider->totalCount && !$saleDataProvider->totalCount) : ?>
			Здесь будут отображаться ваши основные товары. Для добавления товаров выберите
			<a href="<?=Url::toRoute(['store/mainedit', 'code'=>$store->slug])?>">режим редактирования</a> и перейдите во вкладку Каталог.
		<? endif ?>

		</div>
<? Pjax::end(); ?>