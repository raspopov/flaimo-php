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
* Including the abstract base class
*/
@require_once 'class.I18N.inc.php';
/**
* For grouping all the user-preferences used in the other classes
*
* Tested with WAMP (XP-SP1/1.3.24/4.0.12/4.3.0)
* Last change: 2003-05-04
*
* @desc For grouping all the user-preferences used in the other classes
* @access public
* @author Michael Wimmer <flaimo@gmx.net>
* @copyright Michael Wimmer
* @link http://www.flaimo.com/
* @package i18n
* @category FLP
* @version 1.058
*/
class I18NUser extends I18N {

	/*-------------------*/
	/* V A R I A B L E S */
	/*-------------------*/

	/**#@+
	* No Information available
	*
	* @desc No Information available
	* @var string
	* @access private
	*/
	var $last_visit;
	var $last_visit_cookiename = 'lastvisit';
	var $last_visit_sessionname = 'lastvisit';
	var $specialwords_status_name = 'specialwordsstatus';
	var $special_words_status;
	var $time_select_name = 'timeset';
	var $time_select_sessionname = 'sess_timeset';
	var $time_select_cookiename = 'cookie_timeset';
	var $lang_select_name = 'lang';
	var $lang_sessionname = 'sess_lang';
	var $lang_cookiename = 'cookie_lang';
	var $lang_content_select_name = 'lang_content';
	var $lang_content_sessionname = 'sess_lang_content';
	var $lang_content_cookiename = 'cookie_lang_content';
	var $locale_select_name = 'locale';
	var $locale_sessionname = 'sess_locale';
	var $locale_cookiename = 'cookie_locale';
	var $country_select_name = 'country';
	var $country_sessionname = 'sess_country';
	var $country_cookiename = 'cookie_country';
	var $prefered_language;
	var $prefered_locale;
	var $prefered_content_language;
	var $prefered_country;
	var $measure_system;
	var $measure_selectname = 'measure_system';
	var $measure_cookiename = 'cookie_measure';
	var $measure_sessionname = 'session_measure';
	var $select_css_class = 'ns';
	var $timezone_select_name = 'timezone';
	var $timezone_cookiename = 'cookie_timezone';
	var $timezone_sessionname = 'session_timezone';
	/**#@-*/

	/**#@+
	* No Information available
	*
	* @desc No Information available
	* @access private
	*/
	/**
	* @var int
	*/
	var $timeset;

	/**
	* @var int
	*/
	var $default_timeset = 0;

	/**
	* @var int
	*/
	var $timezone;

	/**
	* @var boolean
	*/
	var $default_specialwordsstatus = TRUE;
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
	* Only job is to set all the variablesnames
	*
	* @desc Constructor
	* @uses readDefaultUserSettings(), I18N::I18N()
	*/
	function I18NUser() {
		parent::I18N();
	} // end constructor

	/*-------------------*/
	/* F U N C T I O N S */
	/*-------------------*/

	/**
	* Sets and saves the users last visit to a webpage (ISO date - time)
	*
	* @desc Sets and saves the users last visit to a webpage (ISO date - time)
	*/
	function setLastVisit() {
		$this->last_visit = (string) date('Y-m-d H:i:s');
		setcookie($this->last_visit_cookiename, '', time()-3600);
		setcookie($this->last_visit_cookiename, $this->last_visit, time()+31536000);
		$_SESSION[$this->last_visit_sessionname] = $this->last_visit;
	} // end function

	/**
	* Set SpecialWords ON/OFF
	*
	* Defines the status if a string should be parsed though the
	* SpecialWords function - activates or deactivates underlining of special
	* words
	*
	* @desc Set SpecialWords ON/OFF
	* @see getSpecialWordsStatus()
	*/
	function setSpecialWordsStatus() {
		if (!isset($this->special_words_status)) {
			if (isset($_POST[$this->specialwords_status_name])) {
				$this->special_words_status = (boolean) $_POST[$this->specialwords_status_name];
				setcookie($this->specialwords_status_name, $this->special_words_status, time()+31536000);
				$_SESSION[$this->specialwords_status_name] = (boolean) $this->special_words_status;
			} elseif (isset($_SESSION[$this->specialwords_status_name])) {
				$this->special_words_status =& $_SESSION[$this->specialwords_status_name];
			} elseif (isset($_COOKIE[$this->specialwords_status_name])) {
				$this->special_words_status =& $_COOKIE[$this->specialwords_status_name];
			} else {
				$this->special_words_status =& $this->default_specialwordsstatus;
			} // end if
		} // end if
	} // end function

	/**
	* Sets display-format for time values
	*
	* Sets the timeformat (swatch/iso/standard) depending
	* on POST/GET/COOKIE/SESSION input or else uses standard time
	*
	* @desc Sets display-format for time values
	* @see getPreferedTime()
	*/
	function setPreferedTime() {
		if (!isset($this->timeset)) {
			if (isset($_POST[$this->time_select_name])) {
				$this->timeset = (int) $_POST[$this->time_select_name];
				$_SESSION[$this->time_select_sessionname] = (int) $this->timeset;
				setcookie($this->time_select_cookiename, $this->timeset, time()+31536000);
			} elseif (isset($_SESSION[$this->time_select_sessionname])) {
				$this->timeset =& $_SESSION[$this->time_select_sessionname];
			} elseif (isset($_COOKIE[$this->time_select_cookiename])) {
				$this->timeset =& $_COOKIE[$this->time_select_cookiename];
			} else {
				$this->timeset =& $this->default_timeset;
			} // end if
		} // end if
	} // end function

	/**
	* Looks up post/session/cookies for a transmitted iso-code
    *
    * To make session and cookies work with this class you have
    * to include ob_start(); and session_start(); in your scripts
	*
	* @desc Looks up post/session/cookies for a transmitted iso-code
	* @see getPreferedLanguage()
	*/
	function setPreferedLanguage() {
		if(!isset($this->prefered_language)) {
			if (isset($_POST[$this->lang_select_name])) {
				$this->prefered_language = (string) $_POST[$this->lang_select_name];
                setcookie($this->lang_cookiename, $_POST[$this->lang_select_name], time()+31536000);
				$_SESSION[$this->lang_sessionname] = (string) $_POST[$this->lang_select_name];
			} elseif (isset($_SESSION[$this->lang_sessionname])) {
                $this->prefered_language =& $_SESSION[$this->lang_sessionname];
			} elseif (isset($_COOKIE[$this->lang_cookiename])) {
                $this->prefered_language =& $_COOKIE[$this->lang_cookiename];
			} else {
				$this->prefered_language = (string) '';
			} // end if
		} // end if
	} // end function

	/**
	* Looks up post/session/cookies for a transmitted iso-code
    *
    * To make session and cookies work with this class you have
    * to include ob_start(); and session_start(); in your scripts
	*
	* @desc Looks up post/session/cookies for a transmitted iso-code
	* @see getPreferedLanguage()
	* @since 1.044 - 2003-02-18
	*/
	function setPreferedLocale() {
		if(!isset($this->prefered_locale)) {
			if (isset($_POST[$this->locale_select_name])) {
				$this->prefered_locale = (string) $_POST[$this->locale_select_name];
                setcookie($this->locale_cookiename, $_POST[$this->locale_select_name], time()+31536000);
				$_SESSION[$this->locale_sessionname] = (string) $_POST[$this->locale_select_name];
			} elseif (isset($_SESSION[$this->locale_sessionname])) {
                $this->prefered_locale =& $_SESSION[$this->locale_sessionname];
			} elseif (isset($_COOKIE[$this->locale_cookiename])) {
                $this->prefered_locale =& $_COOKIE[$this->locale_cookiename];
			} else {
				$this->prefered_locale = (string) '';
			} // end if
		} // end if
	} // end function

	/**
	* Looks up post/session/cookies for a transmitted iso-code
	*
	* @desc Looks up post/session/cookies for a transmitted iso-code
	* @see getPreferedContentLanguage()
	*/
	function setPreferedContentLanguage() {
		if (!isset($this->prefered_content_language)) {
			if (isset($_POST[$this->lang_content_select_name])) {
				$this->prefered_content_language = (string) $_POST[$this->lang_content_select_name];
				setcookie($this->lang_content_cookiename, $this->prefered_content_language, time()+31536000);
				$_SESSION[$this->lang_content_sessionname] = (string) $this->prefered_content_language;
			} elseif (isset($_SESSION[$this->lang_content_sessionname])) {
				$this->prefered_content_language =& $_SESSION[$this->lang_content_sessionname];
			} elseif (isset($_COOKIE[$this->lang_content_cookiename])) {
				$this->prefered_content_language =& $_COOKIE[$this->lang_content_cookiename];
			} else {
				$this->prefered_content_language = (string) '';
			} // end if
		} // end if
	} // end function

	/**
	* Looks up post/session/cookies for a transmitted iso-code
	*
	* @desc Looks up post/session/cookies for a transmitted iso-code
	* @see getPreferedCountry()
	*/
	function setPreferedCountry() {
		if (!isset($this->prefered_country)) {
			if (isset($_POST[$this->country_select_name])) {
				$this->prefered_country = (string) $_POST[$this->country_select_name];
				setcookie($this->country_cookiename, $this->prefered_country, time()+31536000);
				$_SESSION[$this->country_sessionname] = (string) $this->prefered_country;
			} elseif (isset($_SESSION[$this->country_sessionname])) {
				$this->prefered_country =& $_SESSION[$this->country_sessionname];
			} elseif (isset($_COOKIE[$this->country_cookiename])) {
				$this->prefered_country =& $_COOKIE[$this->country_cookiename];
			} else {
				$this->prefered_country = (string) '';
			} // end if
		} // end if
	} // end function

	/**
	* Looks up post/session/cookies for a transmitted timezone
	*
	* @desc Looks up post/session/cookies for a transmitted timezone
	* @see getPreferedTimezone()
	* @since 1.001 - 2002-11-09
	*/
	function setPreferedTimezone() {
		if(!isset($this->timezone)) {
			if (isset($_POST[$this->timezone_select_name])) {
				$this->timezone = (string) $_POST[$this->timezone_select_name];
				setcookie($this->timezone_cookiename, $this->timezone, time()+31536000);
				$_SESSION[$this->timezone_sessionname] = (string) $this->timezone;
			} elseif (isset($_COOKIE[$this->timezone_cookiename])) {
				$this->timezone =& $_COOKIE[$this->timezone_cookiename];
			} elseif (isset($_SESSION[$this->timezone_sessionname])) {
				$this->timezone =& $_SESSION[$this->timezone_sessionname];
			} else {
				$this->timezone = (string) '';
			} // end if
		} // end if
	} // end function

	/**
	* Sets display-format for measure values
	*
	* Sets the format (metric, us) depending
	* on POST/GET/COOKIE/SESSION input
	*
	* @desc Sets display-format for measure values
	* @see Measure
	* @see getPreferedMeasureSystem()
	* @since 1.001 - 2002-11-13
	*/
	function setPreferedMeasureSystem() {
		if (!isset($this->measure_system)) {
			if (isset($_POST[$this->measure_selectname])) {
				$this->measure_system = (string) $_POST[$this->measure_selectname];
				$_SESSION[$this->measure_sessionname] = (string) $this->measure_system;
				setcookie($this->measure_cookiename, $this->measure_system, time()+31536000);
			} elseif (isset($_SESSION[$this->measure_sessionname])) {
				$this->measure_system =& $_SESSION[$this->measure_sessionname];
			} elseif (isset($_COOKIE[$this->measure_cookiename])) {
				$this->measure_system =& $_COOKIE[$this->measure_cookiename];
			} else {
				$this->measure_system = (string) '';
			} // end if
		} // end if
	} // end function
	/**#@-*/

	/**#@+
	* @access public
	*/
	/**
	* Returns the users last visit to a webpage (ISO date - time)
	*
	* @desc Returns the users last visit to a webpage (ISO date - time)
	* @return string  String with the ISO date - time in it
	* @see setLastVisit()
	*/
	function &getLastVisit() { // DO TO
		if (isset($_SESSION[$this->last_visit_sessionname])) {
			return (string) $_SESSION[$this->last_visit_sessionname];
		} elseif (isset($_COOKIE[$this->last_visit_cookiename])) {
			return (string) $_COOKIE[$this->last_visit_cookiename];
		} else {
			return (string) '';
		} // end if
	} // end function

	/**
	* Show SpecialWords ON/OFF
	*
	* Returns the status if a string should be parsed though the
	* SpecialWords function
	*
	* @desc Show SpecialWords ON/OFF
	* @return boolean $special_words_status  Show SpecialWords YES/NO
	* @see setSpecialWordsStatus()
	*/
	function &getSpecialWordsStatus() {
		$this->setSpecialWordsStatus();
		return (boolean) $this->special_words_status;
	} // end function

	/**
	* Returns display-format for time values
	*
	* Returns the integer code for the prefered timeformat
	* (swatch, iso, standard)
	*
	* @desc Returns display-format for time values
	* @return int $timeset  0|1|2
	* @see setPreferedTime()
	* @see FormatDate::getTimeset()
	*/
	function &getPreferedTime() {
		$this->setPreferedTime();
		return (int) $this->timeset;
	} // end function

	/**
	* Returns the name for the <select> html-element for choosing the
	* display-format for time values
	*
	* @desc Returns the name for the <select> html-element
	* @return string $time_select_name
	* @see FormatDate::getTimeSelectName()
	*/
	function &getTimeSelectName() {
		return (string) $this->time_select_name;
	} // end function

	/**
	* Returns the name for session variable for saving the display-format for
	* time values
	*
	* @desc Returns the name for session variable
	* @return string $Time_select_sessionname
	* @see FormatDate::getTimeSelectSessionName()
	*/
	function &getTimeSelectSessionName() {
		return (string) $this->Time_select_sessionname;
	} // end function

	/**
	* Returns the name for cookie variable for saving the display-format for
	* time values
	*
	* @desc Returns the name for cookie variable
	* @return string $time_select_cookiename
	* @see FormatDate::getTimeSelectCookieName()
	*/
	function &getTimeSelectCookieName() {
		return (string) $this->time_select_cookiename;
	} // end function

	/**
	* Returns the iso-code
	*
	* @desc Returns the iso-code
	* @return string $PreferedLanguage  iso-code
	* @uses setPreferedLanguage()
	* @see Language::ChooseLang()
	*/
	function &getPreferedLanguage() {
		$this->setPreferedLanguage();
		return (string) $this->prefered_language;
	} // end function

	/**
	* Returns the iso-code
	*
	* @desc Returns the iso-code
	* @return string $PreferedLocale  iso-code
	* @uses setPreferedLocale()
	* @see Language::ChooseLocale()
	* @since 1.044 - 2003-02-18
	*/
	function &getPreferedLocale() {
		$this->setPreferedLocale();
		return (string) $this->prefered_locale;
	} // end function

	/**
	* Returns the iso-code
	*
	* @desc Returns the iso-code
	* @return string $PreferedContentLanguage  iso-code
	* @uses setPreferedContentLanguage()
	* @see Language::ChooseLangContent()
	*/
	function &getPreferedContentLanguage() {
		$this->setPreferedContentLanguage();
		return (string) $this->prefered_content_language;
	} // end function

	/**
	* Returns the iso-code
	*
	* @desc Returns the iso-code
	* @return string $PreferedCountry  iso-code
	* @uses setPreferedCountry()
	* @see Language::ChooseCountry()
	*/
	function &getPreferedCountry() {
		$this->setPreferedCountry();
		return (string) $this->prefered_country;
	} // end function

	/**
	* Returns the user timezone
	*
	* @desc Returns the user timezone
	* @return string $timezone
	* @uses setPreferedTimezone()
	* @since 1.001 - 2002-11-09
	*/
	function &getPreferedTimezone() {
		$this->setPreferedTimezone();
		return (string) $this->timezone;
	} // end function

	/**
	* Returns display-format for measure values
	*
	* Returns the string code for the prefered measure system (metric, us)
	*
	* @desc Returns display-format for measure values
	* @return string $measure_system  si|uscs
	* @see Measure
	* @see Measure::setOutput()
	* @uses setPreferedMeasureSystem()
	* @since 1.001 - 2002-11-13
	*/
	function &getPreferedMeasureSystem() {
		$this->setPreferedMeasureSystem();
		return (string) $this->measure_system;
	} // end function

	/**
	* Returns the name for the language <select> tag
	*
	* @desc Returns the name for the language <select> tag
	* @return string $lang_select_name
	* @see getLangSessionName()
	* @see getLangCookieName()
	*/
	function &getLangSelectName() {
		return (string) $this->lang_select_name;
	} // end function

	/**
	* Returns the name for the language session variable
	*
	* @desc Returns the name for the language session variable
	* @return string $lang_sessionname
	* @see getLangSelectName()
	* @see getLangCookieName()
	*/
	function &getLangSessionName() {
		return (string) $this->lang_sessionname;
	} // end function

	/**
	* Returns the name for the language cookie variable
	*
	* @desc Returns the name for the language cookie variable
	* @return string $lang_cookiename
	* @see getLangSelectName()
	* @see getLangSessionName()
	*/
	function &getLangCookieName() {
		return (string) $this->lang_cookiename;
	} // end function

	/**
	* Returns the name for the language <select> tag
	*
	* @desc Returns the name for the language <select> tag
	* @return string $locale_select_name
	* @see getLocaleSessionName()
	* @see getLocaleCookieName()
	*/
	function &getLocaleSelectName() {
		return (string) $this->locale_select_name;
	} // end function

	/**
	* Returns the name for the language session variable
	*
	* @desc Returns the name for the language session variable
	* @return string $locale_sessionname
	* @see getLocaleSelectName()
	* @see getLocaleCookieName()
	*/
	function &getLocaleSessionName() {
		return (string) $this->locale_sessionname;
	} // end function

	/**
	* Returns the name for the language cookie variable
	*
	* @desc Returns the name for the language cookie variable
	* @return string $locale_cookiename
	* @see getLocaleSelectName()
	* @see getLocaleSessionName()
	*/
	function &getLocaleCookieName() {
		return (string) $this->locale_cookiename;
	} // end function

	/**
	* Returns the name for the content-language <select> tag
	*
	* @desc Returns the name for the content-language <select> tag
	* @return string $lang_content_select_name
	* @see getLangContentSessionName()
	* @see getLangContentCookieName()
	*/
	function &getLangContentSelectName() {
		return (string) $this->lang_content_select_name;
	} // end function

	/**
	* Returns the name for the content-language session variable
	*
	* @desc Returns the name for the content-language session variable
	* @return string $lang_content_sessionname
	* @see getLangContentSelectName()
	* @see getLangContentCookieName()
	*/
	function &getLangContentSessionName() {
		return (string) $this->lang_content_sessionname;
	} // end function

	/**
	* Returns the name for the content-language cookie variable
	*
	* @desc Returns the name for the content-language cookie variable
	* @return string $lang_content_cookiename
	* @see getLangContentSelectName()
	* @see getLangContentSessionName()
	*/
	function &getLangContentCookieName() {
		return (string) $this->lang_content_cookiename;
	} // end function

	/**
	* Returns the name for the country <select> tag
	*
	* @desc Returns the name for the country <select> tag
	* @return string $country_select_name
	* @see getCountrySessionName()
	* @see getCountryCookieName()
	*/
	function &getCountrySelectName() {
		return (string) $this->country_select_name;
	} // end function

	/**
	* Returns the name for the country session variable
	*
	* @desc Returns the name for the country session variable
	* @return string $country_sessionname
	* @see getCountrySelectName()
	* @see getCountryCookieName()
	*/
	function &getCountrySessionName() {
		return (string) $this->country_sessionname;
	} // end function

	/**
	* Returns the name for the country cookie variable
	*
	* @desc Returns the name for the country cookie variable
	* @return string $country_cookiename
	* @see getCountrySelectName()
	* @see getCountrySessionName()
	*/
	function &getCountryCookieName() {
		return (string) $this->country_cookiename;
	} // end function

	/**
	* Returns the name for the measure <select> tag
	*
	* @desc Returns the name for the measure <select> tag
	* @return string $measure_selectname
	* @see getMeasureSessionName()
	* @see getMeasureCookieName()
	* @since 1.043 - 2003-02-13
	*/
	function &getMeasureSelectName() {
		return (string) $this->measure_selectname;
	} // end function

	/**
	* Returns the name for the measure session variable
	*
	* @desc Returns the name for the measure session variable
	* @return string $measure_sessionname
	* @see getMeasureSelectName()
	* @see getMeasureCookieName()
	* @since 1.043 - 2003-02-13
	*/
	function &getMeasureSessionName() {
		return (string) $this->measure_sessionname;
	} // end function

	/**
	* Returns the name for the measure cookie variable
	*
	* @desc Returns the name for the measure cookie variable
	* @return string $measure_cookiename
	* @see getMeasureSelectName()
	* @see getMeasureSessionName()
	* @since 1.043 - 2003-02-13
	*/
	function &getMeasureCookieName() {
		return (string) $this->measure_cookiename;
	} // end function

	/**
	* Returns the name for the css-class attribute for form elements
	*
	* @desc Returns the name for the css-class attribute for form elements
	* @return string $select_css_class
	* @see setSelectCSSClass()
	* @since 1.043 - 2003-02-13
	*/
	function &getSelectCSSClass() {
		return (string) $this->select_css_class;
	} // end function
	/**#@-*/
} // end class i18nUser
?>