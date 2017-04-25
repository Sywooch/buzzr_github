<section class="store">
	<div class="container narrow">
		<? echo $this->render('_header_edit', ['store'=>$store]); ?>
		<? echo $this->render('main-subpage', [
			'store'=>$store,
			'editable'=>true,
			'saleDataProvider'=>$saleDataProvider,
			'newDataProvider'=>$newDataProvider,
			'popularDataProvider'=>$popularDataProvider
		]); ?>
	</div>
</section>
