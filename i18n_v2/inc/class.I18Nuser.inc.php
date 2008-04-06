<?php
/**
* holds preffered locale information about a user; takes care of setting and getting SESSION/COOKIE data
* @author Michael Wimmer <flaimo@gmx.net>
* @category FLP
* @copyright Copyright © 2002-2006, Michael Wimmer
* @example  ../www_root/i18n_example_script.php  i18n example script
* @license Free for non-commercial use
* @link http://flp.sf.net/
* @package I18N
* @version 2.2
*/
class I18NUser extends I18Nbase {

	/**
	* @var boolean whether to use cookies or not
	*/
	const USE_COOKIES = TRUE;

	/**
	* @var int how long should the cookie last
	*/
	const COOKIE_TIME = 31536000;

	/**#@+
	* @var string
	*/
	protected $pref_locale;
	protected $pref_country;
	protected $pref_language;
	/**#@-*/

	/**#@+
	* @var int
	*/
	protected $pref_measure_system;
	protected $pref_time_format;
	protected $pref_highlight_specialwords;
	/**#@-*/

	/**
	* @var array
	*/
	protected $user_settings;

	/**
	* @uses I18Nbase::__construct()
	* @return void
	*/
	public function __construct() {
		parent::__construct();
	} // end constructor

	/**
	* gets the user settings from the session/cookie
	* @uses I18Nuser::$user_settings
	* @uses self::$use_cookies
	* @uses I18Nbase::isFilledString()
	* @return boolean
	*/
	protected function readUserSettings() {
		if (isset($this->user_settings)) {
			return (boolean) TRUE;
		} // end if

		$this->user_settings = array();

		if (isset($_SESSION['i18n']['settings']['user'])) {
			$this->user_settings =& $_SESSION['i18n']['settings']['user'];
		} elseif (self::USE_COOKIES == TRUE) {
			$vars = array('pref_locale', 'pref_language', 'pref_country',
						  'pref_measure_system', 'pref_time_format',
						  'pref_highlight_specialwords');

			foreach ($vars as $var) {
				if (isset($_COOKIE[$var]) && parent::isFilledString($_COOKIE[$var]) == TRUE) {
					$_SESSION['i18n']['settings']['user'][$var] = $_COOKIE[$var];
				} // end if
			} // end foreach
			$this->user_settings =& $_SESSION['i18n']['settings']['user'];
		} // end if

		$this->pref_locale 					=& $this->user_settings['pref_locale'];
		$this->pref_language 				=& $this->user_settings['pref_language'];
		$this->pref_country 				=& $this->user_settings['pref_country'];
		$this->pref_measure_system 			=& $this->user_settings['pref_measure_system'];
		$this->pref_time_format 			=& $this->user_settings['pref_time_format'];
		$this->pref_highlight_specialwords 	=& $this->user_settings['pref_highlight_specialwords'];
		return (boolean) TRUE;
	} // end function

	/**
	* assigns a class var and writes it to the session/cookie
	* @param string $data
	* @param string $var
	* @param string $type
	* @uses I18Nuser::$cookie_time
	* @uses I18Nuser::$use_cookies
	* @uses I18Nbase::setVar()
	* @return boolean
	*/
	protected function setVar($data = '', $var = '', $type = 'string') {
		$_SESSION['i18n']['settings']['user'][$var] = $data;
		if (self::USE_COOKIES == TRUE) {
			// unsing @ to prevent certain error messages under apache 2
			@setcookie($var, addslashes($data), time()+ self::COOKIE_TIME);
		} // end if
		return parent::setVar($data, $var, $type);
	} // end function

	/**
	* assigns a locale/language/country to a class var
	* @param string $data
	* @param string $var
	* @uses I18Nbase::isValidLocaleCode()
	* @uses I18Nuser::setVar()
	* @return boolean
	*/
	protected function setPrefVar($data = '', $var = '') {
		if (parent::isValidLocaleCode($data) == FALSE) {
			return (boolean) FALSE;
		} // end if
		return $this->setVar(strtolower($data), $var, 'string');
	} // end function

	/**#@+
	* asigns class var
	* @return boolean
	* @uses I18Nuser::setPrefVar()
	*/
	/**
	* @param string $locale
	*/
	public function setPrefLocale($locale = '') {
		return $this->setPrefVar($locale, 'pref_locale');
	} // end function

	/**
	* @param string $country
	*/
	public function setPrefCountry($country = '') {
		return $this->setPrefVar($country, 'pref_country');
	} // end function

	/**
	* @param string $lang
	*/
	public function setPrefLanguage($lang = '') {
		return $this->setPrefVar($lang, 'pref_language');
	} // end function
	/**#@-*/

	/**#@+
	* assigns class var
	* @return boolean
	* @uses I18Nuser::setVar()
	*/
	/**
	* @param string $system
	*/
	public function setPrefMeasureSystem($system = 'si') {
		return $this->setVar($system, 'pref_measure_system', 'string');
	} // end function

	/**
	* @param int $int
	*/
	public function setPrefTimeFormat($int = 0) {
		return $this->setVar($int, 'pref_time_format', 'int');
	} // end function

	/**
	* @param int $int
	*/
	public function setHighlightSpecialWords($int = 0) {
		return $this->setVar($boolean, 'pref_highlight_specialwords', 'int');
	} // end function
	/**#@-*/

	/**
	* returns a class var
	* @param string $var
	* @uses I18Nbase::getVar()
	* @uses I18Nuser::readUserSettings()
	* @return mixed
	*/
	protected function getVar($var = 'dummy') {
		if (!isset($this->$var)) {
			$this->readUserSettings();
		} // end if
		return parent::getVar($var);
	} // end if

	/**#@+
	* returns class var
	* @return mixed
	* @uses I18Nuser::getVar()
	*/
	public function getPrefLocale() {
		return $this->getVar('pref_locale');
	} // end function

	public function getPrefLanguage() {
		return $this->getVar('pref_language');
	} // end function

	public function getPrefCountry() {
		return $this->getVar('pref_country');
	} // end function

	public function getPrefMeasureSystem() {
		return $this->getVar('pref_measure_system');
	} // end function

	public function getPrefTimeFormat() {
		return $this->getVar('pref_time_format');
	} // end function

	public function getHighlightSpecialWords() {
		return $this->getVar('pref_highlight_specialwords');
	} // end function
	/**#@-*/
} // end class I18Nuser
?>