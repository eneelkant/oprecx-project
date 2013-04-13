<?php

// change the following paths if necessary
$yii=dirname(__FILE__).'/../framework/yii.php';
$main_config=dirname(__FILE__).'/protected/config/main.php';

// remove the following lines when in production mode
defined('YII_DEBUG') or define('YII_DEBUG',true);


$config_file = dirname(__FILE__).'/config.php';
if (!file_exists($config_file)) {
    header('location: setup.php?a=config');
    return;
}
include($config_file);
    
if (defined('YII_DEBUG') && YII_DEBUG) {
    ini_set('display_errors', true);
    error_reporting(E_ALL);

    if (DB_VERSION < include(dirname(__FILE__) . '/protected/data/dbversion.php')) {
        header('location: setup.php?a=updatedb');
        return;
    }
    
    // specify how many levels of call stack should be shown in each log message
    defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);
}

require_once($yii);
Yii::createWebApplication($main_config)->run();

function oprecx_init() {
    //Yii::app()->language = Yii::app()->session->get('lang', 'id');
    Yii::app()->language = Yii::app()->request->cookies->contains('lang') ?
        Yii::app()->request->cookies['lang']->value : 'id';
}