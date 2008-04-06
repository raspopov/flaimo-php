<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
//+----------------------------------------------------------------------+
//| WAMP (XP-SP1/1.3.24/4.0.12/4.3.0)                                    |
//+----------------------------------------------------------------------+
//| Copyright (c) 1992-2003 Michael Wimmer                               |
//+----------------------------------------------------------------------+
//| I don't have the time to read through all the licences to find out   |
//| what the exactly say. But it's simple. It's free for non commercial  |
//| projects, but as soon as you make money with it, i want my share :-) |
//+----------------------------------------------------------------------+
//| Authors: Michael Wimmer <flaimo@gmx.net>                             |
//+----------------------------------------------------------------------+
//
// $Id$
error_reporting(E_ALL & E_NOTICE);
include_once 'class.Cache.inc.php';
$cache = (object) new Cache('cache');
$vara = $_GET['a'];
$varb = $_GET['b'];
$cache_filename = ($vara . '_' . $varb);
$text = $vara . '_' . $varb . '_' .date('Y-m-d H:i:s') . '_long text goes here';
echo '$vara: ' . $vara . '<br>';
echo '$varb: ' . $varb . '<br>';
echo 'Now: ' . date('Y-m-d H:i:s') . '<br>';
if ($cache->isCached($cache_filename, 14) == TRUE) {
	echo '<b>file is there</b><br>';
	echo '<br>' . $cache->returnCache($cache_filename, FALSE, FALSE);
} else {
	echo '<b>writing file</b>';
	$cache->writeCache($cache_filename, $text, FALSE);
} // end if
?>