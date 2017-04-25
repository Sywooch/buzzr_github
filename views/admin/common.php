<?

?>

<section class="cabinet">
	<div class="container narrow">
		<? echo $this->render('/admin/header', ['user'=>Yii::$app->user->identity]); ?>
		<? echo $this->render($child_template, $params); ?>
	</div>
</section>
