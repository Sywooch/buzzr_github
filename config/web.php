<?php

$params = require(__DIR__ . '/params.php');

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'language' => 'ru-RU',
    'sourceLanguage' => 'en-US',
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'gsdjfklgsdfg',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
		'reCaptcha' => [
		    'name' => 'reCaptcha',
		    'class' => 'himiklab\yii2\recaptcha\ReCaptcha',
		    'siteKey' => '6LcBZQkUAAAAANbnuOPYDeWp-o-lItNLoxyUh2nW',
		    'secret' => '6LcBZQkUAAAAABELM3dqkkd9wswu9MEB0hNEcnzv',
		],
    	'assetManager' => [
            'appendTimestamp' => true,
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => false,
//			'transport' => [
//                'class' => 'Swift_SmtpTransport',
//                'host' => 'smtp.mailtrap.io',
//                'username' => 'd56f007fd539b8',
//                'password' => '5469096244bc76',
//                'port' => '465',
//                'encryption' => 'tls',
//            ],
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => require(__DIR__ . '/db.php'),
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            	'<service:stores>' => 'stores/index',
            	'<service:organizations>' => 'stores/index',

            	'catalog/search' => 'catalog/search',
            	'info/<page>' => 'site/info',
            	
            	'cabinet/chat/<receiver_id:\d+>' => 'cabinet/chat/index',

            	'store/catalog/<id:\d+>' => 'store/catalog',
            	'store/catalog/<id:\d+>/<parent>' => 'store/catalogproducts',
            	'store/catalog-edit/<id:\d+>' => 'store/catalogedit',
            	'store/catalog-select/<id:\d+>' => 'store/catalogselect',
            	'store/catalog/<code>' => 'store/catalog',
            	'store/catalog/<code>/<parent>' => 'store/catalogproducts',
            	'store/catalog-products-edit/<code>/<parent>' => 'store/catalogproductsedit',
            	'store/catalog-edit/<code>' => 'store/catalogedit',
            	'store/catalog-select/<code>' => 'store/catalogselect',

            	'store/about/<id:\d+>' => 'store/about',
            	'store/about/<code>' => 'store/about',
            	'store/about-edit/<id:\d+>' => 'store/aboutedit',
            	'store/about-edit/<code>' => 'store/aboutedit',

            	'store/main/<id:\d+>' => 'store/main',
            	'store/main/<code>' => 'store/main',
            	'store/main-edit/<id:\d+>' => 'store/mainedit',
            	'store/main-edit/<code>' => 'store/mainedit',

            	'store/updates/<id:\d+>' => 'store/updates',
            	'store/updates/<code>' => 'store/updates',
            	'store/updates-edit/<id:\d+>' => 'store/updatesedit',
            	'store/updates-edit/<code>' => 'store/updatesedit',

            	'store/blog/<id:\d+>' => 'store/blog',
            	'store/blog/<code>' => 'store/blog',
            	'store/blog-edit/<id:\d+>' => 'store/blogedit',
            	'store/blog-edit/<code>' => 'store/blogedit',

            	'news/<id:\d+>' => 'news/view',


            	'/' => 'site/index',
            	'map' => 'site/map',
            	'news' => 'news/index',
            	[
			        'class' => 'app\components\CatalogRule', 
            	],
            ],
        ],
    ],
    'params' => $params,
];

if (true) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        'allowedIPs' => ['94.31.163.83', '88.85.221.78', '127.0.0.1']
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

return $config;
