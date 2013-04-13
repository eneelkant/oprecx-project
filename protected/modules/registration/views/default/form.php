<?php
/* @var $this DefaultController */
/* @var $form CForm */

$this->breadcrumbs = array(
    $this->module->id,
);
$form->buttons['submit-form']->attributes['data-theme'] = 'b';

echo $form->renderBegin();
echo $form->renderElements();
$grid = JqmGrid::createGrid('fieldset');
$grid->addColumn(JqmTag::buttonLink(Yii::t('oprecx', 'Back'), $this->getURL('index'))->icon('back')->iconPos('left'));
$grid->addColumn(JqmTag::jSubmit(Yii::t('oprecx', 'Back'))->icon('check')->theme('b')->iconPos('right'));
$grid->render(true);
echo $form->renderEnd();
