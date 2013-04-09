<?php /* @var $this RegisterController */ ?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet"  href="<?php echo Yii::app()->request->baseUrl; ?>/css/themes/default/jquery.mobile-1.3.0.css">
        <link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/jqm-demos.css" />
        <link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/registration.css" />
	<link rel="shortcut icon" href="favicon.ico">
	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
   
	<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery-1.9.0.js"></script>
        <script type="text/javascript">
            (function($){
                $.noConflict();
                $(document).bind("mobileinit", function () {
                    $.mobile.ajaxEnabled = false;
                });
            })(jQuery);
        </script>
        <script src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.mobile-1.3.0.js"></script>

</head>
<body>
<div data-role="page" id="jqm-demos" class="jqm-demos">
<div data-role="header" class="jqm-header head" style="min-height:135px;">
    <h1 class="jqm-logo">
        <a href="<?php echo $this->getURL('index') ?>" data-role="none">
            <?php echo CHtml::encode($this->org->full_name); ?>
        </a>
        
        <?php 
        
        /** @var CWebUser $user */
        $user = Yii::app()->user;
        if (! $user->isGuest) {
            echo CHtml::encode(UserIdentity::getFullName($user->id));
        }
        
        ?>
    </h1>
    
    <a href="#" class="jqm-search-link ui-btn-right" data-icon="search" data-iconpos="notext">Search</a>


</div><!-- /header -->


<div data-role="content" class="jqm-content">
    <?php echo $content; ?>
</div><!-- /content -->

	<div data-role="footer" class="jqm-footer">
            <p>Copyright 2013 <a href="<?php echo Yii::app()->request->baseUrl; ?>/">The Oprecx Team</a></p>
	</div><!-- /jqm-footer -->

</div><!-- /page -->
</body>
</html>
