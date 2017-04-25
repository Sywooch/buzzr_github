<?
use yii\helpers\Url;
use app\widgets\LikeWidget;
use app\widgets\ChatWidget;
use app\widgets\ShareWidget;
use app\models\Store;
use app\components\MediaLibrary;
use app\widgets\SubscribeWidget;
use yii\helpers\Html;


$url = Url::toRoute(['store/main', 'code'=>$model->slug]);

?>
<div class="product-counter">
	товаров: <?=$model->getProductsCount() ?>
</div>
<div class="image">
	<a href="<?=$url?>" data-pjax="0">
		<? if($model->logo) :?>
		<img src="<?=MediaLibrary::getByFilename($model->logo)->getResizedUrl($model->crop . ' -resize 200x'); ?>" alt="" class="img-responsive">
		<? else : ?>
		<img src="/img/nophoto.png" alt="" class="img-responsive nophoto">
		<? endif ?>
	</a>
</div>
<div class="text-container">
	<div class="title">
		<a href="<?=$url?>" data-pjax="0">
			<?=Html::encode($model->title)?>
		</a>
	</div>
	<? if($model->is_supplier) : ?>
		<div class="text-right">
			<div class="supplier-label">Поставщик</div>
		</div>
	<? else : ?>
		<? $cats = $model->getStoreCategories(1); ?>
		<? if(!empty($cats)) : ?>
		<div class="categories">
			<? foreach($cats as $cat) : ?>
				<span class="category-item">
				<a data-pjax="0" href="<?=Url::toRoute(['store/catalogproducts', 'code'=>$model->slug, 'parent'=>$cat['id']]);?>">
					<?=$cat['title']?></a>
				</span></span>
			<? endforeach ?>
		</div>
		<? endif ?>
	<? endif ?>
</div>