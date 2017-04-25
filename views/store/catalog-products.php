<?
use app\widgets\LikeWidget;
use app\widgets\ShareWidget;
use yii\helpers\Url;
use yii\widgets\ListView;

$url = Url::toRoute(['stores/view', 'id'=>$store->id]);

?>
<section class="store">
	<div class="container narrow">
		<? echo $this->render('_header', ['store'=>$store]); ?>
		<div class="subpage-content">
			<? if(isset($toCategories) && $toCategories) : ?>
			<div class="text-right">
				<a class="show-categories" href="<?=Url::toRoute(['store/catalog', 'code'=>$store->slug])?>">показать категории товаров</a>
			</div>
			<? endif ?>
			<div class="catalog-products">
				<? if(isset($showfilter) && $showfilter) echo $this->render('/catalog/_store_filter', ['attributes'=>$attributes, 'filter'=>$filter]) ?>

				<? if($filter->display_type == 'list') : ?>
				<?= ListView::widget([
				        'dataProvider' => $catalogDataProvider,
						'options' => ['class' => 'products-list-by-list row'],
				        'itemOptions' => ['class' => 'col-sm-3'],
				        'itemView' => '/catalog/_product_list_item_list',
						'layout' => '<div class="border-top"></div>{items}<div class="col-xs-12">{pager}</div>',
						'itemOptions' => ['class'=>'col-xs-12 product-list-item-wrapper'],
	        			'viewParams' => ['path'=>'any/any/any', 'parent' => isset($parent) ? $parent : null, 'store' => isset($store) ? $store : null],
				]) ?>
				<? else : ?>
				<?= ListView::widget([
				        'dataProvider' => $catalogDataProvider,
						'options' => ['class' => 'products-list row'],
				        'itemOptions' => ['class' => 'col-sm-3'],
						'layout' => "{items}\n<div class=\"col-xs-12\">{pager}</div>",
				        'itemView' => '/catalog/_product_list_item',
	        			'viewParams' => ['path'=>'any/any/any', 'parent' => isset($parent) ? $parent : null, 'store' => isset($store) ? $store : null],
	        	]); ?>
				<? endif ?>
			</div>
		</div>
	</div>
</section>
