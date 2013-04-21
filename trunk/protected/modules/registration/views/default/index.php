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
    
</div>


<?php if (Yii::app()->user->isGuest) : ?>
    <p class="view-division">
        <?php echo CHtml::link(Yii::t('oprecx', 'View all divisions on {org}', array('{org}' => $this->org->full_name)), 
                $this->getURL('division')); ?>
    </p>
    <?php
    $grid = JqmGrid::createGrid();

    $loginForm->activeForm['htmlOptions']['data-ajax'] = 'false';
    $loginForm->buttons['login']->attributes['data-theme'] = 'b';
    $grid->addColumn($loginForm->render())->id('userregister')
            ->appendContent(Yii::t('oprecx', 'Have Facebook or Twitter account? You can login via {facebook} or {twiiter}.'));

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