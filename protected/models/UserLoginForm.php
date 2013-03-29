<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of UserLoginForm
 *
 * @author abie
 */
class UserLoginForm extends CFormModel {
    
    public $email;
    public $password;
    /** @var UserIdentity */
    private $_identity;
    
    static $formData = array(
        'title' => 'Login',
        'action' => array('/user/login'),
        'elements' => array(
            'email' => array('type' => 'email'),
            'password' => array('type' => 'password'),
        ),
        'buttons' => array(
            'login' => array('type' => 'submit', 'label' => 'Login'),
        ),
    );
    
    public function rules() {
        return array(
            array('password, email', 'required'),
            array('email', 'email'),
            array('password', 'authenticate'),
            
        );
    }
	/**
	 * Authenticates the password.
	 * This is the 'authenticate' validator as declared in rules().
	 */
	public function authenticate($attribute,$params)
	{
		$this->_identity=new UserIdentity($this->email,$this->password);
		if(!$this->_identity->authenticate())
			$this->addError('password','Incorrect email or password.');
	}

	/**
	 * Logs in the user using the given username and password in the model.
	 * @return boolean whether login is successful
	 */
	public function login()
	{
		if($this->_identity===null)
		{
			$this->_identity=new UserIdentity($this->email,$this->password);
			$this->_identity->authenticate();
		}
		return $this->_identity->login();
	}
}

?>
