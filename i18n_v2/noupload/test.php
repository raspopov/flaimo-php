<?php
error_reporting(E_ALL);
header("content-type: text/html;charset=UTF-8 \r\n");
session_start();
require_once '../inc/class.I18Nbase.inc.php';

echo '<pre>';
$t = new I18Ntranslator('lang_main', new I18Nlocale('de'));
echo $t->_('Yes');



/*
$t = new I18Ntranslator('lang_classFormatDate, lang_test');

echo $t->_('hour', 'lang_classFormatDate');


//print_r($t->getTranslationTable());



$possible_locales =& $t->getLocales();
echo '<select>';


foreach ($possible_locales as $code => $locale) {
	$t->changeLocale(new I18Nlocale($code));

	echo '<option value="' , $code , '">' , $t->_($code) , '</option>';
} // end foreach
*/



?>
</select><hr />