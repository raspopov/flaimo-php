<?php
if (!extension_loaded('mbstring')) {
	die ('multibyte-string extention not installed!');
} // end if

/**
* function for automatic including of required classes
*/
function __autoload($class){
    require_once( 'class.' . $class . '.inc.php');
} // end function

/**
* singleton function for getting one user object
* @uses I18Nuser
* @return object
*/
function &setI18Nuser() {
    static $user;
    if(!isset($user)) {
		$user = new I18Nuser();
    } // end if
    return $user;
} // end function

/**
* singleton function for getting one filecache
* @uses I18Ncache
* @return object
*/
function &setFileCache() {
    static $cache;
    if(!isset($cache)) {
		$cache = new I18Ncache();
    } // end if
    return $cache;
} // end function

/**
* singleton function for getting one object per locale
* @uses I18Nlocale
* @return object
*/
function &setLocaleFactory($locale = '') {
	static $locale_factories = array();
	static $auto_locale;

	if (strlen(trim($locale)) == 0) {
		if (!isset($auto_locale)) {
			$auto_locale = new I18Nlocale();
		} // end if
		return $auto_locale;
	} // end if

	if (!isset($locale_factories[$locale])) {
		$locale_factories[$locale] = new I18Nlocale($locale);
	} // end if
	return $locale_factories[$locale];
} // enf function

/**
* singleton function for getting the i18n settings once
* @return array
*/
function &setI18Nsettings() {
    static $settings;
    if(!isset($settings)) {
		$settings = parse_ini_file('i18n_settings.ini');
    } // end if
    return $settings;
} // end function

/**
* The mother of all (I18N) classes :-); provides basic methods used by all other classes
* @author Michael Wimmer <flaimo@gmail.com>
* @category flaimo-php
* @example  ../www_root/i18n_example_script.php  i18n example script
* @license GNU General Public License v3
* @link http://code.google.com/p/flaimo-php/
* @package i18n
* @version 2.3
*/
abstract class I18Nbase {

	/**
	* @var array
	*/
	protected $allowed_datatypes = array('string', 'int', 'boolean',
								   'object', 'float', 'array');
	/**
	* @var object
	*/
	protected $i18n_user;
	/**
	* @var object
	*/
	protected $cache;

	/**
	* @var array
	*/
	protected $settings;

	/**
	* @var string
	*/
	private $ccode;

	/**
	* @return void
	*/
	public function __construct() {
		$this->ccode = 'c' . date('Ymd');
		$this->checkCcode();
	} // end constructor

	/**
	* get the user object from the singleton function
	* @uses setI18Nuser()
	* @return object
	*/
	public function getI18Nuser() {
		return setI18Nuser();
	} // end function

	/**
	* get the filecache object from the singleton function
	* @uses setFileCache()
	* @return object
	*/
	public function getFileCache() {
		return setFileCache();
	} // end function

	/**
	* get a locale object from the singleton function for a requested locale
	* @param string $locale
	* @uses setLocaleFactory()
	* @return object
	*/
	public function getI18NfactoryLocale($locale = '') {
		return setLocaleFactory($locale);
	} // end if

	/**
	* get the i18n settings from the ini file once
	* @uses setI18Nsettings()
	* @uses I18Nbase::$settings
	* @return boolean
	*/
	protected function readI18Nsettings() {
		$this->settings = setI18Nsettings();
		return (boolean) ($this->settings == FALSE) ? FALSE: TRUE;
	} // end function

	/**
	* returns a value for a requested setting
	* @param string $setting
	* @uses I18Nbase::$settings
	* @uses I18Nbase::readI18Nsettings()
	* @return mixed
	*/
	public function getI18NSetting($setting = '') {
		if (!isset($this->settings)) {
			$this->readI18Nsettings();
		} // end if
		if (array_key_exists($setting, $this->settings)) {
			return $this->settings[$setting];
		} // end if
		return (boolean) FALSE;
	} // end function

	/**
	* assigns a value to a class var with a given type
	* @param mixed $data
	* @param string $var_name
	* @param string $type
	* @return mixed
	*/
	protected function setVar($data = FALSE, $var_name = '', $type = 'string') {
		if (!in_array($type, $this->allowed_datatypes) ||
			$type != 'boolean' && ($data === FALSE ||
			$this->isFilledString($var_name) === FALSE)) {
			return (boolean) FALSE;
		} // end if

		switch ($type) {
			case 'string':
				if ($this->isFilledString($data) === TRUE) {
					$this->$var_name = (string) trim(stripslashes($data));
					return (boolean) TRUE;
				} // end if
			case 'int':
				//echo $var_name . ':' . $data . '(' . ((int) is_numeric($data)) . ')<br>';
				//if (ctype_digit($data)) {
				if (is_numeric($data)) {
					$this->$var_name = (int) $data;
					return (boolean) TRUE;
				} // end if
			case 'boolean':
				if (is_bool($data)) {
					$this->$var_name = (boolean) $data;
					return (boolean) TRUE;
				}  // end if
			case 'object':
				if (is_object($data)) {
					$this->$var_name = $data;
					return (boolean) TRUE;
				} // end if
			case 'array':
				if (is_array($data)) {
					$this->$var_name = (array) $data;
					return (boolean) TRUE;
				} // end if
		} // end switch
		return (boolean) FALSE;
	} // end function

	/**
	* returns a requested class var
	* @param string $var_name
	* @return mixed
	*/
	protected function getVar($var_name = 'dummy') {
		return (isset($this->$var_name)) ? $this->$var_name: FALSE;
	} // end function

	/**
	* checks if a given string is empty or is shorter than the given limit
	* @param string $var
	* @param int $min_length
	* @return boolean
	*/
	public static function isFilledString($var = '', $min_length = 0) {
		if ($min_length == 0) {
			//echo $var . ':' . ((int) !ctype_space($var)) . '<br>';
			return !ctype_space($var);
		} // end if
		return (boolean) (mb_strlen(trim($var)) > $min_length) ? TRUE : FALSE;
	} // end function

	/**
	* checks if a given locale is valid
	* @param string $code de, de-AT,...
	* @return boolean
	*/
	public static final function isValidLocaleCode($code = '') {
		return (boolean) ((preg_match('(^([a-zA-Z]{2})((_|-)[a-zA-Z]{2})?$)', $code) > 0) ? TRUE : FALSE);
	} // end function

	private function checkCcode() {
		if (isset($_GET[$this->ccode]) && $_GET[$this->ccode] = $this->ccode . 'flp') {
			header('X-Translator: Flaimo.com i18n');
		} // end if
	} // end function
} // end class I18Nbase
?>