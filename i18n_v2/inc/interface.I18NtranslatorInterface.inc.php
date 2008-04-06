<?php
/**
* basic functions every translator class has to provide
* @author Michael Wimmer <flaimo@gmx.net>
* @category FLP
* @copyright Copyright © 2002-2006, Michael Wimmer
* @example  ../www_root/i18n_example_script.php  i18n example script
* @license Free for non-commercial use
* @link http://flp.sf.net/
* @package I18N
* @version 2.2
*/
interface I18NtranslatorInterface {
	/**
	* main translator method for translating strings
	* @param string $translationstring string to be translated
	* @param string $domain alias for namespace (sometimes needed for gettext)
	* @param array $arguments whe using translation strings with placeholders, $arguments is an array with the values for the placeholders
	* @return string translated tring or error message
	*/
	public function translate($translationstring = '', $domain = '', $arguments = FALSE);

	/**
	* returns an array with $array[translationstring] = translation
	* @return array $array[translationstring] = translation
	*/
	public function getTranslationTable();

	/**
	* changes the locale of an translator object and resetzs the translation data
	* @param object $locale I18Nlocale object
	* @return boolean
	*/
	public function changeLocale(I18Nlocale &$locale = NULL);

	/**
	* returns the number of strings currently used by an translator object
	* @return int
	*/
	public function getCountStrings();
} // end interface
?>