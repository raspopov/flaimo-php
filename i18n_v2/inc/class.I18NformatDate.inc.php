<?php
/**
* load base class which takes care of all the other includes via it's autoload function
*/
require_once 'class.I18Nbase.inc.php';

/**
* formats dates and times based on the current locale
* @author Michael Wimmer <flaimo@gmail.com>
* @category flaimo-php
* @example  ../www_root/i18n_example_script.php  i18n example script
* @license GNU General Public License v3
* @link http://code.google.com/p/flaimo-php/
* @package i18n
* @version 2.3
*/
class I18NformatDate extends I18Nbase {

	/**
	* @var string namespaces needed for translating strings in this class
	*/
	protected $namespace = 'lang_classFormatDate';

	/**
	* @var object holds a I18Ntranslator object
	*/
	protected $translator;

	/**
	* @var array
	*/
	protected $display_times;

	/**
	* @var int
	*/
	protected $current_timeformat;

	/**#@+
	* @var string Format definitions for the different display formats
	*/
    protected $short_date;
   	protected $short_time;
    protected $short_datetime;
    protected $middle_date;
    protected $middle_time;
    protected $middle_datetime;
    protected $long_date;
    protected $long_time;
    protected $long_datetime;
	/**#@-*/

	/**
	* @var array
	*/
	protected $month_array = array('january','february','march','april','may',
								   'june','july','august','september','october',
								   'november','december');

	/**
	* @var array
	*/
	protected $day_array = array('sunday','monday','tuesday','wednesday',
							     'thursday','friday','saturday');

	/**
	* @param object $locale I18Nlocale
	* @return void
	* @uses I18Nbase::getI18NfactoryLocale()
	* @uses I18Ntranslator
	* @uses I18NformatDate::$translator
	* @uses I18NformatDate::readSettings()
	* @uses I18Nbase::getI18Nuser()
	* @uses I18NformatDate::$current_timeformat
	* @uses I18Nbase::getI18Nsetting()
	* @uses I18Nbase::__construct()
	*/
	public function __construct(&$locale = NULL) {
		if (!($locale instanceOf I18Nlocale)) {
			$locale =& parent::getI18NfactoryLocale();
		} // end if
		$this->translator = new I18Ntranslator($this->namespace, $locale);
		$this->readSettings();
		$user_pref =& parent::getI18Nuser()->getPrefTimeFormat();
		$this->current_timeformat = ($user_pref != FALSE) ? $user_pref : parent::getI18Nsetting('default_timeset');
		parent::__construct();
	} // end constructor

	/**
	* fetches the needed l10n settings into class vars
	* @uses I18NformatDate::$translator
	* @return void
	*/
	protected function readSettings() {
        $this->short_date 		=& $this->translator->getTranslatorLocale()->getL10Nsetting('short_date');
        $this->short_time 		=& $this->translator->getTranslatorLocale()->getL10Nsetting('short_time');
        $this->short_datetime 	=& $this->translator->getTranslatorLocale()->getL10Nsetting('short_datetime');
        $this->middle_date 		=& $this->translator->getTranslatorLocale()->getL10Nsetting('middle_date');
        $this->middle_time 		=& $this->translator->getTranslatorLocale()->getL10Nsetting('middle_time');
        $this->middle_datetime 	=& $this->translator->getTranslatorLocale()->getL10Nsetting('middle_datetime');
        $this->long_date 		=& $this->translator->getTranslatorLocale()->getL10Nsetting('long_date');
        $this->long_time 		=& $this->translator->getTranslatorLocale()->getL10Nsetting('long_time');
        $this->long_datetime 	=& $this->translator->getTranslatorLocale()->getL10Nsetting('long_datetime');
	} // end function

	/**
	* Checks if a given timecode is valid
	* @param int $code
	* @return boolean
	*/
	public static function isValidTimeCode($code = 0) {
		return (boolean) ($code < 0 || $code > 2) ? FALSE : TRUE;
	} // end function

	/**#@+
	* Checks if a string is a valid iso date
	* @param string $date
	* @return boolean
	*/
	public static function isValidISODate($date) {
		return (boolean) ((preg_match('(^\d{4}-\d{2}-\d{2}$)', $date) > 0) ? TRUE : FALSE);
	} // end function

	public static function isValidISODateTime($date) {
		return (boolean) ((preg_match('(^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$)', $date) > 0) ? TRUE : FALSE);
	} // end function
	/**#@-*/

	/**
	* Checks if a timestamp is valid
	* @param int $timestamp
	* @return boolean
	*/
	public static function isValidUnixTimeStamp($timestamp) {
		return (boolean) ((preg_match('(^\d{1,10}$)', $timestamp) > 0) ? TRUE : FALSE);
	} // end function

	/**#@+
	* converts iso to timestamp
	* @param string $date
	* @return int
	*/
	public static function ISOdateToUnixtimestamp($date = '1900-01-01') {
		return strtotime($date);
	} // end function

	public static function ISOdatetimeToUnixtimestamp($date = '1900-01-01 00:00:00') {
		return strtotime($date);
	} // end function
	/**#@-*/

	/**#@+
	* converts timestamp to iso date
	* @param int $timestamp
	* @return string
	*/
	public static function unixTimestampToISOdate($timestamp = 0) {
		return date('Y-m-d', $timestamp);
	} // end function

	public static function unixTimestampToISOtime($timestamp = 0) {
		return date('H:i:s', $timestamp);
	} // end function

	public static function unixTimestampToISOdatetime($timestamp = 0) {
		return date('Y-m-d H:i:s', $timestamp);
	} // end function
	/**#@-*/

	/**#@+
	* @param int $timestamp
	* @return string
	* @uses I18NformatDate::$translator
	*/
	/**
	* return a translation of a monthname based on the current translator locale
	*/
	protected function monthName($timestamp = 0) {
		$month = (int) date('n', $timestamp) - 1;
		return $this->translator->_($this->month_array[$month], 'lang_classFormatDate');
	} // end function

	/**
	* return a translation of a dayname based on the current translator locale
	*/
	protected function dayName($timestamp = 0) {
		$day = (int) date('w', $timestamp);
		return $this->translator->_($this->day_array[$day], 'lang_classFormatDate');
	} // end function
	/**#@-*/

	/**
	* returns an "/" encoded string
	* @param string $string
	* @return string $newstring
	*/
	protected static function encodeDateStrings($string = '') {
		// maybe has to be rewritten for multibyte...
		$length = (int) strlen($string);
		$newstring = '';
		for ($i = 0; $i < $length; $i++) {
			$newstring .= '\\' . $string[$i];
		} // end for
		return $newstring;
	} // end function

	/**
	* Returns correctly formated string for using with a date function
	* @param string $format how the timestring should be formated (raw from ini file)
	* @param int $timestamp
	* @uses I18Nbase::isFilledString()
	* @uses I18NformatDate::encodeDateStrings()
	* @uses I18NformatDate::dayName()
	* @uses I18NformatDate::monthName()
	* @uses I18NformatDate::$translator
	* @return string $format
	*/
	protected function dateFilter($format = '', $timestamp = 0) {
		/* mb functions replace "str_replace function */
		if (parent::isFilledString($format) === FALSE) {
			return '';
		} elseif (!preg_match('(monthname|dayname|hour)', $format)) {
			return $format;
		} // end if

		$format = mb_eregi_replace('monthname_short', $this->encodeDateStrings(mb_substr($this->monthName($timestamp), 0, 3)), $format);

		switch($this->translator->getTranslatorLocale()->getI18Nlocale()) {
			case 'ru':
			default:
				$end = (int) 2;
				break;
		} // end switch

		$format = mb_eregi_replace('dayname_short', $this->encodeDateStrings(mb_substr($this->dayName($timestamp), 0, $end)), $format);
		$format = mb_eregi_replace('dayname', $this->encodeDateStrings($this->dayName($timestamp)), $format);
		$format = mb_eregi_replace('monthname', $this->encodeDateStrings($this->monthName($timestamp)), $format);
		$format = mb_eregi_replace('hour', $this->encodeDateStrings($this->translator->_('hour', 'lang_classFormatDate')), $format);
		return $format;
	} // end function

	/**#@+
	* Returns a formated timestamp
	* @param int $timestamp
	* @param string $format how the timestring should be formated
	* @uses I18NformatDate::swatchDate()
	* @uses I18NformatDate::unixTimestampToISOdate()
	* @uses I18NformatDate::dateFilter()
	* @uses I18NformatDate::$current_timeformat
	* @return string
	*/
	protected function fDate($timestamp = 0, $format = '') {
		switch ($this->current_timeformat) {
			case 1: // swatch date
				return $this->swatchDate($timestamp);
				break;
			case 2: // iso date
				return $this->unixTimestampToISOdate($timestamp);
				break;
			default:
				return date($this->dateFilter($format, $timestamp), $timestamp);
				break;
		} // end switch
	} // end function

	protected function fTime($timestamp = 0, $format = '') {
		switch ($this->current_timeformat) {
			case 1: // swatch date
				return $this->swatchTime($timestamp);
				break;
			case 2: // iso date ;
				return $this->unixTimestampToISOtime($timestamp);
				break;
			default:
				return date($this->dateFilter($format, $timestamp), $timestamp);
				break;
		} // end switch
	} // end function

	protected function fDateTime($timestamp = 0, $format = '') {
		switch ($this->current_timeformat) {
			case 1: // swatch date
				return $this->swatchDate($timestamp) . ' – ' . $this->swatchTime($timestamp);
				break;
			case 2: // iso date
				return $this->unixTimestampToISOdatetime($timestamp);
				break;
			default:
				return date($this->dateFilter($format, $timestamp), $timestamp);
				break;
		} // end switch
	} // end function
	/**#@-*/

	/**#@+
	* returns a formated timestamp
	* @param int $timestamp
	* @return string formated date
	*/
	/**
	* @uses fDate()
	*/
	public function shortDate($timestamp = 0) {
		return $this->fDate($timestamp, $this->short_date);
	} // end function

	/**
	* @uses fDate()
	*/
	public function middleDate($timestamp = 0) {
		return $this->fDate($timestamp, $this->middle_date);
	} // end function

	/**
	* @uses fDate()
	*/
	public function longDate($timestamp = 0) {
		return $this->fDate($timestamp, $this->long_date);
	} // end function

	/**
	* @uses fTime()
	*/
	public function shortTime($timestamp = 0) {
		return $this->fTime($timestamp, $this->short_time);
	} // end function

	/**
	* @uses fTime()
	*/
	public function middleTime($timestamp = 0) {
		return $this->fTime($timestamp, $this->middle_time);
	} // end function

	/**
	* @uses fTime()
	*/
	public function longTime($timestamp = 0) {
		return $this->fTime($timestamp, $this->long_time);
	} // end function

	/**
	* @uses fDateTime()
	*/
	public function shortDateTime($timestamp = 0) {
		return $this->fDateTime($timestamp, $this->short_datetime);
	} // end function

	/**
	* @uses fDateTime()
	*/
	public function middleDateTime($timestamp = 0) {
		return $this->fDateTime($timestamp, $this->middle_datetime);
	} // end function

	/**
	* @uses fDateTime()
	*/
	public function longDateTime($timestamp = 0) {
		return $this->fDateTime($timestamp, $this->long_datetime);
	} // end function

	/**
	* returns swatch time
	* @param int $timestamp
	* @return string swatch time
	* @see swatchDate()
	* @author http://www.ypass.net/crap/internettime/ <http://www.ypass.net/crap/internettime/>
	*/
	public static function swatchTime($timestamp = 0) {
		$rawbeat = split('[.]',(($timestamp % 86400) / 86.4), 2);
		return '@' . sprintf('%03d', $rawbeat[0]) . '://' . substr($rawbeat[1], 0, 2);
	} // end function

	/**
	* returns swatch date
	* @param int $timestamp
	* @return string swatch date
	* @see swatchTime()
	*/
	public static function swatchDate($timestamp = 0) {
		return '@d' . date('d.m.y', $timestamp);
	} // end function

	/**
	* returns an array with possible time formats
	* @return array $display_times
	* @uses I18NformatDate::translator()
	*/
	public function getPossibleTimeFormats() {
		if (!isset($this->display_times)) {
			$this->display_times = array($this->translator->_('standard_time'),
							 			 $this->translator->_('swatch_time'),
							 			 $this->translator->_('ISO_time'));
		} // end if
		return $this->display_times;
	} // end function

	/**
	* returns current time format as a string
	* @return string
	* @uses I18NformatDate::getPossibleTimeFormats()
	* @uses I18NformatDate::$current_timeformat
	*/
	public function &getTimeFormat() {
		$times =& $this->getPossibleTimeFormats();
		return $times[$this->current_timeformat];
	} // end function
} // end class I18NformatDate
?>