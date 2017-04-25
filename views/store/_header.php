<?
use yii\widgets\Menu;
use yii\helpers\Url;
use yii\helpers\Html;
use app\widgets\ChatWidget;

use app\components\MediaLibrary;

?>
<div class="banner-area cropped">
	    <?php 
    $fotorama = \metalguardian\fotorama\Fotorama::begin(
        [
            'options' => [
                'loop' => true,
                'hash' => true,
                'width' => 930,
                'maxHeight' => 350,
                'nav' => false
            ],
            'tagName' => 'span',
            'useHtmlData' => false,
            'htmlOptions' => [
                'class' => 'custom-class',
                'id' => 'custom-id',
            ],
        ]
    ); 
    ?>
	<? foreach($store->banners as $banner): ?>
		<? $src = $banner->crop ?
			MediaLibrary::getByFilename($banner->filename)->getResizedUrl($banner->crop . ' -resize 930x350') :
			MediaLibrary::getByFilename($banner->filename)->getCropResized('930x350');
		?>
		<img src="<?=$src; ?>" alt="">
	<? endforeach ?>
    <?php $fotorama->end(); ?>

</div>
<div class="submenu-area">
	<div class="title">
		<?=Html::encode($store->title)?>
	</div>

	<? echo ChatWidget::widget([
			'receiver'=>$store->user_id,
			'template'=>'/widgets/chat-large'
		]);
	?>

	<? if($this->context->canEdit()) : ?>
	<a href="<?=Url::toRoute(['store/mainedit', 'code'=>$store->slug])?>"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Переход в режим редактирования</a>
	<? endif ?>
	<div class="submenu">
		<div class="submenu-area_mobile_btn mobile_menu_btn">
			<span></span><i>Меню</i>
		</div>
		<? echo Menu::widget([
		    'items' => [
		    	['label'=>'Каталог', 'url'=> (['store/catalog', 'code'=>$store->slug])],
		    	['label'=>'Главная', 'url'=> (['store/main', 'code'=>$store->slug])],
		    	['label'=>'О магазине', 'url'=> (['store/about', 'code'=>$store->slug])],
		    	['label'=>'Новости', 'url'=> (['store/blog', 'code'=>$store->slug]), 'visible'=>$store->hasNews],
		    ],
		]);
		?>
	
	</div>
</div>
