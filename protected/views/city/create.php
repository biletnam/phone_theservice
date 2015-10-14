<?php
/* @var $this CityController */
/* @var $model City */
?>

<?php
$this->breadcrumbs=array(
	'Cities'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List City', 'url'=>array('index')),
	array('label'=>'Manage City', 'url'=>array('admin')),
);
?>

<h1>Добавить город</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>