<?
use yii\widgets\ListView;
use yii\helpers\Url;
use yii\widgets\Menu;

?>

<section class="stores">
	<div class="container narrow">
		<div class="catalog-type-selector">
			<? echo Menu::widget(['items' => [
					['url'=>Url::current(['bricks'=>1]), 'label' => '<span><i class="fa fa-th-large"></i></span>', 'encode'=>false,
						'options' => ['class' => $bricks ? 'active' : '']],
					['url'=>Url::current(['bricks'=>0]), 'label' => '<span><i class="fa fa-list-ul"></i></span>', 'encode'=>false,
						'options' => ['class' => !$bricks ? 'active' : '']],
				]]); ?>
		</div>
		<? if($bricks) : ?>
			<div class="stores-bricks-container fixed">
			<? echo ListView::widget([
				'dataProvider'=>$data,
				'itemView'=> '_list_item_bricks',
				'layout' => '{items}<div class="clearfix"></div>{pager}',
				'itemOptions' => ['class'=>'stores-item-bricks'],
				]);
			?>
			</div>
		<? else : ?>
		<? echo ListView::widget([
				'dataProvider'=>$data,
				'itemView'=> '_list_item',
				'layout' => "{items}\n{pager}",
				'itemOptions' => ['class'=>'stores-item'],
				]);
			?>
		<? endif ?>
	</div>
</section>