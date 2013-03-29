<?php

/**
 * This is the model class for table "{{form_fields}}".
 *
 * The followings are the available columns in table '{{form_fields}}':
 * @property string $field_id
 * @property string $form_id
 * @property string $name
 * @property string $type
 * @property string $desc
 * @property integer $weight
 * @property integer $required
 * @property string $options
 * @property string $created
 *
 * The followings are the available model relations:
 * @property Forms $form
 * @property FormValues[] $formValues
 */
class FormFields extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return FormFields the static model class
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
		return '{{form_fields}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('form_id, name, type, desc, created', 'required'),
			array('weight, required', 'numerical', 'integerOnly'=>true),
			array('form_id', 'length', 'max'=>10),
			array('name', 'length', 'max'=>256),
			array('type', 'length', 'max'=>32),
			array('options', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('field_id, form_id, name, type, desc, weight, required, options, created', 'safe', 'on'=>'search'),
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
			'form' => array(self::BELONGS_TO, 'Forms', 'form_id'),
			'formValues' => array(self::HAS_MANY, 'FormValues', 'field_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'field_id' => 'Field',
			'form_id' => 'Form',
			'name' => 'Name',
			'type' => 'Type',
			'desc' => 'Desc',
			'weight' => 'Weight',
			'required' => 'Required',
			'options' => 'Options',
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

		$criteria->compare('field_id',$this->field_id,true);
		$criteria->compare('form_id',$this->form_id,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('type',$this->type,true);
		$criteria->compare('desc',$this->desc,true);
		$criteria->compare('weight',$this->weight);
		$criteria->compare('required',$this->required);
		$criteria->compare('options',$this->options,true);
		$criteria->compare('created',$this->created,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}