<?php

/**
 * This is the model base class for the table "{{interview_user_slots}}".
 * DO NOT MODIFY THIS FILE! It is automatically generated by giix.
 * If any changes are necessary, you must set or override the required
 * property or method in class "InterviewUserSlots".
 *
 * Columns in table "{{interview_user_slots}}" available as properties of the model,
 * followed by relations of table "{{interview_user_slots}}" available as properties of the model.
 *
 * @property integer $slot_id
 * @property integer $user_id
 * @property string $time
 * @property string $created
 * @property string $updated
 *
 * @property Users $user
 * @property InterviewSlots $slot
 */
abstract class BaseInterviewUserSlots extends GxActiveRecord {

	public static function model($className=__CLASS__) {
		return parent::model($className);
	}

	public function tableName() {
		return '{{interview_user_slots}}';
	}

	public static function label($n = 1) {
		return Yii::t('app', 'InterviewUserSlots|InterviewUserSlots', $n);
	}

	public static function representingColumn() {
		return 'created';
	}

	public function rules() {
		return array(
			array('slot_id, user_id, time, created', 'required'),
			array('slot_id, user_id', 'numerical', 'integerOnly'=>true),
			array('updated', 'safe'),
			array('updated', 'default', 'setOnEmpty' => true, 'value' => null),
			array('slot_id, user_id, time, created, updated', 'safe', 'on'=>'search'),
		);
	}

	public function relations() {
		return array(
			'user' => array(self::BELONGS_TO, 'Users', 'user_id'),
			'slot' => array(self::BELONGS_TO, 'InterviewSlots', 'slot_id'),
		);
	}

	public function pivotModels() {
		return array(
		);
	}

	public function attributeLabels() {
		return array(
			'slot_id' => null,
			'user_id' => null,
			'time' => Yii::t('app', 'Time'),
			'created' => Yii::t('app', 'Created'),
			'updated' => Yii::t('app', 'Updated'),
			'user' => null,
			'slot' => null,
		);
	}

	public function search() {
		$criteria = new CDbCriteria;

		$criteria->compare('slot_id', $this->slot_id);
		$criteria->compare('user_id', $this->user_id);
		$criteria->compare('time', $this->time, true);
		$criteria->compare('created', $this->created, true);
		$criteria->compare('updated', $this->updated, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}
}