<?php

namespace app\assets;

use yii\web\AssetBundle;

class ShareAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
    ];
    public $js = [
		"//yastatic.net/es5-shims/0.0.2/es5-shims.min.js",
		"//yastatic.net/share2/share.js"
    ];
    public $depends = [
    ];
}