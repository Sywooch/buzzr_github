<?
use yii\helpers\Url;
use yii\helpers\Html;

?>
<section class="store">
	<div class="container narrow">
		<? echo $this->render('_header_edit', ['store'=>$store]); ?>
		<div class="subpage-content">
			<div class="catalog-categories">
                <? if (!$catalog[0]['has_selected']) : ?>
                    <div class="edit-note">
                        У вас ещё не добавлено ни одного товара.<br>
                        Чтобы добавить товар необходимо
                        создать каталог товаров во вкладке <a
                            href="<?= Url::toRoute(['store/catalogselect', 'code' => $store->slug]) ?>">разделы</a>.
                        <h4><a href="<?= Url::toRoute(['site/info', 'page'=>'settings']) ?>"><i class="fa fa-info-circle"
                                                                                   aria-hidden="true"></i> Помощь</a>
                        </h4>
                    </div>
                <? else : ?>
					<div class="edit-note">
						Для добавления/редактирования товара перейдите в соответствующую категорию.
					</div>	
                <? endif; ?>
			<? foreach($catalog[0]['subcat'] as $t_id) : $topcat = $catalog[$t_id]; if(!$topcat['has_selected'])continue; ?>
			<div class="topcat">
				<div class="title">
					<?=Html::encode($topcat['title']);?>
				</div>
				<div class="content">
					<? foreach($topcat['subcat'] as $m_id) : $midcat = $catalog[$m_id]; if(!$midcat['has_selected'])continue; ?>
						<table class="midcat">
							<tr class="head">
								<th class="title-text"><?=Html::encode($midcat['title']);?></th>
								<th class="counter">товаров<br>активных&nbsp;/&nbsp;всего</th>
							</tr>
							<? foreach($midcat['subcat'] as $l_id) :
							$lastcat = $catalog[$l_id]; if(!$lastcat['has_selected'])continue;
							$url = Url::toRoute(['store/catalogproductsedit', 'code'=>$store->slug, 'parent'=>$l_id]);
							?>
							<tr class="lastcat">
								<td class="title-text">
									<?=Html::encode($lastcat['title']);?>
									<a href="<?=$url?>" class="hoverage"></a>
								</td>
								<td class="counter">
									<?=$lastcat['act_cnt'];?> / <?=$lastcat['cnt'];?>
									<a href="<?=$url?>" class="hoverage"></a>
								</td>
							</tr>
							<? endforeach ?>
						</table>
					<? endforeach ?>
				</div>
			</div>
			<? endforeach ?>
			</div>
		</div>
	</div>
</section>
