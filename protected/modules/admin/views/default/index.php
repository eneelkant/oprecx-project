<?php
/* @var $this DefaultController */
$this->helpView = 'index_help';

?>
<?php $this->beginWidget('bootstrap.widgets.TbHeroUnit', array(
	'heading'=>'Hello, world!',
)); ?>
 
<p>This is a simple hero unit, a simple jumbotron-style component for calling extra attention to featured content or information.</p>
<p>
	<?php $this->widget('bootstrap.widgets.TbButton', array(
    'type'=>'primary',
    'size'=>'large',
    'label'=>'Learn more',
)); ?>
</p>
 
<?php $this->endWidget(); ?>