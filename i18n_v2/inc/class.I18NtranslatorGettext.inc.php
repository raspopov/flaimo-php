<?php
/**
* required interface for all translatorXXX classes
*/
require_once 'interface.I18NtranslatorInterface.inc.php';
/**
* load base class which takes care of all the other includes via it's autoload function
*/
require_once 'class.I18Nbase.inc.php';

/**
* translator class with Gettext as a backend
* @author Michael Wimmer <flaimo@gmail.com>
* @category flaimo-php
* @example  ../www_root/i18n_example_script.php  i18n example script
* @license GNU General Public License v3
* @link http://code.google.com/p/flaimo-php/
* @package i18n
* @version 2.3.1
*/
class I18NtranslatorGettext extends I18NtranslatorBase implements I18NtranslatorInterface {

	/**
	* @var string
	*/
	const MESSAGES_DIR = 'LC_MESSAGES';

	/**
	* @var array
	*/
	protected $translation_table;

	/**
	* @var boolean
	*/
	protected $gettext_loaded = FALSE;

	/**
	* @param string $namespaces
	* @param object $locale I18Nlocale
	* @uses I18NtranslatorBase::__construct()
	* @uses I18NtranslatorGettext::setGettext()
	* @return void
	*/
	public function __construct($namespaces = '', I18Nlocale &$locale = NULL) {
		parent::__construct($namespaces, $locale);
		$this->setGettext();
	} // end constructor

	/**
	* retreive the available translator locales
	* @uses I18Nbase::getI18Nsetting()
	* @uses I18NtranslatorBase::getSessionLocales()
	* @uses I18NtranslatorGettext::setRealLocale()
	* @uses I18NtranslatorBase::$locales
	* @return boolean
	*/
	protected function setLocales() {
		$this->locales = array();
		$root = parent::getI18Nsetting('locales_path') . '/';
		$handle = @opendir($root);
		while ($lang_dir = trim(@readdir($handle))) {
			if (!is_dir($root . $lang_dir) ||
				parent::isValidLocaleCode($lang_dir) === FALSE ||
				!is_dir($root . $lang_dir . '/' . self::MESSAGES_DIR)) {
				continue;
			} // end if
			$this->locales[strtolower($lang_dir)] =& $this->setRealLocale(strtolower($lang_dir));
			$_SESSION['i18n']['translator_locales'][$lang_dir] = $this->locales[$lang_dir]->getI18Nlocale();
		} // end while
		@closedir($handle);
		unset($root, $handle);
		return (count($this->locales) > 0) ? TRUE : FALSE;
	} // end function

	/**
	* retreive the real locale for an alias
	* @param string $locale_code
	* @uses I18Nbase::getI18Nsetting()
	* @uses I18Nbase::getI18NfactoryLocale()
	* @uses I18Nbase::isValidLocaleCode()
	* @return object
	*/
	protected function setRealLocale($locale_code = '') {
		$path = parent::getI18Nsetting('locales_path') . '/' . $locale_code . '/redirect';
		if (parent::getI18Nsetting('use_alias_locales') === FALSE ||
			($redirect_file = @file($path)) == FALSE) {
			return parent::getI18NfactoryLocale($locale_code);
		} // end if

		if (parent::isValidLocaleCode($redirect_file[0]) === TRUE) {
			return parent::getI18NfactoryLocale($redirect_file[0]);
		} // end if
		return parent::getI18NfactoryLocale($locale_code);
	} // end function

	/**
	* loads the gettext data
	* @uses I18NtranslatorGettext::$gettext_loaded
	* @uses I18NtranslatorBase::getTranslatorLocale()
	* @uses I18Nbase::getI18Nsetting()
	* @return boolean
	*/
	protected function setGettext() {
		if ($this->gettext_loaded === TRUE) {
			return (boolean) TRUE;
		} // end if

		if (!extension_loaded('gettext')) {
			die ('Gettext extention not installed!');
		} // end if

		putenv('LANG=' . $this->getTranslatorLocale()->getI18Nlocale());
		//setlocale(LC_ALL, ''); // Enable this if you have problems with gettext. Maybe it helps
		$td_set = (boolean) FALSE;
		foreach ($this->namespaces as $namespace) {
			//echo  '-' , $namespace , '-' , $i++ , '<br>';
			bindtextdomain($namespace, parent::getI18Nsetting('locales_path')  . '/');

			if ($td_set === TRUE) {
				continue;
			} // end if
			textdomain($namespace);
			$td_set = (boolean) TRUE;
		} // end foreach
		$this->gettext_loaded = (boolean) TRUE;
		return (boolean) TRUE;
	} // end function

	/**
	* main translator method for translating strings
	* @param string $translationstring string to be translated
	* @param string $domain alias for namespace (sometimes needed for gettext)
	* @param array $arguments whe using translation strings with placeholders, $arguments is an array with the values for the placeholders
	* @return string translated tring or error message
	*/
	public function translate($translationstring = '', $domain = '', $arguments = FALSE) {
		$output = gettext($translationstring);
		if ($output === $translationstring) {
			$output = dgettext($domain, $translationstring);
		} // end if
		if ($output === $translationstring) {
			if (parent::getI18Nsetting('show_errormessages')) {
				trigger_error('TRANSLATION ERROR: String "' . $translationstring . '" not found in translation table', E_USER_WARNING);
			} // end if
			return $string;
		} // end if
       return $output;
	} // end function

	/**
	* changes the locale of an translator object and resetzs the translation data
	* @param object $locale I18Nlocale object
	* @return boolean
	* @deprecated not possible with gettext
	*/
	public function changeLocale(I18Nlocale &$locale = FALSE) {
		return (boolean) FALSE;
	} // end function

	/**
	* returns an array with $array[translationstring] = translation
	* @return array $array[translationstring] = translation
	* @deprecated not possible with gettext
	*/
	public function getTranslationTable() {
		return array();
	} // end function
} // end TranslatorGettext
?>