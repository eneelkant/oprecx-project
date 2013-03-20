<?php

/**
 * This is the model class for table "{{division_choices}}".
 *
 * The followings are the available columns in table '{{division_choices}}':
 * @property string $div_id
 * @property string $user_id
 * @property string $choosed
 */
class DivisionChoices extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return DivisionChoices the static model class
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
		return '{{division_choices}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('div_id, user_id, choosed', 'required'),
			array('div_id, user_id', 'length', 'max'=>10),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('div_id, user_id, choosed', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'div_id' => 'Div',
			'user_id' => 'User',
			'choosed' => 'Choosed',
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
		$criteria->compare('user_id',$this->user_id,true);
		$criteria->compare('choosed',$this->choosed,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}