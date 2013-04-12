<?php
/* @var $this DefaultController */
/* @var $model DivisionChoiceForm */
/* @var $divisions Divisions[] */

$this->breadcrumbs = array(
    $this->module->id,
);
$this->page_class[] = 'page-division';
?>

<?php

if (! Yii::app()->user->getIsGuest()) :

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
<?php 
$this->endWidget(); 
endif;
?>
<?php foreach ($divisions as $division) : ?>
<div class="division">
    <h2 class="division-name"><?php echo CHtml::encode($division->name); ?></h2>
    <div class="division-description"><?php echo $division->description; ?></div>
</div>
<?php endforeach; ?>

<p><?php echo Yii::t('oprecx', 'Please {register} or {login} to apply this recruitments',array(
    '{register}' => CHtml::link(Yii::t('oprecx', 'register'), array('/user/register', 'next_url' => $_SERVER['REQUEST_URI'])),
    '{login}' => CHtml::link(Yii::t('oprecx', 'login'), array('/user/login', 'next_url' => $_SERVER['REQUEST_URI'])),
    )
); ?></p>

<?php 
if (Yii::app()->user->getIsGuest())
    echo CJqm::jqmLink(Yii::t('oprecx', 'Register Now'), $this->getURL('index'));
?>