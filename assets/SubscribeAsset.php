<?php

namespace app\assets;

use yii\web\AssetBundle;

class SubscribeAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
    ];
    public $js = [
    	'/js/subscribe.js'
    ];
    public $depends = [
		'yii\web\JqueryAsset'
	];
}