<?php
/* @var $this CitySitePhoneController */
/* @var $model CitySitePhone */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
	'id'=>'city-site-phone-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<?php echo $form->errorSummary($model); ?>


    <?php //echo $form->textFieldControlGroup($model,'city_id',array('span'=>5,'maxlength'=>255, 'disabled'=>!$model->isNewRecord)); ?>

    <?php echo $form->labelEx($model,'city_id'); ?>
    <?php echo $form->dropDownList($model,'city_id', CHtml::listData(City::model()->findAll(array('order'=>'city')), 'id', 'city'),array('span'=>5)); ?>

<!--    --><?php //echo $form->labelEx($model,'main_center'); ?>
<!--    --><?php //echo $form->dropDownList($model,'main_center',array('1'=>'Да', '0'=>'Нет'),array('span'=>5)); ?>

    <?php echo $form->labelEx($model,'relation_tpl'); ?>
    <?php echo $form->dropDownList($model,'relation_tpl',array('0'=>'Нет','1'=>'Да'),array('span'=>5)); ?>


    <?php echo $form->labelEx($model,'active'); ?>
    <?php echo $form->dropDownList($model,'active',array('1'=>'Да', '0'=>'Нет'),array('span'=>5)); ?>

    <?php echo $form->textFieldControlGroup($model,'phone',array('span'=>5)); ?>

    <?php echo $form->labelEx($model,'site_id'); ?>
    <?php echo $form->dropDownList($model,'site_id',CHtml::listData(Site::model()->findAll(), 'id', 'site'),array('span'=>5)); ?>

    <?php echo $form->textFieldControlGroup($model,'direct_phone',array('span'=>5)); ?>

    <?php echo $form->textFieldControlGroup($model,'google_phone',array('span'=>5)); ?>

    <div class="form-actions">
        <?php
        echo TbHtml::submitButton($model->isNewRecord ? 'Добавить' : 'Сохранить',array(
            'color'=>TbHtml::BUTTON_COLOR_PRIMARY,
            'size'=>TbHtml::BUTTON_SIZE_LARGE,
        ));
        ?>
    </div>


<?php $this->endWidget(); ?>

</div><!-- form -->