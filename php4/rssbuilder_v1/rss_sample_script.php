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

/**
* @package RSSBuilder
* @category FLP
* @filesource
*/
error_reporting(E_ALL & E_NOTICE);
ob_start();
session_start();
include_once 'class.RSSBuilder.inc.php';

/* create the object - remember, not all attibutes are supported by every rss version. just hand over an empty string if you don't need a specific attribute */
$encoding =(string) 'ISO-8859-1';
$about = (string) 'http://flaimo.com/';
$title = (string) 'flaimo.com fake news';
$description = (string) 'non existing news about my homepage';
$image_link = (string) 'http://flaimo.com/small_logo.png';
$category = (string) 'PHP Development'; // (only rss 2.0)
$cache = (string) 60; // in minutes (only rss 2.0)
$rssfile = new RSSBuilder($encoding, $about, $title, $description, $image_link, $category, $cache);

/* if you want you can add additional Dublic Core data to the basic rss file (if rss version supports it) */
$publisher = (string) 'Flaimo'; // person, an organization, or a service
$creator = (string) 'Flaimo'; // person, an organization, or a service
$date = (string) date('Y-m-d\TH:i:sO');
$language = (string) 'en';
$rights = (string) 'Copyright © 2003 Flaimo.com';
$coverage = (string) ''; // spatial location , temporal period or jurisdiction
$contributor = (string) 'Flaimo'; // person, an organization, or a service
$rssfile->addDCdata($publisher,	$creator, $date, $language,	$rights, $coverage, $contributor);

/* if you want you can add additional Syndication data to the basic rss file (if rss version supports it) */
$period = (string) 'daily'; // hourly / daily / weekly / ...
$frequency = (int) 1; // every X hours/days/...
$base = (string) date('Y-m-d\TH:i:sO');
$rssfile->addSYdata($period, $frequency, $base);

/* data for a single RSS item */
$about = $link = 'http://flaimo.com/sometext.php?somevariable=somevalue';
$title = (string) 'A fake news headline';
$description = (string) 'some abstract text about the fake news';
$subject = (string) 'technology'; // optional DC value
$date = (string) date('Y-m-d\TH:i:sO'); // optional DC value
$author = (string) 'Flaimo'; // author of item
$comments = (string) 'http://flaimo.com/sometext.php?somevariable=somevalue&amp;comments=1'; // url to comment page rss 2.0 value
$image = (string) 'http://flaimo.com/small_logo2.png'; // optional mod_im value for dispaying a different pic for every item
$rssfile->addItem($about, $title, $link, $description, $subject, $date,	$author, $comments, $image);

// add as much items as you want ...

$version = '0.91'; // 0.91 / 1.0 / 2.0
$rssfile->outputRSS($version);
// if you don't want to directly output the content, but instead work with the string (for example write it to a cache file) use
// $rssfile->getRSSOutput($version);
ob_end_flush();
?>