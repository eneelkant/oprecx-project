<?php
/* @var $this DefaultController */
/* @var $org Organizations */
/* @var $regForm CForm */

$this->breadcrumbs = array(
    $this->module->id,
);

?>
<style>
@media all and (max-width: 40em){
    .register-login .description {
        margin-left: 100px;
    }
    .register-login .ui-block-a, .register-login .ui-block-b {
        width: 99.99%;
    }
    .register-login .ui-block-a {
        border-bottom: 1px solid #888;
        padding-bottom: .5em;
        margin-bottom: .5em;
    }
}
@media all and (min-width: 40em){
    .register-login .ui-block-a {
        border-right: 2pt solid #ccc;
    }
    
    .register-login form {
        padding: 10px;
    }
}        
    
</style>
<div class ="description register-login"
<p>
    <?php echo CHtml::encode($this->org->description); ?>
</p>
 <?php echo CHtml::link('Daftar', $this->getURL('division'), array('inline' => true, 'icon' => 'check'))?>
</div>
<div class="ui-grid-a register-login">
    <div class="ui-block-a">
        <?php echo $regForm->render();?>
<?php if (Yii::app()->user->isGuest) : ?>
<?php
/** @var CForm $regForm */

$regForm->buttons['register']->attributes['data-theme'] = 'b';
$loginForm->buttons['login']->attributes['data-theme'] = 'b';


?>
    <div class="ui-grid-a register-login">
        <div class="ui-block-a">
            <?php echo $regForm->render();?>
        </div>
        <div class="ui-block-b">
            <?php echo $loginForm->render();?>
        </div>
    </div>
<?php else : ?>
    <?php echo CJqm::link('Daftar', $this->getURL('division'), array('inline' => true, 'icon' => 'check')) ?>
<?php endif; ?>

<p>
    You may customize this page by editing <tt><?php echo __FILE__; ?></tt>
</p>