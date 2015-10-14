<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 26.08.14
 * Time: 16:25
 */
?>
<style>
    #regionFilter{
        display: none;
    }
    label {
        display:none;
    }
</style>
<?php
$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
    'id' => 'regionFilter',
    'options' => array(
        'title' => 'Фильтрация:Регион',
        'autoOpen' => false,
        'modal' => true,
        'resizable'=> false,
        'width'=>'auto',
        'height'=>'auto',
    ),
));

$form=$this->beginWidget('CActiveForm', array(
    'id'=>'search-word-form-region',
));

if(isset($_GET['radio_selected_region'])){
    if(!empty($_GET['radio_selected_region'])){
        $selected = $_GET['radio_selected_region'];
    }
}else{
    $selected = 'search_word_accept_region';
}


echo CHtml::radioButtonList('radio_selected_region',
    $selected,
    array('search_word_accept_region'=>'Удовлетворяет регулярному выражению',
        'search_word_not_accept_region'=>'Не удовлетворяет регулярному выражению'
    ),
    array(
        'labelOptions'=>array('style'=>'display:inline'), // add this code
        'separator'=>'<br>',
    ));


$reg_expression = '';

if(isset($_GET['search_word_accept_reg_region'])){$reg_expression = $_GET['search_word_accept_reg_region'];}

echo '<br>'.CHtml::telField('search_word_accept_reg_region',$reg_expression);

echo '<br>'.CHtml::button('Применить', array('id'=>'btn_accept_search_word_region'));


$this->endWidget();

$this->endWidget('zii.widgets.jui.CJuiDialog');

echo CHtml::link('Фильтр', '#', array('id'=>'regionFilter_dialog'));

if(isset($_GET['Report[region]'])){
    $data = $_GET['Report[region]'];
}else{
    $data = '';
}

echo CHtml::hiddenField('region[region]', $data, array('id'=>'serch_word_filter_region'));

?>

<!--Обработчик выбора чекбоксов из списка диалогового окна и применение их как фильтра   -->
<script>
    $(function(){
        $(document).on('click', '#btn_accept_search_word_region',function(){

            var cheked_ps =  $('#search-word-form-region').serialize();

            //выбрали/не выбрали галочки - нажали на кнопку применения галочек к выборке
            $('#serch_word_filter_region').val(cheked_ps);

            $( "#regionFilter" ).dialog( "close" );

            //$('#statistics-parsing-grid').yiiGridView('update');
            $('#city-grid').yiiGridView('update', {
                data: cheked_ps
            });
        })

        /*
         кликаем по ссылке и вызываем окно фильтра
         */
        $(document).on('click', '#regionFilter_dialog',function(){
            $("#regionFilter").dialog("open");
            return false;
        })
    })
</script>