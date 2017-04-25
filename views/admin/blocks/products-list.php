<?
use yii\grid\ActionColumn;
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\Html;
?>

<?php
$form = ActiveForm::begin();
?>
<div class="row">
  <table class="table table-hover">
    <thead>
      <tr>
        <th>название</th>
        <th>action</th>
      </tr>
    </thead>
    <tbody>
    <?php $i = -1; ?>
		<?php foreach ($products as $product): ?>
    <?php $i++;?>
			<tr>
				<td class="col-sm-10"><?= $product->title ?></td>
		        <td class="col-sm-2">
            <?php if($model->products[$i]->id == $product->id): ?>
		        	<input type="checkbox" checked disabled>
            <?php else: ?>
              <input type="checkbox" name="product_id[]" value="<?= $product->id ?>">
          <?php endif; ?>
		        	<a href="<?= Url::toRoute(['view', 'id'=>$product->id]) ?>"><i class="fa fa-eye"></i></a>
		        	<a href="#" data-product-id="<?= $product->id ?>" data-block-id="<?= $block->id ?>" class="delete-select-btn"><i class="fa fa-close"></i></a>
	        	</td>
		    </tr>
		<?php endforeach; ?>
    </tbody>
  </table>
</div>
	<input type="hidden" name="block_id" value="<?= $_GET['block_id'] ?>">
<?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']); ?>
<?php ActiveForm::end(); ?>

<?php
$urlDelete = Url::toRoute(['admin/blocks/remove-select-product']);
$script = <<<JS
    $(document).on('click', '.delete-select-btn', function() {
        var product_id = $(this).data('product-id');
        var parent = $(this).parents("tr");
        $.ajax({
            url: '$urlDelete',
            method: 'POST',
            data: {
                product_id: product_id,
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