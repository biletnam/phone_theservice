<?php

/**
 * This is the model class for table "{{city}}".
 *
 * The followings are the available columns in table '{{city}}':
 * @property integer $id
 * @property string $city
 * @property integer $parent_id
 * @property integer $main_city
 *
 * The followings are the available model relations:
 * @property CitySitePhone[] $citySitePhones
 * @property TplCitySite[] $tplCitySites
 */
class City extends CActiveRecord
{

    const ACTIVE = 1;
    const NOT_ACTIVE = 0;

    //public $parent_id;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{city}}';
	}

    static function getYesNot($value){
        if($value==0){
            return 'Нет';
        }else{
            return 'Да';
        }
    }

    /*
     * поиск названия города по его ID
     */
    static function findNameTownById($id_city){
        return YiiBase::app()->db->cache(3600)->createCommand('SELECT city FROM {{city}} WHERE id=:id')->bindValue(':id', $id_city,PDO::PARAM_INT)->queryScalar();
    }

    static function findById($city_id){
        return YiiBase::app()->db->cache(3600)->createCommand('SELECT * FROM {{city}} WHERE id=:id')->bindValue(':id', $city_id,PDO::PARAM_INT)->queryRow();
    }

    /*
     * поиск города по его названию
     * array - return
     */
    static function findTownByName($name_city){
        return YiiBase::app()->db->cache(3600)->createCommand('SELECT * FROM {{city}} WHERE city=:city')->bindValue(':city', $name_city, PDO::PARAM_STR)->queryRow();
    }

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
            array('city', 'unique'),
			array('city, main_city', 'required','on'=>'region'),
            //array('city, parent_id', 'required','on'=>'city'),
			array('parent_id, main_city', 'numerical', 'integerOnly'=>true),
			array('city', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, city, parent_id, main_city, region', 'safe', 'on'=>'search'),
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
			'citySitePhones' => array(self::HAS_MANY, 'CitySitePhone', 'city_id'),
			'tplCitySites' => array(self::HAS_MANY, 'TplCitySite', 'city_id'),
            'region' => array(self::BELONGS_TO, 'City', 'parent_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'city' => 'Название города',
			'parent_id' => 'Рег. центр',
			'main_city' => 'Региональный центр или нет',
            'region'=>'Регион'
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

        $criteria->with = array('region');

		$criteria->compare('t.city',$this->city,true);

        $criteria->compare('t.main_city',$this->main_city);

		$criteria->compare('region.city',$this->region, true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return City the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    protected function afterSave()
    {
        parent::afterSave();
        //при создании - если это регион ? - обновим привязку родителя на него же самого
        if($this->isNewRecord){
            if($this->main_city==1){
                $query = YiiBase::app()->db->createCommand('UPDATE {{city}} SET parent_id=:parent_id WHERE id=:id');
                $query->bindValue(':id', $this->id, PDO::PARAM_INT);
                $query->bindValue(':parent_id', $this->id, PDO::PARAM_INT);
                $query->execute();
            }
        }
    }
}

