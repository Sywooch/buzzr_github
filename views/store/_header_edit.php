<?
use yii\widgets\Menu;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use app\widgets\FileUpload;
use yii\helpers\Html;
use app\widgets\ChatWidget;

?>
<div class="banner-area">
	<div class="banner-edit well">
		<h4>Редактирование баннеров магазина</h4>
		<? $form = ActiveForm::begin(['action'=>Url::toRoute(['store/aboutedit', 'code'=>$store->slug])]); ?>
		<?= FileUpload::widget([
		    'model' => $store,
		    'attribute' => 'image',
		    'templatePath' => '/widgets/uploadButton',
		    'url' => ['store/imageupload', 'id' => $store->id],
		    'options' => ['accept' => 'image/*', 'class'=>'image-upload'],
		    'clientOptions' => [
		        'maxFileSize' => 2000000
		    ],
	        'clientEvents' => [
            'fileuploaddone' => 'function(e, data) {
            						window.fileuploaddone(data, $(".imagelist"), {aspectRatio: "930:350", text: "Выберите изображение. Если изображение не вписывается в необходимые размеры, щелкните по изображению и выделите область вручную. В ином случае будет применена авто обрезка изображения."});
                                }',
            ]
		]);?>
		<div class="imagelist">
			<input type="hidden" class="image-list" name="Store[image_list]" value='<?=JSON_encode($store->images)?>'>
			<? echo $this->render('_images', ['model'=>$store]); ?>
		</div>
		<button class="btn btn-primary">Сохранить</button>
		<? ActiveForm::end(); ?>
	</div>
</div>
<div class="submenu-area">
	<div class="title">
		Редактирование магазина: <?=Html::encode($store->title)?>
	</div>
	<? if(!$store->checkBanned()) : ?>
	<div class="panel panel-danger">
		<div class="panel-heading">Магазин заблокирован администратором
			<? echo ChatWidget::widget([
			'receiver'=>1,
			'template'=>'/widgets/chat-admin'
		]);
	?>
		<div class="clearfix"></div>

		</div>
	</div>
	<? elseif(!$store->check()) : ?>
	<div class="panel panel-danger">
		<? $newroute = ($store->getSelected_categories() === null) ? 'store/catalogselect' : 'store/catalogedit';?>
		<div class="panel-heading">
			<a href="<?=Url::toRoute([$newroute, 'code'=>$store->slug])?>"><span class="btn btn-default fileinput-button">Добавить товар</span></a>
			Магазин принудительно выключен - создайте не менее 5 товаров (добавлено <?=$store->getProductsCount();?>/5)
			<div class="clearfix"></div>
		</div>
	</div>


	<? elseif(!$store->active) : ?>
	<div class="panel panel-warning">
		<div class="panel-heading">Магазин выключен - включите его в настройках</div>
	</div>
	<? endif ?>
	<a href="<?=Url::toRoute(['store/main', 'code'=>$store->slug])?>"><i class="fa fa-eye" aria-hidden="true"></i> Переход в режим просмотра</a>
	<div class="submenu">
		<div class="submenu-area_mobile_btn mobile_menu_btn">
			<span></span><i>Меню</i>
		</div>
		<? echo Menu::widget([
		    'items' => [
		    	['label'=>'Разделы', 'url'=> (['store/catalogselect', 'code'=>$store->slug])],
		    	['label'=> 'Каталог', 'url'=> (['store/catalogedit', 'code'=>$store->slug])],
		    	['label'=>'Заказы', 'url'=> (['store/orders', 'code'=>$store->slug])],
		    	['label'=> 'Главная', 'url'=> (['store/mainedit', 'code'=>$store->slug])],
		    	['label'=>'Настройки', 'url'=> (['store/aboutedit', 'code'=>$store->slug])], 
		    	['label'=>'Новости', 'url'=> (['store/blogedit', 'code'=>$store->slug])],
		    ],
		]);
		?>
	
	</div>
</div>
