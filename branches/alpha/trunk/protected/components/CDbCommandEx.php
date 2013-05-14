<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CDbCommandEx
 *
 * @author abie
 */
class CDbCommandEx extends CDbCommand
{
    private $param_count = 0;
    private $_scheme;
    protected $_escapeMark;
    protected $_conditionRE;
    
    
    /**
     * 
     * @param type $connection
     * @param type $escapeMark
     * @return CDbCommandEx
     */
    public static function create($connection = NULL, $escapeMark = '$') {
        if ($connection === NULL) {
            $connection = O::app()->getDb();
        }
        $connection->setActive(true);
        return new CDbCommandEx($connection, NULL, $escapeMark);
    }


    /**
     * 
     * @param CDbConnection $connection
     * @param type $query
     */
    public function __construct($connection, $query = null, $escapeMark = '$')
    {
        parent::__construct($connection, $query);
        $this->_scheme = $connection->getSchema();
        $this->_escapeMark = $escapeMark;
        $this->_conditionRE = "/(^|[^\\{$escapeMark}\\w\\d\\._])\\{$escapeMark}([\\w\\d\\._]+)/";
        //$this->_conditionRE = "/(^|[^\\w\\d\\._])\\{$escapeMark}([\\w\\d\\._]+)/";
    }
    
    /**
     * 
     * @param type $table
     * @param type $conditions
     * @param type $params
     * @return integer Description
     */
    public function delete($table, $conditions = '', $params = array ())
    {
        return parent::delete($table, $this->processConditionsEx($conditions), $params);
    }
    
    /**
     * 
     * @param type $table
     * @param type $columns
     * @param type $conditions
     * @param type $params
     * @return integer
     */
    public function update($table, $columns, $conditions = '', $params = array ())
    {
        return parent::update($table, $columns, $this->processConditionsEx($conditions), $params);
    }
    
    /**
     * 
     * @param type $conditions
     * @param type $params
     * @return CDbCommandEx
     */
    public function where($conditions, $params = array ())
    {
        return parent::where($this->processConditionsEx($conditions), $params);
    }
    
    /**
     * 
     * @param type $table
     * @param type $conditions
     * @param type $params
     * @return CDbCommandEx
     */
    public function join($table, $conditions, $params = array ())
    {
        return parent::join($table, $this->processConditionsEx($conditions), $params);
    }
    
    /**
     * 
     * @param type $table
     * @param type $conditions
     * @param type $params
     * @return CDbCommandEx
     */
    public function leftJoin($table, $conditions, $params = array ())
    {
        return parent::leftJoin($table, $this->processConditionsEx($conditions), $params);
    }

    public function rightJoin($table, $conditions, $params = array ())
    {
        return parent::rightJoin($table, $this->processConditionsEx($conditions), $params);
    }

    public function quoteConditions($m) {
        return $m[1] . $this->_scheme->quoteColumnName($m[2]);
    }
    
    protected function processConditionsEx($conditions) {
        if (is_string($conditions))
            return preg_replace_callback($this->_conditionRE, array($this, 'quoteConditions'), $conditions);
        else 
            return $conditions;
    }
    
}

?>
