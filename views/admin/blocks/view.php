<?php
use yii\helpers\Html;
use yii\widgets\DetailView;
?>
<div class="row">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'title',
            'manufactures',
        ],
    ]) ?>

</div>