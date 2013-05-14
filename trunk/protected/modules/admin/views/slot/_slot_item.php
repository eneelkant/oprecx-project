<?php /* @var InterviewSlot $item */ 
?>
<?php $re_id = $item->id; ?>
<li id="slot-item-<?php echo $re_id; ?>">
    <i class="icon-move handler pull-left"></i> 
    <h4>
        <span id="slot-name-<?php echo $re_id; ?>">
            <?php echo CHtml::link($item->name, array('edit', 'id' => $re_id)) ?>
        </span>
        
        <span class="actions">
            <a href="#editElementDialog" class="icon-pencil action-edit" data-toggle="modal" data-eid="<?php echo $re_id; ?>"></a>
            <a class="icon-trash action-delete" href="#deleteElementDialog" data-toggle="modal" data-eid="<?php echo $re_id; ?>"></a>
        </span>
       
    </h4>
    
    <?php echo CHtml::hiddenField('OrgFormList[items][]', $re_id, array ('id' => FALSE)); ?>
</li>