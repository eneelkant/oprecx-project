<?php
/*
return array( 
    'connectionString' => 'mysql:host=db4free.net;dbname=oprecx',
    'emulatePrepare' => true,
    'username' => 'oprecx',
    'password' => 'everyonecan',
    'charset' => 'utf8',
);
// */
//*
return array(
    //'mysql:host=localhost;dbname=oprecx', 
    //'sqlite:' . dirname(__FILE__) . '/../data/oprecx.sqlite',  
    // 'pgsql:host=localhost;dbname=oprecx'
    'connectionString' =>'mysql:host=localhost;dbname=oprecx' ,
    'emulatePrepare' => true,
    'username' => 'oprecx',
    'password' => 'oprecx',
    'charset' => 'utf8',
    'tablePrefix' => 'oprecx_',
    //'schemaCachingDuration' => 3600 * 24,
    //'queryCachingDuration' => 100,
);
// */
