<?
use app\widgets\LikeWidget;
use app\widgets\ShareWidget;
use yii\helpers\Url;
use yii\widgets\ListView;

?>
<section class="store">
	<div class="container narrow">
		<? echo $this->render('_header_edit', ['store'=>$store]); ?>
		<div class="subpage-content">
			<div class="catalog-products">
				<?= ListView::widget([
				        'dataProvider' => $catalogDataProvider,
						'options' => ['class' => 'products-list row'],
				        'itemOptions' => ['class' => 'col-sm-3'],
						'layout' => "{items}\n<div class=\"col-xs-12\">{pager}</div>",
				        'itemView' => '/catalog/_product_list_item',
	        			'viewParams' => ['path'=>'any/any/any', 'editable' => true, 'parent' => isset($parent) ? $parent : null, 'store' => isset($store) ? $store : null],
				]) ?>
			</div>
		</div>
	</div>
</section>
