<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity {
    private $_id;
    private $email;
    
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
            $this->setState('fullname', $record->full_name);
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
            return true;
        }
        
        return false;
    }
            


    public function register ($fullname) {
        
    }

}

