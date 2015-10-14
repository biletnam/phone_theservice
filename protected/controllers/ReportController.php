<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 12.12.14
 * Time: 9:27
 */

class ReportController  extends Controller{


    /**
     * @return array action filters
     */
    public function filters()
    {
        return array(
            'accessControl', // perform access control for CRUD operations
            'postOnly + delete', // we only allow deletion via POST request
        );
    }


    /*
     * формируем таблицу для вывода информации о привязках звонков к сайтам, их активности, привязках к шаблонам и т.д.
     */
    public function actionIndex(){

        $model=new CitySitePhone('search');

        $model->unsetAttributes();  // clear any default values

        if(isset($_GET['CitySitePhone'])){
            $model->attributes=$_GET['CitySitePhone'];
        }

        $this->render('table',array(
            'model'=>$model,
        ));

    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate()
    {
        $model=new CitySitePhone;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if(isset($_POST['CitySitePhone']))
        {
            $model->attributes=$_POST['CitySitePhone'];
            if($model->save())
                $this->redirect(array('index'));
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

        if(isset($_POST['CitySitePhone']))
        {
            $model->attributes=$_POST['CitySitePhone'];
            if($model->save())
                $this->redirect(array('index'));
        }

        $this->render('update',array(
            'model'=>$model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return CitySitePhone the loaded model
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model=CitySitePhone::model()->findByPk($id);
        if($model===null)
            throw new CHttpException(404,'The requested page does not exist.');
        return $model;
    }
} 