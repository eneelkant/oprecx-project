<?php
/* @var $this AdminController */
/* @var $form TbActiveForm */
/* @var $model Recruitment */

$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array (
    'id'   => 'organization-edit',
    'type' => 'horizontal',
        ));
?>
<fieldset>

    <legend>General Information</legend>
    <?php echo $form->uneditableRow($model, 'name', array(
        'hint' => O::t('oprecx', '{clickhere} to edit', array(
            '{clickhere}' => CHtml::link(O::t('oprecx', 'Click here'), '#')
            )),
    )); ?>
    
    <?php echo $form->textFieldRow($model, 'full_name'); ?>
    
    <?php echo $form->html5EditorRow($model, 'description', 
            array('rows'=>5, 'height'=>'200', 'options'=>array('image' => false,'font-styles'=>false))); ?>
    
    
    
    <?php echo $form->numberFieldRow($model, 'div_min', 
            array('hint' => O::t('oprecx', 'Minimum number of division which applicants must choose'), 'min' => 1 )); ?>
    <?php echo $form->numberFieldRow($model, 'div_max', 
            array('hint' => O::t('oprecx', 'Maximum number of division which applicant can choose'), 'min' => 1 )); ?>
    
    <?php echo $form->dropDownListRow($model, 'type', array(
        //'ngo' => O::t('oprecx', 'Non-Goverment Organization'),
        //'go' => O::t('oprecx', 'Goverment Organization'),
        'committee' => O::t('oprecx', 'Committee'),
        'organization' => O::t('oprecx', 'Organization'),
        'other' => O::t('oprecx', 'Other'),
    )) ?>
    
    <?php echo $form->dropDownListRow($model, 'scope', array(
        'school' => O::t('oprecx', 'School'),
        'university' => O::t('oprecx', 'University'),
        'faculty' => O::t('oprecx', 'Faculty'),
        'other' => O::t('oprecx', 'Other'),
    )) ?>
    
    <?php echo $form->textFieldRow($model, 'location'); ?>
    
    <?php echo $form->dropDownListRow($model, 'visibility', array(
        'public' => O::t('oprecx', 'Show this recruitment on Oprecx Homepage'),
        'private' => O::t('oprecx', 'Hide this recruitment on Oprecx Homepage'),
    )) ?>
    
    <?php echo $form->dateRangeRow($model, 'regTime', array(
            'prepend'=>'<i class="icon-calendar"></i>',
            'label' => O::t('oprecx', 'Visible time'),
            'labelOptions' => array('required' => true),
            'options' => array(
                    'format'=> 'yyyy-MM-dd',
                )
        )); ?>
    
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