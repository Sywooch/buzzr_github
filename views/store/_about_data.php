<?
use yii\helpers\Html;

?>

<? if ($store->sell_deliver) : ?>
    <? if ($store->delivery_text) : ?>
        <section class="product">
            <div class="bordered smallpad">
                <div class="delivery-text-title">Условия доставки:</div>
                <div class="delivery-text">
                    <?php echo(nl2br($store->delivery_text)); ?>
                </div>
            </div>
        </section>
    <? endif ?>
<? endif ?>
<div class="data">
    <ul>
        <li>
            Зарегистрирован: <?= Html::encode($store->created) ?>
        </li>
        <li>
            Последний раз заходил: <?= date('Y-m-d h:i:s', $store->user->activity) ?>
        </li>
        <li>
            Подписчиков: <?= Html::encode($store->subscribers) ?>
        </li>
        <li>
            Товаров: <?= Html::encode($store->productsCount) ?>
        </li>
        <li>
            Посетители
        </li>
        <li>
            Сегодня: <?= Html::encode($store->_visits->today) ?>
        </li>
        <li>
            В этом месяце: <?= Html::encode($store->_visits->month) ?>
        </li>
        <li>
            Всего: <?= Html::encode($store->_visits->total) ?>
        </li>
    </ul>
</div>
