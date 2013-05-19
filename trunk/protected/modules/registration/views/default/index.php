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
    <?php echo $this->rec->description; ?>
</div>

<?php if (O::app()->user->isGuest) : ?>
    <p class="view-division">
        <?php echo CHtml::link(O::t('oprecx', 'View all divisions on {org}', array('{org}' => $this->rec->full_name)), 
                $this->getURL('division')); ?>
    </p>
    <hr />
    <p><?php 
    /* echo O::t('oprecx', 'To register this recruitment, you must {register} or {login}.', array(
        '{register}' => '<b>' . CHtml::link(O::t('oprecx', 'create an account'), '#user-register-form', array('data-rel' => 'external')) . '</b>',
        '{login}' => '<b>' . CHtml::link(O::t('oprecx', 'login to your own account'), '#user-login-form', array('data-rel' => 'external')) . '</b>',
    )); */ 
    echo O::t('oprecx', 'To register this recruitment, you must create an <strong>Oprecx account</strong>. OR, if you already have it, you just need to {login}.', array(
        //'{register}' => '<b>' . CHtml::link(O::t('oprecx', 'create an account'), '#user-register-form', array('data-rel' => 'external')) . '</b>',
        '{login}' => CHtml::link(O::t('oprecx', 'login to your own account'), 
                array('/user/login', 'nexturl' => $this->getURL('index', array('just_login' => 1)))),
    ));
    ?></p>
    <?php
    /*
    $grid = JqmGrid::createGrid();

    
    $regForm->activeForm['htmlOptions']['data-ajax'] = 'false';
    $regForm->buttons['register']->attributes['data-theme'] = 'b';
    $grid->addColumn($regForm->render())->id('userregister');

    $loginForm->activeForm['htmlOptions']['data-ajax'] = 'false';
    $loginForm->buttons['login']->attributes['data-theme'] = 'b';
    $grid->addColumn($loginForm->render())->id('userregister');
            //->appendContent(O::t('oprecx', 'Have Facebook or Twitter account? You can login via {facebook} or {twiiter}.'));

    $grid->appendClass('register-login')
         ->render(true);
     * 
     */
    
    $this->renderPartial('//user/_register_form', array('form' => $regForm));
    ?>
<?php else : ?>
    <hr class="sep" />
    <?php $this->renderPartial('summary'); ?>
<?php endif; ?>