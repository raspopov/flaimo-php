<?php
/**
* load base class which takes care of all the other includes via it's autoload function
*/
require_once 'class.I18Nbase.inc.php';

/**
* holds information about locale/country/language selected by the user or automatically; also loads settings L10N settings
* @author Michael Wimmer <flaimo@gmail.com>
* @category flaimo-php
* @example  ../www_root/i18n_example_script.php  i18n example script
* @license GNU General Public License v3
* @link http://code.google.com/p/flaimo-php/
* @package i18n
* @version 2.3
*/
class I18Nlocale extends I18Nbase {

	/**#@+
	* @var string
	*/
	protected $locale;
	protected $country;
	protected $language;
	/**#@-*/
	/**#@+
	* @var array
	*/
	protected $locales;
	protected $countries;
	protected $languages;
	protected $locale_settings;
	/**#@-*/

	/**
	* @var boolean
	*/
	protected $use_prefs = TRUE;
	const TRANSLATE_IP = TRUE;
	/**#@-*/

	/**
	* @param string $locale
	* @return void
	* @uses I18Nbase::isValidLocaleCode()
	* @uses I18Nlocale::addLocale()
	* @uses I18Nlocale::$use_prefs
	* @uses I18Nlocale::$locale
	* @uses I18Nlocale::$language
	* @uses I18Nlocale::$country
	* @uses I18Nbase::__construct()
	*/
	public function __construct($locale = '') {
		if (parent::isValidLocaleCode($locale) === TRUE) {

			// hier lokale check  machen

			$this->addLocale($locale);
			$this->use_prefs = FALSE;
			$this->locale =& $this->locales[0];
			$this->language =& $this->languages[0];
			if (count($this->countries) > 0) {
				$this->country =& $this->countries[0];
			} // end if
		} // end if
		parent::__construct();
	} // end if

	/**
	* fetches l10n settings from the ini file
	* @return void
	* @uses I18Nlocale::$locale_settings
	* @uses I18Nbase::getI18Nsetting()
	*/
	protected function readL10Nsettings() {
		$this->locale_settings = @parse_ini_file(parent::getI18Nsetting('locales_path') . '/' . $this->getI18NLocale() . '/' . 'l10n_settings.ini');
		return (boolean) ($this->locale_settings == FALSE) ? FALSE : TRUE;
	} // end function

	/**
	* returns a value for a l10n setting
	* @param string $setting
	* @return mixed
	* @uses I18Nlocale::readL10Nsettings()
	* @uses I18Nlocale::$locale_settings
	*/
	public function getL10NSetting($setting = '') {
		if (!isset($this->locale_settings)) {
			$this->readL10Nsettings();
		} // end if
		if (array_key_exists($setting, $this->locale_settings)) {
			return $this->locale_settings[$setting];
		} // end if
		return (boolean) FALSE;
	} // end function

	/**
	* returns a country from a given domainname
	* @param string $host
	* @return mixed
	*/
	protected static function domain2Country($host = '') {
		if (($lastdot = strrpos($host,'.')) < 1) {
			return (boolean) FALSE;
		} // end if
		$parts = explode('.', $host);
		$domain_name = array_pop($parts);
		/* top level domains (com, org, gov, aero, info)
			or eu-domain are all english */
		if (strlen($domain_name) > 2 || $domain_name === 'eu') {
			return 'en';
		} elseif (strlen($domain_name) == 2) { // country domains
			return $domain_name;
		} // end if
		return (boolean) FALSE;
	} // end function

	/**
	* adds a given locale to the locales array
	* @param string $locale
	* @return void
	* @uses I18Nlocale::isValidLocaleCode()
	* @uses I18Nlocale::$locales
	* @uses I18Nlocale::$languages
	* @uses I18Nlocale::$countries
	*/
	protected function addLocale($locale = '') {
		if (parent::isValidLocaleCode($locale) == FALSE) {
			return (boolean) FALSE;
		} // endif
		$this->locales[] = strtolower($locale);
		$temp = explode('-', $locale);
		$language = trim($temp[0]);

		if (parent::isValidLocaleCode($language) === TRUE) {
			$this->languages[] = strtolower($language);
		} // endif

		if (isset($temp[1]) &&
			parent::isValidLocaleCode(trim($temp[1])) === TRUE) {
			$this->countries[] = strtolower(trim($temp[1]));
		} // end if
	} // end if

	/**
	* gets the data from the http header request
	* @return boolean
	* @uses I18Nlocale::addLocale()
	* @uses I18Nlocale::$locales
	* @uses I18Nlocale::$languages
	* @uses I18Nlocale::$countries
	*/
	protected function readUserHeader() {
		$client_header = explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE']);

		if (count($client_header) < 1) {
			return (boolean) FALSE;
		} // end if

		foreach ($client_header as $raw_entry) {
			$temp = explode(';', $raw_entry);
			$locale = trim($temp[0]);
			$this->addLocale($locale);
		} // end foreach

		if (is_array($this->languages)) {
			$this->languages = array_unique($this->languages);
		} // end if

		if (is_array($this->countries)) {
			$this->countries = array_unique($this->countries);
		} // end if
		$_SESSION['i18n']['settings']['tmp_user_locales'] = $this->locales;
		$_SESSION['i18n']['settings']['tmp_user_languages'] = $this->languages;
		return (boolean) TRUE;
	} // end function

	/**
	* translates the users ip to a domainname
	* @return boolean
	* @uses I18Nlocale::domain2Country()
	* @uses I18Nlocale::$countries
	*/
	protected function readUserIP() {
		$country = $this->domain2Country(gethostbyaddr($_SERVER['REMOTE_ADDR']));

		if ($country == FALSE) {
			$country = $this->domain2Country($_SERVER['SERVER_NAME']);
		} // end if

		if ($country == FALSE) {
			return (boolean) FALSE;
		} // end if

		$this->countries[] = $country;
		$this->countries = array_unique($this->countries);
		$_SESSION['i18n']['settings']['tmp_user_countries'] = $this->countries;
		return (boolean) TRUE;
	} // end function

	/**
	* creates locale/language/country arrays
	* @return boolean
	* @uses I18Nlocale::$locales
	* @uses I18Nlocale::$use_prefs
	* @uses I18Nbase::getI18Nuser()
	* @uses I18Nlocale::addLocale()
	* @uses I18Nlocale::$languages
	* @uses I18Nlocale::$countries
	* @uses I18Nlocale::readUserHeader()
	* @uses I18Nlocale::readUserIP()
	* @uses I18Nlocale::$translate_ip
	* @uses I18Nbase::getI18NSetting(()
	*/
	protected function setLocaleVars() {
		if (isset($this->locales) && isset($this->locale)) {
			return (boolean) TRUE;
		} // end if

		if ($this->use_prefs == TRUE && parent::getI18Nuser()->getPrefLocale() != FALSE) {
			$this->addLocale(parent::getI18Nuser()->getPrefLocale());
			$this->countries[] =& parent::getI18Nuser()->getPrefCountry();
			$this->languages[] =& parent::getI18Nuser()->getPrefLanguage();
		} elseif (isset($_SESSION['i18n']['settings']['tmp_user_locales'])) {
			$sess =& $_SESSION['i18n']['settings'];
			$this->locales =& $sess['tmp_user_locales'];
			$this->countries =& $sess['tmp_user_countries'];
			$this->languages =& $sess['tmp_user_languages'];
		} else {
			$this->readUserHeader();
			if (count($this->countries) < 1 && self::TRANSLATE_IP == TRUE) {
				$this->readUserIP();
			} // end if
		} // end if

		if (count($this->locales) < 1) {
			$this->locale =& parent::getI18NSetting('default_locale');
			$this->country =& parent::getI18NSetting('default_country');
			$this->language =& parent::getI18NSetting('default_language');
			return (boolean) TRUE;
		} // end if

		$this->locale =& $this->locales[0];
		$this->language =& $this->languages[0];

		if (count($this->countries) > 0) {
			$this->country =& $this->countries[0];
			return (boolean) TRUE;
		} // end if
		$this->country =& parent::getI18Nsetting('default_country');
		return (boolean) TRUE;
	} // end function

	/**
	* returns a class var
	* @param string $var
	* @return mixed
	* @uses I18Nlocale::setLocaleVars()
	* @uses I18Nbase::getVar()
	*/
	protected function getVar($var = '') {
		if (!isset($this->$var)) {
			$this->setLocaleVars();
		} // end if
		return parent::getVar($var);
	} // end function

	/**
	* assigns a value to a class var
	* @param string $data
	* @param string $var
	* @return boolean
	* @uses I18Nlocale::isValidLocaleCode()
	* @uses I18Nbase::setVar()
	*/
	protected function setVar($data = '', $var = '', $type = '') {
		if (parent::isValidLocaleCode($data) == FALSE) {
			return (boolean) FALSE;
		} // end if
		return (boolean) parent::setVar(strtolower($data), $var, 'string');
	} // end function

	/**#@+
	* assigns a value to a class var
	* @return boolean
	* @uses I18Nlocale::setVar()
	*/
	/**
	* @param string $locale
	*/
	public function setI18NLocale($locale = '') {
		return (boolean) $this->setVar($locale, 'locale');
	} // end function

	/**
	* @param string $country
	*/
	public function setI18NCountry($country = '') {
		return (boolean) $this->setVar($country, 'country');
	} // end function

	/**
	* @param string $language
	*/
	public function setI18NLanguage($language = '') {
		return (boolean) $this->setVar($language, 'language');
	} // end function
	/**#@-*/

	/**#@+
	* returns a class var
	* @return string
	* @uses I18Nlocale::getVar()
	*/
	public function getI18NLocale() {
		return $this->getVar('locale');
	} // end function

	public function getI18NCountry() {
		return $this->getVar('country');
	} // end function

	public function getI18NLanguage() {
		return $this->getVar('language');
	} // end function

	public function getI18NLanguages() {
		return $this->getVar('languages');
	} // end function

	public function getI18NLocales() {
		return $this->getVar('locales');
	} // end function
	/**#@-*/

	function __toString() {
		return $this->getI18NLocale();
	} // end function
} // end class I18Nlocale
?>
