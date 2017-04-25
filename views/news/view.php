<?
use yii\helpers\Url;
use app\widgets\LikeWidget;
use app\widgets\ShareWidget;
use app\components\MediaLibrary;
use metalguardian\fotorama\FotoramaAsset;
use app\components\HtmlFromUser as Html;

FotoramaAsset::register($this);

$url = Url::toRoute(['news/view', 'id'=>$model->id]);

?>
<section class="blog-single">
	<div class="container narrow">
		<div class="bordered">
			<div class="article-panel">
				<div class="row">
					<div class="col-sm-4">
						<div class="published">
							Опубликовано: <?=$model->created; ?>
						</div>
						<div class="widgets">
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
					<div class="col-sm-4">
					<? if($model->store) : ?>
						<a href="<?=$model->store->url;?>">Магазин: <?=Html::encode($model->store->title);?></a>
					<? endif ?>
					</div>
					<div class="col-sm-4">
						<a href="<?=Url::previous();?>" class="pull-right btn btn-default"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Назад</a>
						<div class="clearfix"></div>
					</div>
				</div>
			</div>
			<div class="content-wrapper">
				<? if(is_array($model->getPhotos())) : ?>
					<img src="<?=$model->getPhotoUrl('-resize "500x300>"')?>" class="img-responsive news-image" alt="">
				<? endif ?>
				<div class="title">
					<h2><?=Html::encode($model->title)?></h2>
				</div>
				<div class="text">
	                <?php echo(Html::encode($model->text)) ?>
				</div>
				
			</div>
		</div>
	</div>
</section>