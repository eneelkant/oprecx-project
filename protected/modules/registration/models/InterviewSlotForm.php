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
    

    public function initTable($org_id, $user_id)
    {
        $this->_orgId = $org_id;
        $this->_userId = $user_id;
        $reader = Yii::app()->db->createCommand()
                ->select('oe.elm_id as id, oe.name, i.*')
                ->from('{{interview_slots}} i')
                ->join('{{org_elms}} oe', 'oe.elm_id = i.elm_id AND oe.org_id = :org_id')
                ->join('{{division_elms}} de', 'de.elm_id = oe.elm_id')
                ->join('{{division_choices}} dc', 'dc.div_id = de.div_id AND dc.user_id = :user_id')
                ->group('i.elm_id')
                ->order('oe.weight, oe.name')
                ->query(array (':user_id' => $user_id, ':org_id'  => $org_id));
        
        $tables = array();
        foreach ($reader as $row) {
            $row['time_range'] = unserialize($row['time_range']);
            $row['options'] = unserialize($row['options']);
            
            
            $tables[$row['id']] = $row;
        }
        $this->_tables =& $tables;
    }
    
    private function parseSlotTable($startDate, $endDate, $time_ranges, $duration, $options) {
        $startDate = explode('-', $startDate);
        $endDate = explode('-', $endDate);
        
        $y = $startDate[0];
        $m = $startDate[1];
        $d = $startDate[2];
        
        $rv = array();
        while ($d < $endDate[2] || $m < $endDate[1] || $y < $endDate[0]) {
            
            
        }
    }

}

?>
