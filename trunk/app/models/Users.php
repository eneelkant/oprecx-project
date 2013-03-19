<?php

/**
 * This is the model class for table "users".
 *
 * The followings are the available columns in table 'users':
 * @property string $user_id
 * @property string $name
 * @property string $sex
 * @property string $photo
 * @property string $about
 * @property string $created
 *
 * The followings are the available model relations:
 * @property FieldValues[] $fieldValues
 * @property InterviewTime[] $interviewTimes
 * @property TaskFiles[] $taskFiles
 * @property UserAuth[] $userAuths
 * @property DivisionItems[] $divisionItems
 * @property UserMetas[] $userMetases
 */
class Users extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Users the static model class
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
		return 'users';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, sex, photo, about, created', 'required'),
			array('name, photo', 'length', 'max'=>256),
			array('sex', 'length', 'max'=>1),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('user_id, name, sex, photo, about, created', 'safe', 'on'=>'search'),
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
			'fieldValues' => array(self::HAS_MANY, 'FieldValues', 'user_id'),
			'interviewTimes' => array(self::HAS_MANY, 'InterviewTime', 'user_id'),
			'taskFiles' => array(self::HAS_MANY, 'TaskFiles', 'user_id'),
			'userAuths' => array(self::HAS_MANY, 'UserAuth', 'user_id'),
			'divisionItems' => array(self::MANY_MANY, 'DivisionItems', 'user_division_choices(user_id, div_id)'),
			'userMetases' => array(self::HAS_MANY, 'UserMetas', 'user_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'user_id' => 'User',
			'name' => 'Name',
			'sex' => 'Sex',
			'photo' => 'Photo',
			'about' => 'About',
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

		$criteria->compare('user_id',$this->user_id,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('sex',$this->sex,true);
		$criteria->compare('photo',$this->photo,true);
		$criteria->compare('about',$this->about,true);
		$criteria->compare('created',$this->created,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}