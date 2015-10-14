<?php
/* @var $this CitySitePhoneController */
/* @var $model CitySitePhone */

$this->breadcrumbs=array(
	'City Site Phones'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List CitySitePhone', 'url'=>array('index')),
	array('label'=>'Create CitySitePhone', 'url'=>array('create')),
	array('label'=>'Update CitySitePhone', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete CitySitePhone', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage CitySitePhone', 'url'=>array('admin')),
);
?>

<h1>View CitySitePhone #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'city_id',
		'site_id',
		'phone',
		'active',
		'relation_tpl',
	),
)); ?>
