<?php /** @var $this RegisterController */ ?>
<?php
/** @var CClientScript $cs */
$cs=Yii::app()->clientScript;
$cs->coreScriptPosition=CClientScript::POS_HEAD;
$cs->scriptMap=array();

$baseUrl=$this->module->assetsUrl;

// $cs->registerCoreScript('jquery');
//$cs->registerScriptFile($baseUrl.'/js/jquery.tooltip-1.2.6.min.js');
//$cs->registerScriptFile($baseUrl.'/js/fancybox/jquery.fancybox-1.3.1.pack.js');
//$cs->registerCssFile($baseUrl.'/css/main.css');
$cs->registerCssFile($baseUrl . '/css/main.css');
?>
<!DOCTYPE html>
<html xml:lang="en" lang="en">
    <head>
       <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="language" content="<?php echo Yii::app()->locale->id; ?>" />
        <title><?php echo CHtml::encode($this->pageTitle); ?></title>

    </head>

    <body>
        <header id="header">
            <h1><?php echo CHtml::encode($this->orgName) ?></h1>
            <div id="mainmenu">
                <?php
                $this->widget('zii.widgets.CMenu', array(
                    'items' => array(
                        array('label' => 'Home', 'url' => array('/register/default/index')),
                        array('label' => 'Back', 'url' => Yii::app()->homeUrl),
                    //array('label'=>'About', 'url'=>array('/site/page', 'view'=>'about')),
                    //array('label'=>'Contact', 'url'=>array('/site/contact')),
                    //array('label'=>'Login', 'url'=>array('/site/login'), 'visible'=>Yii::app()->user->isGuest),
                    //array('label'=>'Logout ('.Yii::app()->user->name.')', 'url'=>array('/site/logout'), 'visible'=>!Yii::app()->user->isGuest)
                    ),
                ));
                ?>
            </div><!-- #mainmenu -->
        </header>
            
        <div class="container" id="page">
            
            <?php if (isset($this->breadcrumbs)): ?>
                <?php
                $this->widget('zii.widgets.CBreadcrumbs', array(
                    'links' => $this->breadcrumbs,
                ));
                ?><!-- breadcrumbs -->
            <?php endif ?>

            <?php echo $content; ?>

            <div class="clear"></div>

            <div id="footer">
                Copyright &copy; <?php echo date('Y'); ?> by My Company.<br/>
                All Rights Reserved.<br/>
                <?php echo Yii::powered(); ?>
            </div><!-- footer -->

        </div><!-- page -->

    </body>
</html>
