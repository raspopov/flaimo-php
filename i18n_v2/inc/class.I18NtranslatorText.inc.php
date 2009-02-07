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
* translator class with flat utf8-textfiles as a backend
* @author Michael Wimmer <flaimo@gmail.com>
* @category flaimo-php
* @example  ../www_root/i18n_example_script.php  i18n example script
* @license GNU General Public License v3
* @link http://code.google.com/p/flaimo-php/
* @package i18n
* @version 2.3.1
*/
class I18NtranslatorText extends I18NtranslatorBase implements I18NtranslatorInterface {

	/**
	* @var boolean
	*/
	const USE_FILECACHE = TRUE;

	/**#@+
	* @var string
	*/
	protected $ext = 'inc';
	protected $delimiter = ' = ';
	/**#@-*/

	/**
	* @var array
	*/
	protected $translation_table;

	/**
	* @param string $namespaces
	* @param object $locale I18Nlocale
	* @uses I18NtranslatorBase::__construct()
	* @return void
	*/
	public function __construct($namespaces = '', I18Nlocale &$locale = NULL) {
		parent::__construct($namespaces, $locale);
	} // end constructor

	/**
	* returns path to a translationfile
	* @param string $namespaces
	* @uses I18Nbase::getI18Nsetting()
	* @uses I18NtranslatorBase::getTranslatorLocale()
	* @uses I18NtranslatorText::$ext
	* @return boolean
	*/
	protected function getNamespaceFilepath($namespace = '') {
		return (string) parent::getI18Nsetting('locales_path') . '/' . parent::getTranslatorLocale()->getI18Nlocale() . '/' . $namespace . '.' . $this->ext;
	} // end function

	/**
	* retreive the available translator locales
	* @uses I18Nbase::getI18Nsetting()
	* @uses I18NtranslatorBase::getSessionLocales()
	* @uses I18NtranslatorText::setRealLocale()
	* @uses I18NtranslatorBase::$locales
	* @return boolean
	*/
	protected function setLocales() {
		if (parent::getSessionLocales() == TRUE) {
			return (boolean) TRUE;
		} // end if

		$this->locales = array();
		$root = parent::getI18Nsetting('locales_path') . '/';
		$handle = @opendir($root);
		while ($lang_dir = trim(@readdir($handle))) {
			if (!is_dir($root . $lang_dir) ||
				parent::isValidLocaleCode($lang_dir) === FALSE) {
				continue;
			} // end if
			$lang_dir = strtolower($lang_dir);
			$this->locales[$lang_dir] =& $this->setRealLocale($lang_dir);
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
	* fetch the translation file
	* @param string $namespace
	* @uses I18NtranslatorText::getNamespaceFilepath()
	* @return mixed
	*/
	protected function fetchTranslationFile($namespace = '') {
		if (($file = file($this->getNamespaceFilepath($namespace))) == FALSE) {
			if (parent::getI18Nsetting('show_errormessages')) {
				trigger_error('TRANSLATION ERROR: File "' . $this->getNamespaceFilepath($namespace) . '" not found ', E_USER_WARNING);
			} // end if
			return (boolean) FALSE;
		} // end if
		return $file;
	} // end function

	/**
	* transform filecontent into translation array
	* @param string $namespace
	* @uses I18NtranslatorText::fetchTranslationFile()
	* @uses I18NtranslatorText::$delimiter
	* @uses I18NtranslatorText::$translation_table
	* @return void
	*/
	protected function fetchTranslations($namespace = '') {
		$file = $this->fetchTranslationFile($namespace);
		if ($file == FALSE) {
			return (boolean) FALSE;
		} // end if
		$file = array_filter(array_map('trim', $file), 'strlen');
		foreach ($file as $line) {
			$tmp = explode($this->delimiter, $line);
			if (count($tmp) < 2) {
				continue;
			} // end if
			list($string, $translation) = $tmp;
			$string = trim($string);
			$translation = trim($translation);
			if (mb_strlen($string) === 0 || mb_strlen($translation) === 0) {
				continue;
			} // end if
			parent::checkForDuplicates($string, $namespace);
			$this->translation_table[$string] = $translation;
		} // end foreach
	} // end function

	/**
	* fetch all translation files and transform them
	* @uses I18Nbase::getFileCache()
	* @uses self::$use_filecache
	* @uses I18NtranslatorBase::$namespaces
	* @uses I18Nbase::getTranslatorLocale()
	* @uses I18NtranslatorText::$translation_table
	* @uses I18NtranslatorText::fetchTranslations()
	* @return boolean
	*/
	protected function fetchAllTranslations() {
		if (self::USE_FILECACHE == TRUE) {
			$cache =& parent::getFileCache();
			$cache_filename = implode('_', $this->namespaces) . parent::getTranslatorLocale()->getI18Nlocale();
			if ($cache->isCached($cache_filename) == TRUE) {
				$this->translation_table = unserialize($cache->returnCache($cache_filename));
				unset($cache);
				return (boolean) TRUE;
			} // end if
		} // end if

		foreach ($this->namespaces as $namespace) {
			$this->fetchTranslations($namespace);
		} // end foreach
		ksort($this->translation_table);
		//$this->translation_table = array_unique($this->translation_table);

		if (self::USE_FILECACHE == TRUE) {
				$cache->writeCache($cache_filename, serialize($this->translation_table));
				unset($cache);
		} // end if
		return (boolean) TRUE;
	} // end function

	/**
	* returns an array with $array[translationstring] = translation
	* @uses I18NtranslatorText::fetchAllTranslations()
	* @return array $array[translationstring] = translation
	*/
	public function getTranslationTable() {
		if (!isset($this->translation_table)) {
			$this->fetchAllTranslations();
		} // end if
		return $this->translation_table;
	} // end function

	/**
	* main translator method for translating strings
	* @param string $translationstring string to be translated
	* @param string $domain alias for namespace (sometimes needed for gettext)
	* @param array $arguments whe using translation strings with placeholders, $arguments is an array with the values for the placeholders
	* @return string translated tring or error message
	* @uses I18NtranslatorText::getTranslationTable()
	* @uses I18NtranslatorText::$translation_table
	*/
	public function translate($translationstring = '', $domain = '', $arguments = FALSE) {
		$string = trim($translationstring);
		if (!array_key_exists($string, $this->getTranslationTable())) {
			if (parent::getI18Nsetting('show_errormessages')) {
				trigger_error('TRANSLATION ERROR: String "' . $string . '" not found in translation table', E_USER_WARNING);
			} // end if
			return $string;
		} // end if

    	if (!is_array($arguments)) {
    		return $this->translation_table[$string];
    	} // end if
    	return vsprintf($this->translation_table[$string], $arguments);
	} // end function
} // end TranslatorText
?>
