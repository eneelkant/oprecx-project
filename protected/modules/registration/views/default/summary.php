<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
$userState = UserState::load(Yii::app()->user->id, $this->org->id);
?>
<div id="division-summary">
    <h3><?php echo CHtml::link(Yii::t('oprecx', 'Division Choices'), $this->getURL('division', array('edit' => 1))); ?></h3>
    <?php renderDivisionChoices($userState->getDivisionChoices()); ?>
</div><!-- #division-summary -->

<div id="form-summary">
    <h3><?php echo CHtml::link(Yii::t('oprecx', 'Form'), $this->getURL('form', array('edit' => 1))); ?></h3>
    <?php renderFormStatus($userState->getFormStatus()); ?>
</div><!-- #form-summary -->

<div id="intslot-summary">
    <h3><?php echo CHtml::link(Yii::t('oprecx', 'Interview Slot'), $this->getURL('interview', array('edit' => 1))); ?></h3>
    <?php renderInterviewSlotStatus($userState->getSelectedInterviewSlot()); ?>
</div><!-- #intslot-summary -->

<?php
/**
* 
* @param UserStateDivisionChoice[] $divChoices
*/
function renderDivisionChoices($divChoices) {
   $divChoiceCount = count($divChoices);

   if ($divChoiceCount == 0) {
       echo Yii::t('oprecx', 'You have not choosen any division');
   } elseif ($divChoiceCount > 1) {
       $divNameList = array();
       for($i = 0; $i<$divChoiceCount - 1; ++$i) {
           $divNameList[] = $divChoices[$i]->div_name;
       }
       echo Yii::t('oprecx', 'You have choosen {divisions} and {division}.', 
               array('{divisions}' => implode(', ', $divNameList), '{division}' => $divChoices[$divChoiceCount - 1]->div_name));
   } else {
       echo Yii::t('oprecx', 'You have choosen {division}.', array('{division}' => $divChoices[0]->div_name));
   }
}


/**
* 
* @param UserStateFormStatus $formsStatus
*/
function renderFormStatus($formsStatus) {
   $ul = HtmlTag::tag('ul');

   foreach ($formsStatus as $status) {
       $ul->appendLi('<strong>' . $status->form_name . '</strong>: ' . 
               ($status->filled ? Yii::t('oprecx', 'OK') : Yii::t('oprecx', 'Not Filled')));
   }
   $ul->render(true);
}

/**
 * 
 * @param UserStateInterviewSlots[] $slotStatus
 */
function renderInterviewSlotStatus($slotStatus) {
   $formatter = Yii::app()->getLocale()->getDateFormatter();
   $ul = HtmlTag::tag('ul');
   foreach ($slotStatus as $status) {
       if ($status->time) {
           $utime = strtotime($status->time);
           $time = Yii::t('oprecx', '{date} at {time}', 
                   array(
                        '{date}' => $formatter->formatDateTime($utime, 'full', null),
                        '{time}' => $formatter->formatDateTime($utime, null, 'medium'),
                       )
                   );
       } else {
           $time = Yii::t('oprecx', 'You have not choosen a slot');
       }
       $ul->appendLi('<strong>' . $status->slot_name . '</strong>: ' . $time);
   }
   $ul->render(true);
}