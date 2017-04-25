<?php
use yii\helpers\Url;
?>
<div class="static-hdr-bar">
	<?=$codeback?>
	<h2>Создание каталога</h2>
</div>
<div class="static-content-area nopad">

	<div class="sec1">
		<div class="sec1-inner">
			<div class="row">
				<div class="col-sm-5">
					<img src="/img/create_img1.png" class="img-responsive" alt="katalog">
				</div>
				<div class="col-sm-7">
					<div class="h4">Переход в режим редактирования</div>
					<div class="text_sec">для того, чтобы внести изменения в страницу магазина, неободимо перейти в режим редактирования. Кнопка смены режима находится под названием магазина. </div>
				</div>
			</div>
			<br>
			<div class="text-center"><img src="/img/arow.png" alt="arow"></div>
		</div>
	</div>
	<div class="sec1">
		<div class="sec1-inner">
			<div class="row">
				<div class="col-sm-6 col-lg-5">
					<img src="/img/create_img2.png" class="img-responsive" alt="katalog">
				</div>
				<div class="col-sm-6 col-lg-7">
					<div class="h4">Редактирование</div>
					<div class="text_sec">Теперь перед вами меню редактирования вашего магазина. Редактирование магазина осуществляется в четырёх вкладках: Разделы, Каталог, О магазине, Новости.</div>
				</div>
			</div>
			<br>
			<div class="text-center"><img src="/img/arow.png" alt="arow"></div>
		</div>
	</div>
	<div class="sec1">
		<div class="sec1-inner">
			<div class="wrap_text_sec2">
				<div class="h4">Создание каталога товаров</div>
				<div class="text_right">1. Перейдите во вкладку разделы. Перед вами список разделов с категориями и подкатегориями товаров. <br>2. Войдите в нужнуый раздел и выберите категории товаров для своего каталога. <br>3. Сохраните изменения.   </div>
			</div>
			<img src="/img/create_img3.png" class="img-responsive" alt="create">
		</div>
	</div>
	<div class="sec1">
		<div class="sec1-inner">
			<div class="wrap_text_sec2">
				<div class="h4">Добавление товара</div>
				<div class="text_right">Перейдите во вкладку каталог. <br>Перед вами катвалог составленный из выбраных вами категорий. <br>Перейдите в нужную категорию из списка. <br>Нажмите на кнопку добавить товар.</div>
			</div>
		</div>
	</div>
	
	<div class="options-bottom">
		<p>Рекомендуем ознакомиться:</p>
		<p><a href="<?=Url::to(['site/info', 'page'=>'add']);?>">Как добавить товар</a>
		</p>
	</div>
</p>
</div>