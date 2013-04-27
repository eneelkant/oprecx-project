<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of OrgInfoForm
 *
 * @author abie
 */
class OrgInfoForm extends CFormModel
{
    var $full_name;
    var $description;
    var $type;
    var $scope;
    var $location;
    var $link;
    var $reg_time_begin;
    var $reg_time_end;
    var $options;
    
    public function attributeLabels()
    {
        return array(
            'full_name' => Yii::t('admin', 'Name'),
            'description' => Yii::t('admin', 'Description'),
            'type' => Yii::t('admin', 'Type'),
            'regTime' => Yii::t('admin', 'Date Range'),
        );
    }
    
    public function getRegTime(){
        return $this->reg_time_begin . ' ' . $this->reg_time_end;
    }
    
}

?>
  id serial NOT NULL,
  name character varying(255) NOT NULL,
  full_name character varying(255) NOT NULL,
  email character varying(255) NOT NULL,
  created timestamp without time zone NOT NULL DEFAULT now(),
  updated timestamp without time zone,
  description text NOT NULL,
  type character varying(255) NOT NULL,
  scope character varying(255) NOT NULL,
  location character varying(255) NOT NULL,
  link character varying(255) NOT NULL,
  img_id integer,
  reg_time_begin timestamp without time zone NOT NULL,
  reg_time_end timestamp without time zone NOT NULL,
  visibility character varying(255) NOT NULL DEFAULT 'public'::character varying,