<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 27.08.14
 * Time: 10:56
 */

//namespace sypexgeo;

/*
компонент для определения города пользователя по его IP
*/

class SypexGeoComponent extends CApplicationComponent{

    public function action($ip){

        $path_data_file = Yii::getpathOfAlias('application.components.sypexgeo').'/SxGeoCity.dat';

        $SxGeo = new SxGeo($path_data_file, SXGEO_BATCH | SXGEO_MEMORY); // Самый производительный режим, если нужно обработать много IP за раз

        $info_city = $SxGeo->getCityFull($ip); // Вся информация о городе

        return $info_city['city']['name_ru'];
    }
} 