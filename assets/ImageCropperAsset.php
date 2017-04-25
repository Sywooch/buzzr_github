<?php
namespace app\assets;

use yii\web\AssetBundle;

class ImageCropperAsset extends AssetBundle
{
    public $sourcePath = '@bower/imgareaselect';
    public $css = [
        'distfiles/css/imgareaselect-animated.css'
    ];
    public $js = [
    	'jquery.imgareaselect.dev.js'
	];
}
