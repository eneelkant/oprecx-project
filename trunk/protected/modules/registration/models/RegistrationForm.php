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
class RegistrationForm extends CFormModel
{

    private $_labels   = array ();
    private $_elements = array ();
    private $_rules    = array ();
    private $_values   = array ();
    
    private $_orgId;
    private $_userId;

    public function initComponent($org_id, $user_id)
    {
        $this->_orgId  = $org_id;
        $this->_userId = $user_id;
        //$this-> = array('id' => "reg-form-{$org_id}-{$user_id}");

        $this->_elements = array ();
        $this->_labels   = array ();
        $required        = array ();

        /**
         * @var CDbDataReader sql ini manggil 
         */
        $reader = Yii::app()->db->createCommand()
                ->select('oe.elm_id AS form_id, oe.name AS form_name, fv.value, ff.*')
                
                ->from('{{form_fields}} ff')
                ->join('{{org_elms}} oe', 'oe.elm_id = ff.form_id AND oe.org_id = :org_id')
                ->join('{{division_elms}} de', 'de.elm_id = oe.elm_id')
                ->join('{{division_choices}} dc', 'dc.div_id = de.div_id AND dc.user_id = :user_id')
                ->leftJoin('{{form_values}} fv', 'fv.field_id = ff.field_id AND fv.user_id = :user_id')
                
                ->group('oe.elm_id, oe.name, fv.value, ff.field_id') // takut dabel
                ->order('oe.weight, oe.name, ff.weight, ff.created')
                ->query(array (':user_id' => $user_id, ':org_id'  => $org_id));


        foreach ($reader as $row) {
            $options = unserialize($row['options']);
            if (!is_array($options)) 
                $options = array ();
            
            $name    = 'field_' . $row['field_id'];

            $options['type']     = $row['type'];
            $options['label']    = $row['name'];
            $options['hint']     = $row['desc'];
            $options['required'] = $row['required'];
            $options['visible']  = true;
            
            $this->_values[$name]   = array ($row['value'], $row['value']);
            $this->_elements[$name] = $options;
            $this->_labels[$name]   = $row['name'];
            if ($row['required']) {
                $required[]         = $name;
            }
        }

        if (count($required)) $this->_rules[] = array (implode(', ', $required), 'required');
    }

    public function &getElements()
    {
        return $this->_elements;
    }

    public function save()
    {
        $db          = Yii::app()->db;
        $transaction = $db->beginTransaction();
        try {
            foreach ($this->_values as $k => $v) {
                $field_id = substr($k, 6);
                if ($v[0] && !$v[1]) {
                    $db->createCommand()->insert('{{form_values}}', array (
                        'field_id' => $field_id,
                        'user_id'  => $this->_userId,
                        'value'    => $v[0],
                    ));
                }
                elseif ($v[0] != $v[1]) {
                    $db->createCommand()->update(
                            '{{form_values}}',
                            array (
                                'value'   => $v[0], 
                                'updated' => new CDbExpression('CURRENT_TIMESTAMP')
                            ),
                            'field_id = :field_id AND user_id = :user_id',
                            array ('field_id' => $field_id, 'user_id'  => $this->_userId)
                    );
                }

            }

            $transaction->commit();
            return true;
        }
        catch (Exception $e) {
            $transaction->rollback();
            $this->addErrors($e);
            return false;
        }
    }

    public function __get($name)
    {
        if (isset($this->_values[$name])) {
            return $this->_values[$name][0];
        }
        return parent::__get($name);
    }

    public function __set($name, $value)
    {
        if (isset($this->_values[$name])) {
            $this->_values[$name][0] = $value;
        }
        else parent::__set($name, $value);
    }

    public function rules()
    {
        return $this->_rules;
    }

    public function attributeLabels()
    {
        return $this->_labels;
    }

}

?>
