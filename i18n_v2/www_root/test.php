<?php
error_reporting(E_ALL);
ob_start(); // you have to use output buffering, otherwise there's a problem with setting cookies/sessions
header("content-type: text/html;charset=UTF-8 \r\n");
session_start();
mb_internal_encoding('UTF-8');
mb_language('uni');

require_once '../inc/class.I18Nbase.inc.php';

$t = new I18NtranslatorMySQL5('', new I18Nlocale('en'));
echo $t->fetchAllTranslations();

//var_dump($t->_('en'));


?>
