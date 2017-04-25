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
		Опубликовано: <?=$model->created ?>
		<a class="<?=$url?>" href="<?=Url::toRoute(['news/delete', 'id'=>$model->id])?>"><i class="fa fa-times"></i></a>
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
			<?=Html::encode($model->announce)?>
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

<div class="action-buttons">
	<a class="blue-grad" href="<?=Url::toRoute(['news/edit', 'id'=>$model->id])?>">Редактировать</a>
	<? if(!$model->published) : ?>
		<a class="green-grad" href="<?=Url::toRoute(['news/publish', 'id'=>$model->id])?>">Опубликовать</a>
	<? endif ?>
</div>