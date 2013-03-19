<?php

/**
 * This is the model class for table "forms".
 *
 * The followings are the available columns in table 'forms':
 * @property string $form_id
 * @property string $org_id
 * @property string $name
 * @property integer $weight
 * @property string $created
 *
 * The followings are the available model relations:
 * @property Divisions[] $divisions
 * @property Organizations $org
 */
class Forms extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Forms the static model class
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
		return 'forms';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('form_id, org_id, name, created', 'required'),
			array('weight', 'numerical', 'integerOnly'=>true),
			array('form_id, org_id', 'length', 'max'=>10),
			array('name', 'length', 'max'=>64),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('form_id, org_id, name, weight, created', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'divisions' => array(self::MANY_MANY, 'Divisions', 'division_forms(form_id, div_id)'),
			'org' => array(self::BELONGS_TO, 'Organizations', 'org_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'form_id' => 'Form',
			'org_id' => 'Org',
			'name' => 'Name',
			'weight' => 'Weight',
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

		$criteria->compare('form_id',$this->form_id,true);
		$criteria->compare('org_id',$this->org_id,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('weight',$this->weight);
		$criteria->compare('created',$this->created,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}