<?php
/* @var $this PhoneController */
/* @var $dataProvider CActiveDataProvider */
?>

<?php
$this->breadcrumbs=array(
	'Phones',
);

$this->menu=array(
	array('label'=>'Create Phone','url'=>array('create')),
	array('label'=>'Manage Phone','url'=>array('admin')),
);
?>

<h1>Phones</h1>

<?php $this->widget('bootstrap.widgets.TbListView',array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>