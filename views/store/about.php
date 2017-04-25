<?
use app\widgets\LikeWidget;
use app\widgets\SubscribeWidget;
use app\widgets\ShareWidget;
use yii\helpers\Url;
use app\components\HtmlFromUser as Html;


$url = Url::toRoute(['stores/view', 'id'=>$store->id]);

?>
<section class="store">
	<div class="container narrow">
		<? echo $this->render('_header', ['store'=>$store]); ?>
		<div class="subpage-content">
			<div class="row">
				<div class="col-xs-7">
					<div class="description">
						<?php echo(Html::encode($store->description)); ?>
					</div>
					<hr>
					<? if($store->can_edit())echo $this->render('_about_data', ['store'=>$store]); ?>

				</div>
				<div class="col-xs-5">
					<div class="widgets above-address-widgets">
						<div class="pull-left">
							<? echo SubscribeWidget::widget([
									'toggleUrl' => Url::toRoute(['stores/subscribe', 'id'=>$store->id]),
									'isSubscribed' => $store->isSubscribedBy(\Yii::$app->user->id)
								]);
							?>
						</div>
						<div class="pull-left">
							<? echo LikeWidget::widget([
									'toggleUrl' => Url::toRoute(['stores/like', 'id'=>$store->id]),
									'initVal' => $store->likes,
									'isLiked' => $store->isLikedBy(\Yii::$app->user->id)
								]);
							?>
						</div>
						<div class="pull-left">
							<? echo ShareWidget::widget([
									'url' => $url,
									'title' => $store->title
								]);
							?>
						</div>
						<div class="clearfix"></div>
					</div>
					<? if($store->address) : ?>
					<div class="address-well">
						<div class="icon"><i class="fa fa-map-marker"></i></div>
						<?=Html::encode($store->address); ?>
						<div class="clearfix"></div>
					</div>
					<? endif ?>
					<? if($store->phone) : ?>
					<div class="address-well">
						<div class="icon roundit"><i class="fa fa-phone"></i></div>
						<?=Html::encode($store->phone); ?>
						<div class="clearfix"></div>
					</div>
					<? endif ?>
					<div id="main_map" data-lat="<?=Html::encode($store->lat)?>" data-lng="<?=Html::encode($store->lng)?>"></div>
				</div>
			</div>
		</div>
	</div>
</section>
