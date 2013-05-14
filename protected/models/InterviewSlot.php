<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of InterviewSlot
 *
 * @author abie
 * @property string $description Description
 * @property int $duration Description
 * @property string $start_date Description
 * @property string $end_date Description
 * @property array $time_range Description
 * @property int $max_user_per_slot Description
 * @property array $options Description
 * 
 * @property array $table Description
 */
class InterviewSlot extends RecruitmentElement
{
    public $specificAttr = array('description', 'duration', 'start_date', 'end_date', 'time_range', 'max_user_per_slot', 'options');
    protected $_tableName = TableNames::INTERVIEW_SLOT;
    private $_table, $_dateList, $_timeList;

    

    /**
     * 
     * @param string $class
     * @return RecruitmentForm
     */
    public static function model($class = __CLASS__)
    {
        return parent::model($class);
    }
    
    
    public function afterPopulateItem()
    {
        $item =& parent::afterPopulateItem()->data;
        if (isset($item['time_range'])) $item['time_range'] = unserialize ($item['time_range']);
        if (isset($item['options'])) $item['options'] = unserialize ($item['options']);
        return $this;
    }
    
    public function getTimeList() {
        if ($this->_timeList) return $this->_timeList;
        $timeList = array();
        foreach ($this->time_range as $time_range) {
            $time = $time_range[0];
            $timeStr = self::formatTimeInt($time);
            for (; $time < $time_range[1]; $time += $this->duration) {
                $timeList[] = array($timeStr, $timeStr = self::formatTimeInt($time + $this->duration));
            }
        }
        return $this->_timeList = $timeList;
    }
    
    public function getDateList(){
        if ($this->_dateList) return $this->_dateList;
        
        static $month_len = array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
        list($y, $m, $d) = explode('-', $this->start_date);
        $endDate = explode('-', $this->end_date);
        $month_len[1] = $y % 4 == 0 ? 29 : 28;
        $dateList = array();
        while ($d <= $endDate[2] || $m < $endDate[1] || $y < $endDate[0]) {
            $dateList[] = self::formatDate($y, $m, $d);
            
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
        return $this->_dateList = $dateList;
    }
    
    public function getTableOptions() {
        
    }


    public function getTable() {
        if ($this->_table) return $this->_table;
        return $this->_table = self::parseSlotTable();
    }
    
    private function parseSlotTable($retriveCount = false) {
        $slotTable = array();
        foreach ($this->getDateList() as $date) {
            $slotDay = array();
            foreach ($this->getTimeList() as $time_range) {
                $slotDay[$time_range[0]] = array('n' => 0, 'max' => $this->max_user_per_slot, 'selected' => false);
            }
            $slotTable[$date] = $slotDay;
        }
        //*
        
        if ($retriveCount) {
            $command = CDbCommandEx::create()
                    //->select('t1.time, COUNT(t1.user_id) as n, t2.user_id')
                    ->select('t1.time, COUNT(t1.user_id) as n')
                    ->from(TableNames::INTERVIEW_USER_SLOT . ' t1')
                    //->leftJoin(TableNames::INTERVIEW_USER_SLOT . ' t2', '$t2.slot_id = $t1.slot_id AND $t2.time = $t1.time AND $t2.user_id = :user_id')
                    ->where('$t1.slot_id = :slot_id', array('slot_id' => $this->id))
                    ->group('t1.time');
            
            foreach ($command->query() as $row) {
                list($date, $time) = explode(' ', $row['time']);
                if (isset($slotTable[$date]) && isset($slotTable[$date][$time])) {
                    $cell =& $slotTable[$date][$time];
                    $cell['n'] = $row['n'];
                    //if ($row['user_id'] != null) $cell['selected'] = true;
                }

            } // */
        }
        if (is_array($this->options))
            self::mergeTable($slotTable, $this->options);
        
        return $slotTable;
    }
    
    
    private function parseSlotTable2($retriveCount = true) {
        static $month_len = array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
       
        $slotTable = array();
        list($y, $m, $d) = explode('-', $this->start_date);
        $endDate = explode('-', $this->end_date);
        $month_len[1] = $y % 4 == 0 ? 29 : 28;
        while ($d <= $endDate[2] || $m < $endDate[1] || $y < $endDate[0]) {
            $slotDay = array();
            foreach ($this->time_range as $time_range) {
                for ($time = $time_range[0]; $time < $time_range[1]; $time += $this->duration) {
                    $slotDay[self::formatTimeInt($time)] = array('n' => 0, 'max' => $this->max_user_per_slot, 'selected' => false);
                }
            }
            $slotTable[self::formatDate($y, $m, $d)] = $slotDay;
            
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
        //*
        
        if ($retriveCount) {
            $command = CDbCommandEx::create()
                    //->select('t1.time, COUNT(t1.user_id) as n, t2.user_id')
                    ->select('t1.time, COUNT(t1.user_id) as n')
                    ->from(TableNames::INTERVIEW_USER_SLOT . ' t1')
                    //->leftJoin(TableNames::INTERVIEW_USER_SLOT . ' t2', '$t2.slot_id = $t1.slot_id AND $t2.time = $t1.time AND $t2.user_id = :user_id')
                    ->where('$t1.slot_id = :slot_id', array('slot_id' => $this->id))
                    ->group('t1.time');
            
            foreach ($command->query() as $row) {
                list($date, $time) = explode(' ', $row['time']);
                if (isset($slotTable[$date]) && isset($slotTable[$date][$time])) {
                    $cell =& $slotTable[$date][$time];
                    $cell['n'] = $row['n'];
                    //if ($row['user_id'] != null) $cell['selected'] = true;
                }

            } // */
        }
        if (is_array($this->options))
            self::mergeTable($slotTable, $this->options);
        
        return $slotTable;
    }
    
    private static function mergeTable(&$a1, $a2) {
        foreach ($a2 as $k => $v) {
            if ($v === NULL) {
                if (isset($a1[$k])) unset($a1[$k]);
            } elseif (is_array($v)) {
                self::mergeTable($a1[$k], $a2[$k]);
            } else {
                $a1[$k] = $a2[$k];
            }
        }
    }
    
    private static function formatDate($y, $m, $d) {
        return sprintf('%04d-%02d-%02d', $y, $m, $d);
    }
    
    private static function formatTime($h, $m, $s) {
        return sprintf('%02d:%02d:%02d', $h, $m, $s);
    }
    
    private static function formatTimeInt($t) {
        $scn = $t % 60;
        $t = (int)($t / 60);
        $mnt = $t % 60;
        $hr = (int)($t / 60);
        return self::formatTime((int)($t / 3600), (int)($t / 60) % 60, $t % 60);
    }
    
    
}

?>
