<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body id="body">
<?php $this->beginBody() ?>


<? echo $this->render('/layouts/header') ?>
<? echo $this->render('/layouts/menu') ?>

<?php if(Yii::$app->session->hasFlash('message')): ?>
<section class="message">
	<div class="container narrow">
	    <div class="alert alert-success">
	        <?= Yii::$app->session->getFlash('message') ?>
	    </div>
	</div>
</section>
<?php endif; ?>

<div class="page-content">
		<?=$content?>
</div>

<? echo $this->render('/layouts/footer') ?>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
