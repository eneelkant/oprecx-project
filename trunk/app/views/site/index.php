<?php
/* @var $this SiteController */
/* @var $orgs Organizations[] */

$this->pageTitle = Yii::app()->name;

?>
<div class="jqm-home-welcome">
        <h2>Selamat datang di OprecX<span class="jqm-version"></span></h2>

        <p class="jqm-intro">Oprecx adalah bla bla bla</p>

        

    <h2 class="jqm-home-widget">Daftar Oprec</h2>
    
<?php

/* @var $org Organizations */
if ($orgs) {
    echo '<ul data-role="listview" data-inset="true" data-filter="true" data-theme="d" data-icon="false" data-filter-placeholder="Search..." class="jqm-list jqm-home-list">';
    foreach($orgs as $org) {
        echo '<li>', CHtml::link($org->full_name, array('register/default/index', 'org' => $org->name)) , '</li>';
        
    }
    echo '</ul>';
}
?>

        </div>
