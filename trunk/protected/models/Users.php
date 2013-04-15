<?php

/**
 * This is the model class for table "{{users}}".
 *
 * The followings are the available columns in table '{{users}}':
 * @property string $id
 * @property string $email
 * @property string $password
 * @property string $token
 * @property string $full_name
 * @property string $img_id
 * @property string $link
 * @property string $created
 * @property string $updated
 *
 * The followings are the available model relations:
 * @property Divisions[] $oprecxDivisions
 * @property UserMetas[] $userMetases
 * @property Images $img
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
                return TableNames::USERS;
        }

        /**
         * 
         * @param int $pk
         * @param string|array $condition
         * @param array $params
         * @return type
         */
        public function findByPk($pk, $condition = '', $params = array()) {
            $cache = Yii::app()->cache;
            $cacheId = 'oprecx:User:id=' . $pk;
            if (($obj = $cache->get($cacheId)) === false) {
                $obj = $this->findByAttributes(array('id' => $pk), $condition, $params);
                $depend = new CDbCacheDependency('SELECT updated FROM {{users}} WHERE id=' . $pk . ' LIMIT 1');
                //$depend->params = array(':user_id', $pk);
                $cache->set($cacheId, $obj, 3600, $depend);
            }
            return $obj;
        }
        
        /**
         * 
         * @return array
         */
        public function defaultScope()
        {
            return array (
                'select' => 'id, email, full_name, img_id',
            );
        }
        
        /**
         * @return array validation rules for model attributes.
         */
        public function rules()
        {
                // NOTE: you should only define rules for those attributes that
                // will receive user inputs.
                return array(
                        array('email, full_name, password', 'required'),
                        array('email, full_name, link, password', 'length', 'max'=>255),
                        //array('img_id', 'length', 'max'=>10),
                        //array('updated', 'safe'),
                        // The following rule is used by search().
                        // Please remove those attributes that should not be searched.
                        array('id, email, full_name, link', 'safe', 'on'=>'search'),
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
                        //'oprecxDivisions' => array(self::MANY_MANY, 'Divisions', '{{division_choices}}(user_id, div_id)'),
                        //'userMetases' => array(self::HAS_MANY, 'UserMetas', 'user_id'),
                        //'img' => array(self::BELONGS_TO, 'Images', 'img_id'),
                );
        }

        /**
         * @return array customized attribute labels (name=>label)
         */
        public function attributeLabels()
        {
                return array(
                        'id' => 'ID',
                        'email' => 'Email',
                        'password' => 'Password',
                        'full_name' => 'Full Name',
                        'img_id' => 'Img',
                        'link' => 'Link',
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

                $criteria->compare('id',$this->id,true);
                $criteria->compare('email',$this->email,true);
                $criteria->compare('full_name',$this->full_name,true);
                $criteria->compare('img_id',$this->img_id,true);
                $criteria->compare('link',$this->link,true);

                return new CActiveDataProvider($this, array(
                        'criteria'=>$criteria,
                ));
        }
}
