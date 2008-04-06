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
* @package i18n
* @category FLP
*/
/**
* We need the language so we know how long date/time strings have to be displayed
*/
@require_once('class.Translator.inc.php');

/**
* Including the user class is necessary for getting the user-preferences
*/
@require_once('class.I18NUser.inc.php');
/**
* Including the abstract base class
*/
@require_once('class.I18N.inc.php');
/**
* Formats dates/times based on Language
*
* Formats date and/or timestrings in a) swatch time, b) iso time, c) local time.
* Local time means that it’s formated by looking at the language set in the
* Language class above and choosing the right format. So if the language would
* be en a short formated version of a date would look like mm/dd/yy. But for
* language de it would look like dd.mm.yy. The class is not complete in terms of
* countries. I’m only sure about the german and english way of writing dates.
* Just send me a mail and tell me how to write dates in your language and i’ll
* add it to the class.
*
* Tested with WAMP (XP-SP1/1.3.24/4.0.12/4.3.0)
* Last change: 2003-05-04
*
* @desc
* @access public
* @author Michael Wimmer <flaimo@gmx.net>
* @copyright Michael Wimmer
* @link http://www.flaimo.com/
* @package i18n
* @category  FLP
* @todo still have to rethink the way the formating is done
* @version 1.058
*/
class FormatDate extends I18N {

	/*-------------------*/
	/* V A R I A B L E S */
	/*-------------------*/

	/**#@+
	* @access private
	*/
	/**
	* No information available
	*
	* @desc no description
	*/
	var $month_name;

	/**
	* No information available
	*
	* @desc no description
	*/
	var $day_name;

	/**
	* No information available
	*
	* @desc no description
	*/
	var $swatch;

	/**
	* For Translation of date names (needs other class)
	*
	* @desc For Translation of date names (needs other class)
	* @var object
	* @see Translator
	*/
	var $lg;

	/**
	* Holds all monthnames in english
	*
	* @desc Holds all monthnames in english
	* @var array
	* @see Language
	*/
	var $month_array;

	/**
	* Holds all daynames in english
	*
	* @desc Holds all daynames in english
	* @var array
	*/
	var $day_array;

	/**
	* For getting user settings
	*
	* @desc For getting user settings
	* @var object
	* @see User
	*/
	var $user;

	/**
	* no description
	*
	* @desc no description
	* @var string
	* @see User
	*/
	var $inputlang;

	/**
	* Holds the settings for this class
	*
	* @desc Holds the settings for this class
	* @var array
	*/
	var $date_settings;

	/**
	* Holds all possible date formats (swatch, iso, default)
	*
	* @desc Holds all possible date formats (swatch, iso, default)
	* @var array
	*/
	var $whattimes;

	/**
	* Holds the settings for this class
	*
	* @desc Holds the settings for this class
	* @var array
	*/
	var $formatdate_settings;

	/**
	* Whether to encode the final output strings or not
	*
	* @desc Holds the settings for this class
	* @var boolean
	*/
	var $encode_strings = TRUE;
	/**#@-*/

	/*-----------------------*/
	/* C O N S T R U C T O R */
	/*-----------------------*/

	/**
	* Constructor
	*
	* No information available
	*
	* @desc Constructor
	* @param string $language  iso based locale
	* @return void
	* @access private
	* @uses readDefaultFormatDateSettings()
	* @uses I18N::I18N()

	*/
	function FormatDate($language = '') {
		parent::I18N();
        $this->inputlang = (string) $language;
		$this->now_timestamp = (int) time();
		$this->readDefaultFormatDateSettings();
	} // end function

	/*-------------------*/
	/* F U N C T I O N S */
	/*-------------------*/

	/**
	* Reads the default settings from the settings file if necessary
	*
	* @desc Reads the default settings from the settings file if necessary
	* @return void
	* @access private
	* @uses I18N::readINIsettings()
	* @since 1.045 - 2003-02-21
	*/
	function readDefaultFormatDateSettings() {
        parent::readINIsettings();
        if (isset($GLOBALS[$this->i18n_globalname])) {
			$this->encode_strings = (boolean) $GLOBALS[$this->i18n_globalname]['FormatDate']['encode_strings'];
    	} // end if
	} // end function

	/**
	* Returns a timestamp based on the current time
	*
	* @desc Returns a timestamp based on the current time
	* @return int $now_timestamp
	* @access public

	*/
	function &getNowTimestamp() {
		return (int) $this->now_timestamp;
	} // end function

	/**
	* Returns the preferred display format for date/timestrings set in the
	* User class
	*
	* @desc Returns the preferred display format for dates
	* @return int $preferedtime
	* @access public
	* @uses I18NUser::getPreferedTime()
	* @uses loadUserClass()

	*/
	function &getTimeset() {
		$this->loadUserClass();
		return (int) $this->user->getPreferedTime();
	} // end function


	/**#@+
	* @static
	* @access public
	*/
	/**
	* isValidTimeCode
	*
	* some small helper function for validating inputs as
	* valid time formats
	*
	* @desc helper function for validating inputs
	* @param int $int  Has to be 0, 1 or 2
	* @return boolean $isvalid
	*/
	function isValidTimeCode($code) {
		return (boolean) ((preg_match('(^[0-3]$)',$code) > 0) ? TRUE : FALSE);
	} // end function

	/**
	* isValidISODate
	*
	* some small helper function for validating inputs as
	* valid time formats
	*
	* @desc helper function for validating inputs
	* @param string $string  Has to be yyyy-mm-dd
	* @return boolean $isvalid
	* @see isValidISODateTime()
	* @see isValidUnixTimeStamp()
	*/
	function isValidISODate($date) {
		return (boolean) ((preg_match('(^\d{4}-\d{2}-\d{2}$)',$date) > 0) ? TRUE : FALSE);
	} // end function

	/**
	* isValidISODateTime
	*
	* some small helper function for validating inputs as
	* valid time formats
	*
	* @desc helper function for validating inputs
	* @param string $string  Has to be yyyy-mm-dd h:i:s
	* @return boolean $isvalid
	* @see isValidISODate()
	* @see isValidUnixTimeStamp()
	*/
	function isValidISODateTime($date) {
		return (boolean) ((preg_match('(^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$)',$date) > 0) ? TRUE : FALSE);
	} // end function

	/**
	* isValidUnixTimeStamp
	*
	* some small helper function for validating inputs as
	* valid time formats
	*
	* @desc helper function for validating inputs
	* @param int $int  Has to be a timestamp
	* @return boolean $isvalid
	* @see isValidISODate()
	* @see isValidISODateTime()
	*/
	function isValidUnixTimeStamp($timestamp) {
		$timestamp = (int) $timestamp;
		return (boolean) ((preg_match('(^\d{1,10}$)',$timestamp) > 0) ? TRUE : FALSE);
	} // end function

	/**
	* ISOdateToUnixtimestamp
	*
	* some small helper function for converting different timeformats
	*
	* @desc some small helper function for converting different timeformats
	* @param string $string  Has to be a ISO date
	* @return int  Timestamp
	* @see ISOdatetimeToUnixtimestamp()
	* @see unixTimestampToISOdate()
	* @see unixTimestampToISOtime()
	* @see unixTimestampToISOdatetime()
	*/
	function ISOdateToUnixtimestamp($date = '1900-01-01') {
		list($year,$month,$day) = split('-',$date);
		return (int) mktime(0, 0, 0, $month, $day, $year);
	} // end function

	/**
	* ISOdatetimeToUnixtimestamp
	*
	* some small helper function for converting different timeformats
	*
	* @desc some small helper function for converting different timeformats
	* @param string $string  Has to be a ISO date + ISO time
	* @return int  Timestamp
	* @see ISOdateToUnixtimestamp()
	* @see unixTimestampToISOdate()
	* @see unixTimestampToISOtime()
	* @see unixTimestampToISOdatetime()
	*/
	function ISOdatetimeToUnixtimestamp($datetime = '1900-01-01 00:00:00') {
		list($date,$time) 		= split(' ',$datetime);
		list($year,$month,$day) = split('-',$date);
		list($hour,$min,$sec) 	= split(':',$time);
		return (int) mktime($hour, $min, $sec, $month, $day, $year);
	} // end function

	/**
	* unixTimestampToISOdate
	*
	* some small helper function for converting different timeformats
	*
	* @desc some small helper function for converting different timeformats
	* @param int $timestamp  Has to be a timestamp
	* @return string  ISO date
	* @see ISOdateToUnixtimestamp()
	* @see ISOdatetimeToUnixtimestamp()
	* @see unixTimestampToISOtime()
	* @see unixTimestampToISOdatetime()
	*/
	function unixTimestampToISOdate($timestamp = 0) {
		$timestamp = (int) $timestamp;
		if ($timestamp == 0) {
			$timestamp =& $this->getNowTimestamp() ;
		} // end if
		return (string) date('Y-m-d', &$timestamp);
	} // end function

	/**
	* unixTimestampToISOtime
	*
	* some small helper function for converting different timeformats
	*
	* @desc some small helper function for converting different timeformats
	* @param int $timestamp  Has to be a timestamp
	* @return string  ISO time
	* @see ISOdateToUnixtimestamp()
	* @see ISOdatetimeToUnixtimestamp()
	* @see unixTimestampToISOdate()
	* @see unixTimestampToISOdatetime()
	* @uses getNowTimestamp()
	*/
	function unixTimestampToISOtime($timestamp = 0) {
		$timestamp = (int) $timestamp;
		if ($timestamp == 0) {
			$timestamp =& $this->getNowTimestamp();
		} // end if
		return (string) date('H:i:s', &$timestamp);
	} // end function

	/**
	* unixTimestampToISOdatetime
	*
	* some small helper function for converting different timeformats
	*
	* @desc some small helper function for converting different timeformats
	* @param int $timestamp  Has to be a timestamp
	* @return string  ISO date + time
	* @see ISOdateToUnixtimestamp()
	* @see ISOdatetimeToUnixtimestamp()
	* @see unixTimestampToISOdate()
	* @see unixTimestampToISOtime()
	* @uses getNowTimestamp()
	*/
	function unixTimestampToISOdatetime($timestamp = 0) {
		$timestamp = (int) $timestamp;
		if ($timestamp == 0) {
			$timestamp =& $this->getNowTimestamp();
		} // end if
		return (string) date('Y-m-d H:i:s', &$timestamp);
	} // end function
	/**#@-*/

	/**#@+
	* @access public
	*/
	/**
	* Returns the name of a month depending on the Language set
	*
	* Gets the month from a given timestamp and returns
	* a translated name of it from the language class
	*
	* @desc Returns the name of a month depending on the Language set
	* @param int $timestamp   Timestamp
	* @return string  Name of the month
	* @see dayName()
	* @uses getNowTimestamp()
	* @uses isValidUnixTimeStamp()
	* @uses returnError()
	* @uses loadLanguageClass()
	* @uses Language::getLocale()
	* @uses Translator::_()
	*/
	function monthName($timestamp = 0) {
		if ($timestamp == 0) {
			$timestamp =& $this->getNowTimestamp();
		} // end if
		if ($this->isValidUnixTimeStamp(&$timestamp) === FALSE) {
			return (string) $this->returnError(&$timestamp, __LINE__);
		} //end if
		if (!isset($this->month_array)) {
			$this->month_array = (array) array(
												'january',
												'february',
												'march',
												'april',
												'may',
												'june',
												'july',
												'august',
												'september',
												'october',
												'november',
												'december'
											   );
		} // end if
		$this->loadLanguageClass();
		switch ($this->lg->getLocale()) {
			case 'de':
			case 'de_at':
			case 'es':
			case 'fr':
			case 'it':
			case 'ru':
			default:
				$month = (int) date('m', &$timestamp) - 1;
				$month = (int) ((substr($month,0,1) == 0) ? substr($month,1,1) : $month);
				return (string) $this->lg->_($this->month_array[$month]);
				break;
		} // end switch
	} // end function

	/**
	* Returns the name of a day depending on the Language set
	*
	* Gets the day from a given timestamp and returns
	* a translated name of it from the language class
	*
	* @desc Returns the name of a day depending on the Language set
	* @param int $timestamp  Timestamp
	* @return string  Name of the day
	* @see monthName()
	* @uses getNowTimestamp()
	* @uses isValidUnixTimeStamp()
	* @uses returnError()
	* @uses loadLanguageClass()
	* @uses Language::getLocale()
	* @uses Translator::_()
	*/
	function dayName($timestamp = 0) {
		if ($timestamp == 0) {
			$timestamp =& $this->getNowTimestamp();
		} // end if
		if ($this->isValidUnixTimeStamp(&$timestamp) === FALSE) {
			return (string) $this->returnError(&$timestamp, __LINE__);
		} // end if
		if (!isset($this->day_array)) {
			$this->day_array = (array) array(
											 'sunday',
											 'monday',
											 'tuesday',
											 'wednesday',
											 'thursday',
											 'friday',
											 'saturday'
											 );
		} // end if
		$this->loadLanguageClass();
		switch ($this->lg->getLocale()) {
			case 'de':
			case 'de_at':
			case 'es':
			case 'fr':
			case 'it':
			case 'ru':
			default:
				$day = (int) date('w', &$timestamp);
				return (string) $this->lg->_($this->day_array[$day]);
				break;
		} // end switch
	} // end function

	/**
	* Returns a formated datestring from a timestamp
	*
	* Returns a formated datestring from a timestamp
	* depending on the language set by the user in the language class
	*
	* @desc Returns a formated datestring from a timestamp
	* @param int $timestamp  Timestamp
	* @return string  Datestring
	* @see timeString()
	* @uses loadUserClass()
	* @uses getNowTimestamp()
	* @uses isValidUnixTimeStamp()
	* @uses returnError()
	* @uses i18nUser::getPreferedTime()
	* @uses loadLanguageClass
	* @uses Language::getLocale()
	* @uses dayName()
	* @uses monthName()
	*/
	function dateString($timestamp = 0) {
		$this->loadUserClass();
		if ($timestamp == 0) {
			$timestamp =& $this->getNowTimestamp();
		} // end if
		if ($this->isValidUnixTimeStamp(&$timestamp) === FALSE) {
			return (string) $this->returnError(&$timestamp, __LINE__);
		} // end if
		if ($this->user->getPreferedTime() == 1) { // swatch time
			return (string) $this->swatchDate(&$timestamp);
		} elseif ($this->user->getPreferedTime() == 2) { // ISO time
			return (string) $this->shortDate(&$timestamp);
		} else { // standard-time
			$this->loadLanguageClass();
			switch($this->lg->getLocale()) {
				case 'de':
				case 'de_at':
                case 'ar';
					return (string) $this->dayName(&$timestamp) . ', ' . date('j', &$timestamp) . '. ' . $this->monthName(&$timestamp) . ' ' . date('Y', &$timestamp);
					break;
				case 'es':
					return (string) $this->dayName(&$timestamp) . ', ' . date('j', &$timestamp) . ' de ' . strtolower($this->monthName(&$timestamp)) . ' de ' . date('Y', &$timestamp);
					break;
                case 'ru':
					return (string) $this->dayName(&$timestamp) . ', ' . date('j', &$timestamp) . ' ' . $this->monthName(&$timestamp) . ' ' . date('Y', &$timestamp);
					break;
				case 'fr':
				case 'it':
					return (string) $this->dayName(&$timestamp) . ', ' . date('j', &$timestamp) . ' ' . strtolower($this->monthName(&$timestamp)) . ' ' . date('Y', &$timestamp);
					break;
				case 'en':
				default:
					return (string) preg_replace('*(st |nd |rd |th )*', '<sup>\\1</sup>',date('l, F jS Y', &$timestamp), 1);
					break;
			} // end switch
		} // end if
	} // end function

	/**
	* Returns a formated timestring from a timestamp
	*
	* Returns a formated timestring from a timestamp
	* depending on the language set by the user in the language class
	*
	* @desc Returns a formated timestring from a timestamp
	* @param int $timestamp  Timestamp
	* @return string  Timestring
	* @see dateString()
	* @uses I18NUser::getPreferedTime()
	* @uses loadUserClass()
	* @uses getNowTimestamp()
	* @uses isValidUnixTimeStamp()
	* @uses returnError()
	* @uses loadLanguageClass()
	* @uses Language::getLocale()
	* @uses Translator::_()
	*/
	function timeString($timestamp = 0) {
		$this->loadUserClass();
		if ($timestamp == 0) {
			$timestamp =& $this->getNowTimestamp();
		} // end if
		if ($this->isValidUnixTimeStamp(&$timestamp) === FALSE) {
			return (string) $this->returnError(&$timestamp, __LINE__);
		} // end if
		if ($this->user->getPreferedTime() === 1) { // swatch time
			return (string) $this->swatchTime(&$timestamp);
		} elseif ($this->user->getPreferedTime() === 2) { // ISO time
			return (string) date('H:i:s', &$timestamp);
		} else { // standard-time
			$this->loadLanguageClass();
			switch ($this->lg->getLocale()) {
				case 'de':
				case 'de_at':
				case 'ru':
                case 'ar':
					return (string) (((date('i', &$timestamp) == 0) ? date('G ', &$timestamp) : date('G.i ', &$timestamp)) . $this->lg->_('hour'));
					break;
				case 'es':
				case 'fr':
				case 'it':
					return (string) date('G.i ', &$timestamp);
					break;
				case 'en':
				default:
					return (string) ((date('i', &$timestamp) == 0) ? date('g a',&$timestamp) : date('g:i a', &$timestamp));
					break;
			} // end switch
		} // end if
	} // end function

	/**
	* Returns swatch time
	*
	* @desc Returns swatch time
	* @param int $timestamp  Timestamp
	* @return string  Timestring
	* @see swatchDate()
	* @uses getNowTimestamp()
	* @uses isValidUnixTimeStamp()
	* @uses returnError()
	* @author http://www.ypass.net/crap/internettime/ <http://www.ypass.net/crap/internettime/>
	*/
	function swatchTime($timestamp = 0) {
		if ($timestamp == 0) {
			$timestamp =& $this->getNowTimestamp();
		} // end if
		if ($this->isValidUnixTimeStamp(&$timestamp) === FALSE) {
			return (string) $this->returnError(&$timestamp, __LINE__);
		} // end if
		$rawbeat = (array) explode('.',(($timestamp % 86400) / 86.4));
		return (string) '@' . sprintf('%03d', $rawbeat[0]) . '://' . substr($rawbeat[1], 0, 2);
	} // end function

	/**
	* Returns swatch date
	*
	* @desc Returns swatch date
	* @param int $timestamp  Timestamp
	* @return string  Datetring
	* @see swatchTime()
	* @uses getNowTimestamp()
	* @uses isValidUnixTimeStamp()
	*/
	function swatchDate($timestamp = '') {
		if ($timestamp == 0) {
			$timestamp =& $this->getNowTimestamp();
		} // end if
		if ($this->isValidUnixTimeStamp(&$timestamp) === FALSE) {
			return (string) $this->returnError(&$timestamp, __LINE__);
		} // end if
		return (string) '@d' . date('j.m.y',&$timestamp);
	} // end function

	/**
	* Returns long date/time string
	*
	* For displaying a combination of a long datestring and a long
	* timestring. for example, 2002-12-24 (iso) or 12/24/02 (u.s.a.)
	* depending on the language set by the user in the language class
	*
	* @desc Returns long date/time string
	* @param int $timestamp  Timestamp
	* @return string  Date/time string
	* @see dateString()
	* @see timeString()
	* @see shortDate()
	* @uses loadUserClass()
	* @uses getNowTimestamp()
	* @uses isValidUnixTimeStamp()
	* @uses returnError()
	* @uses I18NUser::getPreferedTime()
	* @uses dateString()
	* @uses timeString()
	* @uses loadLanguageClass()
	* @uses Language::getLocale()
	*/
	function &longDate($timestamp = 0) {
		$this->loadUserClass();
		if ($timestamp == 0) {
			$timestamp =& $this->getNowTimestamp();
		} // end if
		if ($this->isValidUnixTimeStamp(&$timestamp) === FALSE) {
			return (string) $this->returnError(&$timestamp, __LINE__);
		} // end if
		if ($this->user->getPreferedTime() > 0) { // swatch or iso time
			$output = (string) $this->dateString(&$timestamp) . ' – ' . $this->timeString(&$timestamp);
		} else {
			$this->loadLanguageClass();
			switch ($this->lg->getLocale()) { // standard-time
				case 'es':
					$output = (string) $this->dateString(&$timestamp) . ' a las ' . $this->timeString(&$timestamp);
					break;
				default:
					$output = (string) $this->dateString(&$timestamp) . ' – ' . $this->timeString(&$timestamp);
					break;
			} // end switch
		} // end if
		if ($this->encode_strings === TRUE) {
			return (string) (($this->lg->isUTFencoded($this->lg->getLocale()) === TRUE) ? $this->lg->utf2html($output) : htmlentities($output));
		} else {
			return (string) $output;
		} // end if
	} // end function

	/**
	* Returns a formated datestring from a timestamp
	*
	* Returns a formated datestring from a timestamp
	* depending on the language set by the user in the language class
	*
	* @desc Returns a formated datestring from a timestamp
	* @param int $timestamp  Timestamp
	* @return string  Datestring
	* @see timeString()
	* @uses loadUserClass()
	* @uses getNowTimestamp()
	* @uses isValidUnixTimeStamp()
	* @uses returnError()
	* @uses I18NUser::getPreferedTime()
	* @uses loadLanguageClass
	* @uses Language::getLocale()
	* @uses dayName()
	* @uses monthName()
	*/
	function &middleDateString($timestamp = 0) {
		$this->loadUserClass();
		if ($timestamp == 0) {
			$timestamp =& $this->getNowTimestamp();
		} // end if
		if ($this->isValidUnixTimeStamp(&$timestamp) === FALSE) {
			return (string) $this->returnError(&$timestamp, __LINE__);
		} // end if
		if ($this->user->getPreferedTime() > 0) { // swatch or iso time
			return (string) $this->dateString(&$timestamp);
		} else {
			$this->loadLanguageClass();
			switch($this->lg->getLocale()) {
				case 'de':
				case 'de_at':
					return (string) substr($this->dayName(&$timestamp),0,2) . ', ' . date('j', &$timestamp) . '. ' . substr($this->monthName(&$timestamp),0,4) . '. ' . date('Y', &$timestamp);
					break;
				case 'ru':
					return (string) substr($this->dayName(&$timestamp),0,3) . ', ' . date('j', &$timestamp) . '. ' . substr($this->monthName(&$timestamp),0,4) . '. ' . date('Y', &$timestamp);
					break;
				case 'en':
				default:
					return (string) date('D, j-M-Y', &$timestamp);
					break;
			} // end switch
		} // end if

	} // end function

	/**
	* Returns middle date/time string
	*
	* For displaying a combination of a middle datestring and a normal
	* timestring. for example, Di, 27. Mär 1987 - 13.45 Uhr (de) or Wed, 24-Dec-2003 – 1:45 pm (usa)
	* depending on the language set by the user in the language class
	*
	* @desc Returns middle date/time string
	* @param int $timestamp  Timestamp
	* @return string  Date/time string
	* @see dateString()
	* @see timeString()
	* @see shortDate()
	* @uses loadUserClass()
	* @uses getNowTimestamp()
	* @uses isValidUnixTimeStamp()
	* @uses returnError()
	* @uses I18NUser::getPreferedTime()
	* @uses dateString()
	* @uses timeString()
	* @uses loadLanguageClass()
	* @uses Language::getLocale()
	*/
	function &middleDate($timestamp = 0) {
		$this->loadUserClass();
		if ($timestamp == 0) {
			$timestamp =& $this->getNowTimestamp();
		} // end if
		if ($this->isValidUnixTimeStamp(&$timestamp) === FALSE) {
			return (string) $this->returnError(&$timestamp, __LINE__);
		} // end if
		$output = (string) $this->middleDateString(&$timestamp) . ' – ' . $this->timeString(&$timestamp);
		if ($this->encode_strings === TRUE) {
			return (string) (($this->lg->isUTFencoded($this->lg->getLocale()) === TRUE) ? $this->lg->utf2html($output) : htmlentities($output));
		} else {
			return (string) $output;
		} // end if
	} // end function

	/**
	* Returns short date string
	*
	* For displaying a short datestring. for example,
	* Di, 27. Mär 1987 (de) or Wed, 24-Dec-2003 (usa) depending
	* on the language set by the user in the language
	* class
	*
	* @desc Returns short date string
	* @param int $timestamp  Timestamp
	* @return string  Date/time string
	* @see longDate()
	* @uses I18NUser::getPreferedTime()
	* @uses loadUserClass()
	* @uses getNowTimestamp()
	* @uses isValidUnixTimeStamp()
	* @uses returnError()
	* @uses swatchDate()
	* @uses loadLanguageClass()
	* @uses Language::getLocale()
	*/
	function &shortDate($timestamp = 0) {
		$this->loadUserClass();
		if ($timestamp == 0) {
			$timestamp =& $this->getNowTimestamp();
		} // end if
		if ($this->isValidUnixTimeStamp(&$timestamp) === FALSE) {
			return (string) $this->returnError(&$timestamp, __LINE__);
		} // end if
		if ($this->user->getPreferedTime() === 1) { // swatch time
			$output = (string) $this->swatchDate(&$timestamp);
		} elseif ($this->user->getPreferedTime() === 2) { // ISO time
			$output = (string) date('Y-m-d',&$timestamp);
		} else { // standard-time
			$this->loadLanguageClass();
			switch ($this->lg->getLocale()) {
				case 'de':
				case 'de_at':
				case 'es':
				case 'fr':
				case 'it':
				case 'ru':
                case 'ar':
					$output = (string) date('j.n.Y',&$timestamp);
					break;
				case 'en':
				default:
					$output = (string) date('n/j/y',&$timestamp);
					break;
			} // end switch
		} // end if
		if ($this->encode_strings === TRUE) {
			return (string) (($this->lg->isUTFencoded($this->lg->getLocale()) === TRUE) ? $this->lg->utf2html($output) : htmlentities($output));
		} else {
			return (string) $output;
		} // end if
	} // end function

	/**
	* Returns an array with possible time formats
	*
	* Sets an array with possible variations of how the time can be displayed.
	* key is the number we need for the rest of the script, value is the name
	* of the kind of time displayed (translated)
	*
	* @desc Returns an array with possible time formats
	* @param int $format  optional array key (0-2)
	* @return mixed $whattimes  (Possible) time format(s)
	* @uses loadLanguageClass()
	*/
	function getPossibleDisplayTimes($format = '') {
		if (!isset($this->whattimes)) {
			$this->loadLanguageClass();
			$this->whattimes = (array) array(
											$this->lg->__('standard_time'),
											$this->lg->__('swatch_time'),
											$this->lg->__('ISO_time')
											);
		} // end if
		if (array_key_exists($format, $this->whattimes)) {
			return (string) $this->whattimes[$format];
		} else {
			return (array) $this->whattimes;
		} // end if
	} // end function

	/**
	* Changes the language of the translator object
	*
	* @desc Changes the language of the translator object
	* @param string $locale  iso-code
	* @return void
	* @uses loadLanguageClass()
	* @uses Translator::changeLocale()
	* @since 1.055 - 2003-04-20
	*/
	function changeLocale($locale) {
		$this->loadLanguageClass();
		$this->lg->changeLocale($locale);
	} // end function


	/**
	* Input is a date in iso format. output is the age as an integer
	*
	* @desc Input is a date in iso format. output is the age as an integer
	* @param string $birthday  date (ISO format)
	* @return int  Age
	* @uses loadLanguageClass()
	* @uses Translator::_()
	* @author grparks@mptek.net <grparks@mptek.net>
	*/
	function getAge($birthday = '1900-01-01') {
		$today 	= (array) getdate();
		$month 	= (int) $today['mon'];
		$day 	= (int) $today['mday'];
		$year 	= (int) $today['year'];
		list($byear, $bmonth, $bday) = split('-', $birthday);
		$rawage = (int) $year-$byear;
		if (($month < $bmonth) || (($month == $bmonth) && ($day < $bday))) {
			return (int) $rawage - 1;
		} elseif ($byear == 0) {
			$this->loadLanguageClass();
			return (string) ucfirst($this->lg->_('unknown'));
		} else {
			return (int) $rawage;
		} // end if
	} // end function
	/**#@-*/

	/**
	* Set’s the language variable if it hasn’t been set before
	*
	* @desc Set’s the language variable if it hasn’t been set before
	* @return object $lg  Translator
	* @access private
	* @see loadUserClass()
	* @uses Translator
	*/
	function loadLanguageClass() {
		if (!isset($this->lg)) {
			$this->lg =& new Translator($this->inputlang,'lang_classFormatDate');
		} // end if
	} // end function

	/**
	* Set’s the user variable if it hasn’t been set before
	*
	* @desc Set’s the user variable if it hasn’t been set before
	* @return object $user  User
	* @access private
	* @see loadLanguageClass()
	* @uses User
	*/
	function loadUserClass() {
		if (!isset($this->user)) {
			$this->user =& new i18nUser();
		} // end if
	} // end function

	/**
	* Catches errors and displays the line if the input was somehow wrong
	*
	* @desc Catches errors and displays the line if the input was somehow wrong
	* @param mixed $input  String that causes the error
	* @param int $line  Line where Error accures
	* @return string  Errormessage
	* @access private
	*/
	function returnError($input, $line = '') {
		$return = (string) 'Wrong input: ' . $input . ' (' . basename(__FILE__, '.php');
		return (string) $return . ((strlen(trim($line)) < 1) ? ')' : ' | L: ' . $line . ')' );
	} // end function
} // end class FormatDate
?>
