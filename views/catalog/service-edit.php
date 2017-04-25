<?
use yii\widgets\ActiveForm;
use app\widgets\CustomAttributes;
use unclead\widgets\MultipleInput;
use app\widgets\FileUpload;
use kartik\select2\Select2;
use app\models\Product;
use app\models\User;
use app\models\filters\ProductFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;


?>
<section class="product-edit">
	<div class="container narrow">
		<div class="product-add">
			<? $form = ActiveForm::begin(); ?>

			<div class="row">
				<div class="col-sm-3 buttons">
					<? if(!$product->isNewRecord) : ?>
						<a href="<?=Url::toRoute(['catalog/product', 'product_code'=>$product->slug, 'path'=>$product->categoryPath])?>">Просмотр</a>
						<a href="<?=Url::toRoute([Yii::$app->controller->id . '/' . Yii::$app->controller->action->id]
							+ Yii::$app->controller->actionParams + ['remove'=>1])?>">Удалить</a>
					<? endif ?>
				</div>
				<div class="col-sm-9">
					<a href="<?=Url::previous()?>" class="pull-right btn btn-default"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Назад</a>
					<div class="section-title">
						Добавление/редактирование услуги
					</div>
				</div>
			</div>
			
			<div class="row">
				<div class="col-sm-3 has-right-border">
					<div class="right-border"></div>
					<? echo $form->field($product, 'amount'); ?>
					<? echo $form->field($product, 'pay_type')->dropDownList(Product::getPayTypeList()); ?>
					<? echo $form->field($product, 'keywords'); ?>
					<? echo $form->field($product, 'active')->checkBox(); ?>
					<? if(User::isAdmin() || $product->blocked)echo $form->field($product, 'blocked')->checkBox(['disabled'=>User::isAdmin() ? false : true]); ?>
				</div>
				<div class="col-sm-9">
					<? echo $form->field($product, 'title')->label('Название услуги'); ?>
					<? echo $form->field($product, 'description')->textArea(); ?>
					<? echo $form->field($product, 'photo_link'); ?>

					<div class="border">
						<p>Фотографии (максимум 4 шт):</p>
						<?= FileUpload::widget([
						    'model' => $product,
						    'attribute' => 'image',
						    'templatePath' => '/widgets/uploadButton',
						    'url' => ['store/productimageupload', 'id' => $product->id],
						    'options' => ['accept' => 'image/*', 'class'=>'image-upload'],
						    'clientOptions' => [
						        'maxFileSize' => 2000000
						    ],
					        'clientEvents' => [
				            'fileuploaddone' => 'function(e, data) {
				            						window.fileuploaddone(data, $(".imagelist"));
				                                }',
				            ]
						]);?>
	
	
						<div class="imagelist">
							<? $product->image_list = JSON_encode($product->images); echo $form->field($product, 'image_list')->hiddenInput(['class'=>'image-list'])->label(false); ?>
							<? echo $this->render('_images', ['model'=>$product]); ?>
						</div>
					</div>
					<div class="text-right">
						<input type="submit" class="blue-grad" name="save" value="Сохранить">
					</div>

				</div>
			</div>

			<? ActiveForm::end(); ?>
		</div>
	</div>
</section>
