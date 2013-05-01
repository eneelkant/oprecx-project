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
    
    private $_recId;
    private $_userId;

    public function initComponent($rec_id, $user_id)
    {
        $this->_recId  = $rec_id;
        $this->_userId = $user_id;
        //$this-> = array('id' => "reg-form-{$rec_id}-{$user_id}");

        $this->_elements = array ();
        $this->_labels   = array ();
        $required        = array ();

        /**
         * @var CDbDataReader sql ini manggil 
         */
        $reader = CDbCommandEx::create()
                //->select('oe.elm_id AS form_id, oe.name AS form_name, fv.value, ff.*')
                ->selectDistinct('oe.elm_id AS form_id, oe.name AS form_name, oe.weight AS oe_weight, fv.value, ff.*')
                ->from(TableNames::FORM_FIELD . ' ff')
                ->join(TableNames::REC_ELM . ' oe', '$oe.elm_id = $ff.form_id AND $oe.rec_id = :rec_id')
                ->join(TableNames::DIVISION_ELM . ' de', '$de.elm_id = $oe.elm_id')
                ->join(TableNames::DIVISION_CHOICE . ' dc', '$dc.div_id = $de.div_id AND $dc.user_id = :user_id')
                ->leftJoin(TableNames::FORM_VALUE . ' fv', '$fv.field_id = $ff.field_id AND $fv.user_id = :user_id')
                
                //->group('ff.field_id') // takut dabel
                ->order('oe.weight, oe.name, ff.weight, ff.created')
                ->query(array (':user_id' => $user_id, ':rec_id'  => $rec_id));


        /** @var array $row */
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
            if ('dropdownlist' === $options['type'] && !isset($options['prompt'])) {
                $options['prompt'] = '<<' . $options['label'] . '>>'; //O::t('oprecx', '- select -');
            }
            if (!isset($options['placeholder']))
                $options['placeholder'] = $options['label'];
            
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
        $db          = O::app()->db;
        $transaction = $db->beginTransaction();
        try {
            foreach ($this->_values as $k => $v) {
                $field_id = substr($k, 6); // field_{$id}
                if ($v[0] && !$v[1]) {
                    $db->createCommand()->insert(TableNames::FORM_VALUE, array (
                        'field_id' => $field_id,
                        'user_id'  => $this->_userId,
                        'value'    => $v[0],
                    ));
                }
                elseif ($v[0] != $v[1]) {
                    $db->createCommand()->update(
                            TableNames::FORM_VALUE,
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
            UserState::invalidate($this->_userId, $this->_recId, 'form');
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
