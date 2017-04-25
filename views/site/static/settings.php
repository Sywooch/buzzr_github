<?php
use yii\helpers\Url;
?>
<div class="static-hdr-bar">
	<?=$codeback?>
	<h2>Настройки магазина <span class="greysmall">вкладка "о магазине"</span></h2>
</div>
<div class="static-content-area">

	<div class="wrap_seting">
		<div class="item_set"><div class="number">1</div><div class="item_set_text">Загрузка логотипа - логотип будет отображаться на карточке вашего магазина  </div></div>
		<div class="item_set"><div class="number">2</div><div class="item_set_text">Название магазина</div></div>
		<div class="item_set"><div class="number">3</div><div class="item_set_text">Тип страницы - выберите магазин или организация. От выбора будет зависеть в каком списке <br> (магазины/организации) будет находиться карточка вашей компании</div></div>
		<div class="item_set"><div class="number">4</div><div class="item_set_text">Описание магазина</div></div>
		<div class="item_set"><div class="number">5</div><div class="item_set_text">Профиль магазина - выберите профиль, чтобы покупатель знал тематику вашего магазина, а фильтр сайта вывел его в нужную группу в списке. </div></div>
		<div class="item_set"><div class="number">6</div><div class="item_set_text">Адрес - для определения местоположение вашего магазина на интерактивной карте. Местоположение так же указывается в карточке товара, если отсутствует опция доставки. </div></div>
		<div class="item_set"><div class="number">7</div><div class="item_set_text">Статус магазина Включен/выключен. Если вы по каким-то причинам хотите на время скрыть свой магазин от покупателей - выберите статус выключен. Так же магазин может быть принудительно выключен если количество предлагаемых товаров меньше 5 позиций. </div></div>
		<div class="item_set"><div class="number">8</div><div class="item_set_text">Доставка - если магазин осушествляет доставку выберите опцию с доставкой. - эта функция активна - на странице вашего товара появится кнопка “добавить в корзину”. В ином случае функция вашего магазина ограничена лишь информационой составляющей. </div></div>
		<div class="item_set"><div class="number">9</div><div class="item_set_text">- В поле ниже укажите условия доставки. Вся информация будет отображаться на странице ваших товаров. </div></div>
		<div class="item_set"><div class="number">10</div><div class="item_set_text">Именованная сылка магазина. Измените имя сылки на ваш магазин. </div></div>
	</div>
	
	<img src="/img/set_shop.png" alt="" class="img-responsive">

	<p>Рекомендуем ознакомиться:</p>
	<p>	<a href="<?=Url::to(['site/info', 'page'=>'catalog']);?>">Создание каталога</a></p>
</p>
</div>