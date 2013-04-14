<?php
defined('YII_DEBUG') or define('YII_DEBUG',false);
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',0);


$config_file = dirname(__FILE__).'/config.php';
if (file_exists($config_file)) include $config_file;
if (!defined('DB_VERSION'))
    define('DB_VERSION', 0);

$fieldmap = array (
    'yii' => array('YII_PHP', ''),
    'debug' => array('OPRECX_DEBUG', '0'),
    
    'dsn' => array('DB_CON_STRING', ''),
    'user' => array('DB_USER', 'oprecx'),
    'password' => array('DB_PASSWORD',  'oprecx'),
    'prefix' => array('DB_TABLE_PREFIX', 'oprecx_'),
    'charset' => array('DB_CHARSET', 'utf8'),
);

function field_value($field_name, $ret_def = true) {
    global $fieldmap;
    if (isset($_POST[$field_name])) return $_POST[$field_name];
    elseif (defined($fieldmap[$field_name][0])) return constant ($fieldmap[$field_name][0]);
    else return $ret_def ? $fieldmap[$field_name][1] : '';
}


if (isset($_POST['post_config'])) {
    $fh = fopen($config_file, 'w');
    fwrite($fh, "<?php\n");
    foreach ($fieldmap as $k => $v) {
        $value = addslashes($_POST[$k]);
        fwrite($fh, "define('{$v[0]}', \"$value\");\n");
    }
    fwrite($fh, "define('DB_VERSION', " . DB_VERSION . ");\n");
    fclose($fh);
    header('location: setup.php?a=config');
    return;
}


if (field_value('yii', false) && file_exists($yii = dirname(__FILE__) . '/' . field_value('yii', false))) {
    require_once($yii);

    try {
        $db = new CDbConnection(field_value('dsn', false), field_value('user', false), field_value('password', false));
        $db->tablePrefix = field_value('prefix');

        $db->setActive(true);
        $db_ok = true;
        $curr_db_ver = filemtime(dirname(__FILE__) . '/protected/data/CurrentDbScheme.php');
        $db_new = DB_VERSION >= $curr_db_ver;
    }
    catch (CException $e) {
        $db_ok = false;
        $db_new = false;
        $err_msg = $e->getMessage();
    }
}
else {
    $err_msg = 'Path to yii ("' . field_value('yii') .  '") not found';
}
if (isset($_GET['a'])) $page_act = $_GET['a'];
else $page_act = 'config';

?>
<!DOCTYPE html>
<html>
<head>
    <title>Oprecx Setup Wizard</title>
    <style>
        body {
            font-family: "Open Sans", sans-serif;
            font-size: 11pt;
            color: #444;
        }
        
        pre {
            font-family: "Inconsolata", monospace;
            font-size: 9pt;
        }
        
        input[type='text'], select {
            font-family: "Open Sans", sans-serif;
            font-size: 10pt;
            width: 200px;
        }
        
        input[type='submit'] {
            font-family: "Open Sans", sans-serif;
            font-size: 11pt;
            width: 120px;
        }
        
        #header {
            border-bottom: 1px solid #333;
        }
        
        #footer {
            margin-top: 50px;
            padding-top: 10px;
            border-top: 1px solid #ddd;
            font-size: 9pt;
            text-align: center;
            color: #777;
        }
        
        #wrapper {
            width: 760px;
            margin: 0 auto;
        }
        
        label span {
            width: 150px;
            display: inline-block;
        }
        
        a:link, a:visited {
            color: #0ac;
            text-decoration: none;
        }
        
        a:hover, a:focus {
            color: #d52;
            text-decoration: none;
        }
        
        .desc {
            font-size: .8em;
            margin-bottom: 1em;
            padding-left: 160px;
        }
        
        .desc code {
            display: inline-block;
            padding: 2px 4px;
            font-size: .9em;
            background-color: #ddd;
        }
        
        legend {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div id="wrapper">
        <header id="header">
            <h1>Oprecx Setup</h1>
        </header>
        
        <?php if ($page_act == 'config') : ?>
        <h2>Configuration</h2>
        <p><?php
        if ($db_ok) {
            echo '<b>Your connection is ready';
            if (!$db_new) {
                echo ' but your database is too old. please <a href="setup.php?a=updatedb">click here</a> to update your database';
            } else {
                echo ', <a href="index.php">click here</a> to goto homepage';
            }
            echo '.</b>';
        } else {
            echo '<i>CONFIGURATION ERROR</i><br />', $err_msg;
        }
        ?></p>
        <form method="post">
            <fieldset>
                <legend>General</legend>
                <div class="row">
                    <label><span>Yii.php</span>: <input name="yii" type="text" value="<?php echo field_value('yii'); ?>" /></label>
                    <div class="desc">relative to index.php. default: <code>framework/yiilite.php</code></div>
                </div>
                <div class="row">
                    <label><span>Debug</span>: <input name="debug" type="text" value="<?php echo field_value('debug'); ?>" /></label>
                    <div class="desc">0 = disable, 1 = enable. default: <code>0</code></div>
                </div>
            </fieldset>
            
            <fieldset>
                <legend>DB Connection</legend>
                <div class="row">
                    <label><span>Connection String</span>: <input name="dsn" type="text" value="<?php echo field_value('dsn'); ?>" /></label>
                    <div class="desc">0 = disable, 1 = enable. default: <code>mysql:host=localhost;dbname=oprecx</code></div>
                </div>
                <div class="row">
                    <label><span>User Name</span>: <input name="user" type="text" value="<?php echo field_value('user'); ?>" /></label>
                </div>
                <div class="row">
                    <label><span>Password</span>: <input name="password" type="text" value="<?php echo field_value('password'); ?>" /></label>
                </div>
                <div class="row">
                    <label><span>Table Prefix</span>: <input name="prefix" type="text" value="<?php echo field_value('prefix'); ?>" /></label>
                </div>
                <div class="row">
                    <label><span>Charset</span>: <select name="charset"><option>utf8</option></select></label>
                </div>
            </fieldset>
            <input type="submit" name="post_config" value="submit" />
        </form>
        <p><i>Connection string yang disarankan = </i><code>mysql:host=localhost;dbname=oprecx</code></p>
        <?php endif; ?>
        
        
        <?php if ($page_act == 'updatedb') : ?>
        <h2>Update Database Structure</h2>
        <?php
        if (!$db_ok) {
            echo '<b>Your connection is not ready, <a href="?a=config">click here</a> to configure it</b>';
        } else {
            if (!isset($_GET['confirm'])) {
                if ($db_new) 
                    echo '<p><strong><i>The database is up-to-date</i>, 
                        <a href="index.php">click here</a> to goto homepage</strong></p>';
                echo '<a href="setup.php?a=updatedb&confirm=1">Click here</a> to update your db',
                    '<p style="color: #c03020; border: 1px solid #c03020; padding: .3em"><b>CAUTON:</b> Migrating your db will reset the data</p>';
            } else {

                include './protected/components/TableNames.php';
                include './protected/data/CurrentDbScheme.php';
                
                $mg = new CurrentDbScheme();
                $mg->dbConnection = $db;
                ob_start();
                echo '<pre>';
                $time=microtime(true);
                echo 'STARTED at ', $time, "\n--------------------------------\n\n";
                $mg->up();
                echo "--------------------------------\n\nELAPSED TIME at ", microtime(true) - $time, "\n";
                echo '</pre>';
                $details = ob_get_contents();
                ob_end_clean();
                
                echo '<p><b>Your database has been updated, <a href="index.php">click here</a> to goto homepage.</b></p>';
                echo '<h3>Details</h3>', $details;
                
                $fh = fopen($config_file, 'r');
                $buff = '';
                while($line = fgets($fh)) {
                    $buff .= preg_replace('/define\s*\(\s*(\'|")DB_VERSION(\'|")\s*,\s*\d+\s*\)/', 
                            "define('DB_VERSION', {$curr_db_ver})", $line);
                }
                file_put_contents($config_file, $buff);
            }
        }
        ?>
        <?php endif; ?>
        <footer id="footer">
            copyright (c) 2013 The Oprecx Team
        </footer>
    </div>
</body>
</html>