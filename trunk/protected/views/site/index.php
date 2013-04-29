<?php
/* @var $this SiteController */
/* @var $orgs Organizations[] */

$this->pageTitle = O::app()->name;
?>
<div>
    <h2><?php echo O::t('oprecx', 'Welcome to Oprecx!') ?></h2>
    <?php //echo Yii::t('oprecx', '<p>Oprecx is web based open recruitment system') ?>
    <p>Oprecx adalah sistem rekruitmen terbuka berbasis web.</p>
    <hr />

    <h2><?php echo O::t('oprecx', 'Active Open Recruitment') ?></h2>
    
    <?php
    /* @var $org Organizations */
    //JqmTag::listview()->render(TRUE);
    //*
    if ($orgs) {
        $ul = JqmTag::listview()
                ->inset()
                ->theme('d')
                ->icon('false')
                ->data('filter', true)
                ->data('filter-placeholder', O::t('oprecx', 'Search organizations'))
                ;
        foreach ($orgs as $org) {
            $ul->appendLvItem(HtmlTag::link($org->full_name, array('registration/default/index', 'org_name' => $org->name)));
        }
        $ul->render(true);
    }
    // */
    ?>

</div>
