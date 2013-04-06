<?php
/* @var $this DefaultController */
/* @var $model DivisionChoiceForm */
/* @var $divisions string[][] */

$this->breadcrumbs = array(
    $this->module->id,
);


//$this->widget('')
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

    
    <?php echo $form->errorSummary($model); ?>
    <?php echo CHtml::activeDropDownList($model, 'choices[0]', $model->allDivisionsName, array('prompt' => '-Pilih salah satu-')); ?>
    <?php echo CHtml::activeDropDownList($model, 'choices[1]', $model->allDivisionsName, array('prompt' => '-Pilih salah satu-')); ?>
    <?php //echo CHtml::activeCheckBoxList($model, 'divisions', $divisions, array('separator' => ' ')); ?>

</fieldset>
<fieldset class="ui-grid-a">
    <div class="ui-block-a">
        <?php echo CJqm::jqmLink('back', $this->getURL('index'), array('icon' => 'arrow-l', 'iconpos' => 'left')); ?></div>
    <div class="ui-block-b"><input type="submit" value="next" data-icon="arrow-r" data-iconpos="right" data-theme="b" /></div>
</fieldset>
<?php $this->endWidget(); ?>

<p>
    <?php echo CHtml::encode($this->org->description); ?>
</p>
<p>
    You may customize this page by editing <tt><?php echo __FILE__; ?></tt>
</p>