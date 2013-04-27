<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DbWhere
 *
 * @author abie
 */
class DbWhere
{
    public $scheme;
    public $text = '';


    public static function &create($dbScheme = null) {
        if ($dbScheme == null) {
            $dbScheme = Yii::app()->getDb()->getSchema();
        }
        
        $where = new DbWhere();
        $where->scheme = $dbScheme;
        return $where;
    }
    
    public function __toString()
    {
        return $text;
    }
    
}

?>
