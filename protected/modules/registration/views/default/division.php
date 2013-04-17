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
if (! Yii::app()->user->getIsGuest()) {
    /* @var $form CActiveForm */
    $form = $this->beginWidget('CActiveForm', array(
                'id' => 'division-choice-form',
                'enableClientValidation' => false,
                'clientOptions' => array(
                    'validateOnSubmit' => false,
                ),
            )
    );

    $dropDownListAttr = array('prompt' => Yii::t('oprecx', '-- choose one --'));
    $fieldSet = JqmTag::jTag('fieldset', 'controlgroup')
            ->appendContent($form->errorSummary($model));
    for($i = 0; $i < $model->max_choice; ++$i) {
        $fieldSet->appendContent(CHtml::activeDropDownList($model, "choices[$i]", $model->allDivisionsName, $dropDownListAttr));
    }
    $fieldSet->render(true);
    echo CHtml::error($model, 'choices');
    
    // render submit button
    $this->renderPartial('submit');

    // end
    $this->endWidget(); 
};
?>

<?php foreach ($divisions as $division) : ?>
<div class="division">
    <h3 class="division-name"><?php echo CHtml::encode($division->name); ?></h3>
    <div class="division-description"><?php echo $division->description; ?></div>
</div>
<?php endforeach; ?>

<?php 
if (Yii::app()->user->getIsGuest())
    JqmTag::buttonLink(Yii::t('oprecx', 'Register Now'), $this->getURL('index'))->theme('b')->render(true);

?>