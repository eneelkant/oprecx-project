<?php
/* @var $this AdminController */


$main_menu = array(
    array('title' => Yii::t('admin', 'Options'), 'menus' => array(
        array('text' => Yii::t('admin', 'General'), 'link' => array('options/general')),
        array('text' => Yii::t('admin', 'General'), 'link' => array('options/general')),
        array('text' => Yii::t('admin', 'General'), 'link' => array('options/general')),
    )),

    array('title' => Yii::t('admin', 'Options'), 'menus' => array(
        array('text' => Yii::t('admin', 'General'), 'link' => array('options/general')),
        array('text' => Yii::t('admin', 'General'), 'link' => array('options/general')),
        array('text' => Yii::t('admin', 'General'), 'link' => array('options/general')),
    )),

);


?>
<?php $this->beginContent(); ?>
<div data-options="region:'west',split:true,title:'Menu'" style="width:200px;">
    <div class="easyui-accordion oprecx-admin-menu" data-options="fit:true,border:false">
        <?php
        foreach ($main_menu as $menu) {
            $menus = $menu['menu'];
            $menu['menu'] = null;
            $divTag = HtmlTag::tag($tag, $menu)->appendContent('<ul>');
            foreach ($menus as $menu_item) {
                $divTag->appendContent($content);
            }
        }
        ?>
    </div>
	
	<div data-options="region:'center',title:'<?php echo CHtml::encode($this->pageTitle); ?>'" style="padding: 5px;"><?php echo $content; ?></div>
    <?php if ($this->helpView): ?>
    <div data-options="region:'east',split:true,collapsed:true,title:'Help',iconCls:'icon-help'" style="width:400px;padding:10px;">
        <?php $this->renderPartial($this->helpView); ?>
    </div>
    <?php endif; ?>

<?php $this->endContent(); ?>