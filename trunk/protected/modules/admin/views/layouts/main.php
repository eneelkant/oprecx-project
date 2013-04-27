<?php /* @var $this AdminController */ 

?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />

    <link rel="stylesheet" href="<?php echo O::app()->baseUrl ?>/css/admin.css" />

	<title><?php echo CHtml::encode($this->pageTitle); ?></title>

	<?php O::app()->getComponent('bootstrap', true); // initialize bootstrap ?>
</head>

<body>

<?php 

$cur_org_id = $this->org ? $this->org->id : 0;
$cur_org_name = $this->org ? $this->org->full_name : '-- Select Organization --';

$my_orgs = $this->getMyOrgs();
$org_menus = array();
$htmlOptions = array('data-org' => '123');
foreach ($my_orgs as $org) {
    if ($cur_org_id != 0) $htmlOptions['target'] = 'org_' . $org->id;
    if ($org->id != $cur_org_id)
        $org_menus[] = array('label' => $org->full_name, 'url' => array('setting/info', 'org' => $org->name),
            'linkOptions' => $htmlOptions, 'data-org' => 'tes');
}
if (count($org_menus) > 0) $org_menus[] = '---';

$this->widget('bootstrap.widgets.TbNavbar', array(
    'type'=>'inverse',
    'items'=>array(
        array(
            'class'=>'bootstrap.widgets.TbMenu',
            'items'=>array(
                array('label'=>$cur_org_name, 'url'=>'#', 'items'=> array_merge($org_menus, array(
                    array('label'=>'My Organizations'),
                    array('label'=>'Manage', 'url'=>'#', 'icon' => 'icon-pencil'),
                    array('label'=>'Add', 'url'=>'#', 'icon' => 'icon-plus'),
                ))),
                array('label'=>'Results', 'url'=>array('result/index'), 'icon' => 'icon-list-alt', 
                    'visible' => $cur_org_id != 0, 'active' => $this->layout == 'result'),
                array('label'=>'Settings', 'url'=>array('setting/info'), 'icon' => 'icon-wrench', 
                    'visible' => $cur_org_id != 0, 'active' => $this->layout == 'setting'),
            ),
        ),
        //'<form class="navbar-search pull-left" action=""><input type="text" class="search-query span2" placeholder="Search"></form>',
        array(
            'class'=>'bootstrap.widgets.TbMenu',
            'htmlOptions'=>array('class'=>'pull-right'),
            'items'=>array(
                array('label'=>O::app()->getUser()->getState('fullname'), 'url'=>'#', 'items'=>array(
                    array('label'=>'Profile', 'url'=>'#', 'icon' => 'icon-user'),
                    array('label'=>'Log Off', 'url'=>array('/user/logout'), 'icon' => 'icon-lock'),
                )),
            ),
        ),
    ),
));
?>
    
<div class="container" id="page">

	<?php if(isset($this->breadcrumbs)):?>
		<?php $this->widget('bootstrap.widgets.TbBreadcrumbs', array(
			'links'=>$this->breadcrumbs,
		)); ?><!-- breadcrumbs -->
	<?php endif?>

	<?php echo $content; ?>

	<div class="clear"></div>


</div><!-- page -->

<div id="footer">
    Thank you for recruiting with us. <i>oprecx</i>

</div><!-- footer -->

</body>
</html>
