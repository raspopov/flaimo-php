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
* translator class with xml-files as a backend
* @author Michael Wimmer <flaimo@gmx.net>
* @category FLP
* @copyright Copyright © 2002-2006, Michael Wimmer
* @example  ../www_root/i18n_example_script.php  i18n example script
* @license Free for non-commercial use
* @link http://flp.sf.net/
* @package I18N
* @version 2.2
*/
class I18NtranslatorXML extends I18NtranslatorBase implements I18NtranslatorInterface {

	/**
	* @var boolean
	*/
	const USE_FILECACHE = FALSE;

	/**#@+
	* @var array
	*/
	protected $translation_table;
	protected $xmlfiles;
	/**#@-*/

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
	* @return boolean
	*/
	protected function getNamespaceFilepath($namespace = '') {
		return (string) parent::getI18Nsetting('locales_path') . '/' . parent::getTranslatorLocale()->getI18Nlocale() . '/' . $namespace . '.xml';
	} // end function

	/**
	* retreive the available translator locales
	* @uses I18Nbase::getI18Nsetting()
	* @uses I18NtranslatorBase::getSessionLocales()
	* @uses I18NtranslatorXML::setRealLocale()
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
	* @uses I18Nbase::getI18NfactoryLocale()
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
	* @uses I18NtranslatorXML::getNamespaceFilepath()
	* @return mixed
	*/
	protected function fetchTranslationFile($namespace = '') {
		if (($file = simplexml_load_file($this->getNamespaceFilepath($namespace))) == FALSE) {
			return (boolean) FALSE;
		} // end if
		return $file;
	} // end function

	/**
	* transform filecontent into translation array
	* @param string $namespace
	* @uses I18NtranslatorXML::fetchTranslationFile()
	* @uses I18NtranslatorBase::getTranslatorLocale()
	* @uses I18NtranslatorXML::$translation_table
	* @return void
	*/
	protected function fetchTranslations($namespace = '') {
		$file = $this->fetchTranslationFile($namespace);
		if ($file == FALSE) {
			return (boolean) FALSE;
		} // end if

		$this->xmlfiles[$namespace] = $file;

		foreach ($this->xmlfiles[$namespace]->locale as $locale) {
			if ($locale['id'] != parent::getTranslatorLocale()->getI18Nlocale()) {
		   		continue;
			} // end if

			foreach ($locale->namespace as $namespace_tmp) {
				if ($namespace_tmp['id'] != $namespace) {
			   		continue;
				} // end if

				foreach ($namespace_tmp->translations as $translations) {
					foreach ($translations->translation as $translation) {
						if (mb_strlen($translation['string']) == 0) {
							// cant do mb_strlen on $translation --> apache crash
							continue;
						} // end if
						
						parent::checkForDuplicates(trim($translation['string']), $namespace);
						$this->translation_table[trim($translation['string'])] = trim($translation);
					} // end foreach
				} // end foreach
			} // end foreach
		} // end foreach
	} // end function

	/**
	* fetch all translation files and transform them
	* @uses I18Nbase::getFileCache()
	* @uses I18NtranslatorXML::$use_filecache
	* @uses I18NtranslatorBase::$namespaces
	* @uses I18Nbase::getTranslatorLocale()
	* @uses I18NtranslatorXML::$translation_table
	* @uses I18NtranslatorXML::fetchTranslations()
	* @return boolean
	*/
	protected function fetchAllTranslations() {
		if (self::USE_FILECACHE == TRUE) {
			$cache =& parent::getFileCache();
			$cache_filename = implode('_', $this->namespaces) . parent::getTranslatorLocale()->getI18Nlocale();
			if ($cache->isCached($cache_filename) === TRUE) {
				$this->translation_table = unserialize($cache->returnCache($cache_filename));
				unset($cache);
				return (boolean) TRUE;
			} // end if
		} // end if

		foreach ($this->namespaces as $namespace) {
			$this->fetchTranslations($namespace);
		} // end foreach

		$this->translation_table = array_filter(array_map('trim', $this->translation_table), 'strlen');
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
	* @uses I18NtranslatorXML::fetchAllTranslations()
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
	* @uses I18NtranslatorXML::getTranslationTable()
	* @uses I18NtranslatorXML::$translation_table
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
