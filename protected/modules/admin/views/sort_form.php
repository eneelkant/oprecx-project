<?php
/* @var $this SortForm */ 
$this->beginWidget('CActiveForm', $this->formOptions);
$this->listOptions['class'] .= ' component-list';

/** @var TbActiveForm $form */
?>
<fieldset>

    <legend>
        <?php if ($this->backUrl) echo CHtml::link('', $this->backUrl, array('class' => 'icon-back')); ?>
        <?php echo $this->title; ?>
    </legend>
        <?php 
        echo '<', $this->listTag, CHtml::renderAttributes($this->listOptions), '>';
        
        foreach ($this->listItemData as $item) {
            $this->controller->renderPartial($this->listItemView, array('item' => $item));
        } 
        
        echo '</', $this->listTag, '>';
        ?>
</fieldset>
<div class="form-actions">
    <a href="<?php echo $this->addButtonLink ?>" class="btn-item-add"><i class="icon-plus"></i> <?php echo $this->addButtonText ?></a>
    <?php $this->widget('bootstrap.widgets.TbButton',
            array ('buttonType' => 'submit', 'type' => 'primary', 'label' => $this->saveButtonText, 
                'icon' => 'icon-ok icon-white', 
                'htmlOptions' => array('id'=>'btn-sort-save', 'disabled' => 'disabled', 'class' => 'pull-right save-button'))); ?>
</div>
<?php $this->endWidget(); ?>
