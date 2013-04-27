<?php

/**
 * This is the model class for table "{{divisions}}".
 *
 * The followings are the available columns in table '{{divisions}}':
 * @property string $div_id
 * @property string $org_id
 * @property string $name
 * @property string $description
 * @property string $leader
 * @property integer $max_applicant
 * @property integer $max_staff
 * @property integer $min_staff
 * @property integer $enabled
 * @property string $created
 *
 * The followings are the available model relations:
 * @property Users[] $oprecxUsers
 * @property Forms[] $oprecxForms
 * @property Organizations $org
 */
class Divisions extends CActiveRecord
{
        /**
         * Returns the static model of the specified AR class.
         * @param string $className active record class name.
         * @return Divisions the static model class
         */
        public static function model($className=__CLASS__)
        {
                return parent::model($className);
        }

        /**
         * @return string the associated database table name
         */
        public function tableName()
        {
                return TableNames::DIVISIONS;
        }

        /**
         * @return array validation rules for model attributes.
         */
        public function rules()
        {
                // NOTE: you should only define rules for those attributes that
                // will receive user inputs.
                return array(
                        array('org_id, name, created', 'required'),
                        array('max_applicant, max_staff, min_staff, enabled', 'numerical', 'integerOnly'=>true),
                        array('org_id', 'length', 'max'=>10),
                        array('name', 'length', 'max'=>128),
                        array('leader', 'length', 'max'=>64),
                        array('description', 'safe'),
                        // The following rule is used by search().
                        // Please remove those attributes that should not be searched.
                        array('div_id, org_id, name, description, leader, max_applicant, max_staff, min_staff, enabled, created', 'safe', 'on'=>'search'),
                );
        }
        
        /**
         * 
         * @param int $orgId
         * @param string $conditions
         * @param array $params
         * @return Divisions[]
         */
        public function findAllByOrg($orgId, $conditions = '', $params = array()) {
            $depend = new CDbCacheDependency;
            $depend->sql = 'SELECT updated FROM ' . TableNames::ORGANIZATIONS . ' WHERE id = :org_id LIMIT 1';
            $depend->params = array('org_id' => $orgId);
            $criteria = new CDbCriteria();
            $criteria->select = '*';
            $criteria->compare('org_id', $orgId);
            $criteria->order = 'weight, name';            
            return $this->query($criteria, TRUE);
        }
        
        /**
         * @return array relational rules.
         */
        public function relations()
        {
                // NOTE: you may need to adjust the relation name and the related
                // class name for the relations automatically generated below.
                return array(
                        //'oprecxUsers' => array(self::MANY_MANY, 'Users', '{{division_choices}}(div_id, user_id)'),
                        //'oprecxForms' => array(self::MANY_MANY, 'Forms', '{{division_forms}}(div_id, form_id)'),
                        //'choices' => array(self::HAS_MANY, 'DivisionChoices', 'div_id'),
                        //'org' => array(self::BELONGS_TO, 'Organizations', 'org_id'),
                );
        }

        /**
         * @return array customized attribute labels (name=>label)
         */
        public function attributeLabels()
        {
                return array(
                        'div_id' => 'Div',
                        'org_id' => 'Org',
                        'name' => 'Name',
                        'description' => 'Description',
                        'leader' => 'Leader',
                        'max_applicant' => 'Max Applicant',
                        'max_staff' => 'Max Staff',
                        'min_staff' => 'Min Staff',
                        'enabled' => 'Enabled',
                        'created' => 'Created',
                );
        }

        /**
         * Retrieves a list of models based on the current search/filter conditions.
         * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
         */
        public function search()
        {
                // Warning: Please modify the following code to remove attributes that
                // should not be searched.

                $criteria=new CDbCriteria;

                $criteria->compare('div_id',$this->div_id,true);
                $criteria->compare('org_id',$this->org_id,true);
                $criteria->compare('name',$this->name,true);
                $criteria->compare('description',$this->description,true);
                $criteria->compare('leader',$this->leader,true);
                $criteria->compare('max_applicant',$this->max_applicant);
                $criteria->compare('max_staff',$this->max_staff);
                $criteria->compare('min_staff',$this->min_staff);
                $criteria->compare('enabled',$this->enabled);
                $criteria->compare('created',$this->created,true);

                return new CActiveDataProvider($this, array(
                        'criteria'=>$criteria,
                ));
        }
}