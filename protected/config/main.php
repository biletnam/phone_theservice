<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'Система управление номерами(СУН)',

	// preloading 'log' component
	'preload'=>array('log'),

    // path aliases
    'aliases' => array(
        'bootstrap' => realpath(__DIR__ . '/../extensions/bootstrap'), // change this if necessary
        //'sypexgeo' => realpath(__DIR__ . '/../components/sypexgeo'), // change this if necessary
    ),

    'defaultController' => 'city/index',

    // язык поумолчанию
    'sourceLanguage' => 'en_US',
    'language' => 'ru',

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
        'application.components.sypexgeo.SxGeo',
        'bootstrap.widgets.*',
        'bootstrap.helpers.TbHtml',
        'bootstrap.helpers.TbArray',
        'bootstrap.behaviors.TbWidget',
	),


	'modules'=>array(
		// uncomment the following to enable the Gii tool
                            /*
		'gii'=>array(
			'class'=>'system.gii.GiiModule',
			'password'=>'1',
			// If removed, Gii defaults to localhost only. Edit carefully to taste.
			//'ipFilters'=>array('127.0.0.1','::1'),
            'generatorPaths' => array('bootstrap.gii'),
		),
*/

	),

	// application components
	'components'=>array(

        // установим некоторые значения - по умолчанию
        'widgetFactory'=>array(
            'widgets'=>array(
                'CLinkPager'=>array(
                    'maxButtonCount'=>5,
                    //'cssFile'=>false,
                    'pageSize'=>100,

                ),
                'CJuiDatePicker'=>array(
                    'language'=>'ru',
                ),
            ),
        ),

		'user'=>array(
			// enable cookie-based authentication
			'allowAutoLogin'=>true,
		),




        'cache'=>array(
            'class'=>'system.caching.CFileCache',
        ),

        'sypexgeo'=>array(
            //'class'=>'sypexgeo',
            'class' => 'application.components.sypexgeo.SypexGeoComponent',
        ),

        'bootstrap' => array(
            'class' => 'bootstrap.components.TbApi',
            //'responsiveCss' => true,
        ),
		// uncomment the following to enable URLs in path-format
        'urlManager'=>array(
            'urlFormat'=>'path',
            'rules'=>array(
                ''=>'site/login',
                '<controller:\w+>/<id:\d+>'=>'<controller>/view',
                '<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
                '<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
            ),
            'showScriptName'=>false,
        ),

		// uncomment the following to use a MySQL database
        //конфиги для работы с базами данных
		'db'=>include_once('db.php'),

        'modx'=>include_once('modx.php'),

        'db2'=>include_once('db2.php'),

		'errorHandler'=>array(
			// use 'site/error' action to display errors
			'errorAction'=>'site/error',
		),
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
				),
				// uncomment the following to show log messages on web pages
                                                         /*
				array(
					'class'=>'CWebLogRoute',
				),*/

			),
		),
	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>array(
		// this is used in contact page
		'adminEmail'=>'webmaster@example.com',
	),
);