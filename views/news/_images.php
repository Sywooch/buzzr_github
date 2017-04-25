<?
use yii\helpers\Html;
?>
<div class="row item template">
	<div class="col-sm-2">
		<img src="about:blank" alt="" class="imgsrc-here img-responsive">
	</div>
	<div class="col-sm-8">
		<div class="filename-here"></div>
		<div class="filesize-here"></div>
	</div>
	<div class="col-sm-2">
		<button class="remove btn btn-danger"><i class="fa fa-ban"></i> Удалить</button>
	</div>
</div>
<? foreach($model->images as $image) : ?>
<div class="row item">
	<div class="col-sm-2">
		<img src="<?=$image['url']?>" alt="" class="imgsrc-here img-responsive">
	</div>
	<div class="col-sm-8">
		<div class="filename-here"><?=Html::encode($image['name'])?></div>
		<div class="filesize-here"><?=Html::encode($image['size'])?></div>
	</div>
	<div class="col-sm-2">
		<button class="remove btn btn-danger"><i class="fa fa-ban"></i> Удалить</button>
	</div>
</div>
<? endforeach ?>