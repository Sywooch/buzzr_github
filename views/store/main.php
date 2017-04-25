<section class="store">
	<div class="container narrow">
		<? echo $this->render('_header', ['store'=>$store]); ?>
		<? echo $this->render('main-subpage', [
			'store'=>$store,
			'editable'=>false,
			'saleDataProvider'=>$saleDataProvider,
			'newDataProvider'=>$newDataProvider,
			'popularDataProvider'=>$popularDataProvider
		]); ?>
	</div>
</section>
