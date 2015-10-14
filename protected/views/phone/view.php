<?php
/* @var $this PhoneController */
/* @var $model Phone */
?>

<?php
$this->breadcrumbs=array(
	'Phones'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List Phone', 'url'=>array('index')),
	array('label'=>'Create Phone', 'url'=>array('create')),
	array('label'=>'Update Phone', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Phone', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Phone', 'url'=>array('admin')),
);
?>

<h1>View Phone #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView',array(
    'htmlOptions' => array(
        'class' => 'table table-striped table-condensed table-hover',
    ),
    'data'=>$model,
    'attributes'=>array(
		'id',
		'phone',
		'city_id',
		'template_id',
	),
)); ?>