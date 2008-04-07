<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
//+----------------------------------------------------------------------+
//| WAMP (XP-SP2/2.2/5.2/5.1.0)                                          |
//+----------------------------------------------------------------------+
//| Copyright(c) 2001-2008 Michael Wimmer                                |
//+----------------------------------------------------------------------+
//| Licence: GNU General Public License v3                               |
//+----------------------------------------------------------------------+
//| Authors: Michael Wimmer <flaimo@gmail.com>                           |
//+----------------------------------------------------------------------+
//
// $Id$

/**
* @package MCrypt
* @category flaimo-php
* @filesource
*/
error_reporting(E_ALL);
ob_start();

include_once '../inc/class.MCrypt.inc.php';

//change the key to your password. better put it into an Apache server var 'MCRYPT_PW' and then use $_SERVER['MCRYPT_PW'];
$password = 'testpw';
$crypt = new MCrypt($password);

$cleartext = "Teststring";
echo $cleartext , '<br>';

$encoded_text = $crypt->encrypt($cleartext);
echo $encoded_text , '<br>';

$decoded_text = $crypt->decrypt($encoded_text);
echo $decoded_text , '<br>';

ob_end_flush();
?>
