<?php

/**
 * This is the model class for table "{{organizations}}".
 *
 * The followings are the available columns in table '{{organizations}}':
 * @property string $id
 * @property string $name
 * @property string $full_name
 * @property string $email
 * @property string $password
 * @property string $created
 * @property string $updated
 * @property string $description
 * @property string $type
 * @property string $scope
 * @property string $location
 * @property string $link
 * @property string $img_id
 * @property string $reg_time_begin
 * @property string $reg_time_end
 * @property string $visibility
 *
 * The followings are the available model relations:
 * @property Division[] $divisions
 * @property Forms[] $forms
 * @property OrganizationMetas[] $organizationMetases
 * @property Images $img
 */
class Recruitment extends CActiveRecord
{

    private $_metas;

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Recruitment the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }
    
    private static function getCacheName($name) {
        return 'oprecx:Organizations:name=' . $name;
    }

    public function findByName($name, $cache = TRUE)
    {
        $cache = O::app()->getCache();
        
        if (!$cache || ($obj = $cache->get(self::getCacheName($name))) == false) {
            $attributes = CDbCommandEx::create($this->dbConnection)
                    ->select()
                    ->from($this->tableName())
                    ->where('$name = :org_name', array('org_name' => $name))
                    ->limit(1)
                    ->queryRow();
            
            $obj = $this->populateRecord($attributes);
            if ($cache) $cache->set(self::getCacheName($name), $obj, 60);
        }
        return $obj;
    }
    
    public function invalidateCache($name = NULL) {
        if ($name == NULL) $name = $this->name;
        O::app()->getCache()->delete(self::getCacheName($name));
    }

    public function __construct($scenario = 'insert')
    {
        parent::__construct($scenario);
        $this->_metas = array ();
    }

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return TableNames::RECRUITMENT;
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array (
            array ('full_name, email, description, type, scope, location, reg_time_begin, reg_time_end', 'required'),
            array ('name', 'length', 'max' => 32),
            array ('full_name, email, location, link', 'length', 'max' => 255),
            array ('type, scope, visibility', 'length', 'max' => 16),
            //array ('img_id', 'length', 'max' => 10),
            array ('updated', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array ('id, name, full_name, email, description, type, scope, location, link, reg_time_begin, reg_time_end',
                'safe', 'on' => 'search'),
        );
    }
    
    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array (
            'divisions'           => array (self::HAS_MANY, 'Divisions', 'org_id'),
            //'forms'               => array (self::HAS_MANY, 'Forms', 'org_id'),
            //'organizationMetases' => array (self::HAS_MANY, 'OrganizationMetas', 'org_id'),
            //'img'                 => array (self::BELONGS_TO, 'Images', 'img_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array (
            'id'             => 'ID',
            'name'           => 'Name',
            'full_name'      => 'Full Name',
            'email'          => 'Email',
            'password'       => 'Password',
            'created'        => 'Created',
            'description'    => 'Description',
            'type'           => 'Type',
            'scope'          => 'Scope',
            'location'       => 'Location',
            'link'           => 'Link',
            'img_id'         => 'Img',
            'reg_time_begin' => 'Reg Time Begin',
            'reg_time_end'   => 'Reg Time End',
            'visibility'     => 'Visibility',
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

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id, true);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('full_name', $this->full_name, true);
        $criteria->compare('email', $this->email, true);
        $criteria->compare('password', $this->password, true);
        $criteria->compare('created', $this->created, true);
        $criteria->compare('description', $this->description, true);
        $criteria->compare('type', $this->type, true);
        $criteria->compare('scope', $this->scope, true);
        $criteria->compare('location', $this->location, true);
        $criteria->compare('link', $this->link, true);
        $criteria->compare('img_id', $this->img_id, true);
        $criteria->compare('reg_time_begin', $this->reg_time_begin, true);
        $criteria->compare('reg_time_end', $this->reg_time_end, true);
        $criteria->compare('visibility', $this->visibility, true);

        return new CActiveDataProvider($this, array (
            'criteria' => $criteria,
        ));
    }

}