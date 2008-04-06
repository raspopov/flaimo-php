<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
//+----------------------------------------------------------------------+
//| WAMP (XP-SP1/1.3.29/4.1.1/5.0.0RC1)                                    |
//+----------------------------------------------------------------------+
//| Copyright (c) 1992-2003 Michael Wimmer                               |
//+----------------------------------------------------------------------+
//| Authors: Michael Wimmer <flaimo 'at' gmx 'dot' net>                  |
//|          Rafael Machado Dohms <dooms 'at' terra 'dot' com 'dot' br>  |
//+----------------------------------------------------------------------+
//
// $Id$

/**
* @package valImage
*/

/* creates the image and outputs it to the browser */
error_reporting(E_ALL);
ob_start();
session_start();

require_once 'class.valimage.inc.php';

$vi = new valImage();
$vi->setStringLength(7); // number of characters to be displayed (default: 6)
$vi->useVerticalChars(FALSE); // (default: true)
$vi->outputImage('png', ''); // outputformat (jpg, png, gif, wbmp) (default: png)/ jpg-quality (default: 75)

ob_end_flush();
?>