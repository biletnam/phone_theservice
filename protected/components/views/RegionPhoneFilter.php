<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 26.08.14
 * Time: 16:36
 */

?>
<style>
    #regionphoneFilter{
        display: none;
    }
    label {
        display:none;
    }
</style>
<?php
$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
    'id' => 'regionphoneFilter',
    'options' => array(
        'title' => 'Фильтрация:Областной телефон',
        'autoOpen' => false,
        'modal' => true,
        'resizable'=> false,
        'width'=>'auto',
        'height'=>'auto',
    ),
));

$form=$this->beginWidget('CActiveForm', array(
    'id'=>'search-word-form-regionphone',
));

if(isset($_GET['radio_selected_regionphone'])){
    if(!empty($_GET['radio_selected_regionphone'])){
        $selected = $_GET['radio_selected_regionphone'];
    }
}else{
    $selected = 'search_word_accept_regionphone';
}


echo CHtml::radioButtonList('radio_selected_regionphone',
    $selected,
    array('search_word_accept_regionphone'=>'Удовлетворяет регулярному выражению',
        'search_word_not_accept_regionphone'=>'Не удовлетворяет регулярному выражению'
    ),
    array(
        'labelOptions'=>array('style'=>'display:inline'), // add this code
        'separator'=>'<br>',
    ));


$reg_expression = '';

if(isset($_GET['search_word_accept_reg_regionphone'])){$reg_expression = $_GET['search_word_accept_reg_regionphone'];}

echo '<br>'.CHtml::telField('search_word_accept_reg_regionphone',$reg_expression);

echo '<br>'.CHtml::button('Применить', array('id'=>'btn_accept_search_word_regionphone'));


$this->endWidget();

$this->endWidget('zii.widgets.jui.CJuiDialog');

echo CHtml::link('Фильтр', '#', array('id'=>'regionphoneFilter_dialog'));

if(isset($_GET['Report[regionphone]'])){
    $data = $_GET['Report[regionphone]'];
}else{
    $data = '';
}

echo CHtml::hiddenField('regionphone[regionphone]', $data, array('id'=>'serch_word_filter_regionphone'));

?>

<!--Обработчик выбора чекбоксов из списка диалогового окна и применение их как фильтра   -->
<script>
    $(function(){
        $(document).on('click', '#btn_accept_search_word_regionphone',function(){

            var cheked_ps =  $('#search-word-form-regionphone').serialize();

            //выбрали/не выбрали галочки - нажали на кнопку применения галочек к выборке
            $('#serch_word_filter_regionphone').val(cheked_ps);

            $( "#regionphoneFilter" ).dialog( "close" );

            //$('#statistics-parsing-grid').yiiGridView('update');
            $('#city-grid').yiiGridView('update', {
                data: cheked_ps
            });
        })

        /*
         кликаем по ссылке и вызываем окно фильтра
         */
        $(document).on('click', '#regionphoneFilter_dialog',function(){
            $("#regionphoneFilter").dialog("open");
            return false;
        })
    })
</script>