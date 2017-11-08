<?php

$params = require(__DIR__ . '/params.php');


$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
	'defaultRoute' => 'site/index',
	'layout' => 'home-without-image',
    'components' => [
        'request' => [
			'parsers' => [
					'application/json' => 'yii\web\JsonParser',
				],
	
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'TBW2xEQJHlquyJhU_d2eV0XtzdHAZdjP',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
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
			
            'useFileTransport' => false,
			'transport' => [
            'class' => 'Swift_SmtpTransport',
            'host' => 'smtp.gmail.com',
            'username' => 'arivazhagan0117@gmail.com',
            'password' => 'jwxxtekxvktoihmp',
            'port' => '587',
            'encryption' => 'tls', 
                        ], 
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
        
     /*    'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
        ], */
        
		
		     
        'urlManager' => [
            'enablePrettyUrl' => true,
			///'enableStrictParsing' => true,
            'showScriptName' => false,
            'rules' => [
				 ['class' => 'yii\rest\UrlRule', 'controller' => 'user'],
            ],
        ], 
		
    ],
    'params' => $params,
	 'modules' => [
        'v1' => [
            'class' => 'app\modules\api\v1',
        ],
    ],
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

return $config;
