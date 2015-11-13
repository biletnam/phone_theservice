<?php
/* @var $this CityController */
/* @var $model City */
?>

<?php
$this->breadcrumbs=array(
	'Cities'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List City', 'url'=>array('index')),
	array('label'=>'Create City', 'url'=>array('create')),
	array('label'=>'View City', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage City', 'url'=>array('admin')),
);
?>

    <h1>Редактирование города №<?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>