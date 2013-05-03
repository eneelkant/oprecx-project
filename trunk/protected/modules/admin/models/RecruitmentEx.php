<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of OrganizationsForm
 *
 * @author abie
 */
class RecruitmentEx extends Recruitment
{
    var $options;
    private static function sql_datetime_to_normal_time($time) {
        $tmp = explode(' ', $time, 2);
        return $tmp[0];
        //$elms = explode('-', $tmp[0]);
        //return sprintf('%02d/%02d/%04d', $elms[1], $elms[2], $elms[0]);
    }


    public function getRegTime(){
        return self::sql_datetime_to_normal_time($this->reg_time_begin) . ' - ' . self::sql_datetime_to_normal_time($this->reg_time_end);
    }
    
    public function setRegTime($regTime) {
        $dates = explode(' - ', $regTime);
        $this->reg_time_begin = trim($dates[0]);
        $this->reg_time_end = trim($dates[1]);
    }
    
    public function beforeSave()
    {
        $purifier = new CHtmlPurifier;
        $purifier->options = array(
            'HTML.Allowed' => 'p,div,a[href|target],b,i,u,strong,em,ul,ol,li,blockquote,br',
            'HTML.Parent' => 'div',
        );
        
        $this->description = $purifier->purify($this->description);
        return parent::beforeSave();
    }
    
    public function attributeLabels()
    {
        return array (
            'name'           => O::t('oprecx', 'Name'),
            'full_name'      => O::t('oprecx', 'Title'),
            'email'          => O::t('oprecx', 'Email'),
            'description'    => O::t('oprecx', 'Description'),
            'type'           => O::t('oprecx', 'Type'),
            'scope'          => O::t('oprecx', 'Scope'),
            'location'       => O::t('oprecx', 'Location'),
            'link'           => O::t('oprecx', 'Link'),
            'visibility'     => O::t('oprecx', 'Visibility'),
            'regTime'        => O::t('oprecx', 'Registration date range'),
            'div_min'        => O::t('oprecx', 'Minimum Division'),
            'div_max'        => O::t('oprecx', 'Maximum Division'),
        );
    }
    
    public function setAttributes($values, $safeOnly = true)
    {
        parent::setAttributes($values, $safeOnly);
        if (isset($values['regTime'])) {
            $this->setRegTime($values['regTime']);
        }
        if (isset($values['id'])) {
            $this->id = $values['id'];
        }
    }
}

?>
