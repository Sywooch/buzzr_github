<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/site.css',
        'https://fonts.googleapis.com/css?family=Open+Sans:400,400italic,600,700,700italic,300italic&subset=latin,cyrillic',
        'https://fonts.googleapis.com/css?family=Roboto:400,500&subset=cyrillic',

    ];
    public $js = [
    	'js/main.js',
    	'https://api-maps.yandex.ru/2.1/?lang=ru&load=package.full',
    	'js/map.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'app\assets\ImageCropperAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset',
		'\rmrevin\yii\fontawesome\AssetBundle',
		'\traversient\yii\customscrollbar\AssetBundle',
		'yii\jquery\formstyler\FormStylerAsset'
    ];
}
