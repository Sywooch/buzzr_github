<?php
use yii\helpers\Url;
?>
<div class="static-hdr-bar">
	<?=$codeback?>
	<h2>Возможности сайта</h2>
</div>
<div class="static-content-area nopad">
	<div class="options-item">
		<div class="dtc">
			<img src="/img/opp_img1.png" alt="" class="left-image hidden-xs">
		</div>
		<div class="dtc">
			<h4>Бесплатно создать функционирующий интернет магазина за 10 минут.</h4>
			<p>Всего в несколько кликов вы сможете создать страницу интернет магазина, составить каталог и заполнить свой новый магазин товарами и прочей информацией.</p>
		</div>
	</div>
	<div class="options-item">
		<div class="dtc">
			<h4>Осуществлять продажи ващих товаров.</h4>
			<p>Ваши клиенты смогут заказать товар в вашем магазине. Уведомление о заказе придёт как письмом на вашу почту, так и уведомлением в ваш личный кабинет. <br>
			Обработать поступивший заказ вы сможете во вкладке “мои заказы”, которая находится как в меню личного кабинета, так и в панеле навигации вашего магазина. </p>
		</div>
		<div class="dtc">
			<img src="/img/opp_img2.png" alt="" class="right-image hidden-xs">
		</div>
	</div>
	<div class="options-item">
		<div class="dtc">
			<img src="/img/opp_img3.png" alt="" class="left-image hidden-xs">
		</div>
		<div class="dtc">
			<h4>Отслеживать заинтересованность посетителей вашим интернет магазином</h4>
			<p>Для каждого магазина ведётся учёт поещений страниц ваших товаров, а так же магазина вцелом.
			<br>
			Счётчик посещения товаров находится в левомверхнем углу карточки товара. 
			<br>
			Счётчик посещения магазина - во вкладке “о магазине” в режиме просмотра.
			</p>
		</div>
	</div>
	<div class="options-item">
		<div class="dtc">
			<h4>Отметить свою компанию на торговой карте Крыма</h4>
			<p>Все созданые магазины отмечаются меткой на интерактивной торговой карте. 
			<br>
			При клике пометке открывается окно с описанием магазина и предложением его посетить. 
			</p>
		</div>
		<div class="dtc">
			<img src="/img/opp_img4.png" alt="" class="right-image hidden-xs">
		</div>
		</div>
	<div class="options-item">
		<div class="dtc">
			<img src="/img/opp_img5.png" alt="" class="left-image hidden-xs">
		</div>
		<div class="dtc">
			<h4>Детилься контентом своего интернет магазина через социальные сети</h4>
			<p>Для подвижения магазина имеется функция “поделиться” товарами, услугами и публикациями через социальные сети (vk, facebook, одноклассники). </p>
		</div>
	</div>
	<div class="options-bottom">
		<h4>Рекомендуем ознакомиться:</h4>
		<p><a href="<?=Url::to(['site/info', 'page'=>'options']);?>">Возможности сайта</a><br>
		<a href="<?=Url::to(['site/info', 'page'=>'create']);?>">Создание магазина</a></p>
	</div>
</p>
</div>