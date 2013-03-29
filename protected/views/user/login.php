<?php
/* @var $this UserController */
/* @var $form CForm */

$this->pageTitle = Yii::app()->name;

$form->buttons['login']->attributes['data-theme'] = 'b';
?>
<div class="jqm-home-welcome">
    <?php echo $form->render(); ?>
</div>
