<?php
/**
* load base class which takes care of all the other includes via it's autoload function
*/
require_once 'class.I18Nbase.inc.php';

/**
* adapter class to provide access to the actual translator class based on the selected translation mode
* @author Michael Wimmer <flaimo@gmx.net>
* @category FLP
* @copyright Copyright © 2002-2006, Michael Wimmer
* @example  ../www_root/i18n_example_script.php  i18n example script
* @license Free for non-commercial use
* @link http://flp.sf.net/
* @package I18N
* @version 2.2
*/
class I18Ntranslator extends I18Nbase {

	/**
	* @var object translatorXXX object
	*/
	protected $babel;

	/**
	* @param string $namespaces comma seperated string with namespaces
	* @param object $locale I18Nlocale
	* @uses I18Nbase::__construct()
	* @uses I18Nbase::getI18NfactoryLocale()
	* @uses I18Nbase::getI18Nsetting()
	* @uses I18Ntranslator::setTranslator()
	* @return void
	*/
	public function __construct($namespaces = '', &$locale = NULL) {
		if (!($locale instanceOf I18Nlocale)) {
			$locale =& parent::getI18NfactoryLocale();
		} // end
		$this->setTranslator($locale, $namespaces, parent::getI18Nsetting('mode'));
		parent::__construct();
	} // end constructor

	/**
	* sets the correct translatorXXX object based on the setting in the i18n_settings.ini file
	* @param object $locale I18Nlocale
	* @param string $namespaces comma seperated string with namespaces
	* @param string $mode translation mode
	* @uses I18Nbase::isFilledString()
	* @uses I18Ntranslator::$babel
	* @return boolean
	*/
	protected function setTranslator(I18Nlocale &$locale = NULL, $namespaces = '',
									 $mode = 'Text') {
		if (isset($this->babel)) {
			return (boolean) TRUE;
		} elseif ($locale == NULL ||
				  parent::isFilledString($mode) == FALSE) {
			return (boolean) FALSE;
		} // end if
		$classname = 'I18Ntranslator' . $mode;
		$this->babel = new $classname($namespaces, $locale);

		if ($this->babel == FALSE) {
			return (boolean) FALSE;
		} // end if
		return (boolean) TRUE;
	} // end function

	/**
	* changes the translatorXXX object based on the given locale
	* @param object $locale I18Nlocale
	* @param string $namespaces comma seperated string with namespaces
	* @param string $mode translation mode
	* @uses I18Nbase::isFilledString()
	* @uses I18Ntranslator::$babel
	* @uses I18Ntranslator::setTranslator()
	* @return void
	*/
	public function changeTranslator(I18Nlocale &$locale = NULL, $namespaces = '',
									 $mode = 'Text') {
		if (isset($this->babel)) {
			unset($this->babel);
		} // end if

		if (parent::isFilledString($mode) == FALSE) {
			$mode =& parent::getI18Nsetting('mode');
		} // end if

		$this->setTranslator($locale, $namespaces, $mode);
	} // end function

	/**
	* returns the array with the currently used namespaces
	* @uses I18Ntranslator::$babel
	* @return array
	*/
	public function getNamespaces() {
		return $this->babel->getNamespaces();
	} // end function

	/**#@+
	* @param string $translationstring string to be translated
	* @param string $domain alias for namespace (sometimes needed for gettext)
	* @param array $arguments whe using translation strings with placeholders, $arguments is an array with the values for the placeholders
	* @return string translated tring or error message
	* @uses I18Ntranslator::$babel
	*/
	/**
	* main translator method for translating strings
	*/
	public function translate($translationstring = '', $domain = '', $arguments = FALSE) {
		return $this->babel->translate($translationstring, $domain, $arguments);
	} // end function

	/**
	* shortcut wrapper method for the translate() method
	*/
	public function _($translationstring = '', $domain = '', $arguments = FALSE) {
		return $this->babel->translate($translationstring, $domain, $arguments);
	} // end function
	/**#@-*/

	/**#@+
	* @uses I18Ntranslator::$babel
	*/
	/**
	* returns the number of strings currently used by an translator object
	* @return int
	*/
	public function getCountStrings() {
		return $this->babel->getCountStrings();
	} // end function

	/**
	* returns array with available translation locales
	* @return array
	*/
	public function getLocales() {
		return $this->babel->getLocales();
	} // end function

	/**
	* returns the number of available translation locales
	* @return int
	*/
	public function getCountLocales() {
		return $this->babel->getCountLocales();
	} // end function

	/**
	* returns an array with $array[translationstring] = translation
	* @return array $array[translationstring] = translation
	*/
	public function getTranslationTable() {
		return $this->babel->getTranslationTable();
	}

	/**
	* returns the encoding used for the current translation locale
	* @return string
	*/
	public function getEncoding() {
		return $this->babel->getEncoding();
	} // end function

	/**
	* returns the selected locale
	* @return object I18Nlocale
	*/
	public function getI18NLocale() {
		return $this->babel->getI18NLocale();
	} // end function

	/**
	* changes the locale of an translator object and resetzs the translation data
	* @param object $locale I18Nlocale object
	* @return boolean
	*/
	public function changeLocale(I18Nlocale &$locale = NULL) {
		return $this->babel->changeLocale($locale);
	} // end function

	/**
	* returns the locale used for translating
	* @return object I18Nlocale
	*/
	public function getTranslatorLocale() {
		return $this->babel->getTranslatorLocale();
	} // end function

	/**
	* returns the real locale for an alias
	* @param string $locale
	* @return object I18Nlocale
	*/
	public function getRealLocale($locale = '') {
		return $this->babel->getRealLocale($locale);
	} // end function

	/**
	* checks if a requested locale is available
	* @param string $locale
	* @return boolean
	*/
	public function checkLocale($locale = '') {
		return $this->babel->checkLocale($locale);
	} // end function
	/**#@-*/
} // end Translator
?>