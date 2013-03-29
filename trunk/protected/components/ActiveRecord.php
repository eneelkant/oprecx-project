<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ActiveRecord
 *
 * @author abie
 */
class ActiveRecord extends CActiveRecord {
    //put your code here
    
    /**
     * 
     * @param int $pk
     * @param mixed $condition
     * @param array $params
     * @return ActiveRecord 
     */
    public function findByPk($pk, $condition = '', $params = array()) {
        /** @var string */
        $cache_name = $this->tableName() . '_item_' . $pk;
        
        /** @var ActiveRecord */
        $item =& Yii::app()->cache->get($cache_name);
        if (false === $item) {
            Yii::app()->cache->set($cache_name, $item =& parent::findByPk($pk, $condition, $params));
        } 
        return $item;
        
    }
}

?>
