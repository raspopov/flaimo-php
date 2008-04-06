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
* @package vCard
*/
error_reporting(E_ALL & E_NOTICE);
ob_start();
session_start();
include_once 'class.vCard.inc.php';
$vCard = (object) new vCard('','');

$vCard->setFirstName('Max');
$vCard->setMiddleName('Mobil');
$vCard->setLastName('Mustermann');
$vCard->setEducationTitle('Doctor');
$vCard->setAddon('sen.');
$vCard->setNickname('Maxi');
$vCard->setCompany('Microsoft');
$vCard->setOrganisation('Linux');
$vCard->setDepartment('Product Placement');
$vCard->setJobTitle('CEO');
$vCard->setNote('Additional Note go here');
$vCard->setTelephoneWork1('+43 (05555) 000000');
$vCard->setTelephoneWork2('+43 (05555) 000000');
$vCard->setTelephoneHome1('+43 (05555) 000000');
$vCard->setTelephoneHome2('+43 (05555) 000000');
$vCard->setCellphone('+43 (05555) 000000');
$vCard->setCarphone('+43 (05555) 000000');
$vCard->setPager('+43 (05555) 000000');
$vCard->setAdditionalTelephone('+43 (05555) 000000');
$vCard->setFaxWork('+43 (05555) 000000');
$vCard->setFaxHome('+43 (05555) 000000');
$vCard->setISDN('+43 (05555) 000000');
$vCard->setPreferredTelephone('+43 (05555) 000000');
$vCard->setTelex('+43 (05555) 000000');
$vCard->setWorkStreet('123 Examplestreet');
$vCard->setWorkZIP('11111');
$vCard->setWorkCity('Testcity');
$vCard->setWorkRegion('PA');
$vCard->setWorkCountry('USA');
$vCard->setHomeStreet('123 Examplestreet');
$vCard->setHomeZIP('11111');
$vCard->setHomeCity('Testcity');
$vCard->setHomeRegion('PA');
$vCard->setHomeCountry('USA');
$vCard->setPostalStreet('123 Examplestreet');
$vCard->setPostalZIP('11111');
$vCard->setPostalCity('Testcity');
$vCard->setPostalRegion('PA');
$vCard->setPostalCountry('USA');
$vCard->setURLWork('http://flaimo.com');
$vCard->setRole('Student');
$vCard->setBirthday(time());
$vCard->setEMail('flaimo@gmx.net');

$vCard->outputFile('vcf');
?>
