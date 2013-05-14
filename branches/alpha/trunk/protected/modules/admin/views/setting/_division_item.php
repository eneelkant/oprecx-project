<?php /** @var Division $item */ ?>
<?php $div_id = $item->div_id; ?>
<li id="division-item-<?php echo $div_id; ?>">
    <i class="icon-move handler pull-left"></i> 
    <h4>
        <span id="division-name-<?php echo $div_id; ?>">
            <?php echo CHtml::encode($item->name) ?>
        </span>
        
        <span class="actions">
            <a href="#editDivisionDialog" class="icon-pencil action-edit" data-toggle="modal" data-divid="<?php echo $div_id; ?>"></a>
            <a class="icon-trash action-delete" href="#deleteDivisionDialog" data-toggle="modal" data-divid="<?php echo $div_id; ?>"></a>
        </span>
       
    </h4>
    <div class="description" id="division-description-<?php echo $div_id; ?>">
        <?php echo CHtml::encode($item->description) ?>
        
        
    </div>
    <?php echo CHtml::hiddenField('DivisionList[items][]', $div_id, array ('id' => FALSE)); ?>
</li>