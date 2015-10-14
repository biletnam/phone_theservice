<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 26.08.14
 * Time: 16:15
 */
?>
<style>
#cityFilter{
    display: none;
}
label {
    display:none;
}
</style>
<?php
$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
    'id' => 'cityFilter',
    'options' => array(
        'title' => 'Фильтрация:Город',
        'autoOpen' => false,
        'modal' => true,
        'resizable'=> false,
        'width'=>'auto',
        'height'=>'auto',
    ),
));

$form=$this->beginWidget('CActiveForm', array(
    'id'=>'search-word-form-city',
));

if(isset($_GET['radio_selected_city'])){
    if(!empty($_GET['radio_selected_city'])){
        $selected = $_GET['radio_selected_city'];
    }
}else{
    $selected = 'search_word_accept_city';
}


echo CHtml::radioButtonList('radio_selected_city',
    $selected,
    array('search_word_accept_city'=>'Удовлетворяет регулярному выражению',
        'search_word_not_accept_city'=>'Не удовлетворяет регулярному выражению'
    ),
    array(
        'labelOptions'=>array('style'=>'display:inline'), // add this code
        'separator'=>'<br>',
    ));


$reg_expression = '';

if(isset($_GET['search_word_accept_reg_city'])){$reg_expression = $_GET['search_word_accept_reg_city'];}

echo '<br>'.CHtml::telField('search_word_accept_reg_city',$reg_expression);

echo '<br>'.CHtml::button('Применить', array('id'=>'btn_accept_search_word_city'));


$this->endWidget();

$this->endWidget('zii.widgets.jui.CJuiDialog');

echo CHtml::link('Фильтр', '#', array('id'=>'cityFilter_dialog'));

if(isset($_GET['City[city_id]'])){
    $data = $_GET['City[city_id]'];
}else{
    $data = '';
}

echo CHtml::hiddenField('City[city_id]', $data, array('id'=>'serch_word_filter_city'));

?>

<!--Обработчик выбора чекбоксов из списка диалогового окна и применение их как фильтра   -->
<script>
    $(function(){
        $(document).on('click', '#btn_accept_search_word_city',function(){

            var cheked_ps =  $('#search-word-form-city').serialize();

            //выбрали/не выбрали галочки - нажали на кнопку применения галочек к выборке
            $('#serch_word_filter_city').val(cheked_ps);

            $( "#cityFilter" ).dialog( "close" );

            //$('#statistics-parsing-grid').yiiGridView('update');
            $('#city-grid').yiiGridView('update', {
                data: cheked_ps
            });
        })

        /*
         кликаем по ссылке и вызываем окно фильтра
         */
        $(document).on('click', '#cityFilter_dialog',function(){
            $("#cityFilter").dialog("open");
            return false;
        })
    })
</script>