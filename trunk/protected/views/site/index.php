<?php
/* @var $this SiteController */
/* @var $orgs Organizations[] */

$this->pageTitle = Yii::app()->name;
?>
<div>
    <h2><?php echo Yii::t('oprecx', 'Welcome to Oprecx!') ?></h2>
    <?php //echo Yii::t('oprecx', '<p>Oprecx is web based open recruitment system') ?>
    <p>Oprecx adalah sistem rekruitmen terbuka berbasis web.</p>
    <hr />

    <h2><?php echo Yii::t('oprecx', 'Active Open Recruitment') ?></h2>
    <?php
    /* @var $org Organizations */
    if ($orgs) {
        echo '<ul data-role="listview" data-inset="true" data-filter="true" data-theme="d" data-icon="false" 
            data-filter-placeholder="', Yii::t('oprecx', 'Search organizations'),  '" class="jqm-list jqm-home-list">';
        foreach ($orgs as $org) {
            echo '<li>', CHtml::link($org->full_name, array('registration/default/index', 'org_name' => $org->name)), '</li>';
        }
        echo '</ul>';
    }
    ?>

</div>
