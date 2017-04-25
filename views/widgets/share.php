<?
use app\assets\ShareAsset;
use yii\helpers\Html;

ShareAsset::register($this);
?>
<div class="ya-share2" data-services="vkontakte,facebook,odnoklassniki" data-title="<?=Html::encode($title);?>" data-url="<?=\Yii::$app->request->hostInfo . $url?>"></div>