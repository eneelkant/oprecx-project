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
            'urlFormat' => 'path',
            'showScriptName' => false,
            'rules' => array(
                '/' => 'site/index',
                '/about/<view:\w+>' => array('site/page', 'urlSuffix' => '.html'),
                '/user/<action:\w+>' => 'user/<action>',
                
                '/gii/<controller:\w+>' => 'gii/<controller>/index',
                '/gii/<controller:\w+>/<action:\w+>' => 'gii/<controller>/<action>',
                
                '/<org:\w+>/' => array('register/default/index', 'caseSensitive'=>false),
                '/<org:\w+>/<action:\w+>' => 'register/default/<action>',
                //'/<org:[^(admin)]>/' => 'register/default/index',
                //'/<org:[^(admin)]>/<action:\w+>' => 'register/default/<action>',

                '/<controller:\w+>/<action:\w+>' => '<controller>/<action>',
            ),
        ),
        // */

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
            'class' => getCacheClassName(),
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

function getCacheClassName() {
    return (function_exists('apc_add') ? 'system.caching.CApcCache' : 'system.caching.CFileCache');
}