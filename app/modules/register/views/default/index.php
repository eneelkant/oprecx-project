<?php
/* @var $this DefaultController */
/* @var $org Organizations */

$this->breadcrumbs = array(
    $this->module->id,
);
?>
<h2><?php echo CHtml::encode($org->full_name); ?></h2>


<?php echo CJqm::link('Daftar', array('division', 'org'=>$org->name), array('inline'=>true,  'icon'=>'check')) ?>

<p>
    <?php echo CHtml::encode($org->description); ?>
</p>
<p>
    You may customize this page by editing <tt><?php echo __FILE__; ?></tt>
</p>