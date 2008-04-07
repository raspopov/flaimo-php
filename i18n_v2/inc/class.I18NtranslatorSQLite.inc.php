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
* singleton function for getting one db connection
* @return object
*/
function setSQLiteConn($db) {
	static $conn;
	if (!isset($conn)) {
		$conn = sqlite_popen($db);
	} // end if
	return $conn;
} // end function

/**
* translator class with SQLite as a backend
* @author Michael Wimmer <flaimo@gmail.com>
* @category flaimo-php
* @example  ../www_root/i18n_example_script.php  i18n example script
* @license GNU General Public License v3
* @link http://code.google.com/p/flaimo-php/
* @package i18n
* @version 2.3
*/
class I18NtranslatorSQLite extends I18NtranslatorBase implements I18NtranslatorInterface {

	/**#@+
	* @var string database settings
	*/
	protected $database = 'translations.sqlite';
	protected $table = 'flp_translator';
	/**#@-*/

	/**
	* @var array
	*/
	protected $translation_table;

	/**
	* @var boolean
	*/
	const USE_FILECACHE = FALSE; // filecache normally is slower than using sqlite directly

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
	* fetches the db connection rfom the singleton function
	* @uses setSQLiteConn()
	* @uses I18Nbase::getI18Nsetting()
	* @return resource
	*/
	protected function getConn() {
		return setSQLiteConn(parent::getI18Nsetting('locales_path') . '/' . $this->database);
	} // end function

	/**
	* retreive the available translator locales
	* @uses I18NtranslatorBase::$locales
	* @uses I18NtranslatorSQLite::getConn()
	* @uses I18Nbase::isValidLocaleCode()
	* @uses I18Nbase::getI18NfactoryLocale()
	* @return boolean
	*/
	protected function setLocales() {
		$this->locales = array();
		$result = sqlite_unbuffered_query($this->getConn(), 'SELECT * FROM allfields'); // uses a view
		$columns = sqlite_num_fields($result);
		for ($i = 2; $i < $columns; $i++) {
			$name = sqlite_field_name($result, $i);

			if (parent::isValidLocaleCode($name) == TRUE) {
				$name = str_replace('_', '-', $name);

				$this->locales[$name] =& parent::getI18NfactoryLocale($name);
				$_SESSION['i18n']['translator_locales'][$name] = $name;
			} // end if
		} // end for
		return (count($this->locales) > 0) ? TRUE : FALSE;
	} // end function

	/**
	* fetch all translation files and transform them
	* @uses I18NtranslatorSQLite::$use_filecache
	* @uses I18Nbase::getFileCache()
	* @uses I18NtranslatorBase::$namespaces
	* @uses I18Nbase::getTranslatorLocale()
	* @uses I18NtranslatorSQLite::$translation_table
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

		$sql  = 'SELECT string, ' . str_replace('-', '_', parent::getTranslatorLocale()->getI18Nlocale());
		$sql .= ' FROM ' . $this->table;
		$sql .= ' WHERE namespace IN ("' . implode('","', $this->namespaces) . '")';
		$sql .= ' ORDER BY string ASC';
		$result = sqlite_unbuffered_query($this->getConn(), $sql);

		while ($row = sqlite_fetch_array($result, SQLITE_NUM)) {
			if ((mb_strlen($row[0]) > 0) && (mb_strlen($row[1]) > 0)) {
				parent::checkForDuplicates($row[0], implode(', ', $this->namespaces));
				$this->translation_table[$row[0]] = $row[1];
			} // end if
		} // end while

		if (self::USE_FILECACHE == TRUE) {
				$cache->writeCache($cache_filename, serialize($this->translation_table));
				unset($cache);
		} // end if
		return (boolean) TRUE;
	} // end function

	/**
	* returns an array with $array[translationstring] = translation
	* @uses I18NtranslatorSQLite::fetchAllTranslations()
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
	* @uses I18NtranslatorSQLite::getTranslationTable()
	* @uses I18NtranslatorSQLite::$translation_table
	* @uses I18NtranslatorSQLite::getConn()
	* @uses I18NtranslatorBase::getTranslatorLocale()
	*/
	public function translate($translationstring = '', $domain = '', $arguments = FALSE) {
		$string = trim($translationstring);
		if (array_key_exists($string, $this->getTranslationTable())) {
	    	if (!is_array($arguments)) {
	    		return $this->translation_table[$string];
	    	} // end if
	    	return vsprintf($this->translation_table[$string], $arguments);
	    } // end if

		$sql  = 'SELECT ' . $this->getTranslatorLocale()->getI18Nlocale();
		$sql .= ' FROM ' . $this->table;
		$sql .= ' WHERE string = "' . mysql_real_escape_string($string) . '"';
		$result = sqlite_query($this->getConn(), $sql);

	    if (sqlite_num_rows($result) > 0 &&
	    	(($field = sqlite_fetch_single($result)) != NULL)) {
	        $this->translation_table[$string] = $field;
	        return (string) $this->translation_table[$string];
		} // end if
		if (parent::getI18Nsetting('show_errormessages')) {
			trigger_error('TRANSLATION ERROR: String "' . $string . '" not found in translation table', E_USER_WARNING);
		} // end if
		return $string;
	} // end function
} // end I18NtranslatorSQLite
?>
