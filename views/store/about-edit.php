<?
use app\widgets\LikeWidget;
use app\widgets\ShareWidget;
use yii\helpers\Url;
use yii\widgets\ListView;
use yii\widgets\ActiveForm;
use kartik\switchinput\SwitchInput;
use app\models\Category;
use app\models\Store;
use app\models\Region;
use app\widgets\FileUpload as FileUpload;
use yii\helpers\Html;
use dosamigos\ckeditor\CKEditor;

$url = Url::toRoute(['stores/view', 'id'=>$store->id]);

?>
<section class="store">
	<div class="container narrow">
		<? echo $this->render('_header_edit', ['store'=>$store]); ?>
		<div class="subpage-content">
			<? $form = ActiveForm::begin(); ?>
				<div class="row single-picture-widget">
					<div class="col-sm-3">
							<div class="current-logo">
								<? if($store->logo) : ?>
									<img src="<?=$store->logoUrl?>" alt="" class="img-responsive">
								<? else : ?>
									<img src="/img/nophoto.png" alt="" class="img-responsive">
								<? endif ?>
							</div>
							<div class="new-logo">
								<img src="/img/nophoto.png" alt="" class="img-responsive" data-imgcrop='{"aspectRatio":"200:140"}'>
							</div>
					</div>
					<div class="col-sm-5">
						Логотип
						<div class="button-row">
							<?= FileUpload::widget([
							    'model' => $store,
							    'templatePath' => '/widgets/logo_upload',
							    'attribute' => 'logo_tmp',
							    'url' => ['store/logoupload', 'id' => $store->id],
							    'options' => ['accept' => 'image/*', 'class'=>'image-upload'],
							    'clientOptions' => [
							        'maxFileSize' => 2000000
							    ],
						        'clientEvents' => [
					            'fileuploaddone' => 'function(e, data) {
					            						window.loguploaddone(data, $(".new-logo img"), $("input[name=\"Store[update_logo]\"]"));
					                                }',
					            ]
							]);?>
							<input type="hidden" name="Store[update_logo]" value="0">
							<? foreach(['x1', 'y1', 'width', 'height', 'ratio'] as $attr)
								echo $form->field($store, $attr, ['template'=>'{input}'])->hiddenInput(['class'=>$attr]); ?>

						</div>
						<div class="format">
							Допустимый формат: jpg, jpeg, png
							Допустимый размер изображения: Не более 2 мб
						</div>
						<div class="crop-hint">Щелкните по изображению, чтобы выделить его часть. Выделенная часть станет логотипом магазина.</div>
						<a href="#" class="remove-photo-btn">Отменить загрузку фотографии</a>
					</div>
					<div class="col-sm-4">
						<? echo $form->field($store, 'title'); ?>
						<? echo $form->field($store, 'company_type')
							->dropDownList([0=>'Магазин', 1=>'Организация', '2'=>'Поставщик (опт)']
							); ?>
					</div>
				</div>
				<? echo $form->field($store, 'description')->widget(CKEditor::className(), [
			        'options' => ['rows' => 6],
			        'preset' => 'basic',
			        'clientOptions' => [
			        	'removeButtons' => 'Image,Subscript,Superscript,RemoveFormat,Link,Unlink,Anchor,Table'
    				]
			    ]) ?>
    			<div class="row">
					<div class="col-sm-6">
						<? $data = []; foreach(Category::GetList(0) as $cat){
							$data[$cat->id] = $cat->title;
						}
						echo $form->field($store, 'category_id')->dropDownList(
							$data);
						?>
						<? echo $form->field($store, 'active')->widget(SwitchInput::classname(), [
						    'type' => SwitchInput::CHECKBOX,
						    'pluginOptions' => [
							    'onText' => 'Включен',
							    'offText' => 'Выключен',
							    'onColor' => 'success',
							    'offColor' => 'default'
						    	],
						]); ?>
						<? echo $form->field($store, 'sell_deliver')->widget(SwitchInput::classname(), [
						    'type' => SwitchInput::CHECKBOX,
						    'pluginOptions' => [
							    'onText' => 'С доставкой',
							    'offText' => 'Без&nbsp;доставки',
							    'onColor' => 'success',
							    'offColor' => 'default'
						    	],
						]); ?>
						<? echo $form->field($store, 'delivery_text')->textArea(); ?>
						<div class="slug-row">
							<label>Именованная ссылка магазина</label>
							<table>
								<tr>
									<td><?=Yii::$app->request->hostInfo;?>/</td>
									<td><? echo $form->field($store, 'slug')->label(false); ?></td>
								</tr>
							</table>
							
						</div>
						
						<? echo $form->field($store, 'phone'); ?>
						<? echo $this->render('_about_data', ['store'=>$store]); ?>
					</div>
					<div class="col-sm-6">
						<div class="well">
							<div class="geo-title">Местоположение</div>
							<div class="row">
								<div class="col-sm-6">
									<? echo $form->field($store, 'city_id')->dropDownList(Store::getCities()); ?>
								</div>
								<div class="col-sm-6">
									<? $data = [];
									foreach(Region::find()->all() as $region){
										$data[$region->id] = $region->title;
									}
									echo $form->field($store, 'region_id')->dropDownList($data); ?>
								</div>
							</div>
							<? echo $form->field($store, 'address')->textInput(['data-map-target'=>'#main_map']); ?>
							<input type="hidden" name="Store[lat]" class="latinto" value="<?=$store->lat?>">
							<input type="hidden" name="Store[lng]" class="lnginto" value="<?=$store->lng?>">
							<div id="main_map" data-lat="<?=Html::encode($store->lat)?>" data-lng="<?=Html::encode($store->lng)?>" >
								
							</div>
						</div>
					</div>
				</div>
				<div class="text-center">
					<button class="blue-grad">СОХРАНИТЬ</button>
				</div>
			<? ActiveForm::end(); ?>
		</div>
	</div>
</section>
