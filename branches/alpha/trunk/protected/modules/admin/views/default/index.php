<?php
/* @var $this DefaultController */
$this->helpView = 'index_help';

?>
<?php $this->beginWidget('bootstrap.widgets.TbHeroUnit', array(
	'heading'=>'Hi, there!',
)); ?>
<strong>welcome to the oprecx</strong>
<p>Oprecx is open source, web based open recruitment system developed for helping you recruit people.</p>
<p>
	<?php $this->widget('bootstrap.widgets.TbButton', array(
    'type'=>'primary',
    'size'=>'large',
    'label'=>'Create recruitment',
)); ?>
</p>
 
<?php $this->endWidget(); ?>