<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');
// This is the main Web application configuration. Any writable
// 

Yii::setPathOfAlias('bootstrap', dirname(__FILE__).'/../extensions/bootstrap');

// CWebApplication properties can be configured here.
return array(
    'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
    'name' => 'Oprecx',
    
    // preloading 'log' component
    'preload' => array('log'),
    
    // autoloading model and component classes
    'import' => array(
        'application.models.*',
        'application.components.*',
    ),
    
    'modules' => array(
        // uncomment the following to enable the Gii tool
        /*
        'gii' => array(
            'class' => 'system.gii.GiiModule',
            'password' => 'gii',
            // If removed, Gii defaults to localhost only. Edit carefully to taste.
            'ipFilters' => array('127.0.0.1', '::1'),
            'generatorPaths' => array(
                //'ext.giix-core', // giix generators
            )
        ),
         //*/
        'admin' , 'registration',
    ),
    // application components
    'components' => array(
        'user' => array(
            // enable cookie-based authentication
            'allowAutoLogin' => true,
            'loginUrl' => array('user/login'),
        ),
        
        'bootstrap' => array(
            'class' => 'ext.bootstrap.components.Bootstrap',
            'responsiveCss' => true,
        ),
        
        // uncomment the following to enable URLs in path-format
        'urlManager' => array(
            'urlFormat' => 'path',
            'showScriptName' => false,
            'rules' => array(
                '/' => 'site/index',
                '/about/<view:\w+>' => array('site/page', 'urlSuffix' => '.html'),
                '/<controller:(user|site)>/<action:\w+>' => '<controller>/<action>',
                
                '/<module:(gii|admin)>' => '<module>/default/index',
                '/<module:(gii|admin)>/<controller:\w+>' => '<module>/<controller>/index',
                '/<module:(gii|admin)>/<controller:\w+>/<action:\w+>' => '<module>/<controller>/<action>',
                
                '/<org_name:\w+>/' => array('registration/default/index', 'caseSensitive'=>false),
                '/<org_name:\w+>/<action:\w+>' => 'registration/default/<action>',

                //'/<controller:\w+>/<action:\w+>' => '<controller>/<action>',
            ),
        ),

        'db' => array(
            'connectionString' => DB_CON_STRING ,
            'emulatePrepare' => true,
            'username' => DB_USER,
            'password' => DB_PASSWORD,
            'charset' => defined('DB_CHARSET') ? DB_CHARSET : 'utf8',
            'tablePrefix' => defined('DB_TABLE_PREFIX') ? DB_TABLE_PREFIX : 'oprecx_',
            'schemaCachingDuration' => 3600 * 24,
            //'queryCachingDuration' => 100,
        ),
        // */
        'errorHandler' => array(
            // use 'site/error' action to display errors
            'errorAction' => 'site/error', 
        ),
        'log' => array(
            'class' => 'CLogRouter',
            'routes' => array(
                //*
                array(
                    'class' => 'CFileLogRoute',
                    'levels' => 'error, warning',
                ),
                 // */
            // uncomment the following to show log messages on web pages
            /*
              array(
              'class'=>'CWebLogRoute',
              ),
             */
            ),
        ),
        //*
        'messages' => array(
            'class' =>  'CPhpMessageSource',
            //'onMissingTranslation' => YII_DEBUG ? array('CPhpMessageTranslator', 'appendMessage') : NULL,
            //'cachingDuration' => 3600 * 24,
            'language' => 'en', // dont change it
        ),
        // */
        
        'cache' => array(
            'class' => defined('OPRECX_CACHE') ? OPRECX_CACHE : 
                    (function_exists('apc_add') ? 'CApcCache' : 'CDummyCache'),
            //'hashKey' => false,
            //'serializer' => false,
        ),
        // */
        
        'request'=>array(
            'enableCookieValidation'=>true,
            'enableCsrfValidation'=>true,
            'csrfTokenName' => 'token',
        ),
    ),
    // application-level parameters that can be accessed
    // using Yii::app()->params['paramName']
    
    
    'params' => array(
        // this is used in contact page
        'adminEmail' => 'admin@oprecx.com',
        
        'defaultLang' => 'id',
        'supportedLang' => array(
            'id' => 'Bahasa Indonesia',
            'en' => 'English',
        ),
        'langMap' => array(
            'en-us' => 'en',
            'id-id' => 'id',
        )
    ),
);