<?
use yii\helpers\Url;
use app\models\Product;
use app\components\MediaLibrary;
use yii\widgets\Pjax;
use yii\helpers\Html;

$model = Product::findById($model->id);
$url = Url::toRoute(['catalog/product', 'path'=>'any/any/any', 'product_code'=>$model->slug]) . '#comments';
?>
<div class="row">
	<div class="col-sm-4">
		<div class="product-well">
			<a href="<?=$url; ?>">
				<span class="img-wrap">
					<span class="image">
						<img src="<?=$model->getPhotoUrl('-resize 150x150')?>" alt="" class="img-responsive">
					</span>
				</span>
			</a>
			<div class="row">
				<div class="col-xs-6">
					<span class="title"><? echo Html::encode($model->title); ?></span>
					<div class="price"><? echo Html::encode($model->price); ?> <i class="fa fa-rub"></i></div>
				</div>
				<div class="col-xs-6">
					<a href="<?=$url?>" class="blue-grad">Карточка</a>
				</div>
			</div>
		</div>
	</div>
	<div class="col-sm-8">
		<? foreach($model->productComments as $comment) : ?>
		<? Pjax::begin(); ?>
			<div class="comment">
				<? if($comment->user && $comment->user->avatar) : ?>
				<div class="avatar">
					<img src="<?=MediaLibrary::getByFilename($comment->user->avatar)->getCropResized('50x50'); ?>" alt="">
				</div>
				<? endif ?>
				<div class="nameline">
					<span class="name"><?=Html::encode($comment->name)?></span>
					<span class="date"><?=Html::encode($comment->created)?></span>
				</div>
				<div class="text">
					<?=Html::encode($comment->text)?>
				</div>
				<? if($comment->answer) : ?>
				<div class="answer">
					Ответ: <?=Html::encode($comment->answer)?>
				</div>
				<? endif ?>
				<div class="clearfix"></div>
				<? echo $this->render('_comment_answer', ['comment'=>$comment]); ?>
			</div>
		<? Pjax::end(); ?>
		<? endforeach ?>		
	</div>
</div>
