<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
//+----------------------------------------------------------------------+
//| WAMP (XP-SP1/1.3.27/4.0.12/4.3.2)                                    |
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
* @package i18n
* @category FLP
*/
/**
* We need the language so we know how long date/time strings have to be displayed
*/
@require_once 'class.Translator.inc.php';
/**
* Including the user class is necessary for getting the user-preferences
*/
@require_once 'class.I18NUser.inc.php';
/**
* Including the abstract base class
*/
@require_once 'class.I18N.inc.php';
/**
* Including the FormatString class
*/
@require_once 'class.FormatString.inc.php';
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
* Last change: 2003-06-12
*
* @desc
* @access public
* @author Michael Wimmer <flaimo 'at' gmx 'dot' net>
* @copyright Michael Wimmer
* @link http://www.flaimo.com/
* @package i18n
* @category  FLP
* @todo still have to rethink the way the formating is done
* @version 1.061
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
	* For Translation of date names (needs other class)
	*
	* @desc For Translation of date names (needs other class)
	* @var object
	* @see Translator
	*/
	var $lg;

	/**
	* For getting user settings
	*
	* @desc For getting user settings
	* @var object
	* @see User
	*/
	var $user;

	/**
	* For encoding output strings
	*
	* @desc For encoding output strings
	* @var object
	* @see FormatString
	*/
	var $fs;

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

	/**#@+
	* formating string for the date function
	*
	* @desc formating string for the date function
	* @var string
	* @access private
	*/
    var $short_date;
   	var $short_time;
    var $short_datetime;
    var $middle_date;
    var $middle_time;
    var $middle_datetime;
    var $long_date;
    var $long_time;
    var $long_datetime;
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
		$this->readDefaultFormatDateL10NSettings();
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
	* Reads the default settings for numbers and dates from the settings file if necessary
	*
	* @desc Reads the default settings for numbers and dates from the settings file if necessary
	* @uses I18N::readL10NINIsettings()
	* @uses I18N::readL10NINIsettings()
	* @uses Language::getLocale()
	* @since 1.060 - 2003-06-13
	*/
	function readDefaultFormatDateL10NSettings() {
        $this->loadLanguageClass();
        parent::readL10NINIsettings($this->lg->getLocale());
        if (isset($GLOBALS[$this->l10n_globalname])) {
            $this->short_date 		=& $GLOBALS[$this->l10n_globalname]['FormatDate']['short_date'];
            $this->short_time 		=& $GLOBALS[$this->l10n_globalname]['FormatDate']['short_time'];
            $this->short_datetime 	=& $GLOBALS[$this->l10n_globalname]['FormatDate']['short_datetime'];
            $this->middle_date 		=& $GLOBALS[$this->l10n_globalname]['FormatDate']['middle_date'];
            $this->middle_time 		=& $GLOBALS[$this->l10n_globalname]['FormatDate']['middle_time'];
            $this->middle_datetime 	=& $GLOBALS[$this->l10n_globalname]['FormatDate']['middle_datetime'];
            $this->long_date 		=& $GLOBALS[$this->l10n_globalname]['FormatDate']['long_date'];
            $this->long_time 		=& $GLOBALS[$this->l10n_globalname]['FormatDate']['long_time'];
            $this->long_datetime 	=& $GLOBALS[$this->l10n_globalname]['FormatDate']['long_datetime'];
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
		$month = (int) date('m', &$timestamp) - 1;
		$month = (int) ((substr($month,0,1) == 0) ? substr($month,1,1) : $month);
		return (string) $this->lg->_($this->month_array[$month], 'lang_classFormatDate');
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
		$day = (int) date('w', &$timestamp);
		return (string) $this->lg->_($this->day_array[$day], 'lang_classFormatDate');
	} // end function

	/**
	* encodes a string with backslashes
	*
	* @desc encodes a string with backslashes
	* @param string $string  raq formating string
	* @return string  $newstring formating string. ready as a first argument for the date function
	*/
	function encodeDateStrings($string = '') {
		$length = (int) strlen($string);
		$newstring = (string) '';
		for ($i = 0; $i < $length; $i++) {
			$newstring .= '\\' . $string[$i];
		} // end for
		return (string) $newstring;
	} // end function
	/**#@-*/

	/**
	* Returns correctly formated string for using with a date function
	*
	* @desc Returns correctly formated string for using with a date function
	* @param string $format how the timestring should be formated (raw from ini file)
	* @param int $timestamp Timestamp
	* @access private
	* @return string $format formated string for using with the date function
	* @since 1.060 - 2003-06-13
	* @uses getNowTimestamp()
	* @uses encodeDateStrings()
	* @uses monthName()
	* @uses dayName()
	* @uses Translator::_()
	* @uses Language::getLocale()
	* @uses loadLanguageClass()
	*/
	function dateFilter($format = '', $timestamp) {
		if (strlen(trim($format)) == 0) {
			return (string) '';
		} // end if

		if ($timestamp == 0) {
			$timestamp =& $this->getNowTimestamp();
		} // end if

		if (!preg_match('(monthname|dayname|hour)', $format)) {
			return (string) $format;
		} // end if

		$this->loadLanguageClass();
		$format = str_replace('monthname_short', $this->encodeDateStrings(substr($this->monthName($timestamp), 0, 4)), $format);

		switch($this->lg->getLocale()) {
			case 'ru':
				$format = str_replace('dayname_short', $this->encodeDateStrings(substr($this->dayName($timestamp), 0, 3)), $format);
				break;
			default:
				$format = str_replace('dayname_short', $this->encodeDateStrings(substr($this->dayName($timestamp), 0, 2)), $format);
				break;
		} // end switch

		$format = str_replace('dayname', $this->encodeDateStrings($this->dayName($timestamp)), $format);
		$format = str_replace('monthname', $this->encodeDateStrings($this->monthName($timestamp)), $format);
		$format = str_replace('hour', $this->encodeDateStrings($this->lg->_('hour', 'lang_classFormatDate')), $format);
		return (string) $format;
	} // end function

	/**
	* Encodes date strings
	*
	* @desc Encodes date strings
	* @param string $string string to be encoded
	* @access private
	* @return string $string html encoded date string
	* @uses loadStringClass()
	* @uses Translator::isUTFencoded()
	* @uses Language::getLocale()
	* @uses FormatString::specialChar()
	* @since 1.061 - 2003-06-13
	*/
	function encodeDateString($string) {
		if ($this->encode_strings === TRUE) {
			$this->loadStringClass();
			return (string) (($this->lg->isUTFencoded($this->lg->getLocale()) === TRUE) ? $this->lg->utf2html($string) : $this->fs->specialChar(htmlentities($string)));
		} else {
			return (string) $string;
		} // end if
	} // end function

	/**#@+
	* Returns a formated timestamp
	*
	* @desc Returns a formated timestamp
	* @param int $timestamp  Timestamp
	* @param string $format  how the timestring should be formated
	* @access private
	* @return string  formated date/time
	* @uses loadUserClass()
	* @uses getNowTimestamp()
	* @uses isValidUnixTimeStamp()
	* @uses returnError()
	* @uses getPreferedTime()
	* @uses dateFilter()
	* @since 1.060 - 2003-06-13
	*/
	function fDate($timestamp = 0, $format = '') {

		if ($timestamp == 0) {
			$timestamp =& $this->getNowTimestamp();
		} // end if

		if ($this->isValidUnixTimeStamp(&$timestamp) === FALSE) {
			return (string) $this->returnError(&$timestamp, __LINE__);
		} // end if

		$this->loadUserClass();
		switch ($this->user->getPreferedTime()) {
			case 1: // swatch date
				return (string) $this->swatchDate(&$timestamp);
				break;
			case 2: // iso date
				return (string) $this->unixTimestampToISOdate(&$timestamp);
				break;
			default:
				return (string) $this->encodeDateString(date($this->dateFilter($format, $timestamp),&$timestamp));
				break;
		} // end switch
	} // end function

	function fTime($timestamp = 0, $format = '') {

		if ($timestamp == 0) {
			$timestamp =& $this->getNowTimestamp();
		} // end if

		if ($this->isValidUnixTimeStamp(&$timestamp) === FALSE) {
			return (string) $this->returnError(&$timestamp, __LINE__);
		} // end if

		$this->loadUserClass();
		switch ($this->user->getPreferedTime()) {
			case 1: // swatch date
				return (string) $this->swatchTime(&$timestamp);
				break;
			case 2: // iso date ;
				return (string) $this->unixTimestampToISOtime(&$timestamp);
				break;
			default:
				return (string) $this->encodeDateString(date($this->dateFilter($format, $timestamp),&$timestamp));
				break;
		} // end switch
	} // end function

	function fDateTime($timestamp = 0, $format = '') {

		if ($timestamp == 0) {
			$timestamp =& $this->getNowTimestamp();
		} // end if

		if ($this->isValidUnixTimeStamp(&$timestamp) === FALSE) {
			return (string) $this->returnError(&$timestamp, __LINE__);
		} // end if

		$this->loadUserClass();
		switch ($this->user->getPreferedTime()) {
			case 1: // swatch date
				return (string) $this->swatchDate(&$timestamp) . ' – ' . $this->swatchTime(&$timestamp);
				break;
			case 2: // iso date
				return (string) $this->unixTimestampToISOdatetime(&$timestamp);
				break;
			default:
				return (string) $this->encodeDateString(date($this->dateFilter($format, $timestamp),&$timestamp));
				break;
		} // end switch
	} // end function
	/**#@-*/

	/**#@+
	* Returns a formated timestamp
	*
	* @desc Returns a formated timestamp
	* @access public
	* @since 1.060 - 2003-06-13
	* @param int $timestamp  Timestamp
	* @return string  formated date
	*/
	/**
	* @uses fDate()
	*/
	function shortDate($timestamp = 0) {
		return (string) $this->fDate($timestamp, $this->short_date);
	} // end function

	/**
	* @uses fDate()
	*/
	function middleDate($timestamp = 0) {
		return (string) $this->fDate($timestamp, $this->middle_date);
	} // end function

	/**
	* @uses fDate()
	*/
	function longDate($timestamp = 0) {
		return (string) $this->fDate($timestamp, $this->long_date);
	} // end function

	/**
	* @uses fTime()
	*/
	function shortTime($timestamp = 0) {
		return (string) $this->fTime($timestamp, $this->short_time);
	} // end function

	/**
	* @uses fTime()
	*/
	function middleTime($timestamp = 0) {
		return (string) $this->fTime($timestamp, $this->middle_time);
	} // end function

	/**
	* @uses fTime()
	*/
	function longTime($timestamp = 0) {
		return (string) $this->fTime($timestamp, $this->long_time);
	} // end function

	/**
	* @uses fDateTime()
	*/
	function shortDateTime($timestamp = 0) {
		return (string) $this->fDateTime($timestamp, $this->short_datetime);
	} // end function

	/**
	* @uses fDateTime()
	*/
	function middleDateTime($timestamp = 0) {
		return (string) $this->fDateTime($timestamp, $this->middle_datetime);
	} // end function

	/**
	* @uses fDateTime()
	*/
	function longDateTime($timestamp = 0) {
		return (string) $this->fDateTime($timestamp, $this->long_datetime);
	} // end function
	/**#@-*/

	/**#@+
	* @access public
	*/
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
		return (string) '@d' . date('d.m.y',&$timestamp);
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
											$this->lg->__('standard_time', 'lang_classFormatDate'),
											$this->lg->__('swatch_time', 'lang_classFormatDate'),
											$this->lg->__('ISO_time', 'lang_classFormatDate')
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
		$this->readDefaultFormatDateL10NSettings();
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
			return (string) ucfirst($this->lg->_('unknown', 'lang_classFormatDate'));
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
	* @uses User
	*/
	function loadUserClass() {
		if (!isset($this->user)) {
			$this->user =& new i18nUser();
		} // end if
	} // end function


	/**
	* Set’s the FormatString variable if it hasn’t been set before
	*
	* @desc Set’s the FormatString variable if it hasn’t been set before
	* @return object $fs  FormatString
	* @access private
	* @uses FormatString
	*/
	function loadStringClass() {
		if (!isset($this->fs)) {
			$this->fs =& new FormatString();
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
