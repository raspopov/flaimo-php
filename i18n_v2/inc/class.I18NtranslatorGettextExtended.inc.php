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
* extended translator class with Gettext as a backend
* @author Michael Wimmer <flaimo@gmx.net>
* @category FLP
* @copyright Copyright © 2002-2006, Michael Wimmer
* @example  ../www_root/i18n_example_script.php  i18n example script
* @license Free for non-commercial use
* @link http://flp.sf.net/
* @package I18N
* @version 2.2
*/
class I18NtranslatorGettextExtended extends I18NtranslatorGettext { // implements I18NtranslatorInterfaceExtended {

	/**
	* @param string $namespaces
	* @param object $locale I18Nlocale
	* @uses I18NtranslatorGettext::__construct()
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
	* @uses I18Nbase::getLocaleDir()
	* @uses I18NtranslatorBase::getTranslatorLocale()
	* @uses I18Nbase::getI18Nsetting()
	* @return mixed
	*/
	public function getLastUpdateDateNamespace($namespace = '') { // basically an overloaded method...
		if (parent::isFilledString($namespace) == FALSE) {
			return (boolean) FALSE;
		} elseif (($tmp_namespace = parent::getLastUpdateSession($namespace)) != FALSE) {
			return (int) $tmp_namespace;
		} // end if

		$path = (string) parent::getLocaleDir() . '/' . $this->getTranslatorLocale()->getI18Nlocale() . '/' . MESSAGES_DIR . '/' . trim($namespace) . '.mo';
		if (($lastchange = @filemtime($path)) != FALSE) {
        	parent::setLastUpdateSession($namespace, $lastchange);
        	return (int) $lastchange;
		} else {
			return (boolean) FALSE;
		}// end if

		$path = parent::getI18Nsetting('locales_path') . '/' . $this->getTranslatorLocale()->getI18Nlocale() . '/' . $namespace . '.mo';
		if (($lastchange = @filemtime($path)) != FALSE) {
        	parent::setLastUpdateSession($namespace, $lastchange);
        	return (int) $lastchange;
		} // end if
		return (boolean) FALSE;
	} // end function

	/**#@+
	* @deprecated not possible with gettext
	* @return boolean
	* @param string $string the stranslation string
	* @param string $namespace
	*/
	/**
	* deletes a translation string + translation from the given namespace for the current locale
	*/
	public function deleteTranslation($string = '', $namespace = 'lang_main') {
		return (boolean) FALSE;
	} // end function

	/**
	* deletes a translation string + translation from the given namespace for ALL locales
	*/
	public function deleteTranslationString($string = '', $namespace = 'lang_main') {
		return (boolean) FALSE;
	} // end function

	/**
	* adds a translationstring + translation to the given namespace of the current locale
	* @param string $translation the stranslation itself
	*/
	public function addTranslation($string = '', $translation = '', $namespace = 'lang_main') {
		return (boolean) FALSE;
	} // end function

	/**
	* updates a translationstring or translation or namespace
	* @param string $translation the stranslation itself
	* @param int $position 2 = change namespace; 1 = change string; 0 = change translation
	*/
	public function updateTranslation($string = '', $translation = '', $namespace = 'lang_main', $position = 1) {
		return (boolean) FALSE;
	} // end function
	/**#@-*/
} // end TranslatorGettext
?>