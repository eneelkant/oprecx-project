<?php


class NewSlotForm extends CFormModel
{
    public $name, $description, $dateRange, $timeRanges, $duration, $defaultMax, $divList;
    private $_slot, $_normalizedTimeRanges;
    
    
    public function rules()
    {
        return array(
            array ('name, dateRange, timeRanges, duration, defaultMax', 'required'),
            array ('name', 'length', 'max' => 255),
            array ('description', 'length', 'max' => 4096),
            array ('defaultMax, duration', 'numerical'),
            array ('dateRange', 'validateDataRange'),
            array ('timeRanges', 'validateTimeRanges'),
            array('name, description, dateRange, timeRanges, duration, defaultMax, divList', 'safe'),
        );
    }
    
    public function getId() {
        return $this->getSlot()->id;
    }


    /**
     * 
     * @return InterviewSlot
     */
    public function getSlot() {
        if ($this->_slot) return $this->_slot;
        return $this->_slot = InterviewSlot::model()->create();
    }


    public function attributeLabels()
    {
        return array(
            'name' => O::t('oprecx', 'Name'),
            'description' => O::t('oprecx', 'Description'),
            'dateRange' => O::t('oprecx', 'Data Range'),
            'timeRanges' => O::t('oprecx', 'Time Ranges'),
            'defaultMax' => O::t('oprecx', 'Default Max'),
            'duration' => O::t('oprecx', 'Interview Duration'),
            'divList' => O::t('oprecx', 'For Divisions'),
        );
    }
    
    public function validateDataRange($attribute, $param) {
        
        //list($startDate, $endDate)
    }
    
    private static function timeToInt($time) {
        $parts = explode(':', $time);
        
        if (count($parts) === 3) {
            $t = 0;
            $factor = 1;
            for($i = 2; $i >= 0; $i--) {
                $n = intval($parts[$i]);
                $t += $n * $factor;
                $factor *= 60;
            }
            
            return $t;
        }
        return false;
    }
    
    public function validateTimeRanges($attribute, $param) {
        $timeRange = $this->timeRanges;
        $normalized = array();
        for ($i = 0; $i < count($timeRange); $i += 2) {
            $a = self::timeToInt($timeRange[$i]);
            $b = self::timeToInt($timeRange[$i + 1]);
            $normalized[] = array($a, $b);
        }
        $this->_normalizedTimeRanges = $normalized;
    }
    
    public function submit($rec_id, $user_id = 0) {
        $slot = $this->getSlot();
        $slot->name = $this->name;
        $slot->description = $this->description;
        $slot->duration = intval($this->duration);
        $slot->max_user_per_slot = intval($this->defaultMax);
        list($slot->start_date, $slot->end_date) = explode(' - ', $this->dateRange);
        $slot->time_range = $this->_normalizedTimeRanges;
        $slot->options = array();
        
        $slot->divList = $this->divList;
        
        return $slot->insert($rec_id, $user_id);
    }
}