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
function setSQLiteConn($db_name) {
	static $conn;
	if (!isset($conn)) {
		$conn = new PDO('sqlite:' . $db_name);
	} // end if
	return $conn;
} // end function

function getPrepStat($index, $conn, $sql = '') {
	static $ps;
	if (!isset($ps[$index])) {
		$ps[$index] = $conn->prepare($sql);
	} // end if
	return $ps[$index];
} // end function

/**
* translator class with SQLite3 as a backend
* @author Michael Wimmer <flaimo@gmail.com>
* @category flaimo-php
* @example  ../www_root/i18n_example_script.php  i18n example script
* @license GNU General Public License v3
* @link http://code.google.com/p/flaimo-php/
* @package i18n
* @version 2.3
*/
class I18NtranslatorSQLite3 extends I18NtranslatorBase implements I18NtranslatorInterface {

	/**#@+
	* @var string database settings
	*/
	protected $database = 'translations.sqlite3';
	protected $table = 'flp_translator';
	/**#@-*/

	/**
	* @var array
	*/
	protected $translation_table;
	protected $prep_stat = array();

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
	* @uses I18NtranslatorSQLite3::getConn()
	* @uses I18Nbase::isValidLocaleCode()
	* @uses I18Nbase::getI18NfactoryLocale()
	* @return boolean
	*/
	protected function setLocales() {
		$this->locales = array();
		$ps = getPrepStat('locales', $this->getConn(), 'SELECT * FROM ' . $this->table . ' LIMIT 0,1');
		$ps->execute();
		$result = $ps->fetchAll();
		$keys = array_keys($result[0]);
		foreach ($keys as $name) {
			if (parent::isValidLocaleCode($name) == TRUE) {
				$name = str_replace('_', '-', $name);

				$this->locales[$name] = parent::getI18NfactoryLocale($name);
				$_SESSION['i18n']['translator_locales'][$name] = $name;
			} // end if
		} // end foreach
		return (count($this->locales) > 0) ? TRUE : FALSE;
	} // end function

	/**
	* fetch all translation files and transform them
	* @uses I18NtranslatorSQLite3::$use_filecache
	* @uses I18Nbase::getFileCache()
	* @uses I18NtranslatorBase::$namespaces
	* @uses I18Nbase::getTranslatorLocale()
	* @uses I18NtranslatorSQLite3::$translation_table
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
		$result = $this->getConn()->query($sql);

		foreach ($result as $row) {
			if ((mb_strlen($row[0]) > 0) && (mb_strlen($row[1]) > 0)) {
				parent::checkForDuplicates($row[0], implode(', ', $this->namespaces));
				$this->translation_table[$row[0]] = $row[1];
			} // end if
		} // end foreach

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
	* @uses I18NtranslatorSQLite3::getTranslationTable()
	* @uses I18NtranslatorSQLite3::$translation_table
	* @uses I18NtranslatorSQLite3::getConn()
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

		$ps = $this->getConn()->prepare($sql); // uses a view
		$ex = $ps->execute();
		$result = $ps->fetch();

	    if ($ps->rowCount() > 0 &&
	    	(($field &= $result[0]) != NULL)) {
	        $this->translation_table[$string] = $field;
	        return (string) $this->translation_table[$string];
		} // end if
		if (parent::getI18Nsetting('show_errormessages')) {
			trigger_error('TRANSLATION ERROR: String "' . $string . '" not found in translation table', E_USER_WARNING);
		} // end if
		return $string;
	} // end function
} // end I18NtranslatorSQLite3
?>
