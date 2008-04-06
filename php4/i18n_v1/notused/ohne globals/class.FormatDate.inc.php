<?php
/**
* @package i18n
*/
/**
* We need the language so we know how long date/time strings have to be displayed
*/
@include_once('class.Translator.inc.php');

/**
* Including the user class is necessary for getting the user-preferences
*/
@include_once('class.User.inc.php');

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
* Tested with WAMP (XP-SP1/1.3.24/4.0.4/4.3.0)
* Last change: 2003-02-26
*
* @desc
* @access public
* @author Michael Wimmer <flaimo@gmx.net>
* @copyright Michael Wimmer
* @link http://www.flaimo.com/
* @package i18n
* @version 1.053
*/
class FormatDate {

	/*-------------------*/
	/* V A R I A B L E S */
	/*-------------------*/

	/**
	* No information available
	*
	* @desc no description
	* @var unknown
	* @access private
	*/
	var $month_name;

	/**
	* No information available
	*
	* @desc no description
	* @var unknown
	* @access private
	*/
	var $day_name;

	/**
	* No information available
	*
	* @desc no description
	* @var unknown
	* @access private
	*/
	var $swatch;

	/**
	* For Translation of date names (needs other class)
	*
	* @desc For Translation of date names (needs other class)
	* @var object
	* @access private
	* @see Translator
	*/
	var $lg;

	/**
	* Holds all monthnames in english
	*
	* @desc Holds all monthnames in english
	* @var array
	* @access private
	* @see Language
	*/
	var $month_array;

	/**
	* Holds all daynames in english
	*
	* @desc Holds all daynames in english
	* @var array
	* @access private
	*/
	var $day_array;

	/**
	* For getting user settings
	*
	* @desc For getting user settings
	* @var object
	* @access private
	* @see User
	*/
	var $user;

	/**
	* no description
	*
	* @desc no description
	* @var string
	* @access private
	* @see User
	*/
	var $inputlang;

	/**
	* Holds the settings for this class
	*
	* @desc Holds the settings for this class
	* @var array
	* @access private
	*/
	var $date_settings;

	/**
	* Holds all possible date formats (swatch, iso, default)
	*
	* @desc Holds all possible date formats (swatch, iso, default)
	* @var array
	* @access private
	*/
	var $whattimes;

	/**
	* Holds the settings for this class
	*
	* @desc Holds the settings for this class
	* @var array
	* @access private
	*/
	var $formatdate_settings;

	/**
	* Whether to encode the final output strings or not
	*
	* @desc Holds the settings for this class
	* @var boolean
	* @access private
	*/
	var $encode_strings = TRUE;

	/*-----------------------*/
	/* C O N S T R U C T O R */
	/*-----------------------*/

	/**
	* Constructor
	*
	* No information available
	*
	* @desc Constructor
	* @param (string) $language  iso based locale
	* @return (void)
	* @access private
	* @uses setSelectCSSClass()
	* @since 1.000 - 2002/10/10
	*/
	function FormatDate($language = '') {
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
	* @return (void)
	* @access private
	* @uses setLanguageFilePath(), setIncExtention(), setModus(), setShowErrormessages()
	* @since 1.045 - 2003/02/21
	*/
	function readDefaultFormatDateSettings() {
        if (!isset($this->formatdate_settings) && file_exists('i18n_settings.ini')) {
			$this->formatdate_settings = (array) parse_ini_file('i18n_settings.ini', TRUE);
			$this->encode_strings = (boolean) $this->formatdate_settings['FormatDate']['encode_strings'];
    	} // end if
	} // end function

	/**
	* Returns a timestamp based on the current time
	*
	* @desc Returns a timestamp based on the current time
	* @return (int) $now_timestamp
	* @access public
	* @since 1.000 - 2002/10/10
	*/
	function &getNowTimestamp() {
		return (int) $this->now_timestamp;
	} // end function

	/**
	* Returns the preferred display format for date/timestrings set in the
	* User class
	*
	* @desc Returns the preferred display format for dates
	* @return (int) $preferedtime
	* @access public
	* @uses User::getPreferedTime(), loadUserClass()
	* @since 1.000 - 2002/10/10
	*/
	function &getTimeset() {
		$this->loadUserClass();
		return (int) $this->user->getPreferedTime();
	} // end function

	/**
	* IsValidTimeCode
	*
	* some small helper function for validating inputs as
	* valid time formats
	*
	* @desc helper function for validating inputs
	* @param (int) $int  Has to be 0, 1 or 2
	* @return (boolean) $isvalid
	* @access public
	* @since 1.000 - 2002/10/10
	*/
	function IsValidTimeCode($code) {
		return (boolean) ((preg_match('(^[0-3]$)',$code) > 0) ? TRUE : FALSE);
	} // end function

	/**
	* IsValidISODate
	*
	* some small helper function for validating inputs as
	* valid time formats
	*
	* @desc helper function for validating inputs
	* @param (string) $string  Has to be yyyy-mm-dd
	* @return (boolean) $isvalid
	* @access public
	* @see IsValidISODateTime(), IsValidUnixTimeStamp()
	* @since 1.000 - 2002/10/10
	*/
	function IsValidISODate($date) {
		return (boolean) ((preg_match('(^\d{4}-\d{2}-\d{2}$)',$date) > 0) ? TRUE : FALSE);
	} // end function

	/**
	* IsValidISODateTime
	*
	* some small helper function for validating inputs as
	* valid time formats
	*
	* @desc helper function for validating inputs
	* @param (string) $string  Has to be yyyy-mm-dd h:i:s
	* @return (boolean) $isvalid
	* @access public
	* @see IsValidISODate(), IsValidUnixTimeStamp()
	* @since 1.000 - 2002/10/10
	*/
	function IsValidISODateTime($date) {
		return (boolean) ((preg_match('(^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$)',$date) > 0) ? TRUE : FALSE);
	} // end function

	/**
	* IsValidUnixTimeStamp
	*
	* some small helper function for validating inputs as
	* valid time formats
	*
	* @desc helper function for validating inputs
	* @param (int) $int  Has to be a timestamp
	* @return (boolean) $isvalid
	* @access public
	* @see IsValidISODate(), IsValidISODateTime()
	* @since 1.000 - 2002/10/10
	*/
	function IsValidUnixTimeStamp($timestamp) {
		return (boolean) ((preg_match('(^\d{1,10}$)',$timestamp) > 0) ? TRUE : FALSE);
	} // end function

	//------------------------------------------------

	/**
	* ISOdateToUnixtimestamp
	*
	* some small helper function for converting different timeformats
	*
	* @desc some small helper function for converting different timeformats
	* @param (string) $string  Has to be a ISO date
	* @return (int)  Timestamp
	* @access public
	* @see ISOdatetimeToUnixtimestamp(), UnixtimestampToISOdate(), UnixtimestampToISOtime(), UnixtimestampToISOdatetime()
	* @since 1.000 - 2002/10/10
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
	* @param (string) $string  Has to be a ISO date + ISO time
	* @return (int)  Timestamp
	* @access public
	* @see ISOdateToUnixtimestamp(), UnixtimestampToISOdate(), UnixtimestampToISOtime(), UnixtimestampToISOdatetime()
	* @since 1.000 - 2002/10/10
	*/
	function ISOdatetimeToUnixtimestamp($datetime = '1900-01-01 00:00:00') {
		list($date,$time) 		= split(' ',$datetime);
		list($year,$month,$day) = split('-',$date);
		list($hour,$min,$sec) 	= split(':',$time);
		return (int) mktime($hour, $min, $sec, $month, $day, $year);
	} // end function

	/**
	* UnixtimestampToISOdate
	*
	* some small helper function for converting different timeformats
	*
	* @desc some small helper function for converting different timeformats
	* @param (int) $timestamp  Has to be a timestamp
	* @return (string)  ISO date
	* @access public
	* @see ISOdateToUnixtimestamp(), ISOdatetimeToUnixtimestamp(), UnixtimestampToISOtime(), UnixtimestampToISOdatetime()
	* @since 1.000 - 2002/10/10
	*/
	function UnixtimestampToISOdate($timestamp = 0) {
		if ($timestamp == 0) {
			$timestamp =& $this->getNowTimestamp() ;
		} // end if
		return (string) date('Y-m-d', &$timestamp);
	} // end function

	/**
	* UnixtimestampToISOtime
	*
	* some small helper function for converting different timeformats
	*
	* @desc some small helper function for converting different timeformats
	* @param (int) $timestamp  Has to be a timestamp
	* @return (string)  ISO time
	* @access public
	* @see ISOdateToUnixtimestamp(), ISOdatetimeToUnixtimestamp(), UnixtimestampToISOdate(), UnixtimestampToISOdatetime()
	* @uses getNowTimestamp()
	* @since 1.000 - 2002/10/10
	*/
	function UnixtimestampToISOtime($timestamp = 0) {
		if ($timestamp == 0) {
			$timestamp =& $this->getNowTimestamp();
		} // end if
		return (string) date('H:i:s', &$timestamp);
	} // end function

	/**
	* UnixtimestampToISOdatetime
	*
	* some small helper function for converting different timeformats
	*
	* @desc some small helper function for converting different timeformats
	* @param (int) $timestamp  Has to be a timestamp
	* @return (string)  ISO date + time
	* @access public
	* @see ISOdateToUnixtimestamp(), ISOdatetimeToUnixtimestamp(), UnixtimestampToISOdate(), UnixtimestampToISOtime()
	* @uses getNowTimestamp()
	* @since 1.000 - 2002/10/10
	*/
	function UnixtimestampToISOdatetime($timestamp = 0) {
		if ($timestamp == 0) {
			$timestamp =& $this->getNowTimestamp();
		} // end if
		return (string) date('Y-m-d H:i:s', &$timestamp);
	} // end function


	/* MySQL Timestamp
	function ISOdateToMySQLtimestamp($timestamp)
	{
	return date('Ymd',$timestamp);
	}
	function ISOdatetimeToMySQLtimestamp($timestamp)
	{
	return date('YmdHis',$timestamp);
	}
	*/

	/**
	* Returns the name of a month depending on the Language set
	*
	* Gets the month from a given timestamp and returns
	* a translated name of it from the language class
	*
	* @desc Returns the name of a month depending on the Language set
	* @param (int) $timestamp   Timestamp
	* @return (string)  Name of the month
	* @access public
	* @see DayName()
	* @uses getNowTimestamp(), IsValidUnixTimeStamp(), returnError(), loadLanguageClass(), Language::getLocale()
	* @since 1.000 - 2002/10/10
	*/
	function MonthName($timestamp = 0) {
		if ($timestamp == 0) {
			$timestamp =& $this->getNowTimestamp();
		} // end if
		if ($this->IsValidUnixTimeStamp(&$timestamp) === FALSE) {
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
	* @param (int) $timestamp  Timestamp
	* @return (string)  Name of the day
	* @access public
	* @see MonthName()
	* @uses getNowTimestamp(), IsValidUnixTimeStamp(), returnError(), loadLanguageClass(), Language::getLocale()
	* @since 1.000 - 2002/10/10
	*/
	function DayName($timestamp = 0) {
		if ($timestamp == 0) {
			$timestamp =& $this->getNowTimestamp();
		} // end if
		if ($this->IsValidUnixTimeStamp(&$timestamp) === FALSE) {
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
	* @param (int) $timestamp  Timestamp
	* @return (string)  Datestring
	* @access public
	* @see TimeString()
	* @uses loadUserClass(), getNowTimestamp(), IsValidUnixTimeStamp(), returnError(), User::getPreferedTime(), loadLanguageClass, Language::getLocale(), DayName(), MonthName()
	* @since 1.000 - 2002/10/10
	*/
	function DateString($timestamp = 0) {
		$this->loadUserClass();
		if ($timestamp == 0) {
			$timestamp =& $this->getNowTimestamp();
		} // end if
		if ($this->IsValidUnixTimeStamp(&$timestamp) === FALSE) {
			return (string) $this->returnError(&$timestamp, __LINE__);
		} // end if
		if ($this->user->getPreferedTime() == 1) { // swatch time
			return (string) $this->swatchdate(&$timestamp);
		} elseif ($this->user->getPreferedTime() == 2) { // ISO time
			return (string) $this->ShortDate(&$timestamp);
		} else { // standard-time
			$this->loadLanguageClass();
			switch($this->lg->getLocale()) {
				case 'de':
				case 'de_at':
                case 'ar';
					return (string) $this->DayName(&$timestamp) . ', ' . date('j', &$timestamp) . '. ' . $this->MonthName(&$timestamp) . ' ' . date('Y', &$timestamp);
					break;
				case 'es':
					return (string) $this->DayName(&$timestamp) . ', ' . date('j', &$timestamp) . ' de ' . strtolower($this->MonthName(&$timestamp)) . ' de ' . date('Y', &$timestamp);
					break;
                case 'ru':
					return (string) $this->DayName(&$timestamp) . ', ' . date('j', &$timestamp) . ' ' . $this->MonthName(&$timestamp) . ' ' . date('Y', &$timestamp);
					break;
				case 'fr':
				case 'it':
					return (string) $this->DayName(&$timestamp) . ', ' . date('j', &$timestamp) . ' ' . strtolower($this->MonthName(&$timestamp)) . ' ' . date('Y', &$timestamp);
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
	* @param (int) $timestamp  Timestamp
	* @return (string)  Timestring
	* @access public
	* @see DateString()
	* @uses User::getPreferedTime(), loadUserClass(), getNowTimestamp(), IsValidUnixTimeStamp(), returnError(), loadLanguageClass(), Language::getLocale(), Language::_()
	* @since 1.000 - 2002/10/10
	*/
	function TimeString($timestamp = 0) {
		$this->loadUserClass();
		if ($timestamp == 0) {
			$timestamp =& $this->getNowTimestamp();
		} // end if
		if ($this->IsValidUnixTimeStamp(&$timestamp) === FALSE) {
			return (string) $this->returnError(&$timestamp, __LINE__);
		} // end if
		if ($this->user->getPreferedTime() === 1) { // swatch time
			return (string) $this->swatchtime(&$timestamp);
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
	* @param (int) $timestamp  Timestamp
	* @return (string)  Timestring
	* @access public
	* @see swatchdate()
	* @uses getNowTimestamp(), IsValidUnixTimeStamp(), returnError()
	* @since 1.000 - 2002/10/10
	* @author http://www.ypass.net/crap/internettime/ <http://www.ypass.net/crap/internettime/>
	*/
	function swatchtime($timestamp = 0) {
		if ($timestamp == 0) {
			$timestamp =& $this->getNowTimestamp();
		} // end if
		if ($this->IsValidUnixTimeStamp(&$timestamp) === FALSE) {
			return (string) $this->returnError(&$timestamp, __LINE__);
		} // end if
		$rawbeat = (array) explode('.',(($timestamp % 86400) / 86.4));
		return (string) '@' . sprintf('%03d', $rawbeat[0]) . '://' . substr($rawbeat[1], 0, 2);
	} // end function

	/**
	* Returns swatch date
	*
	* @desc Returns swatch date
	* @param (int) $timestamp  Timestamp
	* @return (string)  Datetring
	* @access public
	* @see swatchtime()
	* @uses getNowTimestamp(), IsValidUnixTimeStamp()
	* @since 1.000 - 2002/10/10
	*/
	function swatchdate($timestamp = '') {
		if ($timestamp == 0) {
			$timestamp =& $this->getNowTimestamp();
		} // end if
		if ($this->IsValidUnixTimeStamp(&$timestamp) === FALSE) {
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
	* @param (int) $timestamp  Timestamp
	* @return (string)  Date/time string
	* @access public
	* @see DateString(), TimeString(), ShortDate()
	* @uses loadUserClass(), getNowTimestamp(), IsValidUnixTimeStamp(), returnError(), User::getPreferedTime(), DateString(), TimeString(), loadLanguageClass(), Language::getLocale()
	* @since 1.000 - 2002/10/10
	*/
	function &LongDate($timestamp = 0) {
		$this->loadUserClass();
		if ($timestamp == 0) {
			$timestamp =& $this->getNowTimestamp();
		} // end if
		if ($this->IsValidUnixTimeStamp(&$timestamp) === FALSE) {
			return (string) $this->returnError(&$timestamp, __LINE__);
		} // end if
		if ($this->user->getPreferedTime() > 0) { // swatch or iso time
			$output = (string) $this->DateString(&$timestamp) . ' – ' . $this->TimeString(&$timestamp);
		} else {
			$this->loadLanguageClass();
			switch ($this->lg->getLocale()) { // standard-time
				case 'es':
					$output = (string) $this->DateString(&$timestamp) . ' a las ' . $this->TimeString(&$timestamp);
					break;
				default:
					$output = (string) $this->DateString(&$timestamp) . ' – ' . $this->TimeString(&$timestamp);
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
	* @param (int) $timestamp  Timestamp
	* @return (string)  Datestring
	* @access public
	* @see TimeString()
	* @uses loadUserClass(), getNowTimestamp(), IsValidUnixTimeStamp(), returnError(), User::getPreferedTime(), loadLanguageClass, Language::getLocale(), DayName(), MonthName()
	* @since 1.000 - 2002/10/10
	*/
	function &MiddleDateString($timestamp = 0) {
		$this->loadUserClass();
		if ($timestamp == 0) {
			$timestamp =& $this->getNowTimestamp();
		} // end if
		if ($this->IsValidUnixTimeStamp(&$timestamp) === FALSE) {
			return (string) $this->returnError(&$timestamp, __LINE__);
		} // end if
		if ($this->user->getPreferedTime() > 0) { // swatch or iso time
			return (string) $this->DateString(&$timestamp);
		} else {
			$this->loadLanguageClass();
			switch($this->lg->getLocale()) {
				case 'de':
				case 'de_at':
					return (string) substr($this->DayName(&$timestamp),0,2) . ', ' . date('j', &$timestamp) . '. ' . substr($this->MonthName(&$timestamp),0,4) . '. ' . date('Y', &$timestamp);
					break;
				case 'ru':
					return (string) substr($this->DayName(&$timestamp),0,3) . ', ' . date('j', &$timestamp) . '. ' . substr($this->MonthName(&$timestamp),0,4) . '. ' . date('Y', &$timestamp);
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
	* @param (int) $timestamp  Timestamp
	* @return (string)  Date/time string
	* @access public
	* @see DateString(), TimeString(), ShortDate()
	* @uses loadUserClass(), getNowTimestamp(), IsValidUnixTimeStamp(), returnError(), User::getPreferedTime(), DateString(), TimeString(), loadLanguageClass(), Language::getLocale()
	* @since 1.000 - 2002/10/10
	*/
	function &MiddleDate($timestamp = 0) {
		$this->loadUserClass();
		if ($timestamp == 0) {
			$timestamp =& $this->getNowTimestamp();
		} // end if
		if ($this->IsValidUnixTimeStamp(&$timestamp) === FALSE) {
			return (string) $this->returnError(&$timestamp, __LINE__);
		} // end if
		$output = (string) $this->MiddleDateString(&$timestamp) . ' – ' . $this->TimeString(&$timestamp);
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
	* @param (int) $timestamp  Timestamp
	* @return (string)  Date/time string
	* @access public
	* @see LongDate()
	* @uses User::getPreferedTime(), loadUserClass(), getNowTimestamp(), IsValidUnixTimeStamp(), returnError(), swatchdate(), loadLanguageClass(), Language::getLocale()
	* @since 1.000 - 2002/10/10
	*/
	function &ShortDate($timestamp = 0) {
		$this->loadUserClass();
		if ($timestamp == 0) {
			$timestamp =& $this->getNowTimestamp();
		} // end if
		if ($this->IsValidUnixTimeStamp(&$timestamp) === FALSE) {
			return (string) $this->returnError(&$timestamp, __LINE__);
		} // end if
		if ($this->user->getPreferedTime() === 1) { // swatch time
			$output = (string) $this->swatchdate(&$timestamp);
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
	* @param (int) $format  optional array key (0-2)
	* @return (mixed) $whattimes  (Possible) time format(s)
	* @access public
	* @uses loadLanguageClass()
	* @since 1.000 - 2002/10/10
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
	* Set’s the language variable if it hasn’t been set before
	*
	* @desc Set’s the language variable if it hasn’t been set before
	* @return (object) $lg  Translator
	* @access private
	* @see loadUserClass()
	* @uses checkClass(), Translator
	* @since 1.000 - 2002/10/10
	*/
	function loadLanguageClass() {
		if (!isset($this->lg)) {
			$this->checkClass('Translator', __LINE__);
			$this->lg =& new Translator($this->inputlang,'lang_classFormatDate');
		} // end if
	} // end function

	/**
	* Set’s the user variable if it hasn’t been set before
	*
	* @desc Set’s the user variable if it hasn’t been set before
	* @return (object) $user  User
	* @access private
	* @see loadLanguageClass()
	* @uses User, checkClass()
	* @since 1.000 - 2002/10/10
	*/
	function loadUserClass() {
		if (!isset($this->user)) {
			$this->checkClass('User', __LINE__);
			$this->user =& new User();
		} // end if
	} // end function

	/**
	* Catches errors and displays the line if the input was somehow wrong
	*
	* @desc Catches errors and displays the line if the input was somehow wrong
	* @param (mixed) $input  String that causes the error
	* @param (int) $line  Line where Error accures
	* @return (string)  Errormessage
	* @access private
	* @since 1.000 - 2002/10/10
	*/
	function returnError($input, $line = '') {
		$return = (string) 'Wrong input: ' . $input . ' (' . basename(__FILE__, '.php');
		return (string) $return . ((strlen(trim($line)) < 1) ? ')' : ' | L: ' . $line . ')' );
	} // end function

	/**
	* Input is a date in iso format. output is the age as an integer
	*
	* @desc Input is a date in iso format. output is the age as an integer
	* @param (string) $birthday  date (ISO format)
	* @return (int)  Age
	* @access public
	* @since 1.000 - 2002/10/10
	* @uses loadLanguageClass(), Language::_()
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

	/**
	* Checks if a class is available
	*
	* @desc Checks if a class is available
	* @return (object) $user  User
	* @access private
	* @since 1.001 - 2002/11/30
	*/
	function checkClass($classname = '', $line = '') {
		if (strlen(trim($classname)) > 0) {
			if (!class_exists($classname)) {
				if (strlen(trim($line)) > 0) {
					$lineinfo = (string) 'at Line ' .$line;
				} // end if
				die('Class "' . get_class($this) . '": Class "' . $classname . '" not found' .$lineinfo . '!');
			} // end if
		} // end if
	} // end function
} // end class FormatDate
?>
