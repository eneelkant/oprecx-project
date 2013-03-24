<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of RegistrationFrom
 *
 * @author abie
 */
class RegistrationForm extends CFormModel {

    public $name;
    public $email;
    public $password;
    public $password2;
    
    static $formData = array(
        'title' => '<h3>Register</h3>',
        'elements' => array(
            'name' => array('type' => 'text'),
            'email' => array('type' => 'text'),
            'password' => array('type' => 'password'),
            'password2' => array('type' => 'password'),
        ),
        'buttons' => array(
            'register' => array('type' => 'submit', 'label' => 'Daftar', 'attributes' => array('data-theme' => 'b')),
        ),
    );

    public function rules() {
        return array(
            array('name, password, password2, email', 'required'),
            array('name', 'length', 'min' => 3, 'max' => 256),
            array('password', 'compare', 'compareAttribute' => 'password2'),
            array('email', 'email'),
            array('password', 'length', 'min' => 4, 'max' => 16),
            array('email', 'unique', 'className' => 'Users', 'attributeName' => 'email'),
        );
    }

    public function attributeLabels() {
        return array(
            'name' => Yii::t('oprecx', 'Full Name'),
            'password2' => Yii::t('oprecx', 'Retype Password'),
        );
    }

}

?>
