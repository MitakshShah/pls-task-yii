<?php
require_once(dirname(dirname(__DIR__)) . '/vendor/autoload.php');
YiiBase::setPathOfAlias('protected', dirname(__DIR__));

return [
	'basePath'   => dirname(__DIR__),
	'name'       => 'PLS 3rd Learning',
	'preload'    => ['log'],
	'import'     => [
		'application.models.*',
		'application.components.*',
		'application.controllers.*',
	],
	'modules'    => [
		'gii' => [
			'class'     => 'system.gii.GiiModule',
			'password'  => 'password',
			'ipFilters' => ['127.0.0.1', '::1'],
		],
	],
	'components' => [
		'user'         => [
			'allowAutoLogin' => TRUE,
		],
		'urlManager'   => [
			'urlSuffix'      => '/',
			'urlFormat'      => 'path',
			'showScriptName' => FALSE,
			'caseSensitive'  => TRUE,
			'rules'          => [
				'post/<id:\d+>/<title:.*?>'     => 'post/view',
				'posts/<tag:.*?>'               => 'post/index',
				'<controller:\w+>/<action:\w+>' => '<controller>/<action>',
			],
		],
		'db'           => require(dirname(__FILE__) . '/database.php'),
		'errorHandler' => [
			'errorAction' => 'site/error',
		],
		'log'          => [
			'class'  => 'CLogRouter',
			'routes' => [
				[
					'class'  => 'CFileLogRoute',
					'levels' => 'error, warning',
				],
			],
		],

	],
	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'     => require((dirname(__FILE__) . '/params.php')),
];
