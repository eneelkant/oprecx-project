<?php

/**
 * This is the model base class for the table "{{users}}".
 * DO NOT MODIFY THIS FILE! It is automatically generated by giix.
 * If any changes are necessary, you must set or override the required
 * property or method in class "Users".
 *
 * Columns in table "{{users}}" available as properties of the model,
 * followed by relations of table "{{users}}" available as properties of the model.
 *
 * @property integer $id
 * @property string $email
 * @property string $password
 * @property string $full_name
 * @property integer $img_id
 * @property string $link
 * @property string $created
 * @property string $last_login
 * @property string $updated
 *
 * @property mixed $oprecxDivisions
 * @property mixed $oprecxFormFields
 * @property InterviewUserSlots[] $interviewUserSlots
 * @property mixed $oprecxOrganizations
 * @property UserMetas[] $userMetases
 * @property Images $img
 */
abstract class BaseUsers extends GxActiveRecord {

	public static function model($className=__CLASS__) {
		return parent::model($className);
	}

	public function tableName() {
		return '{{users}}';
	}

	public static function label($n = 1) {
		return Yii::t('app', 'User|Users', $n);
	}

	public static function representingColumn() {
		return 'email';
	}

	public function rules() {
		return array(
			array('email, full_name, created', 'required'),
			array('img_id', 'numerical', 'integerOnly'=>true),
			array('email, password, full_name, link', 'length', 'max'=>255),
			array('last_login, updated', 'safe'),
			array('password, img_id, link, last_login, updated', 'default', 'setOnEmpty' => true, 'value' => null),
			array('id, email, password, full_name, img_id, link, created, last_login, updated', 'safe', 'on'=>'search'),
		);
	}

	public function relations() {
		return array(
			'oprecxDivisions' => array(self::MANY_MANY, 'Divisions', '{{division_choices}}(user_id, div_id)'),
			'oprecxFormFields' => array(self::MANY_MANY, 'FormFields', '{{form_values}}(user_id, field_id)'),
			'interviewUserSlots' => array(self::HAS_MANY, 'InterviewUserSlots', 'user_id'),
			'oprecxOrganizations' => array(self::MANY_MANY, 'Organizations', '{{org_admins}}(user_id, org_id)'),
			'userMetases' => array(self::HAS_MANY, 'UserMetas', 'user_id'),
			'img' => array(self::BELONGS_TO, 'Images', 'img_id'),
		);
	}

	public function pivotModels() {
		return array(
			'oprecxDivisions' => 'DivisionChoices',
			'oprecxFormFields' => 'FormValues',
			'oprecxOrganizations' => 'OrgAdmins',
		);
	}

	public function attributeLabels() {
		return array(
			'id' => Yii::t('app', 'ID'),
			'email' => Yii::t('app', 'Email'),
			'password' => Yii::t('app', 'Password'),
			'full_name' => Yii::t('app', 'Full Name'),
			'img_id' => null,
			'link' => Yii::t('app', 'Link'),
			'created' => Yii::t('app', 'Created'),
			'last_login' => Yii::t('app', 'Last Login'),
			'updated' => Yii::t('app', 'Updated'),
			'oprecxDivisions' => null,
			'oprecxFormFields' => null,
			'interviewUserSlots' => null,
			'oprecxOrganizations' => null,
			'userMetases' => null,
			'img' => null,
		);
	}

	public function search() {
		$criteria = new CDbCriteria;

		$criteria->compare('id', $this->id);
		$criteria->compare('email', $this->email, true);
		$criteria->compare('password', $this->password, true);
		$criteria->compare('full_name', $this->full_name, true);
		$criteria->compare('img_id', $this->img_id);
		$criteria->compare('link', $this->link, true);
		$criteria->compare('created', $this->created, true);
		$criteria->compare('last_login', $this->last_login, true);
		$criteria->compare('updated', $this->updated, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}
}