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
//| Authors: Michael Wimmer <flaimo 'at' gmx 'dot' net>                  |
//+----------------------------------------------------------------------+
//
// $Id$
/**
* @package Thumbnail
*/
error_reporting(E_ALL & E_NOTICE);
ob_start();

/* Using basic thumbnail class */
/*
include_once('class.Thumbnail.inc.php');
$thumbnail = new Thumbnail('sampleimage.jpg');

$thumbnail->setMaxSize(55, 151);
$thumbnail->setQualityOutput(TRUE);

$thumbnail->outputThumbnail('jpg', 80); // use returnThumbnail() to work with the created thumbnail
*/




/* Using the thumbnail class with built-in cache functions */
/*
include_once('class.CachedThumbnail.inc.php');
$thumbnail = new CachedThumbnail('sampleimage.jpg', 10); // picture, cache time in seconds (default: 0 sec. = no caching)

$thumbnail->setMaxSize(155, 151); // set max. width and height of the thumbnail (default: 100, 100)
$thumbnail->setQualityOutput(TRUE); // quality or speed when creating the thumbnail (default: true)

$thumbnail->outputThumbnail('jpg', 80); // picture type (png, jpg, gif, wbmp), jpg-quality (0-100) (default: png, 75)
*/




/* Using the thumbnail class with built-in cache and watermark / logo function */
require_once 'class.WMThumbnail.inc.php';

/* picture, cache time in seconds (default: 0 sec. = no caching) */
$thumbnail = new WMThumbnail('sampleimage.jpg', 100); //

/* path to logo/watermark picture, position of the logo: 1 = left-top,
2 = right-top, 3 = right-bottom, 4 = left-bottom, 5 = center (default = 3),
margin to the border (default = 1) */
$thumbnail->addLogo('logo2.png', 3, 1);
$thumbnail->addLogo('icon2.png', 2, 3); // add more logos if you want

/* set max. width and height of the thumbnail (default: 100, 100) */
$thumbnail->setMaxSize(150, 120);
/* quality or speed when creating the thumbnail (default: true) */
$thumbnail->setQualityOutput(TRUE);

/* picture type (png, jpg, gif, wbmp), jpg-quality (0-100) (default: png, 75) */
$thumbnail->outputThumbnail('jpg', 80);

/*
or create a hardcoded html image-tag like this:
echo '<img src="' . $thumbnail->getCacheFilepath('jpg', 80) . '" width="' .
$thumbnail->getThumbWidth() . '" height="' . $thumbnail->getThumbHeight() . '" />';
*/
ob_end_flush();
?>