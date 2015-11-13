<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 26.08.14
 * Time: 16:35
 */

?>
<style>
    #localphoneFilter{
        display: none;
    }
    label {
        display:none;
    }
</style>
<?php
$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
    'id' => 'localphoneFilter',
    'options' => array(
        'title' => 'Фильтрация:Местный телефон',
        'autoOpen' => false,
        'modal' => true,
        'resizable'=> false,
        'width'=>'auto',
        'height'=>'auto',
    ),
));

$form=$this->beginWidget('CActiveForm', array(
    'id'=>'search-word-form-localphone',
));

if(isset($_GET['radio_selected_localphone'])){
    if(!empty($_GET['radio_selected_localphone'])){
        $selected = $_GET['radio_selected_localphone'];
    }
}else{
    $selected = 'search_word_accept_localphone';
}


echo CHtml::radioButtonList('radio_selected_localphone',
    $selected,
    array('search_word_accept_localphone'=>'Удовлетворяет регулярному выражению',
        'search_word_not_accept_localphone'=>'Не удовлетворяет регулярному выражению'
    ),
    array(
        'labelOptions'=>array('style'=>'display:inline'), // add this code
        'separator'=>'<br>',
    ));


$reg_expression = '';

if(isset($_GET['search_word_accept_reg_localphone'])){$reg_expression = $_GET['search_word_accept_reg_localphone'];}

echo '<br>'.CHtml::telField('search_word_accept_reg_localphone',$reg_expression);

echo '<br>'.CHtml::button('Применить', array('id'=>'btn_accept_search_word_localphone'));


$this->endWidget();

$this->endWidget('zii.widgets.jui.CJuiDialog');

echo CHtml::link('Фильтр', '#', array('id'=>'localphoneFilter_dialog'));

if(isset($_GET['Report[localphone]'])){
    $data = $_GET['Report[localphone]'];
}else{
    $data = '';
}

echo CHtml::hiddenField('localphone[localphone]', $data, array('id'=>'serch_word_filter_localphone'));

?>

<!--Обработчик выбора чекбоксов из списка диалогового окна и применение их как фильтра   -->
<script>
    $(function(){
        $(document).on('click', '#btn_accept_search_word_localphone',function(){

            var cheked_ps =  $('#search-word-form-localphone').serialize();

            //выбрали/не выбрали галочки - нажали на кнопку применения галочек к выборке
            $('#serch_word_filter_localphone').val(cheked_ps);

            $( "#localphoneFilter" ).dialog( "close" );

            //$('#statistics-parsing-grid').yiiGridView('update');
            $('#city-grid').yiiGridView('update', {
                data: cheked_ps
            });
        })

        /*
         кликаем по ссылке и вызываем окно фильтра
         */
        $(document).on('click', '#localphoneFilter_dialog',function(){
            $("#localphoneFilter").dialog("open");
            return false;
        })
    })
</script>