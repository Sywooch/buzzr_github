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
use yii\helpers\Html;

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
						Добавление/редактирование товара (<?php echo Html::encode($product->category->title); ?>)

					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-3 has-right-border">
					<div class="right-border"></div>
					<? echo $form->field($product, 'amount'); ?>
					<? if($product->store->is_supplier)
						echo $form->field($product, 'supplier_prices')->textArea(); ?>
					<? echo $form->field($product, 'manufactures'); ?>
					<? echo $form->field($product, 'keywords'); ?>
					<? foreach($attributes as $attr_id=>$pairs): ?>
					<div class="filter-item">
						<? echo $form->field($product, '_filter_attribute['.($attr_id).']')->widget(Select2::classname(), [
						    'data' => $pairs[0],
						    'language' => 'ru',
						    'options' => ['placeholder' => $pairs[1], 'multiple'=>Product::is_attribute_multiple($attr_id)],
						    'pluginOptions' => [
						        'allowClear' => true
						    ],
						])->label($pairs[1]);
						?>
					</div>
					<? endforeach ?>
				</div>
				<div class="col-sm-9">
					<? echo $form->field($product, 'active')->checkBox(); ?>
					<? if(User::isAdmin() || $product->blocked):?>
						<?= $form->field($product, 'blocked')->checkBox(['disabled'=>User::isAdmin() ? false : true]) ?>
						<? echo $form->field($product, 'select')->checkBox(); ?>
					<?php endif;?>
					<? echo $form->field($product, 'title'); ?>
					<? echo $form->field($product, 'description')->textArea(); ?>
					<div class="row">
						<div class="col-sm-2"><? echo $form->field($product, 'rating')->dropDownList([0,1,2,3,4,5]); ?></div>
					</div>
					<div class="border">
						Фотографии (максимум 4 шт):<br>
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
						<div class="imagelist" data-limit="4" data-limit-target=".fileinput-button">
							<? $product->image_list = JSON_encode($product->images); echo $form->field($product, 'image_list')->hiddenInput(['class'=>'image-list'])->label(false); ?>
							<? echo $this->render('_images', ['model'=>$product]); ?>
						</div>
					</div>
					<? echo $form->field($product, 'discount_amount'); ?>
					<? echo $form->field($product, 'manufacturer_country'); ?>
					<? echo $form->field($product, 'available')->checkBox(); ?>
					<? echo $form->field($product, 'unavailable_comment')->textArea(); ?>
					<hr>
					<?  $filter = new ProductFilter;
						$filter->store_id = $store->id;
						$filter->active = null;
						$filter->exclude_id = $product->id;
						$items = ArrayHelper::map(Product::jointQuery($filter)->all(), 'id', 'title');
						if(!empty($items))
						echo $form->field($product, '_actions')->widget(MultipleInput::className(), [
					    'limit' => 10,
					    'min' => 0,
					    'columns' => [
					    	[
						    	'name' => 'stock_product_id',
						    	'type' => Select2::classname(),
						    	'title' => 'Товар',
						    	'options' => ['data'=>$items]
					    	],
					    	[
						    	'name' => 'description',
						    	'type' => 'textArea',
						    	'title' => 'Описание акции'
					    	],
						],
						]);?>
					<hr>
					<? echo $form->field($product, '_custom_attributes')->widget(MultipleInput::className(), [
					    'limit' => 10,
					    'min' => 0,
					    'columns' => [
					    	[
						    	'name' => 'name',
						    	'type' => 'textInput',
						    	'title' => 'Характеристика',
					    	],
					    	[
						    	'name' => 'value',
						    	'type' => 'textInput',
						    	'title' => 'Значение'
					    	],
						],
						]);?>

				</div>
			</div>
			<div class="text-right">
				<input type="submit" class="blue-grad" name="save" value="Сохранить">
			</div>
			<? ActiveForm::end(); ?>
		</div>
	</div>
</section>