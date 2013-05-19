<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Token
 *
 * @author abie
 */
class Token extends CApplicationComponent
{
    public $urandom = '/dev/urandom';
    public $tableName = 'token';
    public $connection = 'db';

    private function randChar($len = 32) {
        if (is_readable($this->urandom)) {
            $rand = file_get_contents($this->urandom, false, null, -1, $len);
        } else {
            $rand = '';
            for($i = 0; $i<$len; ++$i) {
                $rand .= chr(mt_rand(0, 255));
            }
        }
        
        return base64_decode($rand);
    }
    
    /**
     * 
     * @return CDbConnection
     */
    private function getConnection() {
        return Yii::app()->getComponent($this->connection);
    }


    public function register($name, $data = NULL, $ttl = 86400)
    {
        $this->getConnection()->createCommand()->delete($this->tableName, 'name = :name', array('name' => $name));
        
        $token = $this->randChar(32);
        if ($this->getConnection()->createCommand()->insert($this->tableName, array(
            'name' => $name,
            'token' => crypt($name . '#' . $token),
            'data' => serialize($data),
            'expired' => time() + $ttl,
        ))) {
            return $token;
        }   
    }
    
    public function validate($name, $token) {
        $result = $this->getConnection()->createCommand()
                ->select('data')
                ->from($this->tableName)
                ->where(array('AND', 'name = :name', 'token = :token', 'expired > :now'))
                ->limit(1)
                ->queryScalar(array('name' => $name, 'token' => crypt($name . '#' . $token), 'now' => time()));
        if ($result !== FALSE) {
           return unserialize($result);
        }
        else {
            return FALSE;
        }
    }
}

?>
