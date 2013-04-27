<?php
/* @var $this AdminController */

$menu_items = array(
    array('label'=>'GENERAL'),
    array('label'=>'Information', 'url'=>array('setting/info'), 'icon' => 'home'),
    array('label'=>'Divisions', 'url'=>array('setting/division'), 'icon' => 'th-list'),
    
    array('label'=>'COMPONENTS'),
    array('label'=>'Forms', 'icon'=>'file', 'url'=>array('form/index')),
    array('label'=>'Interview Slots', 'icon'=>'calendar', 'url'=>array('slot/index')),
    array('label'=>'Tasks', 'icon'=>'tasks', 'url'=>array('task/index')),
);
//O::app()->request->
$curPath = $this->id . '/' . $this->action->id;
foreach ($menu_items as &$menu_item) {
    if (isset($menu_item['url'])) {
        if ($menu_item['url'][0] == $curPath) $menu_item['active'] = true;
    }
}
?>
<?php $this->beginContent(); ?>
<div class="row-fluid">
    <div class="span3"><div class="well well-small">
    <?php $this->widget('bootstrap.widgets.TbMenu', array(
        'type'=> 'list',
        'items'=> $menu_items,
    )); ?>
    </div></div>
    <div class="span9">
        <?php echo $content; ?>
    </div>
</div>
<?php $this->endContent(); ?>