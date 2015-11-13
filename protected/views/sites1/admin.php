<?php
/* @var $this SitesController */
/* @var $model Site */

$this->breadcrumbs=array(
	'Sites'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List Site', 'url'=>array('index')),
	array('label'=>'Create Site', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#site-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>



<?php echo CHtml::link('Добавить сайт','/sites/create/'); ?>


<?php $this->widget('bootstrap.widgets.TbGridView', array(
	'id'=>'site-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		//'id',
		//'site',
        array(
            'name'=>'site',
            'type'=>'raw',
            'value'=>'CHtml::link("$data->site", array("/sites/update/", "id"=>$data->id))'
        ),
		array(
			'class'=>'CButtonColumn',
            'visible'=>false,
            'template'=>'{update}',
		),
	),
)); ?>
