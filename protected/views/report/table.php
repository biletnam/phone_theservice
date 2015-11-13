<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 12.12.14
 * Time: 9:31
 */



echo CHtml::link('Добавить','/report/create/'); ?>


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
            'value'=>'$data->city->city',
            'name'=>'city_id',
            'filter'=>$this->widget('application.components.CityFilterWidget', array(), true)
        ),
        array(
            'header'=>'Регион',
            'name'=>'region_id',
            'type'=>'raw',
            'value'=>'$data->city->region->city',
            'filter'=>$this->widget('application.components.RegionFilterWidget', array(), true)
        ),

        array(
            'header'=>'Областной центр',
            'type'=>'raw',
            'name'=>'main_city',
            'value'=>'City::getYesNot($data->city->main_city)',
            'filter' => array(1 => 'Да', 0 => 'Нет'),
        ),

        array(
            'header'=>'Привязка к шаблону',
            'type'=>'raw',
            'name'=>'relation_tpl',
            'value'=>'$data->relationtpl',
            //'filter' => false,
            'filter' => array(1 => 'Да', 0 => 'Нет'),
        ),

        array(
            'header'=>'Активен',
            'type'=>'raw',
            'name'=>'active',
            'value'=>'CHtml::link(City::getYesNot($data->active),"'.YiiBase::app()->createAbsoluteUrl('city/changeactive').'", array("class"=>"action_active", "city_id"=>$data->id))',
            'filter' => array(1 => 'Да', 0 => 'Нет'),
        ),

        array(
            'header'=>'Местный телефон',
            'name'=>'phone',
            'value'=>'$data->phone',
            'type'=>'raw',
            'filter'=>$this->widget('application.components.LocalPhoneFilterWidget', array(), true)
        ),
        array(
            'name'=>'region_phone',
            'value'=>'$data->region_phone',
            'type'=>'raw',
            'header'=>'Регион. телефон',
            'filter'=>$this->widget('application.components.RegionPhoneFilterWidget', array(), true)
        ),

        array(
            'header'=>'Сайт',
            'type'=>'raw',
            'name'=>'site_id',
            'value'=>'$data->site->site',
            //'filter' => false,
            'filter' => CHtml::listData(Site::model()->findAll(), 'id', 'site'),
        ),


        array(
            'class'=>'bootstrap.widgets.TbButtonColumn',
            'template'=>'{update}'
            //'visible'=>false,
        ),
    ),
));