<?php /* @var $this AdminController */ 

?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo O::app()->getLanguage() ?>" lang="<?php echo O::app()->getLanguage() ?>">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=<?php echo O::app()->charset ?>" />
    <meta name="language" content="<?php echo O::app()->getLanguage() ?>" />
    <meta name="robots" content="noindex" />
    <script>
        var msg = <?php echo json_encode($this->_msg) ?>;
        
    </script>
	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
    <?php O::app()->getComponent('bootstrap', true); // initialize bootstrap ?>
    <?php O::app()->getClientScript()->registerCssFile(O::versionedFileUrl('/css/admin', '.css')); ?>
    <?php O::app()->getClientScript()->registerScriptFile(O::versionedFileUrl('/js/admin', '.js'), CClientScript::POS_END); ?>
</head>

<body>

<?php 

$cur_rec_id = $this->getRec() ? $this->rec->id : 0;
$cur_rec_name = $cur_rec_id ? $this->rec->name : O::t('oprecx', '-- Select Recruitment --');

$rec_menus = array();
$htmlOptions = array();
foreach ($this->getMyRecruitments() as $rec) {
    if ($cur_rec_id != 0) $htmlOptions['target'] = 'rec_' . $rec->id;
    $htmlOptions['title'] = $rec->full_name;
    if ($rec->id != $cur_rec_id)
        $rec_menus[] = array('label' => $rec->name, 'url' => array('setting/general', 'rec' => $rec->name),
            'linkOptions' => $htmlOptions);
}
if (count($rec_menus) > 0) $rec_menus[] = '---';

$this->widget('ext.bootstrap.widgets.TbNavbar', array(
    'type'=>'inverse',
    'brandUrl' => array('/admin'),
    'items'=>array(
        array(
            'class'=>'bootstrap.widgets.TbMenu',
            'items'=>array(
                array('label'=>$cur_rec_name, 'url'=>'#', 'items'=> array_merge($rec_menus, array(
                    array('label'=> O::t('oprecx', 'MY RECRUITMENTS')),
                    array('label'=> O::t('oprecx', 'View'), 'url'=>array('/admin'), 'icon' => 'book'),
                    array('label'=> O::t('oprecx', 'Add'), 'url'=>array('/admin/wizard/index'), 'icon' => 'plus'),
                ))),
                array('label'=> O::t('oprecx', 'Results'), 'url'=>array('result/index'), 'icon' => 'bar-chart', 
                    'visible' => $cur_rec_id != 0, 'active' => $this->layout == 'result'),
                array('label'=> O::t('oprecx', 'Settings'), 'url'=>array('setting/general'), 'icon' => 'cogs', 
                    'visible' => $cur_rec_id != 0, 'active' => $this->layout == 'setting'),
                array('label'=> O::t('oprecx', 'Share Registration Link'), 'url'=>'#shareOrgLink', 'icon' => 'globe', 
                    'visible' => $cur_rec_id != 0, 'linkOptions' => array('data-toggle' => 'modal')),
                
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
    Thank you for recruiting with us. <?php echo CHtml::link('oprecx', O::app()->homeUrl) ?>
</div><!-- footer -->

<?php if ($this->getRec() != NULL) : ?>
<?php 
$url = O::app()->getRequest()->getHostInfo() . CHtml::normalizeUrl(array('/registration/default/index', 'rec_name' => $this->rec->name)); 
$url_encoded = urlencode($url);
?>
<div id="shareOrgLink" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="shareOrgLink" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3>Share Link</h3>
    </div>
    <div class="modal-body">
        <p><?php echo O::t('oprecx', 'Copy this url, and share it') ?></p>
        <div class="row-fluid share-org-input">
            <input class="span12" type="text" readonly="readonly" id="share-link-url" value="<?php echo $url  ?>">
            <?php echo CHtml::link(O::t('oprecx', 'Goto link'), $url, array('target' => '_blank')); ?>    
        </div>
        
        <p><?php echo O::t('oprecx', 'or just click these buttons') ?></p>
        <div>
            <a href="http://www.facebook.com/sharer.php?u=<?php echo $url_encoded ?>" class="share-link facebook">Facebook</a>
            <a href="http://twitter.com/share?url=<?php echo $url_encoded ?>&via=oprecx" class="share-link twitter">Twitter</a>
            <a href="https://plus.google.com/share?url=<?php echo $url_encoded ?>" class="share-link gplus">Google Plus</a>
        </div>
    </div>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
    </div>
</div>
<?php endif; ?>
</body>
</html>
