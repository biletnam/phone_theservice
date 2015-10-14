<?php
/* @var $this TownController */
/* @var $model Town */

$this->breadcrumbs=array(
	'Towns'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List Town', 'url'=>array('index')),
	array('label'=>'Create Town', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#town-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>




<?php echo CHtml::link('Добавить','town/create',array('class'=>'search-button1')); ?>
<!--<div class="search-form" style="display:none">-->
<?php //$this->renderPartial('_search',array(
//	'model'=>$model,
//)); ?>
<!--</div><!-- search-form -->

<?php $this->widget('bootstrap.widgets.TbGridView', array(
	'id'=>'town-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
    'ajaxUpdate'=>false,
    'template'=>'{summary}{items}{pager}',
    'type' => TbHtml::GRID_TYPE_BORDERED,
	'columns'=>array(
		//'id',
		'town',
		array(
			//'class'=>'CButtonColumn',
            'class'=>'bootstrap.widgets.TbButtonColumn',
            'template'=>'{update}'
		),
	),
)); ?>
