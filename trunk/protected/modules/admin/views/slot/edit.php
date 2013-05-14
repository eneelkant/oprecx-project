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
?>
<h2><?php echo CHtml::encode($slot->name) ?></h2>
<p><a href="back"><i class="icon-arrow-left"></i> back</a></p>
<div id="slot-table-resizable">
    <div id="slot-table-container"><?php /*
  <table id="slot-table"><tr id="slot-table-header">
  <?php
  foreach ($slot->getDateList() as $date) {
  echo "<th id=\"slot_th_{$date}\">", O::app()->locale->dateFormatter->formatDateTime(strtotime($date), 'medium', null), '</th>';
  }
  ?>
  </tr><tr>
  <?php
  foreach ($table as $date => $day) {
  echo "<td id=\"slot_td_{$date}\">";
  foreach($day as $time => $slotInfo) {
  $id = str_replace(array('-', ':'), '_', "slot_options_{$date}_{$time}_max");
  ?>
  <div class="slot<?php if ($slotInfo['max'] == 0) echo ' disabled' ?>">
  <p>capacity: <?php echo CHtml::link($slotInfo['max'], '#', array('data-date' => $id, 'class' => 'slot-max-val')) ?></p>
  </div>
  <?php
  }
  echo '</td>';
  }
  ?>
  </tr></table>
  <div id="slot-table-left">
  <?php
  foreach($slot->getTimeList() as $range) {
  echo '<div>', $range[0], ' - ', $range[1], '</div>';
  }
  ?>
  </div>
  <div id="slot-table-top-left"></div>
 * */ ?>
    </div></div>


<!-- Modal -->

<form id="slotEdit" class="modal hide fade form-horizontal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3>Edit Slot</h3>
    </div>
    <div class="modal-body">
        <div class="control-group">
            <label class="control-label" for="slotEditMax">Capacity</label>
            <div class="controls">
                <input type="number" id="slotEditMax" value="1">
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
        <input class="btn btn-primary" type="submit" value="Save changes" />
    </div>
</form>
<?php O::app()->clientScript->registerCoreScript('jquery.ui'); ?>
<script>
jQuery(function($) {
    var defaultMaxUserPerSlot = <?php echo $slot->max_user_per_slot ?>;

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
                var max = options[date + ' ' + timeList[time][0]] || defaultMaxUserPerSlot;
                html += '<div class="slot' + (max === 0 ? ' disabled' : '') + '">' +
                        '<p>capacity: <a href="#" class="slot-max-val" date="' + date +
                        '" time="' + timeList[time][0] + '">' + max + '</a></p></div>';
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
    $('#slot-table-resizable').height(400);
    $('#slot-table-container').scroll(function(e) {
        $('#slot-table-header').css('top', ($(this).scrollTop()) + 'px');
        $('#slot-table-left').css('left', ($(this).scrollLeft()) + 'px');
    });

    $('.slot').click(function(evt) {
        if ($(evt.target).hasClass('slot-max-val'))
            return;

        var $this = $(this);
        var $inp = $this.find('.slot-max-val:first');
        var max = parseInt($inp.html());
        if (max !== 0) {
            $inp.html('0');
            $inp.attr('data-default', max);
            $this.addClass('disabled');
        } else {
            $inp.html($inp.attr('data-default') || defaultMaxUserPerSlot);
            $this.removeClass('disabled');
        }
    });
    
    var slotEditTargetVal;
    $('.slot-max-val').click(function() {
        slotEditTargetVal = $(this);        
        $('#slotEditMax').val(slotEditTargetVal.html());
        $('#slotEdit').modal({keyboard:false});
    });
    
    $('#slotEdit').submit(function() {
        var val = $('#slotEditMax').val();
        slotEditTargetVal.html(val);
        $(this).modal('hide');
        return false;
    });
});
</script>
