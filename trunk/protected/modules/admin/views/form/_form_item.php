<?php /* @var OrgForm $item */ 
?>
<?php $oe_id = $item->id; ?>
<li id="form-item-<?php echo $oe_id; ?>">
    <i class="icon-move handler pull-left"></i> 
    <h4>
        <span id="form-name-<?php echo $oe_id; ?>">
            <?php echo CHtml::link($item->name, array('edit', 'fid' => $oe_id)) ?>
        </span>
        
        <span class="actions">
            <a href="#editDivisionDialog" class="icon-pencil action-edit" data-toggle="modal" data-fid="<?php echo $oe_id; ?>"></a>
            <a class="icon-trash action-delete" href="#deleteDivisionDialog" data-toggle="modal" data-divid="<?php echo $oe_id; ?>"></a>
        </span>
       
    </h4>
    
    <?php echo CHtml::hiddenField('OrgFormList[items][]', $oe_id, array ('id' => FALSE)); ?>
</li>