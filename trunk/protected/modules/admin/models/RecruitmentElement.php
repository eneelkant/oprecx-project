<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CSimpleModel
 * 
 * @author abie
 * @property int $id Description
 * @property string $name Description
 * @property string[] $divList Description
 */
class RecruitmentElement extends CModel
{
    public $data;
    protected $_attributeNames = NULL;
    
    public function attributeNames()
    {
        if (!$this->_attributeNames) $this->_attributeNames = array_keys ($this->data);
        return $this->_attributeNames;
    }
    
    public function __get($name)
    {
        if (array_key_exists($name, $this->data)) {
            return $this->data[$name];
        }
        else
            return parent::__get($name);
    }
    
    public function __set($name, $value)
    {
        if (array_key_exists($name, $this->data)) {
            $this->data[$name] = $value;
        }
        else
            parent::__set($name, $value);
    }
    
    public static function model($class = __CLASS__) {
        return new $class;
    }
    
    /**
     * 
     * @param CDbDataReader $reader
     */
    function populate($reader, $retriveDivList = true)
    {
        $rv = array();
        $class = get_class($this);
        foreach ($reader as $row) {
            if (isset($row['elm_id'])) $row['id'] = $row['elm_id'];
            
            if ($retriveDivList) {
                $row['divList'] = CDbCommandEx::create()
                        ->select('div_id')->from(TableNames::DIVISION_ELM)
                        ->where('$elm_id = :elm_id', array('elm_id' => $row['id']))
                        ->queryColumn();
            }
            
            /** @var CSimpleModel $obj */
            $obj = new $class;
            $obj->data = $row;
            $rv[] = $obj;
        }
        
        return $rv;
    }
}

?>
