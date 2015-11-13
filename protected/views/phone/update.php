<?php
/* @var $this PhoneController */
/* @var $model Phone */
?>

<?php
$this->breadcrumbs=array(
	'Phones'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Phone', 'url'=>array('index')),
	array('label'=>'Create Phone', 'url'=>array('create')),
	array('label'=>'View Phone', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Phone', 'url'=>array('admin')),
);
?>

    <h1>Обновить телефон №<?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>