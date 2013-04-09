<?php

ini_set('display_errors', true);
error_reporting(E_ALL);

$config_file = dirname(__FILE__).'/config.php';
if (!file_exists($config_file)) {
    header('location: setup.php?a=config');
    return;
}
require_once $config_file;

if (DB_VERSION < include(dirname(__FILE__) . '/dbversion.php')) {
    header('location: setup.php?a=updatedb');
    return;
}

// change the following paths if necessary
$yii=dirname(__FILE__).'/../framework/yii.php';
$main_config=dirname(__FILE__).'/protected/config/main.php';

// remove the following lines when in production mode
defined('YII_DEBUG') or define('YII_DEBUG',true);
// specify how many levels of call stack should be shown in each log message
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);

require_once($yii);
Yii::createWebApplication($main_config)->run();
