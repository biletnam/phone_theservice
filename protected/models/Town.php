<?php

/**
 * This is the model class for table "{{town}}".
 *
 * The followings are the available columns in table '{{town}}':
 * @property integer $id
 * @property string $town
 */
class Town extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{town}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('town', 'required'),
            array('town', 'unique'),
			array('town', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, town', 'safe', 'on'=>'search'),
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
			'id' => 'ID',
			'town' => 'Название города',
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

		$criteria->compare('id',$this->id);
		$criteria->compare('town',$this->town,true);

//		return new CActiveDataProvider($this, array(
//			'criteria'=>$criteria,
//		));

        return new CActiveDataProvider($this,
            array(

                'pagination' => array(
                    'pageSize' => 50,
                ),
                'criteria'=>$criteria,
            ));

	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Town the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    /*
     * поиск города по его названию
     */
    public static function findTownByName($town){
        return YiiBase::app()->db->cache(3600)->createCommand('SELECT * FROM {{town}} WHERE town=:town')->bindValue(':town', $town, PDO::PARAM_STR)->queryRow();
    }

    /*
     * поиск названия города по его ID
     */
    static function findNameTownById($id){
        $sql = 'SELECT town FROM {{town}} WHERE id=:id';
        $query = YiiBase::app()->db->cache(3600)->createCommand($sql);
        $query->bindValue(':id', $id, PDO::PARAM_INT);
        return $query->queryScalar();
    }
}
