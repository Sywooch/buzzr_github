<?php
use yii\helpers\Url;
?>
<div class="static-hdr-bar">
	<?=$codeback?>
	<h2>Добавление товара</h2>
</div>
<div class="static-content-area nopad">
	<div class="options-item">
		<p>Товар создается во вкладке каталог (см. <a href="<?=Url::to(['site/info', 'page'=>'catalog']);?>">создание каталога</a>), в выбранных вами категориях. После нажатия на кнопку «добавить товар» откроется шаблон создания товара.</p>
		<p><img src="/img/add_img.png" alt="" class="img-responsive"></p>
	</div>
	<div class="options-item">
		<div class="dtr">
			<div class="dtc sxs">
				Характеристики <span class="number">1</span>
			</div>
			<div class="dtc">
				<p><span class="blue">Характеристики</span> выбранного товара будут соответствовать его категории. Так для мобильных телефонов предложены параметры: размер экрана, операционная система, количество ядер процессора и т.д. для одежды: тип, размер, пол возраст, и так далее. </p>
			</div>
		</div>
		<div class="dtr">
			<div class="dtc sxs">
				Дополнительные характеристики <span class="number">2</span>
			</div>
			<div class="dtc">
				Выбранные характеристики участвуют в расширенном поиске товаров, а так же отображаются на странице вашего товара в блоке «характеристики». Если в шаблоне не присутствуют нужные вам характеристики,
				их можно добавить вручную в блоке <span class="blue">дополнительные характеристики</span>
			</div>
		</div>
	</div>
	<div class="options-item">
		<div class="dtc sxs">
			Ключевые слова <span class="number">3</span>
		</div>
		<div class="dtc">
			Поиск товара осуществляется как по названию так и по <span class="blue">ключевым словам.</span> Добавьте дополнительные ключевые слова по которым поиск выведет покупателя на ваш товар
		</div>
	</div>

	<div class="options-item">
		<div class="dtc sxs">
			Обязательные поля  <span class="number">4</span>
		</div>
		<div class="dtc">
			Обязательными полями для заполнения являются: <span class="blue">цена, название, описание.</span> 
		</div>
	</div>

	<div class="options-item">
		<div class="dtc sxs">
			Изображение <span class="number">5</span>
		</div>
		<div class="dtc">
			<p>Выберите <span class="blue">изображение</span> вашего товара. Первое загруженное изображение будет лицевым изображением товара. Максимальное количество изображений – 4шт. </p>
			<p>Максимальный размер изображения не должен превышать 2 мб. </p>
		</div>
	</div>

	<div class="options-item">
		<div class="dtc sxs">
			Дополнительные<br>функции  <span class="number">6</span>, <span class="number">7</span>
		</div>
		<div class="dtc">
			<p><span class="blue">Цена со скидкой</span>: выберите новую цену, при этом старая цена будет отображаться перечёркнутой, рядом с новой <span class="number">6</span>. </p>
			<p>Если <span class="blue">товар не доступен</span>, вы можете: скрыть его. Или отметить его как <span class="blue">«временно недоступен»</span> <span class="number">7</span>, добавив соответствующую информацию.</p>
		</div>
	</div>

	<div class="options-item">
		<div class="dt">
			<div class="dtc sxs">
				Акция <span class="number">8</span>
			</div>
			<div class="dtc">
				<p>Прикрепите информацию об акционном товаре. (8) Выберите товар из списка имеющихся в вашем магазине товаров, чтобы прикрепить его к акции создаваемого/редактируемого товара. Добавте описание. В результате акционый товар будет отображаться в карточке основного товара</p>
			</div>
		</div>
		<div class="dt">
			<img src="/img/add_img2.png" alt="" class="img-responsive">
		</div>
	</div>

	<div class="options-item">
		<div class="dtc sxs">
			Товар заблокирован <span class="number">9</span><br>
		</div>
		<div class="dtc">
			<p>Если публикация вашего товара нарушает правила пользования сайтом, администратор вправе заблокировать ваш товар. Дальнейшее разблокирование товара обсуждается с администратором. (карточка заблокированного товара буде подсвечиваться красным цветом)</p>
		</div>
	</div>

	<div class="options-item">
		<div class="dtc sxs">
			Рейтинг товара <span class="number">10</span><br>
		</div>
		<div class="dtc">
			<p>Рейтинг качества товара, выставляемый продавцом. отображается звёздочками на карточке товара.</p>
		</div>
	</div>

	<div class="options-item">
		<img src="/img/add_img3.png" alt="" class="img-responsive">
	</div>

	<div class="options-item">
		<div class="dtc sxs">
			Минимум 5 товаров
		</div>
		<div class="dtc">
			<p>Для того чтобы ваш магазин был доступен другим пользователям, необходимо создать <span class="blue">минимум 5 товаров</span> в вашем каталоге.</p>
		</div>
	</div>

	<div class="options-bottom">
		<h4>Рекомендуем ознакомиться:</h4>
		<p><a href="<?=Url::to(['site/info', 'page'=>'settings']);?>">Настройки магазина</a></p>
	</div>
</p>
</div>