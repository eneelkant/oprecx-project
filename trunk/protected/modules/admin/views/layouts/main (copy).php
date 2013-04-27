<?php 
/** @var AdminController $this */

?><!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl ?>/css/jeasy/metro-gray/easyui.css">
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl ?>/css/jeasy/icon.css">
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl ?>/css/admin.css">
    
	<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl ?>/js/jquery-1.9.0.min.js"></script>
	<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl ?>/js/jquery.easyui.min.js"></script>
</head>
<body class="easyui-layout">
	<div data-options="region:'north',border:false" style="height:60px;padding:5px;overflow:hidden;border-top:3px solid #ffbb38">
        <img src="<?php echo Yii::app()->request->baseUrl ?>/images/logo.png" height="50" alt="Oprecx" />
    </div>
	<?php echo $content; ?>
</body>
</html>