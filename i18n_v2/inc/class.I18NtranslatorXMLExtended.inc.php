<?php
/**
* required interface for all translatorXXXExtended classes
*/
require_once 'interface.I18NtranslatorInterfaceExtended.inc.php';
/**
* load base class which takes care of all the other includes via it's autoload function
*/
require_once 'class.I18Nbase.inc.php';

/**
* extedned translator class with xml-files as a backend
* @author Michael Wimmer <flaimo@gmail.com>
* @category flaimo-php
* @example  ../www_root/i18n_example_script.php  i18n example script
* @license GNU General Public License v3
* @link http://code.google.com/p/flaimo-php/
* @package i18n
* @version 2.3
*/
class I18NtranslatorTextExtended extends I18NtranslatorText implements I18NtranslatorInterfaceExtended {
	/**
	* @param string $namespaces
	* @param object $locale I18Nlocale
	* @uses I18NtranslatorText::__construct()
	* @return void
	*/
	public function __construct($namespaces = '', I18Nlocale &$locale = NULL) {
		parent::__construct($namespaces, $locale);
	} // end constructor

	/**
	* returns the timestamp when a given namespace was last updated
	* @param string $namespace
	* @return mixed int/boolean
	* @uses I18Nbase::isFilledString()
	* @uses I18NtranslatorBase::getLastUpdateSession()
	* @uses I18NtranslatorBase::getTranslatorLocale()
	* @uses I18NtranslatorBase::setLastUpdateSession()
	* @deprecated not yet implemented
	*/
	public function getLastUpdateDateNamespace($namespace = '') { // basically an overloaded method...
		if (parent::isFilledString($namespace) == FALSE) {
			return (boolean) FALSE;
		} elseif (($tmp_namespace = parent::getLastUpdateSession($namespace)) != FALSE) {
			return (int) $tmp_namespace;
		} // end if

		foreach ($this->xmlfiles as $namespace => $file) {
			foreach ($file->locale as $locale) {
				if ($locale['id'] != parent::getTranslatorLocale()->getI18Nlocale()) {
			   		continue;
				} // end if
/*
				$max = 0;
				foreach ($locale->namespace as $namespace) {
					parent::setLastUpdateSession($namespace['id'], lastchange);
					$max = (lastchange > $max) ? lastchange : $max;
				} // end foreach
*/
				return (int) $max;
			} // end foreach
		} // end if
		return (boolean) FALSE;
	} // end function

	/**#@+
	* @return boolean
	* @param string $string the stranslation string
	* @param string $namespace
	* @deprecated not yet implemented
	*/
	/**
	* deletes selected values from  the translation array
	* @uses I18NtranslatorText::$translation_table
	* @uses I18NtranslatorBase::$namespaces
	*/
	protected function stripTranslationTable($string = '', $namespace = 'lang_main') {
 		if (isset($this->translation_table[trim($string)]) && in_array(trim($namespace), $this->namespaces)) {
 			unset($this->translation_table[trim($string)]);
 			return (boolean) TRUE;
 		} // end if
 		return (boolean) FALSE;
	} // end function

	/**
	* deletes a translation from the given namespace for a given locale
	* @param object $locale I18Nlocale
	*/
	protected function deleteLocaleString($string = '', $namespace = 'lang_main', I18Nlocale &$locale) {
		die('Not implemented yet');
		return (boolean) FALSE;
	} // end function

	/**
	* deletes a translation string + translation from the given namespace for the current locale
	*/
	public function deleteTranslation($string = '', $namespace = 'lang_main') {
		die('Not implemented yet');
		return (boolean) FALSE;
	} // end function

	/**
	* deletes a translation string + translation from the given namespace for ALL locales
	*/
	public function deleteTranslationString($string = '', $namespace = 'lang_main') {
		die('Not implemented yet');
		return (boolean) FALSE;
	} // end function

	/**
	* adds a translationstring + translation to the given namespace of the current locale
	* @param string $translation the stranslation itself
	*/
	public function addTranslation($string = '', $translation = '', $namespace = 'lang_main') {
		die('Not implemented yet');
		return (boolean) FALSE;
	} // end function

	/**
	* updates a translationstring or translation or namespace
	* @param string $translation the stranslation itself
	* @param int $position 2 = change namespace; 1 = change string; 0 = change translation
	*/
	public function updateTranslation($string = '', $translation = '', $namespace = 'lang_main', $position = 1) {
		die('Not implemented yet');
		return (boolean) FALSE;
	} // end function
	/**#@-*/
} // end TranslatorText
?>