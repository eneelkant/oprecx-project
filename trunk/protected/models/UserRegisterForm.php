<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of UserRegisterForm
 *
 * @author abie
 */
class UserRegisterForm extends CFormModel {

    public $name;
    public $email;
    public $password;
    public $password2;
    private $_identity;
    
    static $formConfig = array(
        'action' => array('/user/register'),
        'title' => 'Register Account',
        'elements' => array(
            'name' => array('type' => 'text'),
            'email' => array('type' => 'text'),
            'password' => array('type' => 'password'),
            'password2' => array('type' => 'password'),
        ),
        'buttons' => array(
            'register' => array('type' => 'submit', 'label' => 'Register'),
        ),
    );

    public function createForm($form_class = 'CForm', $option = array())
    {
        return new $form_class(array_merge(
                array (
                    'action' => array('/user/register'),
                    'title' => O::t('oprecx', 'Register Account'),
                    'elements' => array(
                        'name' => array('type' => 'text'),
                        'email' => array('type' => 'text'),
                        'password' => array('type' => 'password'),
                        'password2' => array('type' => 'password'),
                    ),
                    'buttons' => array(
                        'register' => array('type' => 'submit', 'label' => O::t('oprecx', 'Register')),
                    ),
                ), 
                $option), $this);
    }


    public function rules() {
        return array(
            array('name, password, password2, email', 'required'),
            array('name', 'length', 'min' => 3, 'max' => 256),
            array('password', 'compare', 'compareAttribute' => 'password2'),
            array('email', 'email'),
            array('password', 'length', 'min' => 4, 'max' => 16),
            array('email', 'unique', 'className' => 'User', 'attributeName' => 'email'),
        );
    }

    public function attributeLabels() {
        return array(
            'name' => O::t('oprecx', 'Full Name'),
            'password2' => O::t('oprecx', 'Retype Password'),
        );
    }

    public function register() {
        if ($this->_identity === null) {
            $this->_identity = new UserIdentity($this->email, $this->password);
            //$this->_identity->authenticate();
        }
        return $this->_identity->register($this->name);
    }

}

?>
