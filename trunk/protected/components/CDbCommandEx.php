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
            $connection = Yii::app()->getDb();
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
        /*
        if (is_array($conditions)) {
            $n = count($conditions);
            if ($n == 2) {
                if (preg_match('/^(|=|<|<=|>|>=|<>)(.*)$/', $conditions[1], $m)) {
                    return $this->processCompare($conditions[0], $m[2], $m[1] == '' ? '=' : $m[1]);
                }
            }
            elseif($n == 3 && in_array($conditions[0], array('=', '<', '<=', '>', '>=', '<>'))) {           
                return $this->processCompare($conditions[1], $conditions[2], $conditions[0]);
            } 
        } else {
            return preg_replace_callback('/\$([\w\d\._]+)/', array($this, 'quoteConditions'), $conditions);
        }
        
        // default
        if(!is_array($conditions))
			return $conditions;
		elseif($conditions===array())
			return '';
		$n=count($conditions);
		$operator=strtoupper($conditions[0]);
		if($operator==='OR' || $operator==='AND')
		{
			$parts=array();
			for($i=1;$i<$n;++$i)
			{
				$condition=$this->processConditions($conditions[$i]);
				if($condition!=='')
					$parts[]='('.$condition.')';
			}
			return $parts===array() ? '' : implode(' '.$operator.' ', $parts);
		}

		if(!isset($conditions[1],$conditions[2]))
			return '';

		$column=$conditions[1];
		if(strpos($column,'(')===false)
			$column=$this->_connection->quoteColumnName($column);

		$values=$conditions[2];
		if(!is_array($values))
			$values=array($values);

		if($operator==='IN' || $operator==='NOT IN')
		{
			if($values===array())
				return $operator==='IN' ? '0=1' : '';
			foreach($values as $i=>$value)
			{
				if(is_string($value))
					$values[$i]=$this->_connection->quoteValue($value);
				else
					$values[$i]=(string)$value;
			}
			return $column.' '.$operator.' ('.implode(', ',$values).')';
		}

		if($operator==='LIKE' || $operator==='NOT LIKE' || $operator==='OR LIKE' || $operator==='OR NOT LIKE')
		{
			if($values===array())
				return $operator==='LIKE' || $operator==='OR LIKE' ? '0=1' : '';

			if($operator==='LIKE' || $operator==='NOT LIKE')
				$andor=' AND ';
			else
			{
				$andor=' OR ';
				$operator=$operator==='OR LIKE' ? 'LIKE' : 'NOT LIKE';
			}
			$expressions=array();
			foreach($values as $value)
				$expressions[]=$column.' '.$operator.' '.$this->_connection->quoteValue($value);
			return implode($andor,$expressions);
		}

		throw new CDbException(Yii::t('yii', 'Unknown operator "{operator}".', array('{operator}'=>$operator)));
         * *
         */
    }
    
    /*
    private function quoteAttr($name) {
        if ($name[0] == '$') {
            return $this->getConnection()->getSchema()->quoteColumnName(substr($name, 1));
        }
        elseif ($name[0] == ':') {
            return $name;
        } else {
            $param_name = ':dbprm_' . $this->param_count++;
            $this->params[$param_name] = $name;
            return $param_name;
        }
    }

    private function processCompare($column, $value, $op) {
        return $this->quoteAttr($column) . $op . $this->quoteAttr($value);
    }
    
    */
}

?>
