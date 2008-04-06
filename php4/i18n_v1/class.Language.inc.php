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
* Including the user class is necessary for getting the user-preferences
*/
@require_once 'class.I18NUser.inc.php';
/**
* Including the abstract base class
*/
@require_once 'class.I18N.inc.php';
/**
* Determinates the user language
*
* There are a couple of different ways to define the language that should be
* used for translation. First the script looks out for a variable given by
* POST or GET, then tries to read from a cookie or session. If still no
* variable has been found it checks the prefered language set by the user’s
* browser and at last trys to get the domain from the users ip number.
*
* Tested with WAMP (XP-SP1/1.3.24/4.0.12/4.3.0)
* Last change: 2003-05-04
*
* @desc Determinates the user language
* @access public
* @author Michael Wimmer <flaimo@gmx.net>
* @copyright Michael Wimmer
* @link http://www.flaimo.com/
* @package i18n
* @category FLP
* @version 1.060
*/
class Language extends I18N {

	/*-------------------*/
	/* V A R I A B L E S */
	/*-------------------*/

	/**#@+
	* No Information available
	*
	* @desc No Information available
	* @access private
	* @var string
	*/
	var $input_lang;
	var $input_country;
	var $lang;
	var $locale;
	var $country;
	var $lang_content;
	var $default_locale = 'en'; // PHP5: protected
	var $default_language = 'en'; // PHP5: protected
	var $default_country = 'us'; // PHP5: protected
	/**#@-*/

	/**#@+
	* No Information available
	*
	* @desc No Information available
	* @access private
	*/
	/**
	* @var array
	*/
	var $user_raw_array;

	/**
	* @var array
	*/
	var $user_lang_array;

	/**
	* @var array
	*/
	var $user_country_array;

	/**
	* @var object
	*/
	var $user;
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
	* @param string $inputlang  You can set a language manually and override all other settings
	* @return void
	* @access private
	* @uses I18N::I18N()
	* @uses getCountrySelectName()
	*/
	function Language($inputlang = '') {
		parent::I18N();
		$this->input_lang 		= (string) $inputlang;
		$this->input_country 	= (string) (((isset($_GET[$this->getCountrySelectName()])) && (strlen(trim($_GET[$this->getCountrySelectName()])) > 0)) ? $_GET[$this->getCountrySelectName()] : '');
	} // end constructor

	/*-------------------*/
	/* F U N C T I O N S */
	/*-------------------*/

	/**
	* Reads the default settings from the settings file if necessary
	*
	* @desc Reads the default settings from the settings file if necessary
	* @return void
	* @access protected
	* @uses I18N::readINIsettings()
	* @since 1.055 - 2003-04-30
	*/
	function readINIsettings() {
		parent::readINIsettings();
	} // end function

	/**
	* Reads the default settings from the settings file if necessary
	*
	* @desc Reads the default settings from the settings file if necessary
	* @return void
	* @access private
	* @since 1.045 - 2003-02-21
	*/
	function readDefaultLanguageSettings() {
        parent::readINIsettings();
        if (isset($GLOBALS[$this->i18n_globalname])) {
            $this->default_locale 	=& $GLOBALS[$this->i18n_globalname]['Language']['default_locale'];
            $this->default_language =& $GLOBALS[$this->i18n_globalname]['Language']['default_language'];
            $this->default_country 	=& $GLOBALS[$this->i18n_globalname]['Language']['default_country'];
    	} // end if
	} // end function

	/**#@+
	* @access public
	*/
	/**
	* Returns the ISO code for the content
	*
	* @desc Returns the ISO code for the content
	* @return string $lang_content
	* @see getLang()
	* @see getCountry()
	*/
	function &getLangContent() {
		if (!isset($this->lang_content)){
			$this->chooseLangContent();
		}
		return (string) $this->lang_content;
	} // end function

	/**
	* Returns the ISO code for the (osm)language
	*
	* @desc Returns the ISO code for the (osm)language
	* @return string $lang
	* @see getLangContent()
	* @see getCountry()
	* @see getLocale()
	*/
	function &getLang() {
		if (!isset($this->lang)){
			$this->chooseLang();
		}
		return (string) $this->lang;
	} // end function

	/**
	* Returns the iso code for the locale
	*
	* @desc Returns the iso code for the locale
	* @return string $locale
	* @see getLangContent()
	* @see getCountry()
	* @see getLang()
	* @since 1.044 - 2003-02-18
	*/
	function &getLocale() {
		if (!isset($this->locale)){
			$this->chooseLocale();
		}
		return (string) $this->locale;
	} // end function

	/**
	* Returns the ISO code for the country
	*
	* @desc Returns the ISO code for the country
	* @return string $country
	* @see getLangContent()
	* @see getLang()
	* @see getLocale()
	*/
	function &getCountry() {
		if (!isset($this->country)){
			$this->chooseCountry();
		}
		return (string) $this->country;
	} // end function

	/**
	* Returns the ISO code for the input language
	*
	* @desc Returns the ISO code for the input language
	* @return string $inputlang
	* @see getInputCountry()
	* @see getLocale()
	*/
	function &getInputLang() {
		return (string) $this->input_lang;
	} // end function

	/**
	* Returns the ISO code for the input country
	*
	* @desc Returns the ISO code for the input country
	* @return string $inputcountry
	* @see getInputLang()
	* @see getLocale()
	*/
	function &getInputCountry() {
		return (string) $this->input_country;
	} // end function

	/**
	* Returns the name for the country <select> tag
	*
	* @desc Returns the name for the country <select> tag
	* @return string $countryselectname
	* @see getCountrySessionName()
	* @see getCountryCookieName()
	* @uses loadUserClass()
	* @uses i18nUser::getCountrySelectName()
	*/
	function &getCountrySelectName() {
		$this->loadUserClass();
		return (string) $this->user->getCountrySelectName();
	} // end function

	/**
	* Returns the name for the country session variable
	*
	* @desc Returns the name for the country session variable
	* @return string $countrysessionname
	* @see getCountrySelectName()
	* @see getCountryCookieName()
	* @uses loadUserClass()
	* @uses i18nUser::getCountrySessionName()
	*/
	function &getCountrySessionName() {
		$this->loadUserClass();
		return (string) $this->user->getCountrySessionName();
	} // end function

	/**
	* Returns the name for the country cookie variable
	*
	* @desc Returns the name for the country cookie variable
	* @return string $countrycookiename
	* @see getCountrySelectName()
	* @see getCountrySessionName()
	* @uses loadUserClass()
	* @uses i18nUser::getCountryCookieName()
	*/
	function &getCountryCookieName() {
		$this->loadUserClass();
		return (string) $this->user->getCountryCookieName();
	} // end function

	/**
	* Returns the default language (kind of “backup” if no language ISO code wasn’t found at all)
	*
	* @desc Returns the default language (kind of “backup” if no language ISO code wasn’t found at all)
	* @return string $defaultLanguage
	*/
	function &getDefaultLanguage() {
		return (string) $this->default_language;
	} // end function

	/**
	* Returns the default locale
	*
	* @desc Returns the default locale
	* @return string $defaultLocale
	* @since 1.044 - 2003-02-18
	*/
	function &getDefaultLocale() {
		return (string) $this->default_locale;
	} // end function

	/**
	* Returns the default country
	*
	* @desc Returns the default country
	* @return string $defaultCountry
	* @since 1.044 - 2003-02-18
	*/
	function &getDefaultCountry() {
		return (string) $this->default_country;
	} // end function

	/**
	* Returns an array with the raw user-request header (de-at, de, en-us,…)
	*
	* @desc Returns an array with the raw user-request header (de-at, de, en-us,…)
	* @return (array) $userRawArray
	* @see getUserLangArray()
	* @see getUserCountryArray()
	*/
	function &getUserRawArray() {
		if (!isset($this->user_raw_array)) {
            $this->readUserHeader();
        } // end if
		return (array) $this->user_raw_array;
	} // end function

	/**
	* Returns an array with the languages extracted from the raw user-request header (de-xx, en-xx,…)
	*
	* @desc Returns an array with the languages extracted from the raw user-request header (de-xx, en-xx,…)
	* @return (array) $userLangArray
	* @see getUserRawArray()
	* @see getUserCountryArray()
	* @uses readUserHeader()
	*/
	function &getUserLangArray() {
		if (!isset($this->user_lang_array)) {
            $this->readUserHeader();
        } // end if
        return (array) $this->user_lang_array;
	} // end function

	/**
	* Returns an array with the countries extracted from the raw user-request header (xx-at, xx-us,…)
	*
	* @desc Returns an array with the countries extracted from the raw user-request header (xx-at, xx-us,…)
	* @return (array) $userCountryArray
	* @see getUserRawArray()
	* @see getUserLangArray()
	*/
	function &getUserCountryArray() {
		if (!isset($this->user_country_array)) {
            $this->readUserHeader();
        } // end if
		return (array) $this->user_country_array;
	} // end function

	/**
	* Returns user object
	*
	* @desc Returns user object
	* @return object $user
	* @see User
	* @uses loadUserClass()
	* @since 1.020 - 2002-12-29
	*/
	function &getUser() {
        $this->loadUserClass();
        return (object) $this->user;
	} // end function

	/**
	* Checks if a given string is a valid iso-language-code
	*
	* @desc Checks if a given string is a valid iso-language-code
	* @param string $code  String that should validated
	* @return boolean If string is valid or not
	* @static
	*/
	function isValidLanguageCode($code) {
		// $testRegExp = '(^(af|sq|eu|be|bg|ca|zh-cn|zh-tw|hr|cs|da|nl|nl-be|nl-nl|en|en-au|en-bz|en-ca|en-ie|en-jm|en-nz|en-ph|en-za|en-tt|en-gb|en-us|en-zw|et|fo|fi|fr|fr-be|fr-ca|fr-fr|fr-lu|fr-mc|fr-ch|gl|gd|de|de-at|de-de|de-li|de-lu|de-ch|el|haw|hu|is|in|ga|it|it-it|it-ch|ja|ko|mk|no|pl|pt|pt-br|pt-pt|ro|ro-mo|ro-ro|ru|ru-mo|ru-ru|sr|sk|sl|es|es-ar|es-bo|es-cl|es-co|es-cr|es-do|es-ec|es-sv|es-gt|es-hn|es-mx|es-ni|es-pa|es-py|es-pe|es-pr|es-es|es-uy|es-ve|sv|sv-fi|sv-se|tr|uk)$)';

		return (boolean) ((preg_match('(^([a-zA-Z]{2})((_|-)[a-zA-Z]{2})?$)',$code) > 0) ? TRUE : FALSE);
	} // end function
	/**#@-*/

	/**#@+
	* @access private
	* @return void
	*/
	/**
	* Changes the language of the object. Also loads the new translationfile
	*
	* @desc Changes the language of the object. Also loads the new translationfile
	* @param string $language  iso-code
	* @see setLang()
	* @uses chooseLang()
	* @since 1.044 - 2003-02-18
	*/
	function changeLocale($locale) {
		$this->input_lang = $locale;
        $this->chooseLocale();
	} // end function

	/**
	* Creates a news User object if the class var user doesn’t exists
	*
	* @desc Creates a news User object if the class var user doesn’t exists
	* @uses User
	*/
	function loadUserClass() {
		if (!isset($this->user)) {
			$this->user =& new i18nUser();
		} // end if
	} // end function

	/**
	* Sets the ISO code for the content-language
	*
	* @desc Sets the ISO code for the content-language
	* @param string $language  String to be assigned to the class variable lang_content
	* @see setLang()
	* @see setCountry()
	*/
	function setLangContent($language) {
		$this->lang_content = (string) $language;
	} // end function

	/**
	* Sets the ISO code for the (osm)language
	*
	* @desc Sets the ISO code for the (osm)language
	* @param string $language  String to be assigned to the class variable lang
	* @see setLangContent()
	* @see setCountry()
	* @see setLocale()
	*/
	function setLang($language) {
		$this->lang = (string) $language;
	} // end function

	/**
	* Same as the {@link setLang()} function, only for the raw header
	*
	* @desc Same as the setLang() function, only for the raw header
	* @param string $locale  String to be assigned to the class variable locale
	* @see setLangContent()
	* @see setCountry()
	* @see setLang()
	* @since 1.044 - 2003-02-18
	*/
	function setLocale($locale) {
		$this->locale = (string) $locale;
	} // end function

	/**
	* Sets the ISO code for the manual input language
	*
	* @desc Sets the ISO code for the manual input language
	* @param string $language  String to be assigned to the class variable lang
	* @see setLangContent()
	* @see setCountry()
	* @see setLocale()
	*/
	function setInputLang($language = '') {
		$this->input_lang = (string) $language;
	} // end function

	/**
	* Sets the ISO code for the country
	*
	* @desc Sets the ISO code for the country
	* @param string $country  String to be assigned to the class variable country
	* @see setLangContent()
	* @see setLang()
	* @see setLocale()
	*/
	function setCountry($country) {
		$this->country = (string) $country;
	} // end function

	/**
	* Adds an entry to the raw user-header array
	*
	* @desc Adds an entry to the raw user-header array
	* @param string $string  String to be added (ISO code)
	* @see setUserLangArray()
	* @see setUserCountryArray()
	*/
	function setUserRawArray($string) {
		$this->user_raw_array[] = $string;
	} // end function

	/**
	* Adds an entry to the user-languages array
	*
	* @desc Adds an entry to the user-languages array
	* @param string $string  String to be added (ISO code)
	* @see setUserRawArray()
	* @see setUserCountryArray()
	*/
	function setUserLangArray($string) {
		$this->user_lang_array[] = $string;
	} // end function

	/**
	* Adds an entry to the country array
	*
	* @desc Adds an entry to the country array
	* @param string $string  String to be added (ISO code)
	* @see setUserRawArray()
	* @see setUserLangArray()
	*/
	function setUserCountryArray($string) {
		$this->user_country_array[] = $string;
	} // end function
	/**#@-*/

	/**
	* Filters an array
	*
	* Filters all empty values and dublicates of a given array
	*
	* @desc Filters an array
	* @param array $arrayname
	* @return void
	* @access protected
	*/
	function trimArray($arrayname) {
		if (count($this->$arrayname) > 0) {
			$this->$arrayname = array_values(array_unique($this->$arrayname));
		} // end if
	} // end function

	/**
	* Checks the users request-header for language informations
	*
	* Reads the header of the request and splits it up to 3 arrays:
	* one array with the raw data, one with all the possible
	* (osm)languages and one with all the possible countries. Depends on
	* how the user has set up his browser and/or the domain of his ip adress.
	*
	* @desc Checks the users request-header for language informations
	* @return void
	* @access protected
	* @uses isValidLanguageCode()
	* @uses setUserRawArray()
	* @uses trimArray()
	* @uses setUserLangArray()
	* @uses setUserCountryArray()
	* @uses readDefaultLanguageSettings()
	*/
	function readUserHeader() {
		/* get raw languages */
		if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) &&
			count($pref_user_codes = (array) explode(',',str_replace('-', '_', strtolower($_SERVER['HTTP_ACCEPT_LANGUAGE'])))) > 0) {
			$pref_user_codes = array_map('trim', $pref_user_codes);
			foreach ($pref_user_codes as $raw_entry) { // Strip ";"s
				/* strip of useless quantity information */
				if (($cutat = (int) strpos($raw_entry,';')) == TRUE) {
					$raw_entry = (string) substr($raw_entry, 0, $cutat);
				} // end if
				if ($this->isValidLanguageCode($raw_entry) === FALSE) {
					continue;
				} // endif
				$this->setUserRawArray($raw_entry);
				if (($splitcut = (int) strpos($raw_entry,'_')) == TRUE) {
					$this->setUserLangArray(substr($raw_entry, 0, $splitcut));
					$this->setUserCountryArray(substr($raw_entry, ($splitcut+1), 2));
				} else {
					$this->setUserLangArray($raw_entry);
				} // end if
			} // end foreach
			unset($raw_entry);
		} else {
			/* if no header infos available, use default */
			$this->readDefaultLanguageSettings();
			$this->setUserRawArray($this->default_locale);
			$this->setUserLangArray($this->default_language);
		} // end if
		$this->trimArray('user_raw_array');
		$this->trimArray('user_lang_array');

		/* get domain if user's provider, else webpagehost */
		$country = $this->domain2Country(gethostbyaddr($_SERVER['REMOTE_ADDR']));
		if (strlen(trim($country)) > 0) {
			$this->setUserCountryArray($country);
		} else { // get country by requested URL
			$country = $this->domain2Country($_SERVER['SERVER_NAME']);
			if (strlen(trim($country)) > 0) {
				$this->setUserCountryArray($country);
			} // end if
		} // end if
		unset($country);
		$this->trimArray('user_country_array');
	} // end function

	/**
	* Returns the country of a domainname if possible
	*
	* @desc Returns the country of a domainname if possible
	* @param string $host  domainname (example: cnn.com)
	* @return string  iso code of the country or empty if no country was found
	* @access public
	* @static
	* @since 1.051 - 2003-02-26
	*/
	function domain2Country($host = '') {
		if (($lastdot = (int) strrpos($host,'.')) > 0) {
			$domain_length = (int) strlen($host) - ($lastdot+1);
			$domain_name = (string) substr($host, $lastdot+1);
			/* top level domains (com, org, gov, aero, info) or eu-domain are all english */
			if ($domain_length > 2 || $domain_name === 'eu') {
				return (string) 'en';
			} elseif ($domain_length === 2) { // country domains
				return (string) trim(substr($host, ($lastdot+1), $domain_length));
			} else {
				return (string) '';
			} // end if
		} // end if
		return (string) '';
	} // end function

	/**#@+
	* @access private
	* @return void
	* @uses readUserHeader()
	* @uses loadUserClass()
	* @uses readDefaultLanguageSettings()
	* @uses isValidLanguageCode()
	*/
	/**
	* Sets the (osm)language ISO code
	*
	* sets the (osm)language (iso code) depending on the given information
	* available. first looks if the value is set by the GET method (GET not
	* working at the moment), then checkes if a prefered code has been set in
	* the user class.
	*
	* @desc Sets the (osm)language ISO code
	* @see chooseLangContent()
	* @see chooseCountry()
	* @uses setLang()
	* @uses i18nUser::getPreferedLanguage()
	*/
	function chooseLang() {
		$this->loadUserClass();
		if ($this->isValidLanguageCode($this->input_lang) === TRUE) {
			$this->setLang($this->input_lang);
		} elseif ($this->isValidLanguageCode($this->user->getPreferedLanguage()) === TRUE) {
			$this->setLang($this->user->getPreferedLanguage());
		} else {
			if (!isset($this->user_lang_array)) {
				$this->readUserHeader();
			} // end if
			if (count($this->user_lang_array) > 0) {
                $this->setLang($this->user_lang_array[0]);
			} else {
				$this->readDefaultLanguageSettings();
				$this->setLang($this->default_language);
			} // end if
		} // end if
	} // end function


	/**
	* Sets the locale ISO code
	*
	* sets the locale (iso code) depending on the given information
	* available. first looks if the value is set by the GET method (GET not
	* working at the moment), then checkes if a prefered code has been set in
	* the user class.
	*
	* @desc Sets the (osm)language ISO code
	* @see chooseLangContent()
	* @see chooseCountry()
	* @uses setLang()
	* @uses i18nUser::getPreferedLocale()
	* @since 1.044 - 2003-02-18
	*/
	function chooseLocale() {
		$this->loadUserClass();
		if ($this->isValidLanguageCode($this->input_lang) === TRUE) {
			$this->setLocale($this->input_lang);
		} elseif ($this->isValidLanguageCode($this->user->getPreferedLocale()) === TRUE) {
			$this->setLocale($this->user->getPreferedLocale());
		} else {
			if (!isset($this->user_raw_array)) {
				$this->readUserHeader();
			} // end if
			if (count($this->user_raw_array) > 0) {
                $this->setLocale($this->user_raw_array[0]);
			} else {
				$this->readDefaultLanguageSettings();
				$this->setLocale($this->default_locale);
			} // end if
		} // end if
	} // end function

	/**
	* Sets the country ISO code
	*
	* Sets the country (iso code) depending on the given information available.
	* first looks if the value is set by the GET method, then checkes if
	* if a prefered code has been set in the user class. if still no value has
	* been found, it takes the country array set by the readUserHeader() function.
	*
	* @desc Sets the country ISO code
	* @see chooseLang()
	* @see chooseLangContent()
	* @uses setCountry()
	* @uses i18nUser::getPreferedCountry()
	*/
	function chooseCountry() {
		$this->loadUserClass();
		if ($this->isValidLanguageCode($this->input_country) === TRUE) {
			$this->setCountry($this->input_country);
		} elseif ($this->isValidLanguageCode($this->user->getPreferedCountry()) === TRUE) {
			$this->setCountry($this->user->getPreferedCountry());
		} else {
			if (!isset($this->user_country_array)) {
				$this->readUserHeader();
			} // end if
			if (count($this->user_country_array) > 0) {
                $this->setCountry($this->user_country_array[0]);
			} else {
				//echo 'here';
				$this->readDefaultLanguageSettings();
				$this->setCountry($this->default_country);
			} // end if
		} // end if
	} // end function

	/**
	* Sets the content-language ISO code
	*
	* sets the content-language (iso code) depending on the given information
	* available. first looks if the value is set by the GET method (GET not
	* working at the moment), then checkes if a prefered code has been set in
	* the user class. if still no value has been found, it takes the
	* content-language-array set by the readUserHeader()
	* function.
	*
	* @desc Sets the content-language ISO code
	* @see chooseLang()
	* @see chooseCountry()
	* @uses loadUserClass()
	* @uses setLangContent()
	* @uses i18nUser::getPreferedContentLanguage()
	*/
	function chooseLangContent() {
		$this->loadUserClass();
		if ($this->isValidLanguageCode($this->input_lang) === TRUE) {
			$this->setLangContent($this->input_lang);
		} elseif ($this->isValidLanguageCode($this->user->getPreferedContentLanguage()) === TRUE) {
			$this->setLangContent($this->user->getPreferedContentLanguage());
		} else {
			switch ($this->lang) {
				case 'de':
					$this->setLangContent($this->lang);
					break;
				default:
					$this->readDefaultLanguageSettings();
					$this->setLangContent($this->default_language);
					break;
			} // end switch
		} // end if
	} // end function
	/**#@-*/
} // end class Language
?>