<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
//+----------------------------------------------------------------------+
//| WAMP (XP-SP1/2.0.52/5.0.0/5.1.6)                                     |
//+----------------------------------------------------------------------+
//| Copyright(c) 2001-2006 Michael Wimmer                                |
//+----------------------------------------------------------------------+
//| I don't have the time to read through all the licences to find out   |
//| what the exactly say. But it's simple. It's free for non commercial  |
//| projects, but as soon as you make money with it, i want my share :-) |
//+----------------------------------------------------------------------+
//| Authors: Michael Wimmer <flaimo@gmx.net>                             |
//+----------------------------------------------------------------------+
//
// $Id$

/**
* @package MCrypt
* @category FLP
* @filesource
*/
error_reporting(E_ALL);
ob_start();

include_once '../inc/class.MCrypt.inc.php';

//change the key to your password. better put it into an Apache server var 'MCRYPT_PW' and then use $_SERVER['MCRYPT_PW'];
$password = 'maxmobil';
$crypt = new MCrypt($password);

$cleartext = "";
echo $cleartext , '<br>';

$encoded_text = $crypt->encrypt($cleartext);
echo $encoded_text , '<br>';

$decoded_text = $crypt->decrypt($encoded_text);
echo $decoded_text , '<br>';

ob_end_flush();
?>
