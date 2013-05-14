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
abstract class RecruitmentElement extends CModel
{
    public $data;
    public $specificAttr = array();
    private $_attributeNamesFull = NULL;
    protected $_tableName;
    
    public function attributeNames()
    {
        if ($this->_attributeNamesFull) return $this->_attributeNamesFull;
        $this->_attributeNamesFull = array_merge(array('id', 'name', 'divList'), $this->specificAttr);
        return $this->_attributeNamesFull;
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
    
    public function findAllByRecId($rec_id) {
        $reader = CDbCommandEx::create()
                ->select('*')
                ->from($this->_tableName . ' t')
                ->join(TableNames::REC_ELM_as('re'), '$re.elm_id = $t.elm_id')
                ->where('$re.rec_id = :rec_id', array('rec_id' => $rec_id))
                ->order('re.weight, re.name')
                ->query();
        
        $rv = array();
        foreach($reader as $row) {
            $rv[] = $this->populateItem($row);
        }
        $reader->close();
        
        return $rv;
    }

    public function findById($id) {
        $row = CDbCommandEx::create()
                ->select('*')
                ->from($this->_tableName . ' t')
                ->join(TableNames::REC_ELM_as('re'), '$re.elm_id = $t.elm_id')
                ->where('$t.elm_id = :elm_id', array('elm_id' => $id))
                ->limit(1)
                ->queryRow();
        return $row ? $this->populateItem($row) : NULL;
    }

    /**
     * 
     * @param CDbDataReader $reader
     */
    private function populateItem($data, $retriveDivList = true)
    {
        if (isset($data['elm_id'])) $data['id'] = $data['elm_id'];

        if ($retriveDivList) {
            $data['divList'] = CDbCommandEx::create()
                    ->select('div_id')->from(TableNames::DIVISION_ELM)
                    ->where('$elm_id = :elm_id', array('elm_id' => $data['id']))
                    ->queryColumn();
        }

        /* @var $obj CSimpleModel */
        $class = get_class($this);
        $obj = new $class;
        $obj->data = $data;
        return $obj->afterPopulateItem();
        
    }
    
    protected function afterPopulateItem(){
        return $this;
    }


}

?>
