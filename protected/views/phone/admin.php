<?php
/* @var $this PhoneController */
/* @var $model Phone */



$this->menu=array(
	array('label'=>'List Phone', 'url'=>array('index')),
	array('label'=>'Create Phone', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#phone-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>
<h2><?php echo 'Город:'.$model->city->city;?></h2>
<?php
    //echo CHtml::link('Advanced Search','#',array('class'=>'search-button btn'));
    echo CHtml::link('Добавить',YiiBase::app()->createAbsoluteUrl('phone/create', array('Phone[city_id]'=>$_GET['Phone']['city_id'])));
?>


<?php $this->widget('bootstrap.widgets.TbGridView',array(
	'id'=>'phone-grid',
    'dataProvider'=>$model->search(),
    //'filter'=>$model,

    'type' => TbHtml::GRID_TYPE_BORDERED,
	'columns'=>array(
		//'id',
		//'phone',
        array(
            'name'=>'phone',
            'value'=>'$data->phone',
            'header'=>'Телефон',
        ),
		//'city_id',
        array(
            'name'=>'city_id',
            'header'=>'Город',
            'type'=>'raw',
            'value'=>'$data->city->city',

        ),
		//'template_id',
        array(
            'name'=>'template_id',
            'value'=>'$data->TemplateNameFromModx',
            'header'=>'Шаблон',
        ),
        array(
            'name'=>'site_id',
            'value'=>'$data->site->site',
            'header'=>'Сайт',
        ),
		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
            //'visible'=>false,
            'template'=>'{update}{delete}'
		),
	),
)); ?>