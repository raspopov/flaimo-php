<?php
/**
* basic functions every extended translator class has to provide
* @author Michael Wimmer <flaimo@gmx.net>
* @category FLP
* @copyright Copyright © 2002-2006, Michael Wimmer
* @example  ../www_root/i18n_example_script.php  i18n example script
* @license Free for non-commercial use
* @link http://flp.sf.net/
* @package I18N
* @version 2.2
*/
interface I18NtranslatorInterfaceExtended {
	/**
	* returns a timestamp of the latest change to one of the strings of an namespace
	* @param string $namespace
	* @return mixed int or false
	*/
	public function getLastUpdateDateNamespace($namespace = '');

	/**
	* returns a timestamp of the latest change to one of the strings of given namespaces
	* @param string $namespaces comma-seperated
	* @return mixed int or false
	*/
	public function getLastUpdateDate($namespaces = '');

	/**#@+
	* @return boolean
	* @param string $string the stranslation string
	* @param string $namespace
	*/
	/**
	* deletes a translation string + translation from the given namespace for the current locale
	*/
	public function deleteTranslation($string = '', $namespace = 'lang_main');

	/**
	* deletes a translation string + translation from the given namespace for ALL locales
	*/
	public function deleteTranslationString($string = '', $namespace = 'lang_main');

	/**
	* adds a translationstring + translation to the given namespace of the current locale
	* @param string $translation the stranslation itself
	*/
	public function addTranslation($string = '', $translation = '', $namespace = 'lang_main');

	/**
	* updates a translationstring or translation or namespace
	* @param string $translation the stranslation itself
	* @param int $position 2 = change namespace; 1 = change string; 0 = change translation
	*/
	public function updateTranslation($string = '', $translation = '', $namespace = 'lang_main', $position = 0);
	/**#@-*/
} // end interface
?>