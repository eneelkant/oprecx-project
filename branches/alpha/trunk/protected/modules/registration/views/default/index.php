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
    <?php
    $grid = JqmGrid::createGrid();

    $loginForm->activeForm['htmlOptions']['data-ajax'] = 'false';
    $loginForm->buttons['login']->attributes['data-theme'] = 'b';
    $grid->addColumn($loginForm->render())->id('userregister')
            ->appendContent(O::t('oprecx', 'Have Facebook or Twitter account? You can login via {facebook} or {twiiter}.'));

    $regForm->activeForm['htmlOptions']['data-ajax'] = 'false';
    $regForm->buttons['register']->attributes['data-theme'] = 'b';
    $grid->addColumn($regForm->render())->id('userregister');

    $grid->appendClass('register-login')
         ->render(true);
    ?>
<?php else : ?>
    <hr class="sep" />
    <?php $this->renderPartial('summary'); ?>
<?php endif; ?>