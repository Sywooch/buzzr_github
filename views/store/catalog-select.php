<?
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use app\widgets\ChatWidget;
?>
<section class="store">
	<div class="container narrow">
		<? echo $this->render('_header_edit', ['store'=>$store]); ?>
		<div class="subpage-content select_edit_content">
			<? $form = ActiveForm::begin(); ?>
			<div class="catalog-categories-select">
				<div class="edit-note">
					Для создания каталога перейдите в нужный раздел, затем отметьте категории товаров, для добавления их в каталог.<br>
					После этого сохраните изменения и перейдите в каталог.
				</div>
				<ul class="categories-table">
					<? $i = 0; foreach($catalog[0]['subcat'] as $t_id) : $topcat = $catalog[$t_id]; //if($topcat['title'] == 'Услуги') continue;?>
						<li class="category-item category-item-<?=$topcat['slug'];?> <? if($i++ == 0) echo 'active'?>">
							<a href="#cat-<?=$t_id?>" class="category-selector" data-toggle="tab">
								<?=Html::encode($topcat['title']);?>
							</a>
						</li>
					<? endforeach ?>
					<li class="category-item category-item-search">
						<a href="#cat-search" class="category-selector" data-toggle="tab"><i class="fa fa-search"></i>&nbsp;&nbsp;Поиск категории по названию</a>
					</li>
				</ul>
				<div class="clearfix"></div>
				<div class="tab-content">
					<? $i = 0; foreach($catalog[0]['subcat'] as $t_id) : $topcat = $catalog[$t_id]; ?>
					<div class="topcat tab-pane <? if($i++ == 0)echo 'active'?>" id="cat-<?=$t_id?>">
						<div class="content">
						<?php \yii2masonry\yii2masonry::begin([
						    'clientOptions' => [
						        'columnWidth' => 50,
						        'itemSelector' => '.midcat',
						    ]
						]); ?>
							<? if(isset($topcat['subcat']))foreach($topcat['subcat'] as $m_id) : $midcat = $catalog[$m_id]; ?>
								<div class="midcat">
									<div class="title-text"><?=$midcat['title'];?></div>
									<? if(isset($midcat['subcat']))foreach($midcat['subcat'] as $l_id) :
									$lastcat = $catalog[$l_id]; 
									?>
									<div class="lastcat">
										<div class="title-text">
											<label for="subcat_<?=$l_id?>">
												<input type="checkbox" name="Store[selected_categories][<?=$l_id?>]" id="subcat_<?=$l_id?>" <? if($lastcat['has_selected'])echo 'checked' ?>>
												<?=Html::encode($lastcat['title']);?></label>
										</div>
									</div>
									<? endforeach ?>
								</div>
							<? endforeach ?>
						<?php \yii2masonry\yii2masonry::end(); ?>
						</div>
					</div>
					<? endforeach ?>
					<div class="topcat tab-pane" id="cat-search">
						<div class="form-group">
						<label for="search_category">Введите название категории</label>
						<input type="text" id="search_category" class="form-control">
						</div>
						<ul>
						<? foreach($catalog[0]['subcat'] as $t_id) : $topcat = $catalog[$t_id]; ?>
							<li class="topcat-s">
								<?=Html::encode($topcat['title']);?>
								<ul>
								<? if(isset($topcat['subcat']))foreach($topcat['subcat'] as $m_id) : $midcat = $catalog[$m_id]; ?>
									<li class="midcat-s">
										<?=Html::encode($midcat['title']);?>
										<ul>
											<? if(isset($midcat['subcat']))foreach($midcat['subcat'] as $l_id) : $lastcat = $catalog[$l_id]; ?>
												<li class="lastcat-s">
													<label for="subcat_<?=$l_id?>">
														<input type="checkbox" name="Store[selected_categories][<?=$l_id?>]" data-twin="subcat_<?=$l_id?>" <? if($lastcat['has_selected'])echo 'checked' ?>>
														<span class="lastcat-title"><?=Html::encode($lastcat['title']);?></span>
													</label>
												</li>
											<? endforeach ?>
										</ul>
									</li>
								<? endforeach ?>
								</ul>
							</li>
						<? endforeach ?>
						</ul>
						<div class="not-found-hint">
							<p>По Вашему запросу категории не найдены. Если на сайте нет нужной Вам категории - напишите администрации сайта.</p>
							<? echo ChatWidget::widget([
								'receiver'=>1,
								'template'=>'/widgets/chat-admin'
							]);
							?>
						</div>
					</div>
				</div>
		</div>
		<button class="blue-grad">Сохранить</button>
		<? ActiveForm::end(); ?>
	</div>
</section>
