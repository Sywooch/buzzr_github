<?php
use yii\widgets\Menu;

?>
<div class="infopage-static">
	<div class="container">
		<div class="menu">
			<div class="menu-group">
			<div class="menu-subhdr">
				О сайте
			</div>
				<?=Menu::widget([
					'items' => [
							['label' => 'Идея и назначение сайта', 'url' => ['site/info', 'page'=>'about']],
							['label' => 'Возможности сайта', 'url' => ['site/info', 'page'=>'options']],
						]
					]); ?>
			</div>
			<div class="menu-group">
				<div class="menu-subhdr">
					Создание магазина
				</div>
				<?=Menu::widget([
					'items' => [
							['label' => 'Как создать магазин', 'url' => ['site/info', 'page'=>'create']],
							['label' => 'Настройки магазина', 'url' => ['site/info', 'page'=>'settings']],
							['label' => 'Создание каталога', 'url' => ['site/info', 'page'=>'catalog']],
							['label' => 'Добавление товара', 'url' => ['site/info', 'page'=>'add']],
						]
					]); ?>
			</div>
		</div>
		<div class="content">
			<? echo $this->render($template, ['codeback'=>$codeback]); ?>
		</div>
	</div>
</div>
