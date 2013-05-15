<?php
/* @var $this AdminController */
/* @var $form TbActiveForm */
/* @var $slot InterviewSlot */
$table    = $slot->table;

O::app()->clientScript->registerCssFile(O::versionedFileUrl('/css/interview-slot', '.css'));
$dateList = array ();
foreach ($slot->getDateList() as $date) {
    $dateList[$date] = O::app()->locale->dateFormatter->formatDateTime(strtotime($date), 'medium', null);
}
//O::app()->clientScript->registerCoreScript('jquery.ui');

?>
<h3><?php echo CHtml::encode($slot->name) ?><br />
    <small><a href="back"><i class="icon-arrow-left"></i> back</a></small>
</h3>
<?php
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array (
    'id'   => 'slot-edit',
    'type' => 'horizontal',
        ));
?>
        <fieldset>
        <legend>Custom Table</legend>
        
        <p></p>
        <div id="slot-table-resizable"><div id="slot-table-container"></div></div>
    </fieldset>

    <fieldset>
        <?php $choiceOptions = $slot->getChoiceOptions(); ?>
        <legend>Options</legend>
        <div class="control-group">
            <label class="control-label required">Enabled slot  <span class="required">*</span></label>
            <div class="controls">
                <label class="radio">
                    <input type="radio" id="slot-options-enabletype-default"  name="slot-options-enabletype"
                            <?php if (! $choiceOptions) echo 'checked'; ?>
                           onclick="jQuery('.slot-options-enablerelateive').attr('disabled', 'disabled')" /> 
                    Default
                </label>
                <label class="radio">
                    <input type="radio"  id="slot-options-enabletype-relative" name="slot-options-enabletype"  
                        <?php if ($choiceOptions) echo 'checked'; ?>
                           onclick="jQuery('.slot-options-enablerelateive').removeAttr('disabled')" />
                    Only after specific time from registration time
                </label>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label required">Enabled slot  <span class="required">*</span></label>
            <div class="controls">
                <input type="number" id="slot-options-enabletime-length"  <?php if (! $choiceOptions) echo 'disabled'; ?>
                       class="slot-options-enablerelateive" value="<?php echo $choiceOptions ? $choiceOptions['n'] : '1'; ?>" />
                <?php echo CHtml::dropDownList('slotdur', $choiceOptions ? $choiceOptions['unit'] : 'd', array(
                    'd' => O::t('oprecx', 'day|days', 9999),
                    'h' => O::t('oprecx', 'hour|hours', 9999),
                    'm' => O::t('oprecx', 'minute|minutes', 9999),
                ), array('id' => 'slot-options-enabletime-unit', 'class' => 'slot-options-enablerelateive', 
                    'disabled' => $choiceOptions ? NULL : 'disabled')); ?>
            </div>
        </div>
    </fieldset>

   <div class="form-actions">        
        <?php $this->widget('bootstrap.widgets.TbButton',
                array ('buttonType' => 'submit', 'type' => 'primary', 'label' => O::t('oprecx', 'Save'), 
                    'icon' => 'icon-ok icon-white', 
                    'htmlOptions' => array('id'=>'btn-slot-save', 'class' => 'pull-right save-button'))); ?>
    </div>
<?php $this->endWidget(); ?>

<?php O::app()->clientScript->registerCoreScript('jquery.ui'); ?>
<script>
jQuery(function($) {
    var defaultMaxUserPerSlot = <?php echo $slot->max_user_per_slot ?>,
        saveUrl = <?php echo json_encode($this->createUrl('saveOptions', array('id' => $slot->id))); ?>;

    (function(){
        var dateList = <?php echo json_encode($dateList) ?>,
                timeList = <?php echo json_encode($slot->getTimeList()) ?>,
                options = <?php echo json_encode($slot->options) ?>;
        var html = '';
        html += '<table id="slot-table"><tr id="slot-table-header">';
        for (var date in dateList) {
            html += '<th id="slot_th_' + date.replace(/-/, '_') + '">' + dateList[date] + '</th>';
        }
        html += '</tr><tr>';
        for (var date in dateList) {
            html += '<td id="slot_td_' + date.replace(/-/, '_') + '">';
            for (var time in timeList) {
                var dateTime = date + ' ' + timeList[time][0];
            
                var max = (options[dateTime] && options[dateTime].max) ? options[dateTime].max : defaultMaxUserPerSlot.toString();
                var id = 'slot_options_' + date.replace(/-/, '_') + '_' + timeList[time][0].replace(/:/, '_') + '_max';
                /* html += '<div class="slot' + (max === '0' ? ' disabled' : '') + '">' +
                        '<p>capacity: <a href="#" class="slot-max-val" date="' + date +
                        '" time="' + timeList[time][0] + '">' + max + '</a></p></div>'; */
                /*html += '<div class="slot' + (max === '0' ? ' disabled' : '') + '">' +
                        '<p class="input-append">\
                        <input type="text" class="class slot-max-val span3" date="' + date +
                        '" time="' + timeList[time][0] + '" value="' + max + '" /><button class="btn" type="button">+</button>\
                        <button class="btn" type="button">-</button></p></div>'; */
                                           
                html += '<div class="slot' + (max === '0' ? ' disabled' : '') + '">' +
                        '<p> capacity: <input type="number" class="slot-max-val span3" date="' + date + 
                        '" time="' + timeList[time][0] + '" value="' + max + '" /></p></div>';
            }
            html += '</td>';
        }
        html += '</tr></table><div id="slot-table-left">';
        for (var time in timeList) {
            html += '<div>' + timeList[time].join(' - ') + '</div>';
        }
        html += '</div>';

        $('#slot-table-container').html(html);
    })();

    $('#slot-table-resizable').resizable({handles: 's'});
    $('#slot-table-resizable').height(350);
    $('#slot-table-container').scroll(function(e) {
        $('#slot-table-header').css('top', ($(this).scrollTop()) + 'px');
        $('#slot-table-left').css('left', ($(this).scrollLeft()) + 'px');
    });
//*
    $('.slot').click(function(evt) {
        if ($(evt.target).hasClass('slot-max-val'))
            return;

        var $this = $(this);
        var $inp = $this.find('.slot-max-val:first');
        var max = parseInt($inp.val());
        if (max !== 0) {
            $inp.val('0');
            $inp.attr('data-default', max);
            $this.addClass('disabled');
        } else {
            $inp.val($inp.attr('data-default') || defaultMaxUserPerSlot);
            $this.removeClass('disabled');
        }
    });
    //*/
    
    
    var slotEditTargetVal;
    
    
    $('#slotEdit').submit(function() {
        var val = $('#slotEditMax').val();
        slotEditTargetVal.html(val);
        $(this).modal('hide');
        return false;
    });
    
    $('.slot-max-val').focus(function(){
        $(this).select();
    });
    
    $('#slot-edit').submit(function(){
        var $this = $(this), options = {}
        
        $this.find('.slot-max-val').each(function(i, elm) {
            var $elm = $(elm);
            var val = parseInt($elm.val());
            if (isNaN(val)) {
                alert('Format wrong!');
                $elm.focus();
                
                return false;
            }    
            if (val !== defaultMaxUserPerSlot) {
                options[$elm.attr('date') + ' ' + $elm.attr('time')] = {max: val};
            }
        });
        $('#btn-slot-save').attr('html-tmp', $('#btn-slot-save').html());
        $('#btn-slot-save').html('Loading ...');
        $('#btn-slot-save').prop('disabled', true);
        
        var postData = {slot_options : options};
        
        var token = $this.find('input[name=token]').first().val();
        if (token) postData.token = token;
        
        if ($('#slot-options-enabletype-relative').prop('checked')) {
            postData.slot_options.choiceOptions = {
                n: $('#slot-options-enabletime-length').val(),
                unit: $('#slot-options-enabletime-unit').val()
            };
        }
        
        $.post(saveUrl, postData, function(data) {
            if (data.error) {
                alert(data.error);
            }
            $('#btn-slot-save').html($('#btn-slot-save').attr('html-tmp'));
            $('#btn-slot-save').prop('disabled', false);
        });
        return false;
    });
});
</script>
