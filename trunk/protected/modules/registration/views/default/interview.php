<style>
    .time {
        display: block;
        width: 90px;
        text-align: center;
        height: 45px;
        position: relative;
        float: left;
    }

    
    .time-label {
        position: absolute;
        left: 0;
        right: 0;
        top: 0;
        bottom: 0;
        line-height: 45px;
        text-align: center;
        border: 1px solid #ccc;
        background-color: #fff;

        font-size: .9em;
        -moz-transition: all .4s;
        -webkit-transition: all .4s;
        -o-transition: all .4s;
        transition: all .4s;
    }
    
    .time-label-available {
        cursor: pointer;
    }
    
    .time-label-available:hover {
        background-color: #cdf;
    }
    
    .time-label-selected {
        background-color: #57a !important;
        font-weight: bold;
        color: #fff;
        text-shadow: none;
    }
    
    .time-label-full {
        background-color: #ddd;
    }
    
    .time-label-halffull {
        background-color: #dec;
    }
    
    .date {
       clear: both;
       margin-bottom: .5em;
    }
    .date h3 {
        margin-bottom: 0;
    }
</style>
<script>
jQuery(function($, undefined){
    var curSelect = [];
    $('#submit-button-alt').click(function(){
        $('#interview-slot-form').submit();
    });
    $('.time-label-selected').each(function(i, elm) {
        var dis = $(this);
        curSelect[dis.data('slotid')] = dis;
    });
    $('.time-label-available').click(function(e) {
        var dis = $(this);
        var curElm = curSelect[dis.data('slotid')];
        if (curElm) curElm.removeClass('time-label-selected');
        dis.addClass('time-label-selected');
        curSelect[dis.data('slotid')] = dis;
        $('#selected-time').html(dis.data('time') + ' ' + dis.html());
        $('#submit-popup').popup('open', {positionTo: this, transition: 'pop'});
    });
});
</script>
<?php
/* @var $this DefaultController */
/* @var $model InterviewSlotForm */


/* @var $form CActiveForm */
$form = $this->beginWidget('CActiveForm', array(
            'id' => 'interview-slot-form',
            'enableClientValidation' => false,
            'clientOptions' => array(
                'validateOnSubmit' => false,
            ),
        )
);

$tables = $model->getTables();
foreach ($tables as $id => $table) {
    echo '<fieldset>', $table['name'];
    //$slots = array();
    foreach ($table['slots'] as $date) {
        $value = sprintf('%04d-%02d-%02d', $date[0], $date[1], $date[2]); // "{$date[0]}-{$date[1]}-{$date[2]}";
        $elmid = "InterviewSlotForm_time_{$id}_{$date[0]}_{$date[1]}_{$date[2]}_";
        //$date_str = sprintf('%02d/%02d/%02d', $date[2], $date[1], $date[0]);
        $date_str = strftime('%A %d %B %Y', mktime(1, 0, 0, $date[1], $date[2], $date[0]));
        echo '<div class="date"><h3>', $date_str, '</h3><div data-role="controlgroup" data-type="horizontal">';
        foreach ($date[3] as $slot) {
            $time = $slot[0];
            
            $thisId = $elmid . $time;
            /*echo '<span class="time"><input type="radio" name="InterviewSlotForm[time][', $id, ']" value="', $value, ' ', 
                    int_to_time($time, true), '" id="', $thisId, '" data-role="none" data-inline="true" /><label for="', 
                    $thisId, '" class="time-label" data-time="', $date_str, '" data-slotid="', $id, '">', 
                    int_to_time($time), '-', int_to_time($time + $table['duration']), '</label></span>', PHP_EOL; */
            $label_class = 'time-label ';
            if ($slot[1]) $label_class .= 'time-label-available time-label-selected';
            elseif ($slot[2] >= $slot[3]) $label_class .= 'time-label-full';
            elseif ($slot[2] > 0) $label_class .= 'time-label-available time-label-halffull';
            else $label_class .= 'time-label-available';
            echo '<span class="time">', 
                    CHtml::radioButton('InterviewSlotForm[time][' . $id . ']', $slot[1], array(
                        'id' => $thisId,
                        'value' => ($value . ' ' . int_to_time($time, true)),
                        'data-role' => 'none',
                    )), 
                    CHtml::label(int_to_time($time) . '-' . int_to_time($time + $table['duration']), $thisId, array(
                        'data-slotid' => $id,
                        'data-time' => $date_str,
                        'class' => $label_class,
                    )),
                    '</span>', PHP_EOL;
            
            
        }
        echo '</div></div>';
    }
    echo '</fieldset>';
    //echo CHtml::activeRadioButtonList($model, 'time[' . $id . ']', $slots);
}

function int_to_time($t, $second = false) {
    $s = $t % 60;
    $t = (int)($t / 60);
    $m = $t % 60;
    $h = (int)($t / 60);
    return sprintf('%02d:%02d' . ($second ? ':%02d' : ''), $h, $m, $s);
}
?>
<div id="submit-popup" data-role="popup">
    <p><span id="selected-time"></span><input type="submit" value="Submit" data-theme="b" id="submit-button-alt" /></p>
</div>
<?php
echo CHtml::submitButton('Submit', array('data-theme' => 'b'));
$this->endWidget();
?>
