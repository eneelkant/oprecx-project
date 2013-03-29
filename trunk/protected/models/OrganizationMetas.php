<?php

/**
 * This is the model class for table "{{organization_metas}}".
 *
 * The followings are the available columns in table '{{organization_metas}}':
 * @property string $meta_id
 * @property string $org_id
 * @property string $meta_name
 * @property string $meta_value
 * @property string $created
 * @property string $updated
 *
 * The followings are the available model relations:
 * @property Organizations $org
 */
class OrganizationMetas extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return OrganizationMetas the static model class
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
		return '{{organization_metas}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('org_id, meta_name, meta_value, created, updated', 'required'),
			array('org_id', 'length', 'max'=>10),
			array('meta_name', 'length', 'max'=>16),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('meta_id, org_id, meta_name, meta_value, created, updated', 'safe', 'on'=>'search'),
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
			'org' => array(self::BELONGS_TO, 'Organizations', 'org_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'meta_id' => 'Meta',
			'org_id' => 'Org',
			'meta_name' => 'Meta Name',
			'meta_value' => 'Meta Value',
			'created' => 'Created',
			'updated' => 'Updated',
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

		$criteria->compare('meta_id',$this->meta_id,true);
		$criteria->compare('org_id',$this->org_id,true);
		$criteria->compare('meta_name',$this->meta_name,true);
		$criteria->compare('meta_value',$this->meta_value,true);
		$criteria->compare('created',$this->created,true);
		$criteria->compare('updated',$this->updated,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}