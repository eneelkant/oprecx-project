<?php
/* @var $this DefaultController */
/* @var $model DivisionChoiceForm */


$this->breadcrumbs = array(
    $this->module->id,
);
?>

<?php
/* @var $form CActiveForm */
$form = $this->beginWidget('CActiveForm', array(
            'id' => 'division-choice-form',
            'enableClientValidation' => false,
            'clientOptions' => array(
                'validateOnSubmit' => false,
            ),
        )
);
?>
<fieldset data-role="controlgroup">

    <?php
    /** @var Divisions $division */
    $divisions = array();
    foreach (Divisions::model()->findAllByOrg($this->org->id) as $division) {
        // $id = $division->div_id;
        $divisions[$division->div_id] = $division->name;
    }
    ?>
    <?php echo $form->errorSummary($model); ?>
    <?php echo CHtml::activeDropDownList($model, 'divisions[0]', $divisions, array('prompt' => '-Pilih salah satu-')); ?>
    <?php echo CHtml::activeDropDownList($model, 'divisions[1]', $divisions, array('prompt' => '-Pilih salah satu-')); ?>
    <?php //echo CHtml::activeCheckBoxList($model, 'divisions', $divisions, array('separator' => ' ')); ?>

</fieldset>
<fieldset class="ui-grid-a">
    <div class="ui-block-a">
        <?php echo CJqm::link('back', $this->getURL('index'), array('icon' => 'arrow-l', 'iconpos' => 'left')); ?></div>
    <div class="ui-block-b"><input type="submit" value="next" data-icon="arrow-r" data-iconpos="right" data-theme="b" /></div>
</fieldset>
<?php $this->endWidget(); ?>

<p>
    <?php echo CHtml::encode($this->org->description); ?>
</p>
<p>
    You may customize this page by editing <tt><?php echo __FILE__; ?></tt>
</p>