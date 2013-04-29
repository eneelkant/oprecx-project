<?php /* @var $this AdminController */ 

?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />
    <script>
        var msg = <?php echo CJSON::encode($this->_msg) ?>;
    </script>
	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
    <?php O::app()->getComponent('bootstrap', true); // initialize bootstrap ?>
    <?php O::app()->getClientScript()->registerCssFile(O::app()->request->baseUrl . '/css/admin.css'); ?>
    <?php O::app()->getClientScript()->registerScriptFile(O::app()->request->baseUrl . '/js/admin.js'); ?>
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

$this->widget('ext.bootstrap.widgets.TbNavbar', array(
    'type'=>'inverse',
    'brandUrl' => array('/admin'),
    'items'=>array(
        array(
            'class'=>'bootstrap.widgets.TbMenu',
            'items'=>array(
                array('label'=>$cur_org_name, 'url'=>'#', 'items'=> array_merge($org_menus, array(
                    array('label'=> O::t('oprecx', 'MY ORGANIZATIONS')),
                    array('label'=> O::t('oprecx', 'View'), 'url'=>array('/admin'), 'icon' => 'book'),
                    array('label'=> O::t('oprecx', 'Add'), 'url'=>array('/admin/wizard/index'), 'icon' => 'plus'),
                ))),
                array('label'=> O::t('oprecx', 'Results'), 'url'=>array('result/index'), 'icon' => 'icon-list-alt', 
                    'visible' => $cur_org_id != 0, 'active' => $this->layout == 'result'),
                array('label'=> O::t('oprecx', 'Settings'), 'url'=>array('setting/info'), 'icon' => 'icon-wrench', 
                    'visible' => $cur_org_id != 0, 'active' => $this->layout == 'setting'),
                array('label'=> O::t('oprecx', 'Share Registration Link'), 'url'=>'#shareOrgLink', 'icon' => 'globe', 
                    'visible' => $cur_org_id != 0, 'linkOptions' => array('data-toggle' => 'modal')),
                
            ), // data-toggle="modal"
        ),
        //'<form class="navbar-search pull-left" action=""><input type="text" class="search-query span2" placeholder="Search"></form>',
        array(
            'class'=>'bootstrap.widgets.TbMenu',
            'htmlOptions'=>array('class'=>'pull-right'),
            'items'=>array(
                array('label'=>O::app()->getUser()->getState('fullname'), 'url'=>'#', 'items'=>array(
                    array('label'=> O::t('oprecx', 'Profile'), 'url'=>'#', 'icon' => 'icon-user'),
                    array('label'=> O::t('oprecx', 'Log Off'), 'url'=>array('/user/logout'), 'icon' => 'icon-lock'),
                )),
            ),
        ),
    ),
));
?>
    
<div class="container" id="page">
	<?php echo $content; ?>
	<div class="clear"></div>
</div><!-- page -->

<div id="footer">
    Thank you for recruiting with us. <i>oprecx</i>
</div><!-- footer -->

<?php if ($this->getOrg() != NULL) : ?>
<div id="shareOrgLink" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="shareOrgLink" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3>Share Link</h3>
    </div>
    <div class="modal-body">
        <div class="row-fluid">
            <input class="span12" type="text" readonly="readonly" id="share-link-url" value="<?php 
                echo O::app()->request->hostInfo . CHtml::normalizeUrl(array('/registration/default/index', 'org_name' => $this->org->name)) ?>">
            
        </div>
        <div>
            <a href="#" class="share-link facebook">Facebook</a>
            <a href="#" class="share-link twitter">Twitter</a>
            <a href="#" class="share-link gplus">Google Plus</a>
        </div>
    </div>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
    </div>
</div>
<?php endif; ?>
</body>
</html>
