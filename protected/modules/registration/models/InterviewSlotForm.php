<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of InterviewSlotForm
 * 
 * @property InterviewSlot $slot Description
 * @author abie
 */
class InterviewSlotForm extends CFormModel
{

    private $_recId, $_userId, $_slotId, $_tables, $_slot;
    public $time;
    
    public function __construct($scenario = '', $rec_id = null, $user_id = null, $slotid = null)
    {
        parent::__construct($scenario);
        $this->_recId = $rec_id;
        $this->_userId = $user_id;
        $this->_slotId = $slotid;
    }
    
    public function getSlot() {
        if ($this->_slot) return $this->_slot;
        else return $this->_slot = InterviewSlot::model ()->findById ($this->_slotId);
    }


    public function getTables(){
        if (! isset($this->_tables)) {
            $reader = CDbCommandEx::create()
                    ->selectDistinct('i.*, oe.name, oe.weight')
                    ->from(TableNames::INTERVIEW_SLOT . ' i')
                    ->join(TableNames::REC_ELM . ' oe', '$oe.elm_id = $i.elm_id AND $oe.rec_id = :rec_id')
                    ->join(TableNames::DIVISION_ELM . ' de', '$de.elm_id = $oe.elm_id')
                    ->join(TableNames::DIVISION_CHOICE . ' dc', '$dc.div_id = $de.div_id AND $dc.user_id = :user_id')
                    //->group('i.elm_id, oe.elm_id')
                    ->order('oe.weight, oe.name')
                    ->query(array ('user_id' => $this->_userId, 'rec_id'  => $this->_recId));

            $tables = array();
            foreach ($reader as $row) {
                $row['time_range'] = unserialize($row['time_range']);
                $row['options'] = unserialize($row['options']);

                $row['slots'] = $this->parseSlotTable($row);
                $tables[$row['elm_id']] = $row;
            }
            $this->_tables =& $tables;
        }
        return $this->_tables;
    }
    
    /*
1	January	31 days
2	February	28 days, 29 in leap years
3	March	31 days
4	April	30 days
5	May	31 days
6	June	30 days
7	July	31 days
8	August	31 days
9	September	30 days
10	October	31 days
11	November	30 days
12	December	31 days
     */
    private function parseSlotTable($arg) {
        $id = $arg['elm_id'];
        $startDate = explode('-', $arg['start_date']);
        $endDate = explode('-', $arg['end_date']);
        $time_ranges = $arg['time_range'];
        $duration = $arg['duration'];
        //$options = $arg['options'];
        // SELECT t1.time, count(t1.user_id), t2.user_id FROM `oprecx_interview_user_slots` t1 LEFT JOIN `oprecx_interview_user_slots` t2 ON t2.slot_id = t1.slot_id AND t2.time = t1.time AND t2.user_id = 6 group by t1.time
        $reader = CDbCommandEx::create()
                ->select('t1.time, COUNT(t1.user_id) as cnt, t2.user_id')
                ->from(TableNames::INTERVIEW_USER_SLOT . ' t1')
                ->leftJoin(TableNames::INTERVIEW_USER_SLOT . ' t2', '$t2.slot_id = $t1.slot_id AND $t2.time = $t1.time AND $t2.user_id = :user_id')
                ->where('$t1.slot_id = :slot_id')
                ->group('t1.time, t2.user_id')
                ->order('t1.time')
                ->query(array('slot_id' => $id, 'user_id' => O::app()->user->id));
        $slot_count = array();
        foreach ($reader as $row) {
            $slot_count[$row['time']] = array($row['cnt'], $row['user_id'] != null);
        }
        
        $y = $startDate[0];
        $m = $startDate[1];
        $d = $startDate[2];
        
        $rv = array();
        $month_len = array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
        $month_len[1] = $y % 4 == 0 ? 29 : 28;
        
        while ($d <= $endDate[2] || $m < $endDate[1] || $y < $endDate[0]) {
            $tmp = array();
            foreach ($time_ranges as $time_range) {
                for ($time = $time_range[0]; $time < $time_range[1]; $time += $duration) {
                    $t = $time;
                    $scn = $t % 60;
                    $t = (int)($t / 60);
                    $mnt = $t % 60;
                    $hr = (int)($t / 60);
                    $dt = sprintf('%04d-%02d-%02d %02d:%02d:%02d', $y, $m, $d, $hr, $mnt, $scn);
                    if (isset($slot_count[$dt]))
                        $tmp[] = array($time, $slot_count[$dt][1], $slot_count[$dt][0], 1);
                    else
                        $tmp[] = array($time, false, 0, 1);
                }                
            }
            $rv[] = array($y, $m, $d, $tmp);
            
            $d++;
            if ($d > $month_len[$m - 1]) {
                $m++;
                $d = 1;
            }
            if ($m > 12) {
                $y++;
                $m = 1;
                $month_len[1] = $y % 4 == 0 ? 29 : 28;
            }
        }
        return $rv;
    }
    
    public function save() {
        $db = O::app()->getDb();
        
        $slots = $db->createCommand()
                ->select('us.slot_id')
                ->from(TableNames::INTERVIEW_USER_SLOT . ' us')
                ->join(TableNames::REC_ELM . ' oe', 'oe.elm_id = us.slot_id AND oe.rec_id = :rec_id')
                ->where('us.user_id = :user_id')
                ->queryColumn(array('rec_id' => $this->_recId, 'user_id' => $this->_userId));
        if ($slots)
            $slots = array_flip($slots);
        else
            $slots = array();
        
        $transaction = $db->beginTransaction();
        try {
            foreach($this->time as $id => $time) {
                if (isset($slots[$id])) {
                    $db->createCommand()->update(
                            TableNames::INTERVIEW_USER_SLOT, 
                            array('time' => $time, 'updated' => new CDbExpression('CURRENT_TIMESTAMP')),
                            'slot_id = :slot_id AND user_id = :user_id',
                            array('slot_id' => $id, 'user_id' => $this->_userId)
                    );
                } else {
                    $db->createCommand()->insert(TableNames::INTERVIEW_USER_SLOT, array(
                        'slot_id' => $id,
                        'user_id' => $this->_userId,
                        'time' => $time,
                    ));
                }
                
            }
            $transaction->commit();
            UserState::invalidate($this->_userId, $this->_recId, 'slots');
            return true;
        }        
        catch (Exception $e) {
            $transaction->rollback();
            throw $e;
        }
        return false;
    }
    
    public function validateTime($attribute, $param) {
        
    }

    public function rules(){
        return array (
            //array('choices', 'required'),
            array('time', 'validateTime'),
        );
    }
}

?>
