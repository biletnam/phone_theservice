<?php

class PhoneController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	//public $layout='//layouts/column1';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			//'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
		);
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new Phone;

        if(isset($_GET['Phone']['city_id'])){
            if(!empty($_GET['Phone']['city_id'])){
                $model->city_id = $_GET['Phone']['city_id'];
            }
        }

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if (isset($_POST['Phone'])) {
			$model->attributes=$_POST['Phone'];
            $model->city_id = $_GET['Phone']['city_id'];
			if ($model->save()) {

                //$city = City::model()->findByPk($model->city_id);

                //после добавления связки - номер/шаблон, обновим статус у города, если надо, что он подвязан к шаблону
                $query = YiiBase::app()->db->createCommand('UPDATE tbl_city_site_phone SET relation_tpl=1 WHERE city_id=:city_id AND site_id=:site_id');
                $query->bindValue(':site_id', $model->site_id, PDO::PARAM_INT);
                $query->bindValue(':city_id', $model->city_id, PDO::PARAM_INT);
                $query->execute();

				$this->redirect(array('index','Phone[city_id]'=>$model->city_id));
			}
		}else{
            if (isset($_GET['Phone'])) {
                $model->attributes=$_GET['Phone'];
            }
        }

		$this->render('create',array(
			'model'=>$model,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if (isset($_POST['Phone'])) {
			$model->attributes=$_POST['Phone'];
			if ($model->save()) {
				//$this->redirect(array('view','id'=>$model->id));
                $this->redirect(array('index','Phone[city_id]'=>$model->city_id));
			}
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		if (Yii::app()->request->isPostRequest) {
			// we only allow deletion via POST request
			$model = $this->loadModel($id);

            $city_id = $model->city_id;
            $site_id = $model->site_id;

            $model->delete();

            //после удаления телефона/шаблона, проверим надо ли изменить признак

            $count_query = YiiBase::app()->db->createCommand('SELECT COUNT(id) FROM tbl_tpl_city_site WHERE city_id=:city_id AND site_id=:site_id');
            $count_query->bindValue(':city_id', $city_id, PDO::PARAM_INT);
            $count_query->bindValue('site_id', $site_id, PDO::PARAM_INT);
            $count = $count_query->queryScalar();
            //если нет больше подвязанных номеро/шаблонов изменим статус города, как не подвязанный к шаблону
            if($count==0){
                $query = YiiBase::app()->db->createCommand('UPDATE tbl_city_site_phone SET relation_tpl=0 WHERE city_id=:city_id AND site_id=:site_id');
                $query->bindValue(':city_id',$city_id, PDO::PARAM_INT);
                $query->bindValue(':site_id',$site_id, PDO::PARAM_INT);
                $query->execute();
            }

			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if (!isset($_GET['ajax'])) {
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
			}
		} else {
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
		}
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
        $model=new Phone('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Phone'])) {
            $model->attributes=$_GET['Phone'];
        }

        $this->render('admin',array(
            'model'=>$model,
        ));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Phone('search');
		$model->unsetAttributes();  // clear any default values
		if (isset($_GET['Phone'])) {
			$model->attributes=$_GET['Phone'];
		}

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Phone the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Phone::model()->findByPk($id);
		if ($model===null) {
			throw new CHttpException(404,'The requested page does not exist.');
		}
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Phone $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if (isset($_POST['ajax']) && $_POST['ajax']==='phone-form') {
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}