<?
use yii\widgets\Pjax;
use yii\helpers\Url;
use yii\bootstrap\Html;
use yii\widgets\ListView;

?>
<? Pjax::begin(); ?>
<?php foreach ($blocks as $block): ?>
	<div class="row">
		<h2 class="main_title"><a href="<?=$block->slug?>"><?=$block->title?></a></h2>
		<?php foreach ($block->products as $product): ?>
			<?php foreach ($popularProducts->getModels() as $model) : ?>
				<?php if ($model->id == $product->id) : ?>
					<?php $state = $model->blocked ? 'blocked' : $model->active; ?>
					<div class="col-lg-2 col-md-3 col-sm-4 col-xs-6 main_catalog_products">
						<? if($model->type == \app\models\Product::TYPE_ORDINARY) : ?>
							<?
							if(isset($editable) && $editable){
								$url = Url::toRoute(['store/productedit', 'code'=>$store->slug, 'parent'=>$parent, 'product_id'=>$model->id]);
							}
							elseif($model->category){
								$url = Url::toRoute(['catalog/product', 'path'=>$model->categoryPath, 'product_code'=>$model->slug]);
							}
							else{
								$url = Url::toRoute(['catalog/product', 'product_id'=>$model->id]);
							}
							?>
							<a href="<?=$url?>" data-pjax="0" class="hoverage"></a>
							<div class="product-image-wrapper">
								<div class="rate">
									<? echo \app\widgets\RateWidget::widget(['value'=>$model->rating]); ?>
								</div>
								<? if($model->photos): ?>
									<img src="<?=$model->getPhotoUrl('-resize 160x160')?>" alt="">
								<? endif ?>
							</div>
							<div class="underimage">
								<div class="title">
									<?=Html::encode($model->title)?>
								</div>
								<? if($model->category) : ?>
									<div class="category">
										<?=Html::encode($model->category->title)?>
									</div>
								<? endif ?>
								<div class="comments-n-price">
									<div class="price pull-left">
										<? if($model->amount && $model->discount_amount): ?>
											<span class="old-price"><?=$model->amount?></span>&nbsp;<?=$model->discount_amount?>&nbsp;<i class="fa fa-rouble"></i>&nbsp;/&nbsp;<?=$model->priceMeasure; ?>
										<? else :?>
											<?=$model->price?>&nbsp;<i class="fa fa-rouble"></i>&nbsp;/&nbsp;<?=$model->priceMeasure; ?>
										<? endif ?>
									</div>
									<div class="comments pull-right">
										<a href="<?=$url;?>#comments" class="comment-hoverage"><i class="fa fa-commenting-o"></i> <?=count($model->productComments) ? count($model->productComments) : ''?></a>
									</div>
									<div class="clearfix"></div>
								</div>
							</div>
						<? elseif($model->type == \app\models\Product::TYPE_ADD_PRODUCT) : ?>
							<div class="add-product-plus product-image-wrapper">
								<i class="fa fa-plus"></i>
							</div>
							<div class="underimage">
								<div class="title">Добавить товар или услугу</div>
							</div>
							<a href="<?=Url::toRoute(['store/productedit', 'code'=>$store->slug, 'product_id'=>'add', 'parent'=>$parent])?>" data-pjax="0" class="hoverage"></a>
						<? endif ?>
					</div>
				<?php endif;?>
			<?php endforeach;?>
		<?php endforeach;?>
	</div>
<?php endforeach;?>

<?php echo \yii\widgets\LinkPager::widget([
	'pagination' => $popularProducts->getPagination(),
]); ?>
<? Pjax::end(); ?>
