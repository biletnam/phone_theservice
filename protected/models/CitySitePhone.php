<?php

/**
 * This is the model class for table "{{city_site_phone}}".
 *
 * The followings are the available columns in table '{{city_site_phone}}':
 * @property integer $id
 * @property integer $city_id
 * @property integer $site_id
 * @property string $phone
 * @property integer $active
 * @property integer $relation_tpl
 *
 * The followings are the available model relations:
 * @property Site $site
 * @property City $city
 */
class CitySitePhone extends CActiveRecord
{

    public $region_id;
    public $main_city;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{city_site_phone}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('city_id, site_id, phone, active, relation_tpl', 'required'),

			array('city_id, site_id, active, relation_tpl', 'numerical', 'integerOnly'=>true),

			array('phone, direct_phone, google_phone', 'length', 'max'=>60),


            array('city_id', 'uniqueCity'),

            array('phone, direct_phone, google_phone', 'validatePhone'),

			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, city_id, site_id, phone, active, relation_tpl, region_id, main_city', 'safe', 'on'=>'search'),
		);
	}

    /*
    * проверим на уникальность связку-город-регион-сайт
    */
    public function uniqueCity(){
        if(!$this->hasErrors() && $this->isNewRecord){
            $sql = 'SELECT id FROM '.self::tableName().' WHERE city_id=:city_id AND site_id=:site_id';
            $query = YiiBase::app()->db->createCommand($sql);
            $query->bindValue(':city_id', $this->city_id, PDO::PARAM_INT);
            $query->bindValue(':site_id', $this->site_id, PDO::PARAM_INT);
            $row = $query->queryRow();
            if(!empty($row)){
                $this->addError('city_id','Выбранная вами связка (город-сайт) уже существует');
            }
        }else{
            //print_r($this->getErrors()); die();
        }
    }
    /*
     * валидация номера телефона
     */
    public function validatePhone($attribute,$params){
        if(!$this->hasErrors()){
            if($this->$attribute)
            {
                //формат номера может быть - 8 (4822) 63-32-71  или 8 (482) 263-32-71
                if(!preg_match('/8 \(([0-9]{3,4})\) \d{2,3}-\d{2}-\d{2}/i', $this->$attribute)){
                    $this->addError($attribute, 'Номер телефона указан не верно, необходимо указать в правильном формате:8 (xxxx) xx-xx-xx  или 8 (xxx) xxx-xx-xx');
                }
            }
        }
    }

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'site' => array(self::BELONGS_TO, 'Site', 'site_id'),
			'city' => array(self::BELONGS_TO, 'City', 'city_id'),
		);
	}

    public function getRelationtpl(){

        //ссылка на добавление номеров по шаблону лишь для регионов
        if($this->city->main_city==City::ACTIVE){
            return CHtml::link(City::getYesNot($this->relation_tpl), YiiBase::app()->createAbsoluteUrl('/phone/index', array('Phone[city_id]'=>$this->city_id)));
        }else{
            return City::getYesNot($this->relation_tpl);
        }
        /*if($this->relation_tpl==City::ACTIVE){
            return CHtml::link(City::getYesNot($this->relation_tpl), YiiBase::app()->createAbsoluteUrl('/phone/index', array('Phone[city_id]'=>$this->city_id)));
        }else{
            return City::getYesNot($this->relation_tpl);
        }*/
    }

    /*
     * формирование номера телефона для региона относительно тек. строки-горда в таблице
     */
    public function getRegion_phone(){
        //текущая строка - Регион
        if($this->city->parent_id==$this->city->city){
            return $this->phone;
        }else{
            //поиск по городу и сайту в списке номеров
            return YiiBase::app()->db->createCommand('SELECT phone FROM {{city_site_phone}} WHERE site_id="'.$this->site_id.'" AND city_id="'.$this->city->region->id.'"')->queryScalar();
        }
    }

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'city_id' => 'Город',
			'site_id' => 'Сайт',
			'phone' => 'Телефон',
            'region_id'=>'Регион',
			'active' => 'Активен',
			'relation_tpl' => 'Привязка к шаблону',
            'main_city'=>'Областной центр',
            'region_phone'=>'Регион. телефон',
            'direct_phone'=>'Телефон(я-директ)',
            'google_phone'=>'Телефон(гугл-адводс)',
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
        $criteria->with = array('city','site');
        $criteria->together = true;
        $criteria->compare('t.active',$this->active);
        $criteria->compare('t.relation_tpl',$this->relation_tpl);
        $criteria->compare('t.site_id',$this->site_id);
        $criteria->compare('city.main_city',$this->main_city);
        //==========фильтрация по регулярному выражению или отрацание по регулярке============================
        //проверим и примениним - ФИЛЬТРАЦИЮ по РЕГИОНУ(РЕГУЛЯРКА)
        if(isset($_GET['radio_selected_region']) && isset($_GET['search_word_accept_reg_region'])){
            if(!empty($_GET['radio_selected_region']) && !empty($_GET['search_word_accept_reg_region'])){

                //получаем список регионов, котор. попадают в совпадение по регулярке(ID-список регионов)
                if($_GET['radio_selected_region']=='search_word_accept_region'){//удовлетворяет регулярному выражению
                    $exception_list = YiiBase::app()->db->createCommand('SELECT id FROM {{city}} WHERE city REGEXP "'.$_GET['search_word_accept_reg_region'].'" AND id=parent_id')->queryAll();
                }else{
                    //не удовлетворяет регулярному выражению
                    $exception_list = YiiBase::app()->db->createCommand('SELECT id FROM {{city}} WHERE city NOT REGEXP "'.$_GET['search_word_accept_reg_region'].'" AND id=parent_id')->queryAll();
                }

                if(!empty($exception_list)){

                    $ids = array();

                    foreach($exception_list as $except){$ids[] = $except['id'];}

                    //после получения списка регионов - находим по ним список привязанных городов и формируем ID-list вхождений
                    $list_ids = YiiBase::app()->db->createCommand('SELECT id FROM {{city}} WHERE parent_id IN ('.implode(',', $ids).')')->queryAll();

                    $ids = array();

                    foreach($list_ids as $except){$ids[] = $except['id'];}

                    if(!empty($exception_list)){ $criteria->addInCondition('city_id', $ids);}
                }
            }
        }
        //проверим и примениним - ФИЛЬТРАЦИЮ по ГОРОДУ(РЕГУЛЯРКА)
        if(isset($_GET['radio_selected_city']) && isset($_GET['search_word_accept_reg_city'])){
            if(!empty($_GET['radio_selected_city']) && !empty($_GET['search_word_accept_reg_city'])){
                //2 типа удовлетворяет регулярке или нет по регулярке
                if($_GET['radio_selected_city']=='search_word_accept_city'){//удовлетворяет регулярному выражению
                    $criteria->addCondition('city.city  REGEXP "'.$_GET['search_word_accept_reg_city'].'"');
                }else{
                    //не удовлетворяет регулярному выражению
                    $criteria->addCondition('city.city NOT  REGEXP "'.$_GET['search_word_accept_reg_city'].'"');
                }
            }
        }
        //проверим и примениним - ФИЛЬТРАЦИЮ по Местный телефон(РЕГУЛЯРКА)
        if(isset($_GET['radio_selected_localphone']) && isset($_GET['search_word_accept_reg_localphone'])){
            if(!empty($_GET['radio_selected_localphone']) && !empty($_GET['search_word_accept_reg_localphone'])){
                //2 типа удовлетворяет регулярке или нет по регулярке
                if($_GET['radio_selected_localphone']=='search_word_accept_localphone'){//удовлетворяет регулярному выражению
                    $criteria->addCondition('t.phone  REGEXP "'.$_GET['search_word_accept_reg_localphone'].'"');
                }else{
                    //не удовлетворяет регулярному выражению
                    $criteria->addCondition('t.phone NOT  REGEXP "'.$_GET['search_word_accept_reg_localphone'].'"');
                }
            }
        }

        //проверим и примениним - ФИЛЬТРАЦИЮ по региональный номер телефона(РЕГУЛЯРКА)
        if(isset($_GET['radio_selected_regionphone']) && isset($_GET['search_word_accept_reg_regionphone'])){
            if(!empty($_GET['radio_selected_regionphone']) && !empty($_GET['search_word_accept_reg_regionphone'])){

                //полчаем список ID-регионов, по которым есть воспадение в номере рег. выражения
                if($_GET['radio_selected_regionphone']=='search_word_accept_regionphone'){//удовлетворяет регулярному выражению
                    $exception_list = YiiBase::app()->db->createCommand('SELECT distinct(city_id) FROM {{city_site_phone}} WHERE phone REGEXP "'.$_GET['search_word_accept_reg_regionphone'].'" AND active=1')->queryAll();
                }else{
                    //не удовлетворяет регулярному выражению
                    $exception_list = YiiBase::app()->db->createCommand('SELECT distinct(city_id) FROM {{city_site_phone}} WHERE phone NOT REGEXP "'.$_GET['search_word_accept_reg_regionphone'].'" AND active=1')->queryAll();
                }

                if(!empty($exception_list)){
                    $ids = array();

                    foreach($exception_list as $except){$ids[] = $except['city_id'];}

                    //поиск регионов по найденным совпадениям
                    $inner_ids = YiiBase::app()->db->createCommand('SELECT id FROM {{city}} WHERE main_city=1 AND id IN('.implode(',', $ids).')')->queryAll();

                    if(!empty($inner_ids)){
                        $in_ids = array();
                        foreach($inner_ids as $id){
                            $in_ids[] = $id['id'];
                        }
                        $criteria->addInCondition('city_id', $in_ids);
                    }
                }
            }
        }

        $sort = new CSort();

        $sort->defaultOrder = 'city.city asc'; // устанавливаем сортировку по умолчанию

        $sort->attributes['city_id'] = array('asc' => 'city.city','desc' => 'city.city desc');

        //$sort->attributes['region_id'] = array('asc' => 'city.region.city','desc' => 'city.region.city desc');

        $sort->attributes['main_city'] = array('asc' => 'city.main_city','desc' => 'city.main_city desc');

        $sort->attributes['active'] = array('asc' => 'active','desc' => 'active desc');

        $sort->attributes['site_id'] = array('asc' => 'site.site','desc' => 'site.site desc',);

        $sort->attributes['phone'] = array('asc' => 'phone','desc' => 'phone desc');

        $sort->attributes['relation_tpl'] = array('asc' => 'relation_tpl','desc' => 'relation_tpl desc');

        return new CActiveDataProvider($this,
            array(
                'pagination' => array('pageSize' => 50),
                'criteria'=>$criteria,
                'sort' => $sort
            )
        );
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return CitySitePhone the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
