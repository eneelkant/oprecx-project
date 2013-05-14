<?php
/* @var $this DefaultController */
/* @var $model InterviewSlotForm */
// FIXME: submit button doesnot respond on popup

/**
 * 
 * @param InterviewSlotForm $model 
 */
function generateInterviewTables($model) {
    foreach ($model->getTables() as $id => $table) {
        $radioName = get_class($model) . "[time][{$id}]";
        echo '<fieldset>', $table['name'], '<div class="slots">';
        $radio_id_prefix = "InterviewSlotForm_time_{$id}_";
        $i = 0;

        foreach ($table['slots'] as $slot_date) {
            //$radio_id_prefix = "InterviewSlotForm_time_{$id}_{$date[0]}{$date[1]}{$date[2]}_";
            list($year, $month, $day, $slot_list) = $slot_date;
            
            $date = sprintf('%04d-%02d-%02d', $year, $month, $day); // "{$date[0]}-{$date[1]}-{$date[2]}";
            $date_str = O::app()->getLocale()->getDateFormatter()->formatDateTime(
                    mktime(1, 0, 0, $month, $day, $year), 'full', NULL);
            echo '<div class="date"><h3>', $date_str, '</h3><div data-role="controlgroup" data-type="horizontal">', PHP_EOL;
            
            foreach ($slot_list as $slot) {
                list($time, $checked, $user_count, $max_user) = $slot;

                $label_class = 'time-label ';                
                if ($checked) $label_class .= 'time-label-available time-label-selected';
                elseif ($user_count >= $max_user) $label_class .= 'time-label-full';
                elseif ($user_count > 0) $label_class .= 'time-label-available time-label-halffull';
                else $label_class .= 'time-label-available';
                
                $radio_id = $radio_id_prefix . $i++;

                echo '<span class="time">', 
                        CHtml::radioButton($radioName, $checked, array(
                            'id' => $radio_id,
                            'value' => ($date . ' ' . int_to_time($time, true)),
                            'data-role' => 'none',
                            'disabled' => ($user_count >= $max_user),
                        )), 
                        CHtml::label(int_to_time($time) . '-' . int_to_time($time + $table['duration']), $radio_id, array(
                            'data-slotid' => $id,
                            'data-time' => $date_str,
                            'class' => $label_class,
                        )),
                        '</span>', PHP_EOL;

            }
            echo '</div></div>';
        }
        echo '</div></fieldset>', PHP_EOL;
        //echo CHtml::activeRadioButtonList($model, 'time[' . $id . ']', $slots);
    }
}
function int_to_time($t, $second = false) {
    $s = $t % 60;
    $t = (int)($t / 60);
    $m = $t % 60;
    $h = (int)($t / 60);
    return sprintf('%02d:%02d' . ($second ? ':%02d' : ''), $h, $m, $s);
}
?>
<?php $this->beginWidget('CActiveForm', array(
    'id' => 'interview-slot-form',
    'enableClientValidation' => false,
    'clientOptions' => array(
        'validateOnSubmit' => false,
    ),
)); ?>
<?php generateInterviewTables($model) ?>
<?php
$this->renderPartial('submit');
$this->endWidget();
?>

<div id="submit-popup" data-role="popup">
    <?php 
    // FIXME i have no idea what i am doing
    $this->beginWidget('CActiveForm', array(
        'id' => 'interview-slot-form',
        'enableClientValidation' => false,
        'clientOptions' => array(
            'validateOnSubmit' => false,
        ),
        'htmlOptions' => array(
            'data-role' => 'content'
        )
    )); 
    ?>
    <input type="hidden" id="interview-time-input" />
    <p><span id="selected-time"></span><input type="submit" value="Submit" data-theme="b" id="submit-button-alt" /></p>
    <?php $this->endWidget(); ?>
</div>