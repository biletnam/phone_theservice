<?php
/* @var $this CityController */
/* @var $model City */
/* @var $form TbActiveForm */
?>

<div class="form">

    <?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
	'id'=>'city-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

<!--    <p class="help-block">Поля обязательны к заполнению с <span class="required">*</span>.</p>-->

    <?php echo $form->errorSummary($model); ?>


            <span id="city">
                <?php echo $form->labelEx($model,'city',array('name'=>'city_label')); ?>
                <?php echo $form->textField($model,'city',array('span'=>5,)); ?>
            </span>


            <?php echo $form->labelEx($model,'main_city'); ?>
            <?php echo $form->dropDownList($model,'main_city',array('1'=>'Да', '0'=>'Нет'),array('span'=>5)); ?>

            <!--  в зависимости от выбранного значения в списке - показываем либо поле текстовое либо выпадающий список городов для выбора  -->

            <?php //echo $form->labelEx($model,'relation_tpl'); ?>
<!--    , 'disabled'=>!$model->isNewRecord-->
            <?php //echo $form->dropDownList($model,'relation_tpl',array('0'=>'Нет','1'=>'Да'),array('span'=>5)); ?>


            <?php //echo $form->labelEx($model,'active'); ?>
            <?php //echo $form->dropDownList($model,'active',array('1'=>'Да', '0'=>'Нет'),array('span'=>5)); ?>


            <span id="parent">
                <?php echo $form->labelEx($model,'parent_id'); ?>
                <?php echo $form->dropDownList($model,'parent_id', CHtml::listData(City::model()->findAll('main_city=1',array('order'=>'city')), 'id', 'city'),array('span'=>5)); ?>
            </span>


            <?php //echo $form->labelEx($model,'site_id'); ?>
            <?php //echo $form->dropDownList($model,'site_id',CHtml::listData(Site::model()->findAll(), 'id', 'site'),array('span'=>5)); ?>


        <div class="form-actions">
        <?php echo TbHtml::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить',array(
		    'color'=>TbHtml::BUTTON_COLOR_PRIMARY,
		    'size'=>TbHtml::BUTTON_SIZE_LARGE,
		)); ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->

<script>
    $(function(){
        function select_value(){
            //выбран главный город - показываем текстовое поле, для редактирования названия
            if($('#City_main_city').val()==1){
                //$('#city').show();
                $('#parent').hide();
            }else{
                //показываем список выбора городов
                //$('#city').hide();
                $('#parent').show();
            }
            //$('#City_main_city').show();
        }
        //приводим форму редактирования к нужному виду
        select_value();

        $('#City_main_city').change(function(){
           select_value();
        });
    })
</script>