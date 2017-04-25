<?
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use app\models\filters\ProductFilter;
?>
<? $form = ActiveForm::begin(['options'=>['data-pjax'=>1]]); ?>
<div class="store-filter">
	<div class="row">
		<div class="col-sm-7">
			<div class="price-filter">
						<span class="dib">Цена от</span>
						<? echo $form->field($filter, 'price_from', ['template'=>'{input}']); ?>
						<span class="dib">до</span>
						<? echo $form->field($filter, 'price_to', ['template'=>'{input}']); ?>
						<div class="form-group">
						<button class="blue-grad">Фильтровать</button>
						</div>
			</div>
		</div>
		<div class="col-sm-3">
			<div class="sort-order">
				<? echo $form->field($filter, 'sort_order', ['template'=>'{input}'])->dropDownList(
					ProductFilter::getSorts());
				?>
			</div>
		</div>
		<div class="col-sm-2">
			<div class="list-type-selector text-right">
				<? $displayTypes = [
						'block' => '<i class="fa fa-th-large"></i>',
						'list' => '<i class="fa fa-list-ul"></i>'
					];
					if(!$filter->display_type)
						$filter->display_type = array_keys($displayTypes)[0];
						
					echo $form->field($filter, 'display_type', ['template'=>'{input}'])->radioList($displayTypes,
					['item' => function($index, $label, $name, $checked, $value) {
						return '<label class="list-type">
							<input name="'.$name.'" type="radio" '. ($checked ? 'checked' : '').' value="'.$value.'">
							<span>'.$label.'</span></label>';
					}
						]);
				?>
			</div>
		</div>
	</div>
	<? $c = count($attributes); ?>
	<? if($c > 0) : ?>
	<div class="filter-attributes well">
		<div class="row">
			<div class="col-sm-9">
				<?
					if($c > 2)
						$class = "col-sm-4";
					elseif($c == 2)
						$class = "col-sm-6";
					else
						$class = "col-sm-12";
				?>
				<div class="row">
					<? foreach($attributes as $attr_id=>$pairs): ?>
					<div class="<?=$class?>">
						<? echo $form->field($filter, 'filter_attribute['.($attr_id).']')->widget(Select2::classname(), [
						    'data' => $pairs[0],
						    'language' => 'ru',
						    'options' => ['placeholder' => $pairs[1], 'multiple'=>true],
					        'showToggleAll' => false,
						    'pluginOptions' => [
						        'allowClear' => true,
						    ],
						])->label($pairs[1]);
						?>
					</div>
					<? endforeach ?>
				</div>
			</div>
			<div class="col-sm-3">
				<div class="pad"><label>&nbsp;</label></div>
				<button class="btn btn-primary">Фильтровать</button>
			</div>
		</div>
	</div>
	<? endif ?>
</div>
<? ActiveForm::end(); ?>
