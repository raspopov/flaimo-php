<?php
include_once 'class.TickerBase.inc.php';

$tm = new TickerBase();
echo $tm->readINIsettings();
echo '<pre>';
var_dump($GLOBALS['_TICKER_ini_settings']);
?>