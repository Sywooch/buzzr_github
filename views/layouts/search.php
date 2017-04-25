<?
use yii\widgets\ActiveForm;
use app\models\filters\SearchModel;
use yii\helpers\Url;
use anmaslov\autocomplete\AutoComplete;
use yii\web\JsExpression;

$searchModel = $this->params['headerSearchModel'];

?>
<div class="site-search">
<? $form = ActiveForm::begin(['action'=>Url::toRoute(['site/search'])]); ?>

<div class="row">
	<div class="col-sm-6 header_search_form">
		<div class="searchbox">
			<div class="left">
				<? echo $form->field($searchModel, 'search_query',['template'=>'{input}'])->widget(AutoComplete::classname(), [
					'options' => ['placeholder'=>'Поиск...'],
					'data' => [''],
					'name' => 'SearchModel[search_query]',
					'clientOptions' => [
						'serviceUrl' => Url::toRoute(['site/search']),
						'lookup' => new JsExpression("function(query, done){
								$.getJSON(this.serviceUrl, {json: true, query:query}, function (json_data){
									suggestions = [];
									for(var i in json_data){
										suggestions.push({'value':json_data[i]});
									}
									var result = {
										suggestions: suggestions
									}
									done(result)
								});
							}")
						]
				]); ?>
			</div>
			<? if(false) : ?>
			<div class="right">
				<? echo $form->field($searchModel, 'search_in', ['template'=>'{input}'])
					->dropDownList(SearchModel::getTypes(), ['class'=>'formstyler']); ?>
			</div>
			<? endif ?>
			<div class="activate">
				<button class="form-control"><i class="fa fa-search"></i></button>
			</div>
		</div>
	</div>
	<div class="col-sm-3 header_city">
		<? echo $form->field($searchModel, 'city', ['template'=>'{input}'])
			->dropDownList(SearchModel::getCities(), ['class'=>'formstyler', 'onchange'=>'$(this).closest("form").attr("action", "").submit()']); ?>
	</div>
</div>

<? ActiveForm::end(); ?>
</div>