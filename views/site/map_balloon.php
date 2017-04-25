<?
use yii\helpers\Url;
use app\components\MediaLibrary;
use yii\helpers\Html;

$url = Url::toRoute(['store/main', 'id'=>$store->id]);

if(!function_exists('truncateString')) {
	function truncateString($str, $chars, $to_space, $replacement="...") {
	   if($chars > mb_strlen($str)) return $str;
	
	   $str = mb_substr($str, 0, $chars);
	   $space_pos = mb_strrpos($str, " ");
	   if($to_space && $space_pos >= 0) 
	       $str = mb_substr($str, 0, mb_strrpos($str, " "));
	
	   return($str . $replacement);
	}
}

?>
<div class="store-map-balloon">
	<div class="logo">
		<? if($store->logo) : ?>
		<img src="<?=MediaLibrary::getByFilename($store->logo)->getResizedUrl('-resize 148x99')?>" alt="">
		<? else : ?>
		<img src="/img/nophoto.png" alt="" class="nophoto">
		<? endif ?>
	</div>
	<div class="title">
		<? echo Html::encode($store->title) ?>
	</div>
	<div class="description">
		<? echo Html::encode(truncateString($store->description, 300, true)) ?>	
	</div>
	<div class="address">
		<div class="marker"></div>
		<? echo Html::encode($store->address) ?>	
	</div>
	<div class="clearfix"></div>
	<div class="action-button">
		<? if($store->is_service) : ?>
		<a href="<?=$url?>" class="green-grad">Посетить организацию</a>
		<? else : ?>
		<a href="<?=$url?>" class="green-grad">Войти в магазин</a>
		<? endif ?>
	</div>
</div>

