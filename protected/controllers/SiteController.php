<?php

class SiteController extends Controller
{

    //public $defaultAction = 'index';

    /**
     * @return array action filters
     */
    public function filters()
    {
        return array(
            //'accessControl', // perform access control for CRUD operations
            //'postOnly + delete', // we only allow deletion via POST request
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules()
    {
        return array(
            array('allow',  // allow all users to perform 'index' and 'view' actions
                'actions'=>array('index','sypex','login','logout','error','forjorik'),
                'users'=>array('*'),
            ),
//            array('allow', // allow authenticated user to perform 'create' and 'update' actions
//                //'actions'=>array('index','view','create','update'),
//                'users'=>array('@'),
//            ),
            array('deny',  // deny all users
                'users'=>array('*'),
            ),
        );
    }

	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
//			'captcha'=>array(
//				'class'=>'CCaptchaAction',
//				'backColor'=>0xFFFFFF,
//			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
//			'page'=>array(
//				'class'=>'CViewAction',
//			),
		);
	}

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{

        if(isset($_GET['Request'])){

            $request = new Request();

            $request->attributes = $_GET['Request'];

            if($request->validate()){

                $request->action();

                //возвращаем массив номеров для отображения+список рег. городов с их номерами
                echo CJSON::encode(array('regions'=>$request->regions, 'phones'=>$request->phones));
                //echo '<pre>'; print_r(array('regions'=>$request->regions, 'phones'=>$request->phones));
            }else{
                echo '<pre>'; print_r($request->errors);
            }
        }else{
            echo 'Empty Request array';
        }
	}

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
		if($error=Yii::app()->errorHandler->error)
		{
			if(Yii::app()->request->isAjaxRequest)
				echo $error['message'];
			else
				$this->render('error', $error);
		}
	}



	/**
	 * Displays the login page
	 */
	public function actionLogin()
	{

        if(!YiiBase::app()->user->isGuest){
            $this->redirect('/city/');
        }

		$model=new LoginForm;

		// if it is ajax validation request
		if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}

		// collect user input data
		if(isset($_POST['LoginForm']))
		{
			$model->attributes=$_POST['LoginForm'];
			// validate user input and redirect to the previous page if valid
			if($model->validate() && $model->login())
				$this->redirect(Yii::app()->user->returnUrl);
		}
		// display the login form
		$this->render('login',array('model'=>$model));
	}

	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}

    /*
          * по IP-адресу определяем город пользователя, если не удалось определить - выводим Москва
          */
    public function actionSypex(){

        if(isset($_GET['ip']) && isset($_GET['site_id'])){

            //определяем город по Айпи
            $city = YiiBase::app()->sypexgeo->action($_GET['ip']);

            if(empty($city)){ $city = 'Москва';}

            //заглушка- определить только город по IP
            if(isset($_GET['only_city'])){echo json_encode(array('city'=>$city));die();}

            $city_name = City::findTownByName(trim($city));

            //на основании города/сайта - находим номер телефона
            $phone_query = YiiBase::app()->db->cache(3600)->createCommand('SELECT phone FROM {{city_site_phone}}  WHERE site_id=:site_id AND city_id=:city_id');
            $phone_query->bindValue(':city_id', $city_name['id'], PDO::PARAM_INT);
            $phone_query->bindValue(':site_id', $_GET['site_id'], PDO::PARAM_INT);
            $phone = $phone_query->queryScalar();

            //если НЕ нашли номер телефона по городу, изменим город на Москву и найдём номер
            if(!$phone){
                $city_name = City::findTownByName('Москва');
                $phone_query = YiiBase::app()->db->cache(3600)->createCommand('SELECT phone FROM {{city_site_phone}}  WHERE site_id=:site_id AND city_id=:city_id');
                //$city = 'Москва';
                $phone_query->bindValue(':city_id', $city_name['id'], PDO::PARAM_INT);
                $phone_query->bindValue(':site_id', $_GET['site_id'], PDO::PARAM_INT);
                $phone = $phone_query->queryScalar();
            }

            echo json_encode(array($phone=>$city));
        }
    }


    /*
     * https://seomanager.bitrix24.ru/company/personal/user/9/tasks/task/view/9899/
     */
    public function actionForjorik(){
        if(isset($_GET['ip']) && isset($_GET['site_id'])){

            //Yii::app()->cache->flush();

            //определяем город по Айпи
            $city = YiiBase::app()->sypexgeo->action($_GET['ip']);

            if(empty($city)){ $city = 'Курск';}

            $city_name = City::findTownByName(trim($city));

            if(empty($city_name)){
                $city_name = City::findTownByName('Курск');
                $city = 'Курск';
            }

            //на основании города/сайта - находим номер телефона
            $phone_query = YiiBase::app()->db->createCommand('SELECT phone FROM {{city_site_phone}}  WHERE site_id=:site_id AND city_id=:city_id');
            $phone_query->bindValue(':city_id', $city_name['id'], PDO::PARAM_INT);
            $phone_query->bindValue(':site_id', $_GET['site_id'], PDO::PARAM_INT);
            $phone = $phone_query->queryScalar();

            //если НЕ нашли номер телефона по городу, изменим город на Москву и найдём номер
            if(!$phone){
                $city_name = City::findTownByName('Курск');
                $city = 'Курск';
                $phone_query = YiiBase::app()->db->createCommand('SELECT phone FROM {{city_site_phone}}  WHERE site_id=:site_id AND city_id=:city_id');
                //$city = 'Москва';
                $phone_query->bindValue(':city_id', $city_name['id'], PDO::PARAM_INT);
                $phone_query->bindValue(':site_id', $_GET['site_id'], PDO::PARAM_INT);
                $phone = $phone_query->queryScalar();
            }else{
                $city = $city_name['city'];
            }


            //получаем список активных регионов для списка
            $sql = 'SELECT tbl_city_site_phone.phone, tbl_city.city AS city
                FROM tbl_city_site_phone
                LEFT JOIN tbl_city ON tbl_city_site_phone.city_id = tbl_city.id
                WHERE tbl_city_site_phone.site_id=:site_id AND tbl_city_site_phone.active =:active AND main_city =:main_city
                ORDER BY (city =  "Москва") DESC , (city =  "Санкт-Петербург") DESC , city ASC';

            $query = YiiBase::app()->db->createCommand($sql);

            $query->bindValue(':main_city', City::ACTIVE,PDO::PARAM_INT);
            $query->bindValue(':active', City::ACTIVE,PDO::PARAM_INT);
            $query->bindValue(':site_id', $_GET['site_id'],PDO::PARAM_INT);

            $regions = CHtml::listData($query->queryAll(), 'city','phone');

            //echo json_encode(array($phone=>$city));
            echo CJSON::encode(array('regions'=>$regions, 'phones'=>array($phone=>$city)));

        }
    }


    /*
     * получаем список рег. центров+их номера телефонов  для футера
     * $site_id - по-умолчанию список регионов для зесервиса получаем(ID=1)
     */
    public function actionGetRegList(){

        if(!isset($_GET['site_id'])){$site_id = 1;}else{$site_id = $_GET['site_id'];}

        //если указана метка рекламы - формируем запрос с учётом номеров для рекламы
        if(isset($_GET['utm_source']))
        {
            //метка для гугл-рекламы
            if($_GET['utm_source']=='google')
            {
                $select = 'SELECT (IF(LENGTH( tbl_city_site_phone.google_phone) > 0, tbl_city_site_phone.google_phone, tbl_city_site_phone.phone))as phone, tbl_city.city AS city';

            }elseif($_GET['utm_source']=='direct')
            {
                $select = 'SELECT (IF(LENGTH( tbl_city_site_phone.direct_phone) > 0, tbl_city_site_phone.direct_phone, tbl_city_site_phone.phone))as phone, tbl_city.city AS city';
            }
        }else{
            $select = 'SELECT tbl_city_site_phone.phone, tbl_city.city AS city';
        }

        //получаем список активных регионов для списка
        $sql = $select.'
                FROM tbl_city_site_phone
                LEFT JOIN tbl_city ON tbl_city_site_phone.city_id = tbl_city.id
                WHERE tbl_city_site_phone.site_id=:site_id AND tbl_city_site_phone.active =:active AND main_city =:main_city
                ORDER BY (city =  "Москва") DESC , (city =  "Санкт-Петербург") DESC , city ASC';

        $query = YiiBase::app()->db->cache(3600)->createCommand($sql);

        $query->bindValue(':main_city', City::ACTIVE,PDO::PARAM_INT);
        $query->bindValue(':active', City::ACTIVE,PDO::PARAM_INT);
        $query->bindValue(':site_id', $site_id,PDO::PARAM_INT);

        $regions = CHtml::listData($query->queryAll(), 'city','phone');

        echo CJSON::encode($regions);
    }


    /*
     * https://seomanager.bitrix24.ru/company/personal/user/9/tasks/task/view/10755/
     * Сценарий:
        Если звонок из Москвы - подставляется текст "Москва" и номер 8 495 255-33-85
        Если звонок из городов МО - подставляется название города и номер 74993468924, который куплен в задарма
        Если звонок из городов, к которым куплен номер в задарма, то название города и номер
        Если города нет в списке, то просто номер 8-800-333-85-07
     */
    public function actionSypexAdvanced(){
        if(isset($_GET['ip']) && isset($_GET['site_id'])){

            //определяем город по Айпи
            $city = YiiBase::app()->sypexgeo->action($_GET['ip']);

            //не удалось определить город по IP
            if(empty($city)){ echo '8-800-333-85-07'; die();}

            $city_name = City::findTownByName(trim($city));

            if(empty($city_name)){ echo '8-800-333-85-07'; die();}

            //на основании города/сайта - находим номер телефона
            $phone_query = YiiBase::app()->db->cache(3600)->createCommand('SELECT phone FROM {{city_site_phone}}  WHERE site_id=:site_id AND city_id=:city_id');
            $phone_query->bindValue(':city_id', $city_name['id'], PDO::PARAM_INT);
            $phone_query->bindValue(':site_id', $_GET['site_id'], PDO::PARAM_INT);
            $phone = $phone_query->queryScalar();
            //если нашли номер телефона по городу, изменим город на Москву и найдём номер
            if($phone) {
                echo $city_name['city'].' '.$phone;die();
            }else{
                //НЕ нашли совпадение по городу и сайту
                //ищем совпадение по Московском области, если город попадает в МО
                $city_moscow = City::findTownByName('Москва');
                //есть совпадение по МО
                if($city_name['parent_id']==$city_moscow['id']){
                    $phone_query = YiiBase::app()->db->cache(3600)->createCommand('SELECT phone FROM {{city_site_phone}}  WHERE site_id=:site_id AND city_id=:city_id');
                    $phone_query->bindValue(':city_id', $city_moscow['id'], PDO::PARAM_INT);
                    $phone_query->bindValue(':site_id', $_GET['site_id'], PDO::PARAM_INT);
                    $phone = $phone_query->queryScalar();
                    echo 'Москва '.$phone;die();
                }else{
                    echo '8-800-333-85-07'; die();
                }
            }
        }
        die();
    }

    /*
     * для СУН - список номеров всех активных в системе с городами и сайтами
     */
    public function actionSYNInfo(){
        //$models = CitySitePhone::model()->findAllByAttributes(array('active'=>1, 'main_center'=>1));

        $criteria=new CDbCriteria;
        $criteria->with = array('city','site');
        $criteria->together = true;
        //$criteria->compare('city.main_city',1);
        $criteria->compare('t.active',1);

        $models = CitySitePhone::model()->findAll($criteria);


        if($models){

            $json = array();

            $city = '';

            foreach($models as $model){
                //$json[] = array('phone'=>$model->phone, 'city'=>$model->city->city, 'site'=>$model->site->site);
               //если город основной тогда выводим его название, если нет тогда выводим название рег. центра
               if($model->city->main_city==1){
                   $json[] = array('phone'=>$model->phone, 'city'=>$model->city->city, 'site'=>$model->site->site, 'direct_phone'=>$model->direct_phone, 'google_phone'=>$model->google_phone);
                }else{
                   $json[] = array('phone'=>$model->phone, 'city'=>$model->city->region->city, 'site'=>$model->site->site, 'direct_phone'=>$model->direct_phone, 'google_phone'=>$model->google_phone);
                }
            }

            echo json_encode($json);
        }
    }
}