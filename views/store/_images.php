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
		<input type="hidden" class="x1">
		<input type="hidden" class="y1">
		<input type="hidden" class="width">
		<input type="hidden" class="height">
		<input type="hidden" class="ratio">
	</div>
	<div class="col-sm-2">
		<button class="remove btn btn-danger"><i class="fa fa-ban"></i> Удалить</button>
	</div>
</div>
<? foreach($model->images as $image) : ?>
<? $hasCrop = isset($image['crop']); ?>
<? if($hasCrop){
	$hasCrop = preg_match('/-crop (\d+)x(\d+)\+(\d+)\+(\d+)/', $image['crop'], $m);
}
?>
<div class="row item">
	<div class="col-sm-2 images_image">
		<img src="<?=$image['url']?>" alt="" class="imgsrc-here img-responsive">
	</div>
	<div class="col-sm-8 images_filename">
		<div class="filename-here"><?=Html::encode($image['name'])?></div>
		<div class="filesize-here"><?=Html::encode($image['size'])?></div>
		<? if($hasCrop) : ?>
		<input type="hidden" class="x1" value="<?=$m[3]; ?>">
		<input type="hidden" class="y1" value="<?=$m[4]; ?>">
		<input type="hidden" class="width" value="<?=$m[1]; ?>">
		<input type="hidden" class="height" value="<?=$m[2]; ?>">
		<input type="hidden" class="ratio" value="1">
		<? endif ?>
	</div>
	<div class="col-sm-2 images_btn">
		<button class="remove btn btn-danger"><i class="fa fa-ban"></i> Удалить</button>
	</div>
</div>
<? endforeach ?>