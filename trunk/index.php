<?php

$oprecx_config = dirname(__FILE__).'/config.php';
if (!file_exists($oprecx_config)) {
    header('location: setup.php?a=config');
    return;
}
include($oprecx_config);

if (!defined('YII_PHP') || !defined('DB_CON_STRING') || !defined('DB_USER') || !defined('DB_PASSWORD') || 
        !file_exists(YII_PHP)) {
    
    header('location: setup.php?a=config');
    return;
}

// remove the following lines when in production mode
defined('YII_DEBUG') or define('YII_DEBUG', defined('OPRECX_DEBUG') && (OPRECX_DEBUG == '1'));


if (defined('YII_DEBUG') && YII_DEBUG) {
    ini_set('display_errors', true);
    error_reporting(E_ALL);

    if (DB_VERSION < filemtime(dirname(__FILE__) . '/protected/data/CurrentDbScheme.php')) {
        header('location: setup.php?a=updatedb');
        return;
    }
    
    // specify how many levels of call stack should be shown in each log message
    defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);
}

require_once(YII_PHP);
require_once(dirname(__FILE__) . '/oprecx.php');

$config = dirname(__FILE__).'/protected/config/main.php';
O::createOprecxWebApplication($config)->run();
