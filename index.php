<?php
//echo phpinfo();
error_reporting(E_ALL);

ini_set('display_errors', true);

ini_set('error_reporting',  E_ALL);
/*
mb_internal_encoding('8bit');
include("/var/www/theservice/data/www/phone.theservice.ru/protected/components/sypexgeo/SxGeo.php");
$SxGeo = new SxGeo('/var/www/theservice/data/www/phone.theservice.ru/protected/components/sypexgeo/SxGeoCity.dat', SXGEO_BATCH | SXGEO_MEMORY); // Самый быстрый режим
//$geo_info = $SxGeo->get($ip); //(возвращает информацию о городе, без названия региона и временной зоны)
var_export($SxGeo->about());
die();
*/
// change the following paths if necessary
$yii=dirname(__FILE__).'/../framework/yii.php';
$config=dirname(__FILE__).'/protected/config/main.php';

// remove the following lines when in production mode
defined('YII_DEBUG') or define('YII_DEBUG',true);
// specify how many levels of call stack should be shown in each log message
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);

require_once($yii);
Yii::createWebApplication($config)->run();
 //$city = YiiBase::app()->sypexgeo->action('176.110.163.230');
//echo 'city='. $city;