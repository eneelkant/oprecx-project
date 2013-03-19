<?php

/**
 * This is the model class for table "images".
 *
 * The followings are the available columns in table 'images':
 * @property string $img_id
 * @property string $file_path
 * @property string $file_url
 * @property integer $width
 * @property integer $height
 * @property string $created
 *
 * The followings are the available model relations:
 * @property Organizations[] $organizations
 * @property Users[] $users
 */
class Images extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Images the static model class
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
		return 'images';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('file_path, file_url, width, height, created', 'required'),
			array('width, height', 'numerical', 'integerOnly'=>true),
			array('file_path, file_url', 'length', 'max'=>256),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('img_id, file_path, file_url, width, height, created', 'safe', 'on'=>'search'),
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
			'organizations' => array(self::HAS_MANY, 'Organizations', 'img_id'),
			'users' => array(self::HAS_MANY, 'Users', 'img_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'img_id' => 'Img',
			'file_path' => 'File Path',
			'file_url' => 'File Url',
			'width' => 'Width',
			'height' => 'Height',
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

		$criteria->compare('img_id',$this->img_id,true);
		$criteria->compare('file_path',$this->file_path,true);
		$criteria->compare('file_url',$this->file_url,true);
		$criteria->compare('width',$this->width);
		$criteria->compare('height',$this->height);
		$criteria->compare('created',$this->created,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}