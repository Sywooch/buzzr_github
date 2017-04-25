<?
use yii\helpers\Url;
use yii\widgets\ListView;
use app\components\MediaLibrary;
use yii\helpers\Html;

?>
		<h2 class="main_title"><a href="#">Популярные магазины</a></h2>
		<div class="stores-bricks-container">
			<? foreach($popularStores as $store) : ?>
			<div class="stores-item-bricks">
				<? echo $this->render('/stores/_list_item_bricks', ['model'=>$store]); ?>
			</div>
			<!-- <div class="fluid-separator"></div> -->
			<? endforeach ?>
