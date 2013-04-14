<?php
/* @var $this DefaultController */
/* @var $form CForm */

$this->breadcrumbs = array(
    $this->module->id,
);
// $form->buttons['submit-form']->attributes['data-theme'] = 'b';

echo $form->renderBegin();
echo $form->renderElements();

$this->renderPartial('submit', array('name' => 'submit-form'));

echo $form->renderEnd();
