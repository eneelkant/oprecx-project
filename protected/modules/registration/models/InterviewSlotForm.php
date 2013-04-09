<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of InterviewSlotForm
 *
 * @author abie
 */
class InterviewSlotForm extends CFormModel
{

    private $_orgId, $_userId, $_tables;
    public $time;
    
    public function __construct($scenario = '', $org_id = null, $user_id = null)
    {
        parent::__construct($scenario);
        $this->_orgId = $org_id;
        $this->_userId = $user_id;
    }
    
    public function getTables(){
        if (! isset($this->_tables)) {
            $reader = Yii::app()->db->createCommand()
                    ->select('oe.elm_id as id, oe.name, i.*')
                    ->from('{{interview_slots}} i')
                    ->join('{{org_elms}} oe', 'oe.elm_id = i.elm_id AND oe.org_id = :org_id')
                    ->join('{{division_elms}} de', 'de.elm_id = oe.elm_id')
                    ->join('{{division_choices}} dc', 'dc.div_id = de.div_id AND dc.user_id = :user_id')
                    ->group('i.elm_id, oe.elm_id, oe.name')
                    ->order('oe.weight, oe.name')
                    ->query(array ('user_id' => $this->_userId, 'org_id'  => $this->_orgId));

            $tables = array();
            foreach ($reader as $row) {
                $row['time_range'] = unserialize($row['time_range']);
                $row['options'] = unserialize($row['options']);

                $row['slots'] = $this->parseSlotTable($row);
                $tables[$row['id']] = $row;
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
        $id = $arg['id'];
        $startDate = $arg['start_date'];
        $endDate = $arg['end_date'];
        $time_ranges = $arg['time_range'];
        $duration = $arg['duration'];
        //$options = $arg['options'];
        // SELECT t1.time, count(t1.user_id), t2.user_id FROM `oprecx_interview_user_slots` t1 LEFT JOIN `oprecx_interview_user_slots` t2 ON t2.slot_id = t1.slot_id AND t2.time = t1.time AND t2.user_id = 6 group by t1.time
        $reader = Yii::app()->db->createCommand()
                ->select('t1.time, COUNT(t1.user_id) as cnt, t2.user_id')
                ->from('{{interview_user_slots}} t1')
                ->leftJoin('{{interview_user_slots}} t2', 't2.slot_id = t1.slot_id AND t2.time = t1.time AND t2.user_id = :user_id')
                ->where('t1.slot_id = :slot_id')
                ->group('t1.time, t2.user_id')
                ->order('t1.time')
                ->query(array('slot_id' => $id, 'user_id' => Yii::app()->user->id));
        $slot_count = array();
        foreach ($reader as $row) {
            $slot_count[$row['time']] = array($row['cnt'], $row['user_id'] != null);
        }
        $startDate = explode('-', $startDate);
        $endDate = explode('-', $endDate);
        
        $y = $startDate[0];
        $m = $startDate[1];
        $d = $startDate[2];
        
        $rv = array();
        $month_len = array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
        $month_len[1] = $y % 4 == 0 ? 29 : 28;
        
        while ($d < $endDate[2] || $m < $endDate[1] || $y < $endDate[0]) {
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
        $db = Yii::app()->getDb();
        
        $slots = $db->createCommand()
                ->select('us.slot_id')
                ->from('{{interview_user_slots}} us')
                ->join('{{org_elms}} oe', 'oe.elm_id = us.slot_id AND oe.org_id = :org_id')
                ->where('us.user_id = :user_id')
                ->queryColumn(array('org_id' => $this->_orgId, 'user_id' => $this->_userId));
        if ($slots)
            $slots = array_flip($slots);
        else
            $slots = array();
        
        $transaction = $db->beginTransaction();
        try {
            foreach($this->time as $id => $time) {
                if (isset($slots[$id])) {
                    $db->createCommand()->update(
                            '{{interview_user_slots}}', 
                            array('time' => $time, 'updated' => new CDbExpression('CURRENT_TIMESTAMP')),
                            'slot_id = :slot_id AND user_id = :user_id',
                            array('slot_id' => $id, 'user_id' => $this->_userId)
                    );
                } else {
                    $db->createCommand()->insert('{{interview_user_slots}}', array(
                        'slot_id' => $id,
                        'user_id' => $this->_userId,
                        'time' => $time,
                    ));
                }
                
            }
            $transaction->commit();
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
