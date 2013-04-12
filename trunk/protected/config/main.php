<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');
// This is the main Web application configuration. Any writable
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
        'ext.giix-components.*',
    ),
    
    'modules' => array(
        // uncomment the following to enable the Gii tool
        'gii' => array(
            'class' => 'system.gii.GiiModule',
            'password' => 'gii',
            // If removed, Gii defaults to localhost only. Edit carefully to taste.
            'ipFilters' => array('127.0.0.1', '::1'),
            'generatorPaths' => array(
                'ext.giix-core', // giix generators
            )
        ),
        'admin', 'registration',
    ),
    // application components
    'components' => array(
        'user' => array(
            // enable cookie-based authentication
            'allowAutoLogin' => true,
        ),
        
        // uncomment the following to enable URLs in path-format
        'urlManager' => array(
            'urlFormat' => 'path',
            'showScriptName' => false,
            'rules' => array(
                '/' => 'site/index',
                '/about/<view:\w+>' => array('site/page', 'urlSuffix' => '.html'),
                '/<controller:(user|site)>/<action:\w+>' => '<controller>/<action>',
                
                '/<module:(gii)>' => '<module>/default/index',
                '/<module:(gii)>/<controller:\w+>' => '<module>/<controller>/index',
                '/<module:(gii)>/<controller:\w+>/<action:\w+>' => '<module>/<controller>/<action>',
                
                '/<org_name:\w+>/' => array('registration/default/index', 'caseSensitive'=>false),
                '/<org_name:\w+>/<action:\w+>' => 'registration/default/<action>',

                '/<controller:\w+>/<action:\w+>' => '<controller>/<action>',
            ),
        ),

        'db' => array(
            'connectionString' => DB_CON_STRING ,
            'emulatePrepare' => true,
            'username' => DB_USER,
            'password' => DB_PASSWORD,
            'charset' => DB_CHARSET,
            'tablePrefix' => DB_TABLE_PREFIX,
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
            'onMissingTranslation' => array('CPhpMessageTranslator', 'appendMessage'),
            //'cachingDuration' => 3600 * 24,
            'language' => 'en_us',
        ),
        // */
        
        'cache' => array(
            'class' => 'CApcCache',
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
    //'language' => 'id',
    'onBeginRequest' => 'oprecx_init',
    'params' => array(
        // this is used in contact page
        'adminEmail' => 'admin@oprecx.com',
        
        'supportedLang' => array(
            'en_us' => 'English',
            'id' => 'Bahasa Indonesia',
        ),
    ),
);

function getCacheClassName() {
    //return 'system.caching.CFileCache';
    return (function_exists('apc_add') ? 'system.caching.CApcCache' : 'system.caching.CFileCache');
}