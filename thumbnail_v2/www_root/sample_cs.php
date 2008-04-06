<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
//+----------------------------------------------------------------------+
//| WAMP (XP-SP1/1.3.29/4.1.1/5.0.0RC1)                                    |
//+----------------------------------------------------------------------+
//| Copyright (c) 1992-2004 Michael Wimmer                               |
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
error_reporting(E_ALL);
ob_start();

require_once '../inc/class.CachedContactSheet.inc.php';

/* folder to search for pics, search subdirectories, cache time in seconds
 (default: '', FALSE, 60) */
$cs = new CachedContactSheet('samplefolder', TRUE, 100);

/* changing the pic settings */
/* sets the color for the picture background (red, green, blue) */
$cs->setBGColor(255, 255, 255);
/* sets the color for the thumbnail background (red, green, blue) */
$cs->setThumbBGColor(220, 220, 220);
/* sets the color for the thumbnail text (red, green, blue) */
$cs->setTextColor(0, 0, 0);
/* sets the size for all thumbs to be displayed (default: 100, 100) */
$cs->setThumbnailSize(110, 110);
/* sets the margin between thumbs (default: 10) */
$cs->setMargin(20);
/* sets the margin thumb and thumbnailbox (default: 4) */
$cs->setThumbMargin(4);
/* sets the lineup for the thumbs: columns, rows (default: 3, 3) */
$cs->setGrid(3, 3);
/* displays pic infos under each thumb (default: false) */
$cs->showFileInfos(TRUE);

/* images from the folder are placed before the manually added pics... */
$cs->addThumbnail('sampleimage.jpg');
$cs->addThumbnail('sampleimage2.jpg');
$cs->addThumbnail('sampleimage2.jpg');
$cs->addThumbnail('sampleimage.jpg');
$cs->addThumbnail('sampleimage2.jpg');
$cs->addThumbnail('sampleimage.jpg');

$cs->outputContactSheet('jpg', 75);

// some other output options
//echo serialize($cs->getImagepaths());
//imagepng($cs->returnContactSheet());
//echo $cs->getCacheFilepath('jpg', 75);

ob_end_flush();
?>