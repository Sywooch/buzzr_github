<?
use yii\widgets\ActiveForm;
use app\models\filters\ArticleFilter;

?>
<div class="news-filter">
	<? $form = ActiveForm::begin(); ?>
	<div class="row">
		<div class="col-sm-offset-6 col-sm-3">
			<div class="sort-order">
				<? echo $form->field($filter, 'sort_order', ['template'=>'{input}'])->dropDownList(
					ArticleFilter::getSorts());
				?>
			</div>
		</div>
		<div class="col-sm-3">
			<div class="category">
				<? echo $form->field($filter, 'category_id', ['template'=>'{input}'])->dropDownList(
					ArticleFilter::getCategories());
				?>
			</div>
		</div>
	</div>

	<? ActiveForm::end(); ?>
</div>