<?php
/* @var $this AdminController */
/* @var $slots InterviewSlot[] */

$this->widget('SortForm', array(
    'title' => O::t('oprecx', 'Interview Slot'),
    'listItemData' => $slots,
    'id' => 'interview-slot-list-form',
    'listItemView' => '_slot_item',
    'action' => array('sort'),
));
?>

<?php $this->beginWidget('TbModalEx', array('id'=>'editElementDialog', 'modalTitle' => 'Title')); ?>
    <p>One fine body...</p>
<?php $this->endWidget(); ?>
<script>
jQuery(function($){
   $('#interview-slot-list-form .action-edit').click(function(){
       $this = $(this);
   });
});
</script>