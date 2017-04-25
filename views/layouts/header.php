<header>
	<div class="container">
		<div class="row header_row">
			<div class="col-sm-2 header_logo_wrap">
				<a href="/" class="header_logo"><img src="/img/logo.png" alt="" class="img-responsive"></a>
			</div>
			<div class="col-sm-6 header_search">
				<? echo $this->render('/layouts/search') ?>
			</div>
			<div class="col-sm-4 header_cabinet">
				<? echo $this->render('/layouts/cabinet') ?>
			</div>
		</div>
		<div class="mobile_menu_btn mobile_menu_btn_click">
			<span></span>
		</div>
	</div>
</header>
