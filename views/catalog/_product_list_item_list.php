<?
use yii\helpers\Url;
use app\models\Product;
use yii\helpers\Html;

$state = $model->blocked ? 'blocked' : $model->active;

?>
<div class="product-item-listed active-state-<?=$state?>">
	<? if($model->type == Product::TYPE_ORDINARY) : ?>
		<?
		if(isset($editable) && $editable)
			$url = Url::toRoute(['store/productedit', 'code'=>$store->slug, 'parent'=>$parent, 'product_id'=>$model->id]);
		elseif($model->category)
			$url = Url::toRoute(['catalog/product', 'path'=>$model->categoryPath, 'product_code'=>$model->slug]);
		else
			$url = Url::toRoute(['catalog/product', 'product_id'=>$model->id]);
		?>
		<div class="row">
			<div class="col-xs-3">
				<? if($model->photos): ?>
					<a href="<?=$url;?>" class="image">
					<img src="<?=$model->getPhotoUrl('-resize 200x200')?>" alt="" class="img-responsive">
					</a>
				<? endif ?>
			</div>
			<div class="col-xs-9">
				<div class="row">
					<div class="col-xs-9">
						<a href="<?=$url;?>" class="title">
						<?=Html::encode($model->title)?>
						</a>
					</div>
					<div class="col-sm-3">
						<? if($model->amount && $model->discount_amount): ?>
							<div class="price"><?=$model->discount_amount?>&nbsp;<i class="fa fa-rouble"></i><span class="measure">&nbsp;/&nbsp;<?=$model->priceMeasure; ?></span></div>
							<div class="old-price"><?=$model->amount?>&nbsp;<i class="fa fa-rouble"></i></div>
						<? elseif($model->price) :?>
							<div class="price">
							<?=$model->price?>&nbsp;<i class="fa fa-rouble"></i><span class="measure">&nbsp;/&nbsp;<?=$model->priceMeasure; ?></span>
							</div>
						<? endif ?>
					</div>
				</div>

				<? $attributes = $model->getAllProductAttributes(); ?>
				<? if(!empty($attributes)) : ?>
				<div class="chars">
					<ul>
					<? $cc = 0; ?>
					<? foreach($attributes as $char) : if(++$cc > 4)break; ?>
						<li><span class="key"><?=Html::encode($char['key'])?>:</span> <span class="value"><?=Html::encode($char['val'])?></span></li>
					<? endforeach ?>
					</ul>
				</div>
				<? endif ?>
			</div>
			<div class="col-xs-2">
			</div>
		</div>
		<div class="row">
			<div class="col-sm-3"></div>
			<div class="col-sm-7">
				<? if(!isset($store)) : ?>
				Магазин: <a href="<?=$model->store->url; ?>"><?=Html::encode($model->store->title); ?></a>
				<? endif ?>
			</div>
			<div class="col-sm-2">
				<a href="<?=$url;?>#comments" class="comment-hoverage"><i class="fa fa-commenting-o"></i> <?=count($model->productComments) ? count($model->productComments) : ''?></a>
				<? if($model->store->is_supplier) : ?>
					<div class="supplier-label">Оптом</div>
				<? else : ?>
						<? echo \app\widgets\RateWidget::widget(['value'=>$model->rating]); ?>
				<? endif ?>
			</div>
		</div>
	<? endif ?>
</div>
