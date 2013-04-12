<?php
/* @var $this CController */

$page_class = explode('/', $this->route);
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?php echo CHtml::encode($this->pageTitle); ?></title>
        
        <link rel="stylesheet"  href="<?php echo Yii::app()->request->baseUrl; ?>/css/jquery.mobile-1.3.0.css">
        <link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/oprecx.css" />
        <link rel="shortcut icon" href="favicon.ico">
        <script src="<?php echo Yii::app()->request->baseUrl; ?>/js/all.js"></script>
        <!-- script src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery-1.9.0.js"></script>
        <script type="text/javascript">
            (function($) {
                $.noConflict();
                $(document).bind("mobileinit", function() {
                    $.mobile.ajaxEnabled = false;
                });
            })(jQuery);
        </script>
        <script src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.mobile-1.3.0.js"></script -->
    
    </head>
    <body class="oprecx">
        <div data-role="page" id="<?php echo 'page-', implode('-', $page_class) ?>" 
             class="theme-aloe <?php echo implode(' ', $page_class) ?>" data-url="<?php echo Yii::app()->request->requestUri; ?>">
            <?php echo $content; ?>
            
            <div data-role="footer" class="footer wrapper">
                <p>Copyright 2013 <a href="<?php echo Yii::app()->request->baseUrl; ?>/">The Oprecx Team</a></p>
                <p><?php 
                    $langs = array();
                    foreach (Yii::app()->params['supportedLang'] as $k => $v) {
                        $langs[] = CHtml::link($v, array('/site/lang', 'locale' => $k, 'return' => $_SERVER['REQUEST_URI']));
                    }
                    echo implode(' | ', $langs);
                ?></p>
            </div><!-- /jqm-footer -->

        </div><!-- /page -->
        
    </body>
</html>
