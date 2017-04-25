<?
use yii\widgets\ActiveForm;
use app\models\filters\ArticleFilter;
?>
<section class="news-publish">
	<div class="container narrow">
		<? $form = ActiveForm::begin(); ?>
		<label>Выберите категорию новостей</label>
		<? echo $form->field($model, 'category_id')->dropDownList(ArticleFilter::getCategories(false))->label(false); ?>
		<button class="blue-grad">Опубликовать</button>
		<? ActiveForm::end(); ?>
	</div>
</section>