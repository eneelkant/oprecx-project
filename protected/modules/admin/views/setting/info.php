<?php
/* @var $this AdminController */
$this->helpView = 'index_help';

?>
<?php

$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array (
    'id'   => 'organization-edit',
    'type' => 'horizontal',
        ));

/** @var TbActiveForm $form */

?>
<fieldset>

    <legend>General Information</legend>
    <?php echo $form->uneditableRow($model, 'name'); ?>
    
    <?php echo $form->textFieldRow($model, 'full_name'); ?>
    
    <?php echo $form->html5EditorRow($model, 'description', 
            array('rows'=>5, 'height'=>'200', 'options'=>array('image' => false,'font-styles'=>false))); ?>
    
    <?php echo $form->dateRangeRow($model, 'regTime', array(
            'prepend'=>'<i class="icon-calendar"></i>',
            'options' => array(
                //'callback'=>'js:function(start, end){console.log(start.toString("MMMM d, yyyy") + " - " + end.toString("MMMM d, yyyy"));}',
                'format'=> 'yyyy-MM-dd',
                )
        )); ?>
    
    <div class="control-group ">
        <label class="control-label required">Number of division choices</label>
        <div class="controls">
            <div>
                <input type="text" class="span3" id="OrganizationsEx_min_div" value="1" readonly="" />
                <input type="text" class="span3" id="OrganizationsEx_max_div" value="3" readonly="" />
                
                <?php $this->widget('zii.widgets.jui.CJuiSliderInput',array(
                    'model'=>$model,
                    'attribute'=>'div_min',
                    'maxAttribute'=>'div_max',
                    // additional javascript options for the slider plugin
                    'options'=>array(
                        'range'=>true,
                        'min'=>0,
                        'max'=>10,
                    ),
                ));?>
            </div>
            <p class="help-block"><div class="span6" style="margin-top: 10px;">
            <?php /*$this->widget('zii.widgets.jui.CJuiSlider', array(
                'options'=>array(
                    'min'=>1,
                    'max'=>10,
                    'range'=>true,
                    'values'=>array(1, 3),
                    'slide' => 'js:function(e, ui){$("#OrganizationsEx_min_div").val(ui.values[0]);'.
                    '$("#OrganizationsEx_max_div").val(ui.values[1]);}',
                ),
                )); */?></div></p>
        </div>
    </div>
    
    <?php echo $form->dropDownListRow($model, 'type', array(
        'ngo' => O::t('oprecx', 'Non-Goverment Organization'),
        'go' => O::t('oprecx', 'Goverment Organization'),
        'committee' => O::t('oprecx', 'Committee'),
    )) ?>
    
    <?php echo $form->dropDownListRow($model, 'scope', array(
        'school' => O::t('oprecx', 'School'),
        'university' => O::t('oprecx', 'University'),
        'faculty' => O::t('oprecx', 'Faculty'),
        'other' => O::t('oprecx', 'Other'),
    )) ?>
    
    <?php echo $form->textFieldRow($model, 'location'); ?>
    
    
    <?php echo $form->checkBoxListRow($model, 'options', array(
		'set' => 'Option one is this and that—be sure to include why it\'s great',
		'Option two can also be checked and included in form results',
		'Option three can—yes, you guessed it—also be checked and included in form results',
	), array('hint'=>'<strong>Note:</strong> Labels surround all the options for much larger click areas.')); ?>
</fieldset>
<div class="form-actions">
        <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'type'=>'primary', 'label'=>'Submit')); ?>
        <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'reset', 'label'=>'Reset')); ?>
    </div>
<?php $this->endWidget(); ?>