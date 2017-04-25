<?
use yii\grid\GridView;
use yii\grid\ActionColumn;
use yii\widgets\Pjax;
use yii\helpers\Url;
use app\components\MediaLibrary;
use yii\helpers\Html;
?>

<? Pjax::begin(); ?>
		<div class="row">
			<div class="col-sm-3">
				<div class="avatar">
				<? if($user->avatar) : ?>
					<img src="<?=MediaLibrary::getByFilename($user->avatar)->getResizedUrl('-resize 200x200');?>" alt="" class="img-responsive">
				<? else : ?>
					<img src="/img/no-photo.jpg" alt="" class="img-responsive">
				<? endif ?>
				</div>
				<div class="username text-center">
					<?=Html::encode($user->name); ?>
				</div>
			</div>
			<div class="col-sm-9">
				<ul>
					<li>Email: <?=Html::encode($user->email)?></li>
					<li>Телефон: <?=Html::encode($user->phone)?></li>
					<li>Дата регистрации: <?=Html::encode($user->created)?></li>
					<li>Последнее изменение: <?=Html::encode($user->updated)?></li>
				</ul>
				<a class="blue-grad" href="<?=Url::previous();?>">Назад</a>
			</div>
		</div>
<? Pjax::end(); ?>
