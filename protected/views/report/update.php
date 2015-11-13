<?php
/* @var $this CitySitePhoneController */
/* @var $model CitySitePhone */

$this->breadcrumbs=array(
	'City Site Phones'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List CitySitePhone', 'url'=>array('index')),
	array('label'=>'Create CitySitePhone', 'url'=>array('create')),
	array('label'=>'View CitySitePhone', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage CitySitePhone', 'url'=>array('admin')),
);
?>

<h1>Редактирование для ID <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>