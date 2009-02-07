<?php
/**
* basic functions every translator class has to provide
* @author Michael Wimmer <flaimo@gmail.com>
* @category flaimo-php
* @example  ../www_root/i18n_example_script.php  i18n example script
* @license GNU General Public License v3
* @link http://code.google.com/p/flaimo-php/
* @package i18n
* @version 2.3.1
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