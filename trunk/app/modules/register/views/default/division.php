<?php
/* @var $this DefaultController */
/* @var $org Organizations */
/* @var $model DivisionChoiceForm */


$this->breadcrumbs = array(
    $this->module->id,
);
?>
<h2><?php echo CHtml::link(CHtml::encode($org->full_name), array('index', 'org' => $org->name)); ?></h2>

<?php
if (isset($_REQUEST['division'])) {
    var_dump($_REQUEST['division']);
}
?>
<?php
/* @var $form CActiveForm */
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'division-choice-form',
    'enableClientValidation' => false,
    'clientOptions' => array(
        'validateOnSubmit' => false,
    ),
        ));
?>
<fieldset data-role="controlgroup">

    <?php
    /** @var $division Divisions */
    $divisions = array();
    foreach ($org->divisions as $division) {
        $id = $division->div_id;
        //echo '<input type="checkbox" name="division[choice][]" value="', $id, '" id="division_', $id,
        //'" /><label for="division_', $id, '">', $division->name, '</label>';
        $divisions[$id] = $division->name;


        //CHtml::activeCheckBoxList($model, $attribute, $data)
    }

    //echo CHtml::checkBoxList('division', null, $divisions);
    ?>


    <?php echo $form->errorSummary($model); ?>
    <?php echo CHtml::activeDropDownList($model, 'divisions[0]', $divisions, array('prompt' => '-Pilih salah satu-')); ?>
    <?php echo CHtml::activeDropDownList($model, 'divisions[1]', $divisions, array('prompt' => '-Pilih salah satu-')); ?>
    <?php //echo CHtml::activeCheckBoxList($model, 'divisions', $divisions, array('separator' => ' ')); ?>
    
</fieldset>
<fieldset class="ui-grid-a">
    <div class="ui-block-a">
        <?php echo CJqm::link('back', array('index', 'org' => $org->name), array('icon' => 'arrow-l', 'iconpos' => 'left')); ?></div>
    <div class="ui-block-b"><input type="submit" value="next" data-icon="arrow-r" data-iconpos="right" data-theme="b" /></div>
</fieldset>
<?php $this->endWidget(); ?>

<p>
    <?php echo CHtml::encode($org->description); ?>
</p>
<p>
    You may customize this page by editing <tt><?php echo __FILE__; ?></tt>
</p>