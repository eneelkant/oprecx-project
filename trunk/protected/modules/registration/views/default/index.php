<?php
/* @var $this DefaultController */
/* @var $regForm CForm */
/* @var $regForm CForm */

$this->breadcrumbs = array(
    $this->module->id,
);
$this->page_class[] = 'page-home';
?>

<div class ="org-description">
<?php echo $this->org->description; ?>
    <p class="view-division">
        <?php echo CHtml::link(Yii::t('oprecx', 'View all divisions on {org}', array('{org}' => $this->org->full_name)), 
                $this->getURL('division')); ?>
    </p>
</div><hr class="sep" />


<?php if (Yii::app()->user->isGuest) : ?>
<?php
/** @var CForm $regForm */

$regForm->buttons['register']->attributes['data-theme'] = 'b';
$loginForm->buttons['login']->attributes['data-theme'] = 'b';

?>
    <div class="ui-grid-a register-login">
        <div class="ui-block-a" id="userregister">
            <?php echo $regForm->render();?>
        </div>
        <div class="ui-block-b" id="userlogin">
            <?php echo $loginForm->render();?>
        </div>
    </div>
<?php else : ?>
    <?php JqmTag::buttonLink('Daftar', $this->getURL('division'))->inline()->icon('check')->render(true); ?>
<?php endif; ?>