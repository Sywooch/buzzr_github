<?

use yii\helpers\Url;
use yii\widgets\ActiveForm;
use app\models\Category;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use kartik\select2\Select2;
use unclead\widgets\MultipleInput;

?>
<? if($model->isNewRecord) : ?>
<h2>Создание категории товаров</h2>
<? else : ?>
<h2>Редактирование категории товаров</h2>
<? endif ?>
<div class="row">
	<div class="col-sm-6">
		<? $form = ActiveForm::begin(); ?>
		<? echo $form->field($model, 'title'); ?>
		<? echo $form->field($model, 'active')->checkbox(); ?>
		<? echo $form->field($model, 'sort'); ?>
		<? echo $form->field($model, 'slug'); ?>
		<div class="well">
			<p class="text-danger">Выбор родительской категории - опасная настройка. Ничего тут не меняйте, если не понимаете, какие у этого будут последствия</p>
			<? echo $form->field($model, 'parent_id')->widget(Select2::classname(), [
				'data'=>['0'=>'Верхний уровень'] + ArrayHelper::map(Category::find()->all(),'id','title'),
				]
			); ?>
		</div>
		<input type="submit" name="save" class="blue-grad" value="Сохранить">
		<button class="default-grad">Отменить</button>
		<? ActiveForm::end(); ?>
	</div>
	<div class="col-sm-6">
		<p>Свойства категории:</p>
		<? echo $this->render('_category_attr', ['attr_id'=>'new', 'attr_name'=>'Новое свойство', 'attributes_update'=>$attributes_update]); ?>
		<? foreach($attributes as $attr_id => $attr_pair): ?>
		<? echo $this->render('_category_attr', ['attr_id'=>$attr_id, 'attr_name'=>$attr_pair[1], 'attributes_update'=>$attributes_update]); ?>
		<? endforeach ?>
	</div>
</div>
