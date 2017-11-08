<?php

$params = require(__DIR__ . '/params.php');
$db = require(__DIR__ . '/db.php');

$config = [
    'id' => 'basic-console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'app\commands',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
		'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'useFileTransport' => false,
			'transport' => [
            'class' => 'Swift_SmtpTransport',
            'host' => 'smtp.gmail.com',
            'username' => 'arivazhagan0117@gmail.com',
            'password' => 'Arivu@!@#Ilan',
            'port' => '587',
            'encryption' => 'tls', 
                        ], 
        ],

        'db' => $db,
		
	  'urlManager' => [
		    'class' => 'yii\web\UrlManager',
			'scriptUrl' => 'http://wordpressmonks.com/works/simplywishes_yii/web/',           
        ], 
		// Cron Job Url Change	
    ],
    'params' => $params,
];



return $config;
