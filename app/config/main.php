<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');
// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
    'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
    'name' => 'OprecX',
    
    // preloading 'log' component
    'preload' => array('log'),
    
    // autoloading model and component classes
    'import' => array(
        'application.models.*',
        'application.components.*',
    ),
    
    'modules' => array(
        // uncomment the following to enable the Gii tool
        'gii' => array(
            'class' => 'system.gii.GiiModule',
            'password' => 'gii',
            // If removed, Gii defaults to localhost only. Edit carefully to taste.
            'ipFilters' => array('127.0.0.1', '::1'),
        ),
        'admin', 'register',
    ),
    // application components
    'components' => array(
        'user' => array(
            // enable cookie-based authentication
            'allowAutoLogin' => true,
        ),
        // uncomment the following to enable URLs in path-format
        //*
        'urlManager' => array(
            //'urlFormat' => 'path',
            //'showScriptName' => false,
            'rules' => array(
                '/' => 'site/index',
                '/site/page/<view:\w+>' => array('site/page', 'urlSuffix' => '.html'),
                //'/<controller:\w+>/<id:\d+>' => array('<controller>/view', 'urlSuffix' => '.html'),
                '/<org_name:(salamui)>' => 'register/default/index',
                //'/reg/<controller:\w+>/<action:\w+>' => 'register/<controller>/<action>',
                '/<controller:\w+>/<action:\w+>' => '<controller>/<action>',
            ),
        ),
        // */
        //'db'=>array(
        //	'connectionString' => 'sqlite:'.dirname(__FILE__).'/../data/testdrive.db',
        //),
        // uncomment the following to use a MySQL database
        ///*
        'db' => require (dirname(__FILE__) . '/db.php'),
        // */
        'errorHandler' => array(
            // use 'site/error' action to display errors
            'errorAction' => 'site/error',
        ),
        'log' => array(
            'class' => 'CLogRouter',
            'routes' => array(
                array(
                    'class' => 'CFileLogRoute',
                    'levels' => 'error, warning',
                ),
            // uncomment the following to show log messages on web pages
            /*
              array(
              'class'=>'CWebLogRoute',
              ),
             */
            ),
        ),
        'cache' => array(
            'class' => 'system.caching.CFileCache',
            'hashKey' => false,
            
        )
    ),
    // application-level parameters that can be accessed
    // using Yii::app()->params['paramName']
    'params' => array(
        // this is used in contact page
        'adminEmail' => 'webmaster@example.com',
    ),
);