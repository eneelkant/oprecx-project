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
    <div class="control-group ">
        <label class="control-label required" for="OrganizationsForm_full_name">URL</label>
        <div class="controls"><div class="input-prepend"><span class="add-on">oprecx.com/</span>
            <input id="OrganizationsForm_full_name" type="text" value="<?php echo $model->name ?>" disabled="disabled" />
        </div></div>
    </div>
    <?php echo $form->textFieldRow($model, 'full_name'); ?>
    
    <?php echo $form->html5EditorRow($model, 'description', 
            array('rows'=>5, 'height'=>'200', 'options'=>array('image' => false,'font-styles'=>false))); ?>
    
    <?php echo $form->dateRangeRow($model, 'regTime',
        array('hint'=>'Click inside! An even a date range field!.',
            'prepend'=>'<i class="icon-calendar"></i>',
            
            'options' => array(
                //'callback'=>'js:function(start, end){console.log(start.toString("MMMM d, yyyy") + " - " + end.toString("MMMM d, yyyy"));}',
                'format'=> 'yyyy-MM-dd',
                )
        )); ?>
    
    <?php echo $form->dropDownListRow($model, 'type', array(
        'ngo' => Yii::t('admin', 'Non-Goverment Organization'),
        'go' => Yii::t('admin', 'Goverment Organization'),
        'committee' => Yii::t('admin', 'Committee'),
    )) ?>
    
    <?php echo $form->dropDownListRow($model, 'scope', array(
        'school' => Yii::t('admin', 'School'),
        'university' => Yii::t('admin', 'University'),
        'faculty' => Yii::t('admin', 'Faculty'),
        'other' => Yii::t('admin', 'Other'),
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