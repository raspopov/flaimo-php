<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
//+----------------------------------------------------------------------+
//| WAMP (XP-SP2/2.2/5.2/5.3.0)                                          |
//+----------------------------------------------------------------------+
//| Copyright(c) 2001-2009 Michael Wimmer                                |
//+----------------------------------------------------------------------+
//| Licence: GNU General Public License v3                               |
//+----------------------------------------------------------------------+
//| Authors: Michael Wimmer <flaimo@gmail.com>                           |
//+----------------------------------------------------------------------+
//
// $Id$

/**
* @package AtomBuilder
* @category flaimo-php
* @filesource
*/
error_reporting(E_ALL);
ob_start();
include_once '../inc/class.AtomBuilder.inc.php';

/* create new feed object with the required informations: title, url to feed, id of the feed */
$atom = new AtomBuilder('Example.com fake news - feed title', 'http://example.com/linktofeedhomepage', 'tag:flaimo@flaimo.com,2004-12-31:linktofeedhomepage');
$atom->setEncoding('UTF-8'); // only needed if NOT utf-8
$atom->setLanguage('en'); // recommended, but not required
$atom->setSubtitle('The subtitle of the feed'); //optional
$atom->setRights('2005-2006 © Example.com'); //optional
$atom->setUpdated('2005-10-20T10:04:00Z'); // required !! last time the feed or one of it's entries was last modified/updated; in php5 you can use date('c', $timestamp) to generate a valid date
$atom->setAuthor('Flaimo', 'flaimo@example.com', 'http://example.com'); // name required, email and url are optional
$atom->setIcon('http://example.com/16x16_icon.png'); // optional
$atom->setLogo('http://example.com/100x50_logo.png'); // optional
$atom->addContributor('Jörg', 'joerg@example.com'); // optional
$atom->addContributor('Joe', 'joe@example.com', 'http://joespage.com');

/* you can add a lot of different links to the feed and it's entries, see intertwingly.net/wiki/pie/ for more infos */
$atom->addLink('http://php.net/', 'Official PHP homepage', 'related', 'text/html', 'en');
$atom->addLink('http://example.com/', 'Example.com – HTML Homepage', 'alternate', 'text/html', 'en');
$atom->addCategory('Webdesign', 'http://www.example.com/tags', 'Web 2.0 Webdesign'); // optional
$atom->addCategory('PHP'); // optional

/* now start adding entries... */
$entry_1 = $atom->newEntry('The title of the first news item', 'http://example.com/linktofeedhomepage/permalink_to_the_first_news_item', 'tag:flaimo@flaimo.com,2004-12-31:linktofeedhomepage/detail/1'); // required infos: title, link, id
$entry_1->setUpdated('2005-10-29T14:44:00Z'); // required (last modified/updated)
$entry_1->setPublished('2005-10-29T14:34:00.25Z'); // optional
$entry_1->setAuthor('Flaimo', 'flaimo@example.com', 'http://example.com/'); // name required, email and url are optional
$entry_1->addContributor('Matt', 'matt@example.com'); // optional
$entry_1->addContributor('Joe');
$entry_1->setRights('2005-2009 © Example.com'); // optional

/* optional links */
$entry_1->addLink('http://www.meyerweb.com/', 'meyerweb.com', 'via', 'text/html', 'en'); // for example if you post your plog entry in reply to someone elses plog entry
$entry_1->addLink('http://orf.at/', 'ORF ON', 'related', 'text/html', 'de'); // related links for that entry
$entry_1->addLink('http://feedvalidator.org/', 'Feed Validator for RSS and ATOM', 'related', 'text/html', 'en');
$entry_1->addCategory('Webdesign', 'http://www.flaimo.com/tags', 'Web 2.0 Webdesign'); // optional
$entry_1->setSummary('A <b>short</b> summary of the entry', 'html'); // both summary and content can contain plain text, html and xhtml
$entry_1->setContent('The whole content of the entry. This <b>and</b> the summary <big>can contain HTML</big>', 'html');
//$entry_1->setContent('dgwgtgfgg897dfg87fdgd876gdf86g…', 'image/png'); // you can also use other mime types
$atom->addEntry($entry_1); // add the created entry to the feed

/* add another entry... */
$entry_2 = $atom->newEntry('The title of the second news item', 'http://example.com/linktofeedhomepage/permalink_to_the_second_news_item', 'tag:flaimo@flaimo.com,2004-12-31:linktofeedhomepage/detail/2');
$entry_2->setUpdated('2005-10-29T15:54:00+01:00');
$entry_2->setPublished('2005-10-29T15:34:00.11+01:00');
$entry_2->setAuthor('Marcus', 'marcus@example.com', 'http://example.com/');
$entry_2->addContributor('Matt', 'matt@example.com');
$entry_2->addContributor('Joe', '', 'http://joespage.com');
$entry_2->setRights('2003 © Slashdot');

$entry_2->addLink('http://slashdot.org/', 'slashdot.org', 'via', 'text/html');
$entry_2->addLink('http://www.validome.org/', 'HTML / XHTML / WML / XML Validator', 'related', 'text/html');
$entry_2->addLink('http://thisweekintech.com/twit31.mp3', 'This week in tech Potcast Nr. 31', 'related', 'audio/mp3');
$entry_2->addLink('http://flaimo.com/'); // only the url is required, the rest is optional
$entry_2->addCategory('PHP', 'http://www.example.com/tags', 'PHP-Scripting'); // optional
$entry_2->setSummary('A short summary of the entry');

// content can also be declared xhtml. but then your xhtml code has to be valid. invalid xhtml (xml) code will show an error message in the feed
$entry_2->setContent('<p>The whole content of the entry. This <b>and</b> the summary <big>can contain XHTML</big></p>', 'xhtml');

/*
// you could also just link to the content
$entry_2->setContent('', 'application/pdf', 'http://flaimo.com/sample.pdf');
*/

$atom->addEntry($entry_2);

$version = '1.0.0'; // 1.0 is the only version so far
$atom->outputAtom($version);

/*
// if you don't want to directly output the content, but instead work with the string (for example write it to a cache file) use
$foo = $atom->getAtomOutput($version);
*/

/*
// saves the xml file to the given path and returns the path + filename as a string; if no filename is given it will automatically be created
$path = '';
$filename = 'myfeed.xml';
echo $atom->saveAtom($version, $path, $filename);
*/

ob_end_flush();
?>
