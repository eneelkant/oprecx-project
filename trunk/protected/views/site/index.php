<?php
/* @var $this SiteController */
/* @var $recs Recruitment[] */

$this->pageTitle = O::app()->name;
?>
<div>
    <h2><?php echo O::t('oprecx', 'Welcome to Oprecx!') ?></h2>
    <?php //echo O::t('oprecx', '<p>Oprecx is web based open recruitment system') ?>
    <p>Oprecx adalah sistem rekruitmen terbuka berbasis web.</p>
    <hr />

    <h2><?php echo O::t('oprecx', 'Active Open Recruitment') ?></h2>
    
    <?php
    /* @var $rec Recruitment */
    //JqmTag::listview()->render(TRUE);
    //*
    if ($recs) {
        $ul = JqmTag::listview()
                ->inset()
                ->theme('d')
                ->icon('false')
                ->data('filter', true)
                ->data('filter-placeholder', O::t('oprecx', 'Search recruitment'))
                ;
        foreach ($recs as $rec) {
            $ul->appendLvItem(HtmlTag::link($rec->full_name, array('registration/default/index', 'rec_name' => $rec->name)));
        }
        $ul->render(true);
    }
    // */
    ?>

</div>
