<?php
/* @var $this CController */

$page_class = explode('/', $this->route);
$jsUrl = O::app()->request->baseUrl . '/js/';
if (! YII_DEBUG) {
    $jsFiles = array(
        array('//cdnjs.cloudflare.com/ajax/libs/jquery/1.9.1/jquery.min.js', 
            array(
                $jsUrl . 'oprecx.js?m=' . filemtime(O::app()->basePath . '/../js/oprecx.js'),
                '//cdnjs.cloudflare.com/ajax/libs/jquery-mobile/1.3.1/jquery.mobile.min.js', 
            )
        ),
    );
    $jqmCss = '//cdnjs.cloudflare.com/ajax/libs/jquery-mobile/1.3.1/jquery.mobile.min.css';
} else {
    $jsFiles = array(
        array($jsUrl . 'jquery-1.9.0.js', 
            array($jsUrl . 'oprecx.js?m=' . filemtime(O::app()->basePath . '/../js/oprecx.js'),
                $jsUrl . 'jquery.mobile-1.3.0.js',                 
            )
        ),
    );
    $jqmCss = O::app()->request->baseUrl . '/css/jquery.mobile-1.3.1.css';
}

?>
<!DOCTYPE html>
<html class="ui-mobile no-js">
    <head>
        <meta charset="utf-8">
        <title><?php echo CHtml::encode($this->pageTitle); ?></title>
        
        <?php if (!O::app()->request->isAjaxRequest) : ?>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet"  href="<?php echo $jqmCss; ?>">
        <link rel="stylesheet" href="<?php echo O::app()->request->baseUrl; ?>/css/oprecx.css" />
        <link rel="shortcut icon" href="<?php echo O::app()->request->baseUrl; ?>/favicon.ico">
        <script>
        var lazyLoad = <?php echo json_encode($jsFiles); ?>;
        </script>
        <script src="<?php echo O::app()->request->baseUrl; ?>/js/load.js"></script>
        <?php endif; ?>
    </head>
    <body class="ui-mobile-viewport ui-overlay-c" id="body">
        <div data-role="page" id="<?php echo 'page-', implode('-', $page_class) ?>" 
             data-title="<?php echo CHtml::encode($this->pageTitle); ?>"
             class="<?php echo implode(' ', $page_class) ?> ui-page ui-body-c ui-page-panel ui-page-active" 
             data-url="<?php echo CHtml::encode(O::app()->request->requestUri); ?>">
            
            <?php echo $content; ?>
            
            <div data-role="footer" class="footer wrapper">
                <p>
                    Copyright 2013 <a href="<?php echo O::app()->request->baseUrl; ?>/">The Oprecx Team</a> |
                    <?php echo CHtml::link(O::t('oprecx', 'About'), array('/site/about')); ?> |
                    <?php echo CHtml::link(O::t('oprecx', 'Admin'), array('/admin'), array('rel' => 'external')); ?>
                </p>
                <p><?php 
                    $langs = array();
                    $curLang = O::app()->language;
                    foreach (O::app()->params['supportedLang'] as $k => $v) {
                        if ($curLang == $k)
                            $langs[] = '<b>' . $v . '</b>';
                        else
                            $langs[] = CHtml::link($v, 
                                    array('/site/lang', 'locale' => $k, 'return' => $_SERVER['REQUEST_URI']),
                                    array('data-ajax' => 'false'));
                    }
                    echo implode(' | ', $langs);
                ?></p>
            </div><!-- /footer -->

        </div><!-- /page -->
        
        
        <?php if (!O::app()->request->getIsAjaxRequest()) : ?>
        <div id="main-load" style="display: none"><div></div></div>
        <script>if (!lazyLoad.finished) {
            (function(o){
                o.getElementById('main-load').style.display = ''; 
                o.getElementById('body').className='loading';
            })(document);
        }</script>
        <?php endif; ?>
    </body>
</html>
