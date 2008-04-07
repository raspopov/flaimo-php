<?php
/**
* load base class which takes care of all the other includes via it's autoload function
*/
require_once 'class.I18Nbase.inc.php';

/**
* adapter class to provide access to the actual extended translator class based on the selected translation mode
* @author Michael Wimmer <flaimo@gmail.com>
* @category flaimo-php
* @example  ../www_root/i18n_example_script.php  i18n example script
* @license GNU General Public License v3
* @link http://code.google.com/p/flaimo-php/
* @package i18n
* @version 2.3
*/
class I18NtranslatorExtended extends I18Ntranslator {

	/**
	* @param string $namespaces comma seperated string with namespaces
	* @param object $locale I18Nlocale
	* @uses I18Ntranslator::__construct()
	* @return void
	*/
	public function __construct($namespaces = '', &$locale = NULL) {
		parent::__construct($namespaces, $locale);
	} // end constructor

	/**
	* sets the correct translatorXXXExtended object based on the setting in the i18n_settings.ini file
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
		} elseif ($locale == FALSE ||
				  parent::isFilledString($mode) == FALSE) {
			return (boolean) FALSE;
		} // end if
		$classname = 'I18Ntranslator' . $mode . 'Extended';
		$this->babel = new $classname($namespaces, $locale);

		if ($this->babel == FALSE) {
			return (boolean) FALSE;
		} // end if
		return (boolean) TRUE;
	} // end function

	/**#@+
	* @uses I18Ntranslator::$babel
	*/
	public function getCountStrings() {
		return $this->babel->getCountStrings();
	} // end function

	/**
	* returns a timestamp of the latest change to one of the strings of given namespaces
	* @param string $namespaces comma-seperated
	* @return mixed int or false
	*/
	public function getLastUpdateDate($namespaces = '') {
		return $this->babel->getLastUpdateDate($namespaces);
	} // end function

	/**
	* returns a timestamp of the latest change to one of the strings of an namespace
	* @param string $namespace
	* @return mixed int or false
	*/
	public function getLastUpdateDateNamespace($namespace = '') {
		return $this->babel->getLastUpdateDateNamespace($namespace);
	} // end function
	/**#@-*/

	/**#@+
	* @return boolean
	* @param string $string the stranslation string
	* @param string $namespace
	* @uses I18Ntranslator::$babel
	*/
	/**
	* adds a translationstring + translation to the given namespace of the current locale
	* @param string $translation the stranslation itself
	*/
	public function addTranslation($string = '', $translation = '', $namespace = 'lang_main') {
		return $this->babel->addTranslation($string, $translation, $namespace);
	} // end function

	/**
	* updates a translationstring or translation or namespace
	* @param string $translation the stranslation itself
	* @param int $position 2 = change namespace; 1 = change string; 0 = change translation
	*/
	public function updateTranslation($string = '', $translation = '', $namespace = 'lang_main', $position = 1) {
		return $this->babel->updateTranslation($string, $translation, $namespace, $position);
	} // end function

	/**
	* deletes a translation string + translation from the given namespace for the current locale
	*/
	public function deleteTranslation($string, $namespace = 'lang_main') {
		return $this->babel->deleteTranslation($string, $namespace);
	} // end function

	/**
	* deletes a translation string + translation from the given namespace for ALL locales
	*/
	public function deleteTranslationString($string, $namespace = 'lang_main') {
		return $this->babel->deleteTranslationString($string, $namespace);
	} // end function
	/**#@-*/
} // end Translator
?>