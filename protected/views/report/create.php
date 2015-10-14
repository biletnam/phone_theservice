<?php
/* @var $this CitySitePhoneController */
/* @var $model CitySitePhone */

$this->breadcrumbs=array(
	'City Site Phones'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List CitySitePhone', 'url'=>array('index')),
	array('label'=>'Manage CitySitePhone', 'url'=>array('admin')),
);
?>

<h1>Добавить номер к городу и сайту</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>