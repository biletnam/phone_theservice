<?php
/* @var $this CityController */
/* @var $model City */




$this->menu=array(
	array('label'=>'Города', 'url'=>array('index')),
	array('label'=>'Добавить город', 'url'=>array('create')),
);

?>




<?php echo CHtml::link('Добавить','/city/create/'); ?>


<?php $this->widget('bootstrap.widgets.TbGridView',array(
	'id'=>'city-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
    'ajaxUpdate'=>false,
    'template'=>'{summary}{items}{pager}',
    'type' => TbHtml::GRID_TYPE_BORDERED,
	'columns'=>array(
		//'id',
		//'city',
        array(
            'header'=>'Город',
            'value'=>'$data->city',
            'name'=>'city',
            //'filter'=>$this->widget('application.components.CityFilterWidget', array(), true)
        ),
        array(
            'header'=>'Регион',
            'name'=>'region',
            'type'=>'raw',
            'value'=>'$data->region->city',
            //'filter'=>$this->widget('application.components.RegionFilterWidget', array(), true)
        ),

        array(
            'header'=>'Областной центр',
            'type'=>'raw',
            'name'=>'main_city',
            'value'=>'City::getYesNot($data->main_city)',
            'filter' => array(1 => 'Да', 0 => 'Нет'),
        ),


		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
            'template'=>'{update}'
            //'visible'=>false,
		),
	),
));
?>
<script>
    $( document ).ready(function() {
        $(document).on('click','.action_active', function(event){
            event.preventDefault();
            $.ajax({
                type: "POST",
                url: $(this).attr('href'),
                data: 'city_id='+$(this).attr('city_id'),
                success:  function( data ) {
                    //console.log('data='+data);

                    if(data==''){
                        $('#city-grid').yiiGridView('update');
                    }else{
                        alert(data);
                    }
                    //
                },
                error: function(xhr, status, error) {
                    //console.log('111');
                    //var err = eval("(" + xhr.responseText + ")");
                    alert(xhr.responseText );
                }
            });
        });
    });
</script>