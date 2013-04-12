<?php
/* @var $this DefaultController */
/* @var $form CForm */

$this->breadcrumbs = array(
    $this->module->id,
);
$form->buttons['submit-form']->attributes['data-theme'] = 'b';
?>

    <?php echo $form->render();
