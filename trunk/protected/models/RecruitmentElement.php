<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class RecElmException extends CException {};

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
    
    public function create() {
        $data = array('name' => '', 'divList' => array());
        foreach ($this->specificAttr as $attrName) {
            $data[$attrName] = '';
        }
        $this->data = $data;
        return $this;
    }
    
    public function encodeAttribute($name, $value) {
        if (is_array($value))
            return serialize ($value);
        else
            return $value;
    }
    
    public function decodeAttribute($name, $value) {
        return $value;
    }


    public function insert($rec_id, $user_id = 0) {
        $db = O::app()->db;
        
        $weight = CDbCommandEx::create($db)
                ->select('MAX(weight) as w')
                ->from($this->_tableName . ' t')
                ->join(TableNames::REC_ELM_as('re'), '$re.elm_id = $t.elm_id')
                ->where('$re.rec_id = :rec_id', array('rec_id' => $rec_id))
                ->queryScalar();
        
        $specificData = array();
        foreach ($this->specificAttr as $v) {
            $specificData[$v] = $this->encodeAttribute($v, $this->data[$v]);
        }
        
        $trans = $db->beginTransaction();
        
        try {
            CDbCommandEx::create($db)->insert(TableNames::REC_ELM, array(
                'name' => $this->name,
                'rec_id' => $rec_id, 
                'weight' => $weight,
            ));
            
            $id = $db->getLastInsertID(TableNames::REC_ELM . '_elm_id_seq');
            $specificData['elm_id'] = $id;
            CDbCommandEx::create()->insert(TableNames::INTERVIEW_SLOT, $specificData);
            
            foreach ($this->divList as $div_id) {
                CDbCommandEx::create()->insert(TableNames::DIVISION_ELM, array('div_id' => $div_id, 'elm_id' => $id));
            }
            
            $trans->commit();
            $this->data['id'] = $id;
            return true;
        }
        catch (Exception $e) {
            $trans->rollback();
            throw $e;
            return false;
        }
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
    
    public function sortOrDelete($rec_id, $newList) {
        $db = O::app()->getDb();
        $transaction = $db->beginTransaction();
        
        try {
            
            $idToDelete = CDbCommandEx::create($db)
                        ->select('t.elm_id')
                        ->from($this->_tableName . ' t')
                        ->join(TableNames::REC_ELM_as('re'), '$re.elm_id = $t.elm_id')
                        ->where(array('AND', 're.rec_id = :rec_id', array('NOT IN', 're.elm_id', $newList)))
                        ->queryColumn(array('rec_id' => $rec_id));
            
            $weight = 0;
            foreach ($newList as $elm_id) {
                
                if (CDbCommandEx::create($db)->update(
                        TableNames::REC_ELM, 
                        array('weight' => $weight),
                        'elm_id = :elm_id AND rec_id = :rec_id',
                        array('elm_id' => $elm_id, 'rec_id' => $rec_id)
                )) {
                    $weight++;
                }   
            }
            
            CDbCommandEx::create($db)->delete(TableNames::REC_ELM, array('IN', 'elm_id', $idToDelete));
            
            $transaction->commit();
            return true;
        }
        catch (CException $e) {
            $transaction->rollback();
            
            throw $e;
        }
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
