<?php

/**
 * This is the model base class for the table "{{interview_slots}}".
 * DO NOT MODIFY THIS FILE! It is automatically generated by giix.
 * If any changes are necessary, you must set or override the required
 * property or method in class "InterviewSlots".
 *
 * Columns in table "{{interview_slots}}" available as properties of the model,
 * followed by relations of table "{{interview_slots}}" available as properties of the model.
 *
 * @property integer $elm_id
 * @property string $description
 * @property integer $duration
 * @property string $start_date
 * @property string $end_date
 * @property string $time_range
 * @property integer $max_user_per_slot
 * @property integer $max_slot_per_user
 * @property integer $min_slot_per_user
 * @property string $options
 *
 * @property OrgElms $elm
 * @property InterviewUserSlots[] $interviewUserSlots
 */
abstract class BaseInterviewSlots extends GxActiveRecord {

	public static function model($className=__CLASS__) {
		return parent::model($className);
	}

	public function tableName() {
		return '{{interview_slots}}';
	}

	public static function label($n = 1) {
		return Yii::t('app', 'InterviewSlots|InterviewSlots', $n);
	}

	public static function representingColumn() {
		return 'start_date';
	}

	public function rules() {
		return array(
			array('start_date, end_date, time_range', 'required'),
			array('duration, max_user_per_slot, max_slot_per_user, min_slot_per_user', 'numerical', 'integerOnly'=>true),
			array('description, options', 'safe'),
			array('description, duration, max_user_per_slot, max_slot_per_user, min_slot_per_user, options', 'default', 'setOnEmpty' => true, 'value' => null),
			array('elm_id, description, duration, start_date, end_date, time_range, max_user_per_slot, max_slot_per_user, min_slot_per_user, options', 'safe', 'on'=>'search'),
		);
	}

	public function relations() {
		return array(
			'elm' => array(self::BELONGS_TO, 'OrgElms', 'elm_id'),
			'interviewUserSlots' => array(self::HAS_MANY, 'InterviewUserSlots', 'slot_id'),
		);
	}

	public function pivotModels() {
		return array(
		);
	}

	public function attributeLabels() {
		return array(
			'elm_id' => null,
			'description' => Yii::t('app', 'Description'),
			'duration' => Yii::t('app', 'Duration'),
			'start_date' => Yii::t('app', 'Start Date'),
			'end_date' => Yii::t('app', 'End Date'),
			'time_range' => Yii::t('app', 'Time Range'),
			'max_user_per_slot' => Yii::t('app', 'Max User Per Slot'),
			'max_slot_per_user' => Yii::t('app', 'Max Slot Per User'),
			'min_slot_per_user' => Yii::t('app', 'Min Slot Per User'),
			'options' => Yii::t('app', 'Options'),
			'elm' => null,
			'interviewUserSlots' => null,
		);
	}

	public function search() {
		$criteria = new CDbCriteria;

		$criteria->compare('elm_id', $this->elm_id);
		$criteria->compare('description', $this->description, true);
		$criteria->compare('duration', $this->duration);
		$criteria->compare('start_date', $this->start_date, true);
		$criteria->compare('end_date', $this->end_date, true);
		$criteria->compare('time_range', $this->time_range, true);
		$criteria->compare('max_user_per_slot', $this->max_user_per_slot);
		$criteria->compare('max_slot_per_user', $this->max_slot_per_user);
		$criteria->compare('min_slot_per_user', $this->min_slot_per_user);
		$criteria->compare('options', $this->options, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}
}