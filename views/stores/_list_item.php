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
<div class="row">
	<div class="col-sm-3 col-xs-6 stores_description_image">
		<div class="image">
			<a href="<?=$url?>" data-pjax="0">
				<? if($model->logo) :?>
				<img src="<?=MediaLibrary::getByFilename($model->logo)->getResizedUrl($model->crop . ' -resize 200x'); ?>" alt="" class="img-responsive">
				<? else : ?>
				<img src="/img/nophoto.png" alt="" class="img-responsive nophoto">
				<? endif ?>
			</a>
		</div>
	</div>
	<div class="col-xs-6 stores_description">
		<div class="title">
			<a href="<?=$url?>" data-pjax="0">
				<?=Html::encode($model->title)?>
			</a>
			<? if(isset($display_subscribe)) : ?>
					<div class="subscribe-placeholder">
						<? echo SubscribeWidget::widget([
								'toggleUrl' => Url::toRoute(['stores/subscribe', 'id'=>$model->id]),
								'isSubscribed' => $model->isSubscribedBy(\Yii::$app->user->id)
							]);
						?>
					</div>
			<? endif ?>
		</div>
		<? $cats = $model->getStoreCategories(6); ?>
		<? if(!empty($cats)) : ?>
		<div class="categories">
			Категории товаров:
			<? foreach($cats as $cat) : ?>
				<span class="category-item">
				<a data-pjax="0" href="<?= Url::toRoute(['store/catalogproducts', 'code'=>$model->slug, 'parent'=>$cat['id']]);?>">
					<?=$cat['title']?></a>&nbsp;<span>(<?=$cat['cnt']?>)
				</span></span>
			<? endforeach ?>
		</div>
		<div class="goods-count">
			товаров: <?=$model->getProductsCount() ?>
		</div>
		<? endif ?>
		<? if($model->address) : ?>
		<div class="address">
			<a data-pjax="0" href="<?=Url::toRoute(['site/map', 'id'=>$model->id]); ?>"><i class="fa fa-map-marker map-marker"></i><?=Html::encode($model->address)?></a>
		</div>
		<? endif ?>
	</div>
	<div class="col-md-3 col-sm-4 stores_social_wrap">
		<div class="widgets">

			<div class="pull-left stores_social">
				<? echo ChatWidget::widget([
						'receiver'=>$model->user_id,
					]);
				?>
			</div>
			<div class="pull-left stores_social">
				<? echo LikeWidget::widget([
						'toggleUrl' => Url::toRoute(['stores/like', 'id'=>$model->id]),
						'initVal' => $model->likes,
						'isLiked' => $model->isLikedBy(\Yii::$app->user->id)
					]);
				?>
			</div>
			<div class="pull-left stores_social">
				<? echo ShareWidget::widget([
						'url' => $url,
						'title' => $model->title
					]);
				?>
			</div>
			<div class="clearfix"></div>
		</div>
		<? if($model->is_supplier) : ?>
			<div class="supplier-label">Поставщик</div>
		<? endif ?>
	</div>
</div>

