<?php
/* @var $this PhoneController */
/* @var $model Phone */
/* @var $form TbActiveForm */
?>

<div class="form">

    <?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
	'id'=>'phone-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

    <?php echo $form->errorSummary($model); ?>

            <?php echo $form->textFieldControlGroup($model,'phone',array('span'=>5,'maxlength'=>60)); ?>

            <?php
                //echo $form->textFieldControlGroup($model,'city_id',array('span'=>5));
                echo $form->dropDownListControlGroup($model, 'city_id', CHtml::listData(City::model()->findAll('main_city=1',array('order'=>'city')), 'id', 'city'),array('disabled'=>!$model->isNewRecord ? true:false));
            ?>

            <?php
                //echo $form->textFieldControlGroup($model,'template_id',array('span'=>5));
                echo $form->dropDownListControlGroup($model, 'tpl_id', CHtml::listData(Phone::getTemplatesFromModx(), 'id', 'templatename'));
            ?>


            <?php
                //echo $form->textFieldControlGroup($model,'template_id',array('span'=>5));
                echo $form->dropDownListControlGroup($model, 'site_id', CHtml::listData(Site::model()->findAll(), 'id', 'site'),array('disabled'=>!$model->isNewRecord ? true:false));
            ?>

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