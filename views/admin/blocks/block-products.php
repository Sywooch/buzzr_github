<?
use yii\grid\GridView;
use yii\grid\ActionColumn;
use yii\widgets\Pjax;
use yii\helpers\Url;
?>
<div class="headr" style="padding-bottom: 40px;">
	<a href="<?=Url::toRoute(['products-list', 'block_id' => $_GET['id']]);?>" class="pull-right orange-grad"><i class="fa fa-plus"></i> Добавить</a>
</div>
<div class="row">
  <table class="table table-hover">
    <thead>
      <tr>
        <th>название</th>
        <th>Порядок очередности отображения</th>
        <th>Действие</th>
      </tr>
    </thead>
    <tbody>
        <?php $populars = $popularProducts->getModels(); ?>
		<?php foreach ($block->products as $key => $product): ?>
            <?php $url = Url::toRoute(['store/productedit', 'code'=>$populars[$key]->store_slug, 'parent'=>$parent, 'product_id'=>$populars[$key]->id]); ?>
			<tr>
                <td class="col-sm-5"><?= $product->title ?></td>
				<td class="col-sm-5"><?= $product->sort ?></td>
		        <td class="col-sm-2">
                    <a href="<?= Url::toRoute(['block-product-update', 'id'=>$product->id, 'block_id' => $block->id]) ?>"><i class="fa fa-edit"></i></a>
                    <a href="<?=$url?>"><i class="fa fa-eye"></i></a>
                    <a href="#" data-product-id="<?= $product->id ?>" data-block-id="<?= $block->id ?>" class="delete-btn"><i class="fa fa-close"></i></a>
	        	</td>
		    </tr>
		<?php endforeach; ?>
    </tbody>
  </table>
</div>
<?php
$urlDelete = Url::toRoute(['admin/blocks/remove-block-product']);
$script = <<<JS
    $(document).on('click', '.delete-btn', function() {
        var product_id = $(this).data('product-id');
        var block_id = $(this).data('block-id');
        var parent = $(this).parents("tr");
        $.ajax({
            url: '$urlDelete',
            method: 'POST',
            data: {
                product_id: product_id,
                block_id: block_id,
            },

            success:function(response) {
                if (response['result']) {
                   console.log(response['result']);
                   parent.remove();
                }
            }
        });
    });
JS;

$this->registerJs($script);
?>