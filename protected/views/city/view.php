<?php
/* @var $this CityController */
/* @var $model City */
?>

<?php
$this->breadcrumbs=array(
	'Cities'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List City', 'url'=>array('index')),
	array('label'=>'Create City', 'url'=>array('create')),
	array('label'=>'Update City', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete City', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage City', 'url'=>array('admin')),
);
?>

<h1>Информация о городе #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView',array(
    'htmlOptions' => array(
        'class' => 'table table-striped table-condensed table-hover',
    ),
    'data'=>$model,
    'attributes'=>array(
		'id',
		'city',
		'region_id',
		'main_center',
		'relation_tpl',
		'active',
	),
)); ?>