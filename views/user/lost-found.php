<section class="lostpass">
	<div class="container narrow">
		<? if(isset($error)) : ?>
		<div class="panel panel-danger">
			<div class="panel-heading">
				<?=$error; ?>
			</div>
		</div>
		<? else : ?>
		<div class="panel panel-info">
			<div class="panel-heading">
				Письмо отправлено
			</div>
		</div>
		<? endif ?>
	</div>
</section>