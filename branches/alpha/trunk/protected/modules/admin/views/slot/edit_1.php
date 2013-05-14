<?php
/* @var $this AdminController */
/* @var $form TbActiveForm */
/* @var $slot InterviewSlot */
$table = $slot->table;
O::app()->clientScript->registerCssFile(O::versionedFileUrl('/css/interview-slot', '.css'));

?>
<h2><?php echo CHtml::encode($slot->name) ?></h2>
<p><a href="back"><i class="icon-arrow-left"></i> back</a></p>
<div id="slot-table-resizable">
<div id="slot-table-container">
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
        
        <?php echo CHtml::textField("options[$date][$time][max]", $slotInfo['max'], 
                array('class' => 'options-max-input options-input', 'id' => $id)) ?>
        <p>capacity: <?php echo CHtml::link($slotInfo['max'], '#', array('data-for' => $id, 'class' => 'slot-max-val')) ?></p>
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
          <input type="text" id="slotEditMax" value="1">
        </div>
      </div>
  </div>
  <div class="modal-footer">
    <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
    <button class="btn btn-primary" type="submit">Save changes</button>
  </div>
</form>
<?php O::app()->clientScript->registerCoreScript('jquery.ui'); ?>
<script>
jQuery(function($){
    var defaultMaxUserPerSlot = <?php echo $slot->max_user_per_slot ?>;
   $('#slot-table-resizable').resizable({handles: 's'});
   $('#slot-table-resizable').height(400);
   $('#slot-table-container').scroll(function(e){
       $('#slot-table-header').css('top', ($(this).scrollTop()) + 'px');
       $('#slot-table-left').css('left', ($(this).scrollLeft()) + 'px');
   });
   
   $('.slot').click(function(evt){
       if ($(evt.target).hasClass('slot-max-val'))
           return;
       
       var $this = $(this);
       var $inp = $this.find('.options-max-input:first');
       var max = $inp.val();
       if (max != 0) {
           $inp.val('0');
           $inp.attr('data-default', max);
           $this.addClass('disabled');
       } else {
           $inp.val($inp.attr('data-default') || defaultMaxUserPerSlot);
           $this.removeClass('disabled');
       }
   });
   
   var slotEditTarget, slotEditTargetVal;
   $('.slot-max-val').click(function(){
       var $this = $(this);
       slotEditTargetVal = $this;
       slotEditTarget = $('#' + $this.attr('data-for'));
       $('#slotEditMax').val(slotEditTarget.val());
       $('#slotEdit').modal();
   });
   $('#slotEdit').submit(function(){
       var val = $('#slotEditMax').val();
       slotEditTarget.val(val);
       slotEditTargetVal.html(val);
       $(this).modal('hide');
       return false;
   });
});
</script>
