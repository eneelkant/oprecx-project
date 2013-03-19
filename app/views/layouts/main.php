<?php /* @var $this Controller */ ?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
	<link rel="stylesheet"  href="<?php echo Yii::app()->request->baseUrl; ?>/css/themes/default/jquery.mobile-1.3.0.css">
        <link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/jqm-demos.css" />
	<link rel="shortcut icon" href="docs/_assets/favicon.ico">
   
	<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery-1.9.0.js"></script>
<script type="text/javascript">
$(document).bind("mobileinit", function () {
    $.mobile.ajaxEnabled = false;
});
</script>	<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.mobile-1.3.0.js"></script>

</head>
<body>
<div data-role="page" id="jqm-demos" class="jqm-demos">
<div data-role="header" class="jqm-header">
    
    <h1 class="jqm-logo"><a href="<?php echo CHtml::normalizeUrl(array('/site/index')) ?>" data-role="none"><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/logo.png" alt="OprecX"></a></h1>
    
    <a href="#" class="jqm-search-link ui-btn-right" data-icon="search" data-iconpos="notext">Search</a>


</div><!-- /header -->


<div data-role="content" class="jqm-content">
    <?php echo $content; ?>
</div><!-- /content -->

	<div data-role="footer" class="jqm-footer">
		<p class="jqm-version"></p>
		<p>Copyright 2013 The jQuery Foundation</p>
	</div><!-- /jqm-footer -->

</div><!-- /page -->
</body>
</html>
