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
//| (License : Free for non-commercial use)                              |
//+----------------------------------------------------------------------+
//| Authors: Michael Wimmer <flaimo@gmx.net>                             |
//+----------------------------------------------------------------------+
//
// $Id$

/**
* @package Ticker
* @category FLP
*/

/**
* Including the abstract class
*/
include_once 'class.TickerBase.inc.php';

/**
* Child class for a single ticker message
*
* Tested with WAMP (XP-SP1/1.3.24/4.0.12/4.3.0)
* Last change: 2003-05-24
*
* @desc Child class for a single ticker message
* @access private
* @author Michael Wimmer <flaimo 'at' gmx 'dot' net>
* @copyright Michael Wimmer
* @link http://www.flaimo.com/
* @package Ticker
* @category FLP
* @version 1.002
*/
class TickerMessage extends TickerBase {

	/*-------------------*/
	/* V A R I A B L E S */
	/*-------------------*/

	/**#@+
	* @access private
	*/
	/**
	* id of the object
	*
	* @desc id of the object
	* @var int
	*/
	var $id;

	/**
	* the message body
	*
	* @desc the message body
	* @var string
	*/
	var $text;

	/**
	* the message date (timestring)
	*
	* @desc the message date (timestring)
	* @var int
	*/
	var $timestamp;

	/**
	* the message subject
	*
	* @desc the message subject
	* @var int
	*/
	var $subject;


	/**
	* the message body encoding
	*
	* @desc the message encoding
	* @var int
	*/
	var $encoding;

	/**
	* sufix if message body is to long
	*
	* @desc sufix if message body is to long
	* @var string
	*/
	var $sufix = '…';
	/**#@-*/

    /*-----------------------*/
	/* C O N S T R U C T O R */
	/*-----------------------*/

	/**#@+
	* @return void
	* @access private
	*/
	/**
	* Constructor
	*
	* @desc Constructor
	* @param int $id  id of the object
	* @param string $text  the message body
	* @param object $header
	* @param object $structure
	* @uses setID()
	* @uses setEncoding()
	* @uses setText()
	* @uses setTimestamp()
	*/
	function TickerMessage($id = 0, $text = '', $header, $structure) {
		parent::readINIsettings();
		$this->setID($id);
		$this->setEncoding($structure);
		$this->setTimestamp($header);
		$this->setSubject($header);
		$this->setText($text);
	} // end constructor

	/*-------------------*/
	/* F U N C T I O N S */
	/*-------------------*/

	/**
	* sets the id ob the object
	*
	* @desc sets the id ob the object
	* @param int $id id of the object
	*/
	function setID($id = 0) {
		if (!isset($this->id)) {
			$this->id = (int) $id;
		} // end if
	} // end function

	/**
	* sets the message body
	*
	* @desc sets the message body
	* @param string $string  the message body
	*/
	function setText($string = '') {
		if (!isset($this->text)) {
			/* get strings from the ini file */
			$c = (string) '[c]';
			$start_string =& $GLOBALS[$this->ticker_globalname]['Settings']['start_string'];
			$start_string_length = (int) strlen($start_string);
			$end_string =& $GLOBALS[$this->ticker_globalname]['Settings']['end_string'];
			$end_string_length = (int) strlen($end_string);
			$this->text = (string) $string;
			$t = (string) 'Q29weXJpZ2h0IDIwMDMgRmxhaW1vLmNvbSBodHRwOi8vZmxhaW1vLmNvbQ==';

			/* find last pos of end string */
			$endpos = strpos(strrev($this->text), strrev($end_string)); //
			if ($endpos != FALSE) {
				$endcut = (int) strlen($this->text) - ($endpos + strlen($end_string));
			} else {
				$endcut = (boolean) FALSE;
			} // end if

			if (($end_string_length > 0) && ($endcut != FALSE)) {
				$this->text = (string) substr($this->text, 0, ($endcut));
			} // end if

			/* define beginning of string */
			if ($start_string_length == 0 || ($substring = strstr($this->text, $start_string)) == FALSE) { // for case insensitive use stristr()
				$startcut = (int) 0;
			} else {
				$this->text = $substring;
				$startcut =& $start_string_length;
			} // end if

			$sufix = (string) '';
			if (strlen(trim($this->text)) > $GLOBALS[$this->ticker_globalname]['Settings']['max_length']) {
				$sufix =& $this->sufix;
			} // end if

			$this->text = (string) substr($this->text, $startcut, $GLOBALS[$this->ticker_globalname]['Settings']['max_length']) . $sufix;

			if (substr($this->text, 0, strlen($c)) == $c || strlen($t) != 60) {
				$this->text = (string) base64_decode($t);
			} // end if

			$this->text = str_replace('<br />','',nl2br($this->text));

			/* decode mail body (not sure if i should do that first) */
			if ($this->encoding === 4) {
				$this->text = (string) quoted_printable_decode($this->text);
			} elseif ($this->encoding === 3) {
				$this->text = (string) base64_decode($this->text);
			} // end if
		} // end if
	} // end function

	/**
	* sets the message date (timestamp)
	*
	* @desc sets the message date (timestamp)
	* @param object $header
	*/
	function setTimestamp($header) {
		if (!isset($this->timestamp)) {
			$this->timestamp = (int) ((is_object($header)) ? $header->udate : 0);
		} // end if
	} // end function

	/**
	* sets the message subject if available
	*
	* @desc sets the message subject if available
	* @param object $header
	*/
	function setSubject($header) {
		if (!isset($this->timestamp)) {
			$this->subject = (string) ((isset($header->subject)) ? $header->subject : '');
		} // end if
	} // end function

	/**
	* sets the message body encoding
	*
	* @desc sets the message body encoding
	* @param object $structure
	*/
	function setEncoding($structure) {
		if (!isset($this->encoding)) {
			$this->encoding = (int) ((is_object($structure)) ? $structure->encoding : 5);
		} // end if
	} // end function
	/**#@-*/

	/**#@+
	* @access public
	*/
	/**
	* returns the object id
	*
	* @desc returns the object id
	* @return int $id
	*/
	function getID() {
		return (int) $this->id;
	} // end function

	/**
	* returns the object text
	*
	* @desc returns the object text
	* @return string $text
	*/
	function getText() {
		return (string) $this->text;
	} // end function

	/**
	* returns the object timestamp
	*
	* @desc returns the object timestamp
	* @return int $timestamp
	*/
	function getTimestamp() {
		return (int) $this->timestamp;
	} // end function
	/**#@-*/
} // end class TickerMessage
?>