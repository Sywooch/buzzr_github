<?php
use yii\helpers\Url;

$text = [
	'Создай свой магазин <span>Всего товаров (353)</span>',
	'Загрузи товары <span>Всего товаров (353)</span>',
	'Продавай <span>Всего товаров (353)</span>',
	'Отметь свою компанию на тороговой<br>карте Крыма',
	'Делай покупки<br>через интернет<br>в своем городе',
	'Делись товарами через социальные сети'
	];

?>
<div class="static-hdr-bar">
	<?=$codeback?>
	<h2>Идея и назначение сайта</h2>
</div>
<div class="static-content-area">
	<p>Buzzr.ru - является торговой площадкой с конструктором магазинов. Данная платформа связывает все предприятия и товары единым поисковым механизмом, позволяющим покупателю найти необходимый товар, не выходя из дома.</p>
	<p>Идея сайта: дать возможность покупателю найти необходимый товар посредством посещения магазинов онлайн и дистанционного общения с продавцом. А так же дать возможность каждому желающему перенести свой бизнес на торговую онлайн платформу. </p>
	<div class="logo">Buzzr</div>
	<div class="sublogo">Крымская торговая площадка</div>
	<div class="wonderwuffel">
		<div class="center-round hidden-xs">
			<img src="/img/krym.jpg" alt="">
		</div>
		<div class="wonderitems hidden-xs">
			<? for($i=1; $i <= 6; $i++) : ?>
			<div class="wonderitem wonderitem-<?=$i;?>">
				<div class="line"></div>
				<div class="bound">
					<img class="wonderimage" src="/img/ofer_img<?=$i;?>.png" alt="">
					<div class="text">
						<?=$text[$i-1];?>
					</div>
				</div>
			</div>
			<? endfor ?>
		</div>
	</div>
	<div class="plank">
		Покупай в Крыму <br>
		<a href="/" class="blue-grad">Перейти на сайт</a>
	</div>
	<div class="plank">
		Создай магазин или карточку организации <br>
		<a href="<?=Url::to(['site/addshop']);?>" class="orange-grad">Создать аккаунт</a>
	</div>
	<p>Рекомендуем ознакомиться:</p>
	<p><a href="<?=Url::to(['site/info', 'page'=>'options']);?>">Возможности сайта</a><br>
	<a href="<?=Url::to(['site/info', 'page'=>'create']);?>">Создание магазина</a></p>
</p>
</div>