<?php

/**
 * This is the model class for table "{{phone}}".
 *
 * The followings are the available columns in table '{{phone}}':
 * @property integer $id
 * @property string $phone
 * @property integer $city_id
 * @property integer $template_id
 *
 * The followings are the available model relations:
 * @property City $city
 */
class Phone extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{tpl_city_site}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('phone, city_id, tpl_id, site_id', 'required'),
			array('city_id, tpl_id, site_id', 'numerical', 'integerOnly'=>true),
			array('phone', 'length', 'max'=>60),
            array('phone', 'validatePhone'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, phone, city_id, site_id ,tpl_id', 'safe', 'on'=>'search'),
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
			'city' => array(self::BELONGS_TO, 'City', 'city_id'),
            'site' => array(self::BELONGS_TO, 'Site', 'site_id'),
		);
	}

    /*
     * валидация номера телефона
     */
    public function validatePhone(){
        if(!$this->hasErrors()){
            //формат номера может быть - 8 (4822) 63-32-71  или 8 (482) 263-32-71
            if(!preg_match('/8 \(([1-9]{3,4})\) \d{2,3}-\d{2}-\d{2}/i', $this->phone)){
                $this->addError('phone', 'Номер телефона указан не верно, необходимо указать в правильном формате:8 (xxxx) xx-xx-xx  или 8 (xxx) xxx-xx-xx');
            }
        }
    }

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'phone' => 'Номер телефона подвязанного к городу',
			'city_id' => 'Город по котор. указываем номер телефона',
            'site_id' => 'Сайт',
			'tpl_id' => 'На каких страницах модкс(использующего этот ID шаблона выводить именно этот номер телефона)',
		);

	}

    public function defaultScope(){
        return array(
            //'order'=>'t.phone ASC'
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

        $criteria->with = array('city');
        $criteria->together = true;
		$criteria->compare('t.id',$this->id);
		$criteria->compare('t.phone',$this->phone,true);
		$criteria->compare('t.city_id',$this->city_id);
		$criteria->compare('t.tpl_id',$this->tpl_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

    /*
     * читаем из БД модкс название шаблона по его ID и отображаем
     */
    public function getTemplateNameFromModx(){

        $sql = 'SELECT templatename FROM modx_site_templates WHERE id=:id';

        $query = YiiBase::app()->modx->createCommand($sql);

        $query->bindValue(':id', $this->tpl_id, PDO::PARAM_INT);

        return $query->queryScalar();
    }

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Phone the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    /*
     * ищем номер телефона подвязанного к городу и шаблону
     */
    static function getPhoneByTemplateANDCity($template, $city_id, $site_id){
        $sql = 'SELECT phone FROM {{tpl_city_site}} WHERE city_id=:city_id AND tpl_id=:template_id AND site_id=:site_id';
        $query = YiiBase::app()->db->createCommand($sql);
        $query->bindValue(':template_id', $template, PDO::PARAM_INT);
        $query->bindValue(':city_id', $city_id, PDO::PARAM_INT);
        $query->bindValue(':site_id', $site_id, PDO::PARAM_INT);
        return $query->queryScalar();
    }

    /*
     * получаем список шаблонов из системы Модкс
     */
    static function getTemplatesFromModx(){
        return YiiBase::app()->modx->createCommand('SELECT id,templatename FROM modx_site_templates ORDER BY templatename')->queryAll();
    }

    protected function afterSave()
    {
        parent::afterSave();
        //при обновлении подвязки-шаблона и телефона мог измениться город
        if(!$this->isNewRecord){
            //принудительно укажем, что к данной связке(город+сайт)-подвязан (шаблон+телефон+сайт)
            $query = YiiBase::app()->db->createCommand('UPDATE {{city_site_phone}} SET relation_tpl=1 WHERE city_id=:city_id AND site_id=:site_id');
            $query->bindValue(':city_id', $this->city_id, PDO::PARAM_INT);
            $query->bindValue(':site_id', $this->site_id, PDO::PARAM_INT);
            $query->execute();
        }
    }
}
