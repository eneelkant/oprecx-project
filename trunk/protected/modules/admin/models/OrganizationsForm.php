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
class OrganizationsForm extends Organizations
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
    
    public function attributeLabels()
    {
        return array (
            'name'           => 'Name',
            'full_name'      => 'Full Name',
            'email'          => 'Email',
            'description'    => 'Description',
            'type'           => 'Type',
            'scope'          => 'Scope',
            'location'       => 'Location',
            'link'           => 'Link',
            'visibility'     => 'Visibility',
            'regTime' => Yii::t('admin', 'Registration date range'),
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
