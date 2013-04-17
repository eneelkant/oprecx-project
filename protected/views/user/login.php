<?php
/* @var $this UserController */
/* @var $form CForm */

$this->pageTitle = Yii::app()->name;

$form->buttons['login']->attributes['data-theme'] = 'b';
$form->activeForm['htmlOptions']['data-ajax'] = 'false';
?>
<div class="ui-grid-a">
    <div class="ui-block-a">
        <?php echo $form->render(); ?>
        <?php echo CHtml::link(Yii::t('oprecx', 'Forget password'), array('/user/forget')); ?>
    </div>
    <div class="ui-block-b">
        <h3>Why login?</h3>
        <p></p>
    </div>
</div>