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
* extended translator class with MySQL as a backend
* @author Michael Wimmer <flaimo@gmail.com>
* @category flaimo-php
* @example  ../www_root/i18n_example_script.php  i18n example script
* @license GNU General Public License v3
* @link http://code.google.com/p/flaimo-php/
* @package i18n
* @version 2.3.1
*/
class I18NtranslatorMySQLExtended extends I18NtranslatorMySQL implements I18NtranslatorInterfaceExtended {

	/**
	* @param string $namespaces
	* @param object $locale I18Nlocale
	* @uses I18NtranslatorMySQL::__construct()
	* @return void
	*/
	public function __construct($namespaces = '', I18Nlocale &$locale = NULL) {
		parent::__construct($namespaces, $locale);
	} // end constructor

	/**
	* writes the last update timestamp to the session
	* @param string $namespace
	* @param int $timestamp
	* @uses I18NtranslatorBase::getLocales()
	* @return boolean
	*/
	protected function setLastUpdateSession($namespace = '', $timestamp = 0) {
		$locales =& $this->getLocales();
		foreach ($locales as $locale => &$object) {
			$_SESSION['i18n']['settings']['last_update'][$locale][$namespace] = (int) $timestamp;
		} // end if
		return (boolean) TRUE;
	} // end function

	/**
	* returns the timestamp when a given namespace was last updated
	* @param string $namespace
	* @return mixed int/boolean
	* @uses I18Nbase::isFilledString()
	* @uses I18NtranslatorBase::getLastUpdateSession()
	* @uses I18NtranslatorMySQL::$table
	* @uses I18NtranslatorMySQL::getConn()
	* @uses I18NtranslatorBase::setLastUpdateSession()
	* @return mixed
	*/
	public function getLastUpdateDateNamespace($namespace = '') { // basically an overloaded method...
		if (parent::isFilledString($namespace) == FALSE) {
			return (boolean) FALSE;
		} elseif (($tmp_namespace = parent::getLastUpdateSession($namespace)) != FALSE) {
			return (int) $tmp_namespace;
		} // end if

		$sql  = 'SELECT MAX(lastupdate)';
		$sql .= ' FROM ' . $this->table;
		$sql .= ' WHERE namespace = "' . mysql_real_escape_string($namespace) . '"';

		$result = parent::getConn()->query($sql);
		$row = $result->fetch_row();
		$this->setLastUpdateSession($namespace, $row[0]);
		$result->close();
		return $row[0];
	} // end function

	/**#@+
	* @return boolean
	* @param string $string the stranslation string
	* @param string $namespace
	*/
	/**
	* deletes selected values from  the translation array
	* @uses I18Nbase::isFilledString()
	* @uses I18NtranslatorMySQL::$translation_table
	* @uses I18NtranslatorBase::$namespaces
	*/
	protected function stripTranslationTable($string = '', $namespace = 'lang_main') {
 		if (parent::isFilledString($string) == FALSE ||
 			parent::isFilledString($namespace) == FALSE) {
 			return (boolean) FALSE;
 		} // end if

 		if (isset($this->translation_table[trim($string)]) && in_array(trim($namespace), $this->namespaces)) {
 			unset($this->translation_table[trim($string)]);
 			return (boolean) TRUE;
 		} // end if
 		return (boolean) FALSE;
	} // end function

	/**
	* deletes a translation from the given namespace for a given locale
	* @param object $locale I18Nlocale
	* @uses I18NtranslatorMySQL::$database
	* @uses I18NtranslatorMySQL::$table
	* @uses I18Nlocale::getI18Nlocale()
	* @uses I18NtranslatorMySQL::getConn()
	*/
	protected function deleteLocaleString($string = '', $namespace = 'lang_main', I18Nlocale &$locale) {
		$sql  = 'UPDATE ' . $this->database . '.' . $this->table;
		$sql .= ' SET `' . $locale->getI18Nlocale() . '`=NULL, lastupdate=UNIX_TIMESTAMP()';
		$sql .= ' WHERE string = "' . mysql_real_escape_string(trim($string)) . '" AND namespace = "' . mysql_real_escape_string(trim($namespace)) . '"';

		$result = parent::getConn()->query($sql);
		if (parent::getConn()->affected_rows < 1) {
			return (boolean) FALSE;
		} // end if
		return (boolean) TRUE;
	} // end function

	/**
	* deletes a translation string + translation from the given namespace for ALL locales
	* @uses I18NtranslatorMySQLExtended::deleteLocaleString()
	* @uses I18NtranslatorMySQL::getTranslatorLocale()
	*/
	public function deleteTranslationString($string = '', $namespace = 'lang_main') {
		$this->deleteLocaleString($string, $namespace, parent::getTranslatorLocale());
	} // end function

	/**
	* deletes a translation string + translation from the given namespace for the current locale
	* @uses I18NtranslatorMySQL::$database
	* @uses I18NtranslatorMySQL::$table
	* @uses I18NtranslatorMySQL::getConn()
	*/
	public function deleteTranslation($string = '', $namespace = 'lang_main') {
		$sql  = 'DELETE FROM ' . $this->database . '.' . $this->table;
		$sql .= ' WHERE string = "' . mysql_real_escape_string(trim($string)) . '" AND namespace = "' . mysql_real_escape_string(trim($namespace)) . '"';

		$result = parent::getConn()->query($sql);
		if (parent::getConn()->affected_rows < 1) {
			return (boolean) FALSE;
		} // end if
		return (boolean) TRUE;
	} // end function

	/**
	* adds a translationstring + translation to the given namespace of the current locale
	* @param string $translation the stranslation itself
	* @uses I18NtranslatorMySQL::$database
	* @uses I18NtranslatorMySQL::$table
	* @uses I18Nbase::validTranslationInput()
	* @uses I18NtranslatorMySQL::getConn()
	* @uses I18NtranslatorMySQL::$translation_table
	*/
	public function addTranslation($string = '', $translation = '', $namespace = 'lang_main') {
		if (parent::validTranslationInput($string, $translation, $namespace) === FALSE) {
			return (boolean) FALSE;
		} // end if

		$sql  = 'REPLACE INTO ' . $this->database . '.' . $this->table . ' (namespace, string, lastupdate, `' . parent::getTranslatorLocale()->getI18Nlocale() . '`)';
		$sql .= ' VALUES ("' . mysql_real_escape_string(trim($namespace)) . '", "' . mysql_real_escape_string(trim($string)) . '", UNIX_TIMESTAMP(), "' . mysql_real_escape_string(trim($translation)) . '")';

		$result = parent::getConn()->query($sql);
		if (parent::getConn()->affected_rows < 1) {
			return (boolean) FALSE;
		} // end if
		$this->translation_table[$string] = $translation;
		return (boolean) TRUE;
	} // end function

	/**
	* updates a translationstring or translation or namespace
	* @param string $translation the stranslation itself
	* @param int $position 2 = change namespace; 1 = change string; 0 = change translation
	* @uses I18NtranslatorMySQL::$database
	* @uses I18NtranslatorMySQL::$table
	* @uses I18Nbase::validTranslationInput()
	* @uses I18NtranslatorBase::getTranslatorLocale()
	* @uses I18NtranslatorMySQL::getConn()
	* @uses I18NtranslatorMySQLExtended::stripTranslationTable()
	*/
	public function updateTranslation($string = '', $translation = '', $namespace = 'lang_main', $position = 1) {
		if (parent::validTranslationInput($string, $translation, $namespace) === FALSE) {
			return (boolean) FALSE;
		} // end if

		$sql = 'UPDATE ' . $this->database . '.' . $this->table;
		$locale =& parent::getTranslatorLocale()->getI18Nlocale();

		if ($position == 0) { // changes string field
			$sql .= ' SET string="' . mysql_real_escape_string(trim($string)) . '", lastupdate=UNIX_TIMESTAMP()';
			$sql .= ' WHERE namespace="' . mysql_real_escape_string(trim($namespace)) . '" AND `' . $locale . '`="' . mysql_real_escape_string(trim($translation)) . '"';
		} elseif ($position == 2) { // changes translation field (default)
			$sql .= ' SET namespace="' . mysql_real_escape_string(trim($namespace)) . '", lastupdate=UNIX_TIMESTAMP()';
			$sql .= ' WHERE string="' . mysql_real_escape_string(trim($string)) . '" AND `' . $locale . '`="' . mysql_real_escape_string(trim($translation)) . '"';
		} else { // changes namespace
			$sql .= ' SET `' . $locale . '`="' . mysql_real_escape_string(trim($translation)) . '", lastupdate=UNIX_TIMESTAMP()';
			$sql .= ' WHERE string="' . mysql_real_escape_string(trim($string)) . '" AND namespace="' . mysql_real_escape_string(trim($namespace)) . '"';
		} // end if

		$result = parent::getConn()->query($sql);
		if (parent::getConn()->affected_rows < 1) {
			return (boolean) FALSE;
		} // end if
		$this->stripTranslationTable($string, $namespace);
		return (boolean) TRUE;
	} // end function
	/**#@-*/
} // end class
?>