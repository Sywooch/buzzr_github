<?
use yii\helpers\Url;
use yii\helpers\Html;

?>
<section class="store">
    <div class="container narrow">
        <? echo $this->render('_header', ['store' => $store]); ?>
        <div class="subpage-content">
            <div class="pull-right">
                <?php if ($subcount > 1): ?>
                    <a href="<?= Url::toRoute(['store/catalogproductsall', 'code' => $store->slug]) ?>">показать все
                        товары</a>
                <?php endif; ?>
            </div>
            <div class="catalog-categories">
                <? if (!$model[0]['has_selected']) : ?>
                    <div class="edit-note">
                        У вас ещё не добавлено ни одного товара.<br>
                        Чтобы добавить товар необходимо
                        <a href="<?= Url::toRoute(['store/mainedit', 'code' => $store->slug]) ?>"><i
                                class="fa fa-pencil-square-o" aria-hidden="true"></i> перейти в режим редактирования</a>
                        и создать каталог товаров во вкладке <a
                            href="<?= Url::toRoute(['store/catalogselect', 'code' => $store->slug]) ?>">разделы</a>.
                        <h4><a href="<?= Url::toRoute(['site/info', 'page'=>'settings']) ?>"><i class="fa fa-info-circle"
                                                                                   aria-hidden="true"></i> Помощь</a>
                        </h4>
                    </div>
                <? endif; ?>
                <? foreach ($model[0]['subcat'] as $t_id) : $topcat = $model[$t_id];
                    if (!$topcat['has_selected'] || (0 === $topcat['act_cnt'])) continue; ?>
                    <div class="topcat">
                        <div class="title">
                            <?= Html::encode($topcat['title']); ?>
                        </div>
                        <div class="content">
                            <? foreach ($topcat['subcat'] as $m_id) : $midcat = $model[$m_id];
                                if (!$midcat['has_selected'] || (0 === $midcat['act_cnt'])) continue; ?>
                                <table class="midcat">
                                    <tr class="head">
                                        <th class="title-text"><?= Html::encode($midcat['title']); ?></th>
                                        <th class="counter">товаров:</th>
                                    </tr>
                                    <? foreach ($midcat['subcat'] as $l_id) :
                                        $lastcat = $model[$l_id];
                                        if (!$lastcat['has_selected'] || (0 === $lastcat['act_cnt'])) continue;
                                        $url = Url::toRoute(['store/catalogproducts', 'code' => $store->slug, 'parent' => $l_id]);
                                        ?>
                                        <tr class="lastcat">
                                            <td class="title-text">
                                                <?= Html::encode($lastcat['title']); ?>
                                                <a href="<?= $url ?>" class="hoverage"></a>
                                            </td>
                                            <td class="counter">
                                                <?= $lastcat['act_cnt']; ?>
                                                <a href="<?= $url ?>" class="hoverage"></a>
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
