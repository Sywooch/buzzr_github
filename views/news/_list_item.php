<?
use yii\helpers\Url;
use app\widgets\LikeWidget;
use app\widgets\ShareWidget;
use app\models\Store;
use app\components\MediaLibrary;
use yii\helpers\Html;
use app\models\User;

$url = Url::toRoute(['news/view', 'id'=>$model->id]);

?>

<div class="row">
	<div class="col-sm-3">
		<? if($model->photos) :?>
		<div class="image">
			<a href="<?=$url?>">
				<img src="<?=MediaLibrary::getByFilename($model->getPhotos()[0])->getResizedUrl('-resize 230x150')?>" alt="" class="img-responsive">
			</a>
		</div>
		<? endif ?>
	</div>
	<div class="col-sm-9">
		<div class="title">
			<a href="<?=$url?>">
				<?=Html::encode($model->title)?>
			</a>
		</div>
		<div class="date">
			Опубликовано: <?=Html::encode($model->created); ?>
			<? if(User::isAdmin()) : ?>
			<a href="<?=Url::toRoute(['store/blogedit', 'code'=>$model->store->slug]); ?>"><?=Html::encode($model->store->title);?></a>
			<? endif ?>
		</div>
		<div class="announce">
            <?php echo(nl2br(Html::encode($model->announce))); ?>
		</div>
		<div class="widgets">
			<a href="<?=Url::toRoute(['cabinet/chat/index', 'receiver_id'=>$model->user_id])?>"><button type="button" class="btn btn-xs chat pull-left">
				<span class="fa fa-envelope"></span>
			</button></a>

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
		<div class="clearfix"></div>
	</div>
</div>

