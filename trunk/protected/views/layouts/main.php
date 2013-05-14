<?php
/* @var $this Controller */

$page_class = explode('/', $this->route);
$jsUrl = O::app()->request->baseUrl . '/js/';
if (! YII_DEBUG) {
    $jsFiles = array(
        array('//cdnjs.cloudflare.com/ajax/libs/jquery/1.9.1/jquery.min.js', 
            array(
                O::versionedFileUrl('/js/oprecx', '.js'),
                '//cdnjs.cloudflare.com/ajax/libs/jquery-mobile/1.3.1/jquery.mobile.min.js', 
            )
        ),
    );
    $jqmCss = '//cdnjs.cloudflare.com/ajax/libs/jquery-mobile/1.3.1/jquery.mobile.min.css';
} else {
    $jsFiles = array(
        array($jsUrl . 'jquery-1.9.1.js', 
            array(
                O::versionedFileUrl('/js/oprecx', '.js'),
                $jsUrl . 'jquery.mobile-1.3.1.js',                 
            )
        ),
    );
    $jqmCss = O::app()->request->baseUrl . '/css/jquery.mobile-1.3.1.css';
}

if (is_array($this->metaData)) {
    if (! isset($this->metaData['type'])) $this->metaData['type'] = 'article';
    
    switch ($this->metaData['type']) {
        case 'recruitment':
            $schemeType = 'http://schema.org/Product';
            $ogType = 'website';
            $twitterCard = 'product';
            break;
        
        default:
            $schemeType = 'http://schema.org/Article';
            $ogType = 'website';
            $twitterCard = 'summary';
    }
    unset($this->metaData['type']);
    $headAttr = 'prefix="og: http://ogp.me/ns#" itemscope itemtype="' . $schemeType . '"';
    
    if (!isset($this->metaData['og:type'])) $this->metaData['og:type'] = $ogType;
    if (!isset($this->metaData['twitter:card'])) $this->metaData['twitter:card'] = $twitterCard;
    if (!isset($this->metaData['twitter:site'])) $this->metaData['twitter:site'] = '@oprecx';
    
    if (!isset($this->metaData['og:title'])) $this->metaData['og:title'] = $this->pageTitle;
    if (!isset($this->metaData['twitter:title'])) $this->metaData['twitter:title'] = $this->pageTitle;
    if (!isset($this->metaData['name'])) $this->metaData['name'] = $this->pageTitle;
    
    if (! isset($this->metaData['description'])) $this->metaData['description'] = 
            O::t ('oprecx', 'Oprecx is complete solution for your recruitments. It combines registration form, interview slot selector, and serve result optimized for open recruitment process.');
    if (!isset($this->metaData['og:description'])) $this->metaData['og:description'] = $this->metaData['description'];
    if (!isset($this->metaData['twitter:description'])) $this->metaData['twitter:description'] = $this->metaData['description'];

    
    if (! isset($this->metaData['image'])) $this->metaData['image'] = O::app ()->request->getBaseUrl (TRUE) . '/images/oprecx.png';
    if (!isset($this->metaData['og:image'])) $this->metaData['og:image'] = $this->metaData['image'];
    if (!isset($this->metaData['twitter:image'])) $this->metaData['twitter:image'] = $this->metaData['image'];
    
    
    if (!isset($this->metaData['og:site_name'])) $this->metaData['og:site_name'] = O::app()->name;
    if (!isset($this->metaData['og:locale'])) $this->metaData['og:locale'] = O::app()->language;
}


?>
<!DOCTYPE html>
<html class="ui-mobile no-js" <?php echo isset($headAttr) ? $headAttr : ''; ?>>
    <head>
        <meta charset="utf-8">
        <title><?php echo CHtml::encode($this->pageTitle); ?></title>
        
        <?php if (!O::app()->request->isAjaxRequest) : ?>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet"  href="<?php echo $jqmCss; ?>">
        <link rel="stylesheet" href="<?php echo O::versionedFileUrl('/css/oprecx', '.css') ?>" />
        <link rel="shortcut icon" href="<?php echo O::app()->request->baseUrl; ?>/favicon.ico">
        <link type="text/plain" rel="author" href="<?php echo O::app()->request->getBaseUrl(TRUE); ?>/humans.txt" />
        
        <?php 
        if (is_array($this->metaData)) {
            echo '<meta name="description" itemprop="description" content="', $this->metaData['description'], '" />';
            unset($this->metaData['description']);
            
            ksort($this->metaData);
            foreach ($this->metaData as $prop => $val) {
                $val = CHtml::encode($val);
                
                if (strncmp('og:', $prop, 3)) {
                    echo '<meta property="', $prop, '" content="', $val, '" />';
                } 
                elseif (strncmp('twitter:', $prop, 8)) {
                    echo '<meta name="', $prop, '" content="', $val, '" />';
                }
                else {
                    echo '<meta itemprop="', $prop, '" content="', $val, '" />';
                }
            }
        }
        else {
            echo '<meta name="robots" content="noindex" />';
        }
        ?>
        <script>
        var lazyLoad = <?php echo json_encode($jsFiles); ?>;
        </script>
        <script src="<?php echo O::app()->request->baseUrl; ?>/js/load.js" async></script>
        
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
