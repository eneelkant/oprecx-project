<?php /** @var Division $division */ ?>
<?php $div_id = $division->div_id; ?>
<li id="division-item-<?php echo $div_id; ?>">
    <h4>
        <i class="icon-move handler"></i> 
        
        <span id="division-name-<?php echo $div_id; ?>">
            <?php echo CHtml::encode($division->name) ?>
        </span>
        
        <span class="actions">
            <a href="#editDivisionDialog" class="icon-pencil action-edit" data-toggle="modal" data-divid="<?php echo $div_id; ?>"></a>
            <a class="icon-trash action-delete" href="#deleteDivisionDialog" data-toggle="modal" data-divid="<?php echo $div_id; ?>"></a>
        </span>
       
    </h4>
    <div class="description" id="division-description-<?php echo $div_id; ?>">
        <?php echo CHtml::encode($division->description) ?>
        
        
    </div>
    <?php echo CHtml::hiddenField('DivisionList[items][]', $div_id, array ('id' => FALSE)); ?>
</li>