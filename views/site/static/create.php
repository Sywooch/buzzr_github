<?php
use yii\helpers\Url;
?>
<div class="static-hdr-bar">
	<?=$codeback?>
	<h2>Как создать магазин</h2>
</div>
<div class="static-content-area">
	<p>1. Перед созданием магазина, необходимо создать личный кабинет пользователя. Для этого нажмите кнопку <span class="btn btn-default disabled">Регистрация</span> в верхнем левом углу экрана.
	Пройдите регистрацию и подтвердите её электронную почту. </p>
	<p>2. После создания учётной записи, на месте кнопки регистрация вы найдёте кнопку <span class="btn btn-default disabled">Создать магазин</span> магазина генерируется мнгновенно. </p>
	<p>3. Ваш магазин создан. Перейти в него можно по кнопке <span class="btn btn-default disabled">мой магазин</span>, которая появится вместо кнопки “Создать магазин”. </p>
	<div class="well">
		<h2>Преимущества создание магазина на Buzzr.ru</h2>
		<p><span class="adv-wrap"><img src="/img/adv_ico1.png" alt=""></span> Бесплатно создать функционирующий интернет магазина</p>
		<p><span class="adv-wrap"><img src="/img/adv_ico2.png" alt=""></span> Осуществлять и контролировать продажи ващих товаров. </p>
		<p><span class="adv-wrap"><img src="/img/adv_ico3.png" alt=""></span> Отслеживать заинтересованность посетителей вашим магазином </p>
		<p><span class="adv-wrap"><img src="/img/adv_ico4.png" alt=""></span> Отметить свою компанию на торговой карте Крыма</p>
		<p><span class="adv-wrap"><img src="/img/adv_ico5.png" alt=""></span> Размещенать рекламные баннеры на главной странице торговой площадки</p>
		<p><span class="adv-wrap"><img src="/img/adv_ico6.png" alt=""></span> Детилься контентом (товарами, новостями) своего интернет магазина через социальные сети</p>
		<p><span class="adv-wrap"><img src="/img/adv_ico7.png" alt=""></span> Созданые товары или услуги находятся как в вашем магазине так и в общем каталоге сайта. </p>
		<p><span class="adv-wrap"><img src="/img/adv_ico8.png" alt=""></span> Поиск товарав осуществляется по названию, ключевым словам, а так же по заданным храктеристикам</p>
	</div>
	<p>Рекомендуем ознакомиться:</p>
	<p>
		<a href="<?=Url::to(['site/info', 'page'=>'settings']);?>">Настройка магазина</a><br>
		<a href="<?=Url::to(['site/info', 'page'=>'catalog']);?>">Создание каталога</a><br>
		<a href="<?=Url::to(['site/info', 'page'=>'add']);?>">Добавление товара</a>
	</p>
</p>
</div>