<?php
/* @var $this DefaultController */
/* @var $org Organizations */

$this->breadcrumbs = array(
    $this->module->id,
);
?>
<h2><?php echo CHtml::link(CHtml::encode($org->full_name), array('index', 'org'=>$org->name)); ?></h2>

<?php
if (isset($_REQUEST['division'])) {
    var_dump($_REQUEST['division']);
}
?>

<?php echo CHtml::beginForm(array('/'), 'post'); ?>
        <fieldset data-role="controlgroup">
            <legend>Pilihan divisi</legend>
            <?php
            /** @var $division Divisions */
            $divisions = array();
            foreach ($org->divisions as $division){
                $id = $division->divisions_id;
                echo '<input type="checkbox" name="division[choice][]" value="', $id, '" id="division_', $id,
                        '" /><label for="division_', $id, '">', $division->name, '</label>';
                $divisions[$id] = $division->name;
                        
                        //CHtml::activeCheckBoxList($model, $attribute, $data)
            }
            
            //echo CHtml::checkBoxList('division', null, $divisions);
            
            ?>
            
        </fieldset>

        <fieldset class="ui-grid-a">
            <div class="ui-block-a"><a data-role="button" data-rel="back" data-icon="arrow-l" data-iconpos="left">back</a></div>
            <div class="ui-block-b"><input type="submit" name="division[submit]" value="next" data-icon="arrow-r" data-iconpos="right" data-theme="b" /></div>
        </fieldset>
<?php echo CHtml::endForm(); ?>
<?php



?>

<p>
    <?php echo CHtml::encode($org->description); ?>
</p>
<p>
    You may customize this page by editing <tt><?php echo __FILE__; ?></tt>
</p>