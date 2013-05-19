<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/* @var $this AdminController */
/* @var $form TbActiveForm */
/* @var $model NewSlotForm */

$form      = $this->beginWidget('bootstrap.widgets.TbActiveForm',
        array (
    'id'   => 'interview-slot',
    'type' => 'horizontal',
        ));
?>
<fieldset>

    <legend>Create new Interview Slot</legend>

    <?php echo $form->textFieldRow($model, 'name'); ?>

    <?php echo $form->html5EditorRow($model,
            'description', array ('rows'    => 5, 'height'  => '200', 'options' => array ('image' => false, 'font-styles' => false)));
    ?>


    <?php
    echo $form->dateRangeRow($model, 'dateRange', array (
        'prepend'      => '<i class="icon-calendar"></i>',
        'label'        => O::t('oprecx', 'Visible time'),
        'labelOptions' => array ('required' => true),
        'options'      => array (
            'format' => 'yyyy-MM-dd',
        )
    ));
    ?>

    <?php echo $form->numberFieldRow($model, 'defaultMax',
            array ('hint' => O::t('oprecx', 'maximum number user per slot'), 'min'  => 0));
    ?>
    
    <?php echo $form->numberFieldRow($model, 'duration',
            array ('hint' => O::t('oprecx', 'duration'), 'min'  => 0));
    ?>

    <div class="control-group ">
            <?php echo CHtml::activeLabelEx($model,
                    'timeRanges', array ('class' => 'control-label ')) ?>
        <div class="controls">
            <div id="time-range-input-container">
            <?php
            $timeRange = $model->timeRanges;
            for ($i = 0; $i < count($timeRange); $i += 2) {
                echo '<p>',
                CHtml::activeTextField($model, 'timeRanges[]',
                        array ('class' => 'input-time input-small', 'value' => $timeRange[$i])),
                ' - ',
                CHtml::activeTextField($model, 'timeRanges[]',
                        array ('class' => 'input-time input-small', 'value' => $timeRange[$i + 1])),
                ' <a href="#" class="time-delete-icon"><i class="icon-remove"></i></a>',
                '</p>';
            }
            ?>
            </div>
            <a href="#" id="time-range-add"><i class="icon-plus"></i> Add Range</a>
        </div>
    </div>
    
    <?php echo $form->checkBoxListRow($model, 'divList', $this->getDivList(), 
            array('hint'=>'Division')); ?>
    
    <div class="bootstrap-timepicker ">
        <input type="text" id="abie" name="abie" />
    </div>
</fieldset>
<div class="form-actions">
<?php $this->widget('bootstrap.widgets.TbButton',
        array ('buttonType' => 'submit', 'type'       => 'primary', 'label'      => 'Submit')); ?>
<?php $this->widget('bootstrap.widgets.TbButton',
        array ('buttonType' => 'reset', 'label'      => 'Reset')); ?>
</div>
<?php $this->endWidget(); ?>

<script>
    jQuery(function($) {
        
        function validateInput(elm) {
            var parts = elm.value.split(':');
            if (elm.value === '' || 
                    (elm.value.indexOf('.') === -1 && parts.length === 3 && 
                    parts[0] !== '' && parts[0] >= 0 && parts[0] <= 23 &&
                    parts[1] !== '' && parts[1] >= 0 && parts[1] <= 59 && 
                    parts[2] !== '' && parts[2] >= 0 && parts[2] <= 59))
            {
                $(elm).removeClass('error');
            }
            else {
                $(elm).addClass('error');
            }
        }
        $('#time-range-input-container').on('change', '.input-time', function(e){
            validateInput(e.target);
        });
        
        $('#time-range-input-container').on('click', '.time-delete-icon', function(e){
            $(e.currentTarget.parentElement).remove();
            return false;
        });
        //$('.input-time').change(function(e) {validateInput(this)});
        
        $('#time-range-add').click(function(){
            var inp = '<input type="text" name="NewSlotForm[timeRanges][]" class="input-time input-small" />';
            var html = '<p>' + inp + ' - ' + inp + ' <a href="#" class="time-delete-icon"><i class="icon-remove"></i></a>';
            var $new = $(html);
            $('#time-range-input-container').append($new);
            //$new.find('.input-time').change(function(){validateInput(this)});
            return false;
        })
    });
</script>