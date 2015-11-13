<?php
/* @var $this CitySitePhoneController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'City Site Phones',
);

$this->menu=array(
	array('label'=>'Create CitySitePhone', 'url'=>array('create')),
	array('label'=>'Manage CitySitePhone', 'url'=>array('admin')),
);
?>

<h1>City Site Phones</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
