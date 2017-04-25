<?
use yii\helpers\Html;
?>
<section class="map">
	<div class="container">
		<? if($singleStore) : ?>
			<div id="main_map" data-lat="<?=Html::encode($singleStore->lat);?>" data-lng="<?=Html::encode($singleStore->lng);?>" data-loadall="true"></div>
		<? else : ?>
			<div id="main_map"></div>
		<? endif ?>
	</div>
</section>

<section class="stores">
	<div class="container">
		<? echo $this->render('/site/_home_stores.php', ['popularStores'=>$popularStores, 'itemClass'=>'col-lg-2 col-md-3 col-sm-4']); ?>
		</div>
	</div>
</section>
