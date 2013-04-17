<?php
/* @var $this DefaultController */
/* @var $model InterviewSlotForm */


/* @var $form CActiveForm */
//$form = 
$this->beginWidget('CActiveForm', array(
    'id' => 'interview-slot-form',
    'enableClientValidation' => false,
    'clientOptions' => array(
        'validateOnSubmit' => false,
    ),
));

foreach ($model->getTables() as $id => $table) {
    echo '<fieldset>', $table['name'], '<div class="slots">';
    //$slots = array();
    foreach ($table['slots'] as $date) {
        $value = sprintf('%04d-%02d-%02d', $date[0], $date[1], $date[2]); // "{$date[0]}-{$date[1]}-{$date[2]}";
        $elmid = "InterviewSlotForm_time_{$id}_{$date[0]}_{$date[1]}_{$date[2]}_";
        //$date_str = sprintf('%02d/%02d/%02d', $date[2], $date[1], $date[0]);
        //$date_str = strftime('%A %d %B %Y', mktime(1, 0, 0, $date[1], $date[2], $date[0]));
        $date_str = Yii::app()->getLocale()->getDateFormatter()->formatDateTime($value, 'full', NULL);
        echo '<div class="date"><h3>', $date_str, '</h3><div data-role="controlgroup" data-type="horizontal">';
        foreach ($date[3] as $slot) {
            $time = $slot[0];
            
            $thisId = $elmid . $time;
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
    echo '</div></fieldset>';
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
$this->renderPartial('submit');
$this->endWidget();
