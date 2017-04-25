<?
use yii\helpers\Url;
use app\widgets\LikeWidget;
use app\widgets\ShareWidget;
use app\models\Store;
use app\components\HtmlFromUser as Html;

$url = Url::toRoute(['news/view', 'id'=>$model->id]);

?>
<div class="title-row">
	<div class="published pull-right">
		Опубликовано: <?=Html::encode($model->created) ?>
	</div>
	<div class="title">
		<a href="<?=$url?>">
			<?=Html::encode($model->title)?>
		</a>
	</div>
	<div class="clearfix"></div>
</div>
<div class="row">
	<div class="col-sm-3">
		<div class="image">
			<a href="<?=$url?>">
			<? if($model->photos) :?>
				<img src="<?=$model->photoUrl?>" alt="" class="img-responsive">
			<? else : ?>
				<img src="/img/nophoto.png" class="img-responsive" alt="">
			<? endif ?>
			</a>
		</div>
	</div>
	<div class="col-sm-6">
		<div class="announce">
			<?php echo(Html::encode($model->announce)); ?>
		</div>
	</div>
	<div class="col-sm-3">
		<div class="pull-left">
			<? echo LikeWidget::widget([
					'toggleUrl' => Url::toRoute(['news/like', 'id'=>$model->id]),
					'initVal' => $model->likes,
					'isLiked' => $model->isLikedBy(\Yii::$app->user->id)
				]);
			?>
		</div>
		<div class="pull-left">
			<? echo ShareWidget::widget([
				'url' => $url,
				'title' => $model->title
			]);
			?>
		</div>
	</div>
</div>