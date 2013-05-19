<?php
/* @var $this DefaultController */
/* @var $model InterviewSlotForm */
// FIXME: submit button doesnot respond on popup

/**
 * 
 * @param InterviewSlotForm $model 
 */
function generateInterviewTables($model) {
    /* @var $slot InterviewSlot */
    $slot = $model->slot;
    $dateFormatter = O::app()->locale->dateFormatter;
    echo '<fieldset><legend>', $slot->name, '<br /><small>', $slot->description, '</small></legend><div class="slots">';
    $radio_id_prefix = "InterviewSlotForm_time_{$slot->id}_";
    $i = 0;
    $radioName = get_class($model) . "[time][{$slot->id}]";
    
    $slotStatus = $slot->getStatus();
    $slotOptions = $slot->options;
    
    $datetime = new DateTime;
    if (isset($slotOptions['choiceOptions'])) {
        $n = $slotOptions['choiceOptions']['n'];
        
        switch ($slotOptions['choiceOptions']['unit']) {
            case 'd':
            case 'D':
                $datetime->add(new DateInterval('P' . $n . strtoupper($slotOptions['choiceOptions']['unit'])));
                $datetime->setTime(0, 0, 0);
                break;
            
            default:
                $datetime->add(new DateInterval('PT' . $n . strtoupper($slotOptions['choiceOptions']['unit'])));
        }
        
    }
    $enableFrom = $datetime->getTimestamp();
    
    $currentChoice = CDbCommandEx::create()
                    ->select('time')
                    ->from(TableNames::INTERVIEW_USER_SLOT)
                    ->where('$slot_id = :slot_id AND $user_id = :user_id')
                    ->queryScalar(array('slot_id' => $slot->id, 'user_id' => O::app()->user->id));
    
    foreach ($slot->getDateList() as $date) {
        $date_str = $dateFormatter->formatDateTime($date, 'full', NULL);
        echo '<div class="date"><h3>', $date_str, '</h3><div data-role="controlgroup" data-type="horizontal">', PHP_EOL;
        
        foreach ($slot->getTimeList() as $time) {
            $radio_id = $radio_id_prefix . $i++;
            $value = $date . ' ' . $time[0];
            $checked = $currentChoice == $value;
            
            if (isset($slotStatus[$value])) {
                $user_count = $slotStatus[$value]['selected'];
            } else {
                $user_count = 0;
            }
            
            if (isset($slotOptions[$value]) && isset($slotOptions[$value]['max'])) {
                $max_user = $slotOptions[$value]['max'];
            } else {
                $max_user = $slot->max_user_per_slot;
            }
            $label_class = 'time-label ';
            if ($enableFrom == 0 || strtotime($value) > $enableFrom) {
                if ($checked) $label_class .= 'time-label-available time-label-selected';
                elseif ($user_count >= $max_user) $label_class .= 'time-label-full';
                elseif ($user_count > 0) $label_class .= 'time-label-available time-label-halffull';
                else $label_class .= 'time-label-available';
            }
            else {
                $label_class .= 'time-label-full';
            }
            echo '<span class="time">', 
                        CHtml::radioButton($radioName, $checked, array(
                            'id' => $radio_id,
                            'value' => $value,
                            'data-role' => 'none',
                            'disabled' => ($user_count >= $max_user),
                        )), 
                        CHtml::label(substr($time[0], 0, 5) . '-' . substr($time[1], 0, 5), $radio_id, array(
                            'data-slotid' => $slot->id,
                            'data-time' => $date_str,
                            'class' => $label_class,
                        )),
                        '</span>', PHP_EOL;
        }
        echo '</div></div>';
    }
    echo '</div></fieldset>', PHP_EOL;
}

?>
<?php $this->beginWidget('CActiveForm', array(
    'id' => 'interview-slot-form',
    'enableClientValidation' => false,
    'clientOptions' => array(
        'validateOnSubmit' => false,
    ),
)); ?>
<p class="current-time">
    <?php echo O::t('oprecx', 'Current time: {time}', 
            array('{time}' => O::app()->dateFormatter->formatDateTime(time()))); ?>
</p>
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