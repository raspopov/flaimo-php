<?php
/**
* @package i18n
*/
/**
* Including the user class is necessary for getting the user-preferences
*/
@include_once('class.User.inc.php');

/**
* Determinates the user language
*
* There are a couple of different ways to define the language that should be
* used for translation. First the script looks out for a variable given by
* POST or GET, then tries to read from a cookie or session. If still no
* variable has been found it checks the prefered language set by the user’s
* browser and at last trys to get the domain from the users ip number.
*
* Tested with WAMP (XP-SP1/1.3.24/4.0.4/4.3.0)
* Last change: 2003-02-26
*
* @desc Determinates the user language
* @access public
* @author Michael Wimmer <flaimo@gmx.net>
* @copyright Michael Wimmer
* @link http://www.flaimo.com/
* @package i18n
* @version 1.053
*/
class Language {

	/*-------------------*/
	/* V A R I A B L E S */
	/*-------------------*/

	/**
	* No Information available
	*
	* @desc No Information available
	* @var string
	* @access private
	*/
	var $input_lang; // PHP5: protected

	/**
	* No Information available
	*
	* @desc No Information available
	* @var string
	* @access public
	*/
	var $lang;

	/**
	* No Information available
	*
	* @desc No Information available
	* @var string
	* @access public
	*/
	var $locale;

	/**
	* No Information available
	*
	* @desc No Information available
	* @var string
	* @access public
	*/
	var $country;

	/**
	* No Information available
	*
	* @desc No Information available
	* @var string
	* @access public
	*/
	var $lang_content;

	/**
	* No Information available
	*
	* @desc No Information available
	* @var array
	* @access private
	*/
	var $userRawArray;

	/**
	* No Information available
	*
	* @desc No Information available
	* @var array
	* @access private
	*/
	var $userLangArray;

	/**
	* No Information available
	*
	* @desc No Information available
	* @var array
	* @access private
	*/
	var $userCountryArray;

	/**
	* No Information available
	*
	* @desc No Information available
	* @var string
	* @access private
	*/
	var $defaultLocale = 'en'; // PHP5: protected

	/**
	* No Information available
	*
	* @desc No Information available
	* @var string
	* @access private
	*/
	var $defaultLanguage = 'en'; // PHP5: protected

	/**
	* No Information available
	*
	* @desc No Information available
	* @var string
	* @access private
	*/
	var $defaultCountry = 'us'; // PHP5: protected

	/**
	* Holds the user object
	*
	* @desc Holds the user object
	* @var object
	* @access private
	*/
	var $user;

	/**
	* Holds the settings for this class
	*
	* @desc Holds the settings for this class
	* @var array
	* @access private
	*/
	var $language_settings;

	/*-----------------------*/
	/* C O N S T R U C T O R */
	/*-----------------------*/

	/**
	* Constructor
	*
	* No information available
	*
	* @desc Constructor
	* @param (string) $inputlang  You can set a language manually and override all other settings
	* @return (void)
	* @access private
	* @uses setSelectCSSClass(), setSelectCSSClass(), ChooseLang(), ChooseLangContent(), ChooseCountry()
	* @since 1.000 - 2002/10/10
	*/
	function Language($inputlang = '') {
		$this->inputlang 		= (string) $inputlang;
		$this->inputcountry 	= (string) (((isset($_GET[$this->getCountrySelectName()])) && (strlen(trim($_GET[$this->getCountrySelectName()])) > 0)) ? $_GET[$this->getCountrySelectName()] : '');
	} // end constructor

	/*-------------------*/
	/* F U N C T I O N S */
	/*-------------------*/

	/**
	* Reads the default settings from the settings file if necessary
	*
	* @desc Reads the default settings from the settings file if necessary
	* @return (void)
	* @access private
	* @since 1.045 - 2003/02/21
	*/
	function readDefaultLanguageSettings() {
        if (!isset($this->language_settings) && file_exists('i18n_settings.ini')) {
            $this->language_settings = (array) parse_ini_file('i18n_settings.ini', TRUE);
            $this->defaultLocale 	=& $this->language_settings['Language']['default_locale'];
            $this->defaultLanguage 	=& $this->language_settings['Language']['default_language'];
            $this->defaultCountry 	=& $this->language_settings['Language']['default_country'];
    	} // end if
	} // end function

	/**
	* Returns the ISO code for the content
	*
	* @desc Returns the ISO code for the content
	* @return (string) $lang_content
	* @access public
	* @see getLang(), getCountry()
	* @since 1.000 - 2002/10/10
	*/
	function &getLangContent() {
		if (!isset($this->lang_content)){
			$this->ChooseLangContent();
		}
		return (string) $this->lang_content;
	} // end function

	/**
	* Returns the ISO code for the (osm)language
	*
	* @desc Returns the ISO code for the (osm)language
	* @return (string) $lang
	* @access public
	* @see getLangContent(), getCountry(), getLocale()
	* @since 1.000 - 2002/10/10
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
	* @return (string) $locale
	* @access public
	* @see getLangContent(), getCountry(), getLang()
	* @since 1.044 - 2003/02/18
	*/
	function &getLocale() {
		if (!isset($this->locale)){
			$this->ChooseLocale();
		}
		return (string) $this->locale;
	} // end function

	/**
	* Returns the ISO code for the country
	*
	* @desc Returns the ISO code for the country
	* @return (string) $country
	* @access public
	* @see getLangContent(), getLang(), getLocale()
	* @since 1.000 - 2002/10/10
	*/
	function &getCountry() {
		if (!isset($this->country)){
			$this->ChooseCountry();
		}
		return (string) $this->country;
	} // end function

	/**
	* Returns the ISO code for the input language
	*
	* @desc Returns the ISO code for the input language
	* @return (string) $inputlang
	* @access public
	* @see getInputCountry(), getLocale()
	* @since 1.000 - 2002/10/10
	*/
	function &getInputLang() {
		return (string) $this->inputlang;
	} // end function

	/**
	* Returns the ISO code for the input country
	*
	* @desc Returns the ISO code for the input country
	* @return (string) $inputcountry
	* @access public
	* @see getInputLang(), getLocale()
	* @since 1.000 - 2002/10/10
	*/
	function &getInputCountry() {
		return (string) $this->inputcountry;
	} // end function

	/**
	* Returns the name for the country <select> tag
	*
	* @desc Returns the name for the country <select> tag
	* @return (string) $countryselectname
	* @access public
	* @see getCountrySessionName(), getCountryCookieName()
	* @uses loadUserClass(), User::getCountrySelectName()
	* @since 1.000 - 2002/10/10
	*/
	function &getCountrySelectName() {
		$this->loadUserClass();
		return (string) $this->user->getCountrySelectName();
	} // end function

	/**
	* Returns the name for the country session variable
	*
	* @desc Returns the name for the country session variable
	* @return (string) $countrysessionname
	* @access public
	* @see getCountrySelectName(), getCountryCookieName()
	* @uses loadUserClass(), User::getCountrySessionName()
	* @since 1.000 - 2002/10/10
	*/
	function &getCountrySessionName() {
		$this->loadUserClass();
		return (string) $this->user->getCountrySessionName();
	} // end function

	/**
	* Returns the name for the country cookie variable
	*
	* @desc Returns the name for the country cookie variable
	* @return (string) $countrycookiename
	* @access public
	* @see getCountrySelectName(), getCountrySessionName()
	* @uses loadUserClass(), User::getCountryCookieName()
	* @since 1.000 - 2002/10/10
	*/
	function &getCountryCookieName() {
		$this->loadUserClass();
		return (string) $this->user->getCountryCookieName();
	} // end function

	/**
	* Returns the default language (kind of “backup” if no language ISO code wasn’t found at all)
	*
	* @desc Returns the default language (kind of “backup” if no language ISO code wasn’t found at all)
	* @return (string) $defaultLanguage
	* @access public
	* @since 1.000 - 2002/10/10
	*/
	function &getDefaultLanguage() {
		return (string) $this->defaultLanguage;
	} // end function

	/**
	* Returns the default locale
	*
	* @desc Returns the default locale
	* @return (string) $defaultLocale
	* @access public
	* @since 1.044 - 2003/02/18
	*/
	function &getDefaultLocale() {
		return (string) $this->defaultLocale;
	} // end function

	/**
	* Returns the default country
	*
	* @desc Returns the default country
	* @return (string) $defaultCountry
	* @access public
	* @since 1.044 - 2003/02/18
	*/
	function &getDefaultCountry() {
		return (string) $this->defaultCountry;
	} // end function

	/**
	* Returns an array with the raw user-request header (de-at, de, en-us,...)
	*
	* @desc Returns an array with the raw user-request header (de-at, de, en-us,...)
	* @return (array) $userRawArray
	* @access public
	* @see getUserLangArray(), getUserCountryArray()
	* @since 1.000 - 2002/10/10
	*/
	function &getUserRawArray() {
		if (!isset($this->userRawArray)) {
            $this->readUserHeader();
        } // end if
		return (array) $this->userRawArray;
	} // end function

	/**
	* Returns an array with the languages extracted from the raw user-request header (de-xx, en-xx,...)
	*
	* @desc Returns an array with the languages extracted from the raw user-request header (de-xx, en-xx,...)
	* @return (array) $userLangArray
	* @access public
	* @see getUserRawArray(), getUserCountryArray()
	* @uses readUserHeader()
	* @since 1.000 - 2002/10/10
	*/
	function &getUserLangArray() {
		if (!isset($this->userLangArray)) {
            $this->readUserHeader();
        } // end if
        return (array) $this->userLangArray;
	} // end function

	/**
	* Returns an array with the countries extracted from the raw user-request header (xx-at, xx-us,...)
	*
	* @desc Returns an array with the countries extracted from the raw user-request header (xx-at, xx-us,...)
	* @return (array) $userCountryArray
	* @access public
	* @see getUserRawArray(), getUserLangArray()
	* @since 1.000 - 2002/10/10
	*/
	function &getUserCountryArray() {
		if (!isset($this->userCountryArray)) {
            $this->readUserHeader();
        } // end if
		return (array) $this->userCountryArray;
	} // end function

	/**
	* Creates a news User object if the class var user doesn’t exists
	*
	* @desc Creates a news User object if the class var user doesn’t exists
	* @return (void)
	* @access private
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
	* Returns user object
	*
	* @desc Returns user object
	* @return (object) $user
	* @access public
	* @see User
	* @uses loadUserClass()
	* @since 1.020 - 2002/12/29
	*/
	function &getUser() {
        $this->loadUserClass();
        return (object) $this->user;
	} // end function

	/**
	* Sets the ISO code for the content-language
	*
	* @desc Sets the ISO code for the content-language
	* @param (string) $language  String to be assigned to the class variable lang_content
	* @return (void)
	* @access private
	* @see setLang(), setCountry()
	* @since 1.000 - 2002/10/10
	*/
	function setLangContent($language) {
		$this->lang_content = (string) $language;
	} // end function

	/**
	* Sets the ISO code for the (osm)language
	*
	* @desc Sets the ISO code for the (osm)language
	* @param (string) $language  String to be assigned to the class variable lang
	* @return (void)
	* @access private
	* @see setLangContent(), setCountry(), setLocale()
	* @since 1.000 - 2002/10/10
	*/
	function setLang($language) {
		$this->lang = (string) $language;
	} // end function

	/**
	* Same as the setLang function, only for the raw header
	*
	* @desc Same as the setLang function, only for the raw header
	* @param (string) $locale  String to be assigned to the class variable locale
	* @return (void)
	* @access private
	* @see setLangContent(), setCountry(), setLang()
	* @since 1.044 - 2003/02/18
	*/
	function setLocale($locale) {
		$this->locale = (string) $locale;
	} // end function

	/**
	* Sets the ISO code for the manual input language
	*
	* @desc Sets the ISO code for the manual input language
	* @param (string) $language  String to be assigned to the class variable lang
	* @return (void)
	* @access private
	* @see setLangContent(), setCountry(), setLocale()
	* @since 1.000 - 2002/10/10
	*/
	function setInputLang($language = '') {
		$this->inputlang = (string) $language;
	} // end function

	/**
	* Changes the language of the object. Also loads the new translationfile
	*
	* @desc Changes the language of the object. Also loads the new translationfile
	* @param (string) $language  iso-code
	* @return (void)
	* @access private
	* @see setLang()
	* @uses ChooseLang()
	* @since 1.044 - 2003/02/18
	*/
	function changeLocale($locale) {
		$this->inputlang = $locale;
        $this->ChooseLocale();
	} // end function

	/**
	* Sets the ISO code for the country
	*
	* @desc Sets the ISO code for the country
	* @param (string) $country  String to be assigned to the class variable country
	* @return (void)
	* @access private
	* @see setLangContent(), setLang(), setLocale()
	* @since 1.000 - 2002/10/10
	*/
	function setCountry($country) {
		$this->country = (string) $country;
	} // end function

	/**
	* Adds an entry to the raw user-header array
	*
	* @desc Adds an entry to the raw user-header array
	* @param (string) $string  String to be added (ISO code)
	* @return (void)
	* @access private
	* @see setUserLangArray(), setUserCountryArray()
	* @since 1.000 - 2002/10/10
	*/
	function setUserRawArray($string) {
		$this->userRawArray[] = $string;
	} // end function

	/**
	* Adds an entry to the user-languages array
	*
	* @desc Adds an entry to the user-languages array
	* @param (string) $string  String to be added (ISO code)
	* @return (void)
	* @access private
	* @see setUserRawArray(), setUserCountryArray()
	* @since 1.000 - 2002/10/10
	*/
	function setUserLangArray($string) {
		$this->userLangArray[] = $string;
	} // end function

	/**
	* Adds an entry to the country array
	*
	* @desc Adds an entry to the country array
	* @param (string) $string  String to be added (ISO code)
	* @return (void)
	* @access private
	* @see setUserRawArray(), setUserLangArray()
	* @since 1.000 - 2002/10/10
	*/
	function setUserCountryArray($string) {
		$this->userCountryArray[] = $string;
	} // end function

	/**
	* Filters an array
	*
	* Filters all empty values and dublicates of a given array
	*
	* @desc Filters an array
	* @param (array) $arrayname
	* @return (void)
	* @access protected
	* @since 1.000 - 2002/10/10
	*/
	function trimArray($arrayname) {
		if (count($this->$arrayname) > 0) {
			$this->$arrayname = array_values(array_unique($this->$arrayname));
		} // end if
	} // end function

	/**
	* Checks the users request-header for language informations
	*
	* @desc Checks the users request-header for language informations
	* Reads the header of the request and splits it up to 3 arrays:
	* one array with the raw data, one with all the possible
	* (osm)languages and one with all the possible countries. Depends on
	* how the user has set up his browser and/or the domain of his ip adress.
	*
	* @return (void)
	* @access protected
	* @uses IsValidLanguageCode(), setUserRawArray(), trimArray(), setUserLangArray(), setUserCountryArray(), readDefaultLanguageSettings()
	* @since 1.000 - 2002/10/10
	*/
	function readUserHeader() {
		if (count($prefUserCodes = (array) explode(',',$_SERVER['HTTP_ACCEPT_LANGUAGE'])) != 0) { // get raw languages
			foreach ($prefUserCodes as $rawEntry) { // Strip ";"s
				if (($cutat = (int) strpos($rawEntry,';')) == TRUE) {
					$new_Raw_Entry = (string) substr($rawEntry, 0, $cutat);
				} else {
					$new_Raw_Entry = (string) $rawEntry;
				} // end if
				if ($this->IsValidLanguageCode($new_Raw_Entry)) {
					$new_Raw_Entry = str_replace('-', '_',strtolower(trim($new_Raw_Entry)));
					$this->setUserRawArray($new_Raw_Entry);
					if (($splitcut = (int) strpos($new_Raw_Entry,'_')) == TRUE) {
						$this->setUserLangArray(substr($new_Raw_Entry, 0, $splitcut));
						$this->setUserCountryArray(substr($new_Raw_Entry, ($splitcut+1), 2));
					} else {
						$this->setUserLangArray(strtolower(trim($new_Raw_Entry)));
					} // end if
				} // end if
			} // end foreach
			unset($rawEntry);
		} else {
			$this->readDefaultLanguageSettings();
			$this->setUserRawArray($this->defaultLocale);
		} // end if
		$this->trimArray('userRawArray');
		$this->trimArray('userLangArray');
		unset($rawEntry);

		$country = $this->domain2Country(gethostbyaddr($_SERVER['REMOTE_ADDR']));
		if (strlen(trim($country)) > 0) {
			$this->setUserCountryArray($country);
		} else {
			$country = $this->domain2Country($_SERVER['SERVER_NAME']);
			if (strlen(trim($country)) > 0) {
				$this->setUserCountryArray($country);
			}
		} // end if
		unset($country);
		$this->trimArray('userCountryArray');
	} // end function

	/**
	* Returns the country of a domainname if possible
	*
	* @desc Returns the country of a domainname if possible
	* @param (string) $host  domainname (example: cnn.com)
	* @return (string)  iso code of the country or empty if no country was found
	* @access public
	* @since 1.051 - 2003/02/26
	*/
	function domain2Country($host = '') {
		if (($lastdot = (int) strrpos($host,'.')) > 0) {
			$domain_length = (int) strlen($host) - ($lastdot+1);
			$domain_name = (string) substr($host, $lastdot+1);
			if ($domain_length > 2 || $domain_name === 'eu') { // top level domains (com, org, gov, aero, info) or eu-domain are all english
				return (string) 'en';
			} elseif ($domain_length === 2) { // country domains
				return (string) trim(substr($host, ($lastdot+1), $domain_length));
			} else {
				return (string) '';
			} // end if
		} else {
			return (string) '';
		} // end if
	} // end function

	/**
	* Checks if a given string is a valid iso-language-code
	*
	* @desc Checks if a given string is a valid iso-language-code
	* @param (string) $code  String that should validated
	* @return (boolean) $isvalid  If string is valid or not
	* @access public
	* @since 1.000 - 2002/10/10
	*/
	function IsValidLanguageCode($code) {
		return (boolean) ((preg_match('(^([a-zA-Z]{2})((_|-)[a-zA-Z]{2})?$)',trim($code)) > 0) ? TRUE : FALSE);
	} // end function

	/**
	* Sets the (osm)language ISO code
	*
	* sets the (osm)language (iso code) depending on the given information
	* available. first looks if the value is set by the GET method (GET not
	* working at the moment), then checkes if a prefered code has been set in
	* the user class.
	*
	* @desc Sets the (osm)language ISO code
	* @return (void)
	* @access protected
	* @see ChooseLangContent(), ChooseCountry()
	* @uses loadUserClass(), IsValidLanguageCode(), setLang(), User::getPreferedLanguage(), readUserHeader(), readDefaultLanguageSettings()
	* @since 1.000 - 2002/10/10
	*/
	function ChooseLang() {
		$this->loadUserClass();
		if ($this->IsValidLanguageCode($this->inputlang) === TRUE) {
			$this->setLang($this->inputlang);
		} elseif ($this->IsValidLanguageCode($this->user->getPreferedLanguage()) === TRUE) {
			$this->setLang($this->user->getPreferedLanguage());
		} else {
			if (!isset($this->userLangArray)) {
				$this->readUserHeader();
			} // end if
			if (count($this->userLangArray) > 0) {
                $this->setLang($this->userLangArray[0]);
			} else {
				$this->readDefaultLanguageSettings();
				$this->setLang($this->defaultLanguage);
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
	* @return (void)
	* @access protected
	* @see ChooseLangContent(), ChooseCountry()
	* @uses loadUserClass(), IsValidLanguageCode(), setLang(), User::getPreferedLanguage(), readUserHeader(), readDefaultLanguageSettings()
	* @since 1.044 - 2003/02/18
	*/
	function ChooseLocale() {
		$this->loadUserClass();
		if ($this->IsValidLanguageCode($this->inputlang) === TRUE) {
			$this->setLocale($this->inputlang);
		} elseif ($this->IsValidLanguageCode($this->user->getPreferedLocale()) === TRUE) {
			$this->setLocale($this->user->getPreferedLocale());
		} else {
			if (!isset($this->userRawArray)) {
				$this->readUserHeader();
			} // end if
			if (count($this->userRawArray) > 0) {
                $this->setLocale($this->userRawArray[0]);
			} else {
				$this->readDefaultLanguageSettings();
				$this->setLocale($this->defaultLocale);
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
	* @return (void)
	* @access protected
	* @see ChooseLang(), ChooseLangContent()
	* @uses loadUserClass(), IsValidLanguageCode(), setCountry(), User::getPreferedCountry(), readUserHeader(), readDefaultLanguageSettings()
	* @since 1.000 - 2002/10/10
	*/
	function ChooseCountry() {
		$this->loadUserClass();
		if ($this->IsValidLanguageCode($this->inputcountry) === TRUE) {
			$this->setCountry($this->inputcountry);
		} elseif ($this->IsValidLanguageCode($this->user->getPreferedCountry()) === TRUE) {
			$this->setCountry($this->user->getPreferedCountry());
		} else {
			if (!isset($this->userCountryArray)) {
				$this->readUserHeader();
			} // end if
			if (count($this->userCountryArray) > 0) {
                $this->setCountry($this->userCountryArray[0]);
			} else {
				//echo 'here';
				$this->readDefaultLanguageSettings();
				$this->setCountry($this->defaultCountry);
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
	* @return (void)
	* @access protected
	* @see ChooseLang(), ChooseCountry()
	* @uses loadUserClass(), IsValidLanguageCode(), setLangContent(), User::getPreferedContentLanguage(), readDefaultLanguageSettings()
	* @since 1.000 - 2002/10/10
	*/
	function ChooseLangContent() {
		$this->loadUserClass();
		if ($this->IsValidLanguageCode($this->inputlang) === TRUE) {
			$this->setLangContent($this->inputlang);
		} elseif ($this->IsValidLanguageCode($this->user->getPreferedContentLanguage()) === TRUE) {
			$this->setLangContent($this->user->getPreferedContentLanguage());
		} else {
			switch ($this->lang) {
				case 'de':
					$this->setLangContent($this->lang);
					break;
				default:
					$this->readDefaultLanguageSettings();
					$this->setLangContent($this->defaultLanguage);
					break;
			} // end switch
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
} // end class Language
?>