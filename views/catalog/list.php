<?
use yii\widgets\ListView;
use yii\helpers\Url;
?>

<section class="catalog-products">
	<div class="container narrow">
		<? if($parent_cat)echo $this->render('/layouts/_breadcrumbs', ['cats'=>$parent_cat->breadcrumbs, 'notlinklast'=>true]); ?>
		<? echo $this->render('_store_subcats', ['subcats'=>$subcats, 'parent_path'=>$parent_cat->slugPath]) ?>
		<? echo $this->render('_store_filter', ['attributes'=>$attributes, 'filter'=>$filter]) ?>
		
		<? if($filter->display_type == 'list') : ?>
			<? echo ListView::widget([
				'dataProvider'=>$data,
				'itemView'=>'_product_list_item_list',
				'options' => ['class' => 'products-list-by-list row'],
				'layout' => '<div class="border-top"></div>{items}<div class="col-xs-12">{pager}</div>',
				'itemOptions' => ['class'=>'col-xs-12 product-list-item-wrapper'],
				]);
			?>
		<? else : ?>
			<? echo ListView::widget([
				'dataProvider'=>$data,
				'itemView'=>'_product_list_item',
				'options' => ['class' => 'products-list row'],
				'layout' => '{items}<div class="col-xs-12">{pager}</div>',
				'itemOptions' => ['class'=>'col-sm-3 col-xs-6'],
				]);
			?>
		<? endif ?>
	</div>
</section>