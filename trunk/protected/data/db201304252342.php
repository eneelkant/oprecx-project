<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

return array(
    TableNames::USER => array(
        'column' => array(
            'id'         => 'pk',
            'email'      => 'string NOT NULL UNIQUE',
            'password'   => 'string DEFAULT NULL',
            'full_name'  => 'string NOT NULL',
            'img_id'     => 'integer DEFAULT NULL',
            'link'       => 'string DEFAULT NULL',
            'created'    => 'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP',
            'last_login' => 'datetime DEFAULT NULL',
            'updated'    => 'datetime DEFAULT NULL',
        ),
        'index' => array(),
        'foreign' => array(
            array('img_id', TableNames::IMAGE, 'img_id')
        ),
        'data' => array(
            array('email' => 'admin', 'password' => crypt('admin'), 'full_name' => 'Administrator'),
        ),
    ),
    
    TableNames::USER_META => array(
        'column' => array(
            
        ),
        'index' => array(
            'PRIMARY KEY (user_id, meta_name)',
        ),
        'foreign' => array(
            array()
        ),
        'data' => array(
            
        ),
    ),
    
    TableNames::USER_META => array(
        'column' => array(
            
        ),
        'index' => array(
            
        ),
        'foreign' => array(
            
        ),
        'data' => array(
            
        ),
    ),
);