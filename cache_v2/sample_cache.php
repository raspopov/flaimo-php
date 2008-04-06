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
error_reporting(E_ALL & E_NOTICE);
include_once 'class.Cache.inc.php';
$cache = (object) new Cache('cache');
$vara = 'hi';
$varb = 'bye';
$cache_filename = ($vara . '_' . $varb);
$text = $vara . '_' . $varb . '_' .date('Y-m-d H:i:s') . '_long text goes here';
echo '$vara: ' . $vara . '<br>';
echo '$varb: ' . $varb . '<br>';
echo 'Now: ' . date('Y-m-d H:i:s') . '<br>';
if ($cache->isCached($cache_filename, 14) == TRUE) {
	echo '<b>file is there</b><br>';
	echo '<br>' . $cache->returnCache($cache_filename, false, true);
} else {
	echo '<b>writing file</b>';
	$cache->writeCache($cache_filename, $text, FALSE);
} // end if
?>