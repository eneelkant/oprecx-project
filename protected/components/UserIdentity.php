<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity {
    private $_id;
    //private $email;
    
    private $authenticated = false;

    /**
     * Authenticates a user.
     * The example implementation makes sure if the username and password
     * are both 'demo'.
     * In practical applications, this should be changed to authenticate
     * against some persistent user identity storage (e.g. database).
     * @return boolean whether authentication succeeds.
     */
    public function authenticate() {
        /** @var Users $record Description */
        $record = Users::model()->findByAttributes(array('email' => $this->username));
        if ($record === null)
            $this->errorCode = self::ERROR_USERNAME_INVALID;
        else if ($record->password !== crypt($this->password, $record->password))
            $this->errorCode = self::ERROR_PASSWORD_INVALID;
        else {
            $this->_id = $record->id;
            //$this->setState('fullname', $record->full_name);
            $this->errorCode = self::ERROR_NONE;
            $this->authenticated = true;
        }
        return !$this->errorCode;
    }

    public function getId() {
        return $this->_id;
    }
    
    public function login() {
        if ($this->errorCode === self::ERROR_NONE) {
            Yii::app()->user->login($this);
            Users::model()->updateByPk($this->id, array('last_login' => new CDbExpression('CURRENT_TIMESTAMP')));
            return true;
        }
        
        return false;
    }
    
    
    public function register ($fullname) {
        $user = new Users();
        $user->full_name = $fullname;
        $user->email = $this->username;
        $user->password = crypt($this->password);
        if ($user->save(false)) {
            $this->_id = $user->getPrimaryKey();
            $this->authenticated = true;
            Yii::app()->user->login($this);
            return true;
        }
        return false;
    }

    public static function getFullName ($id) {
        
        return Users::model()->findByPk($id)->full_name;
        
    }
}

