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
function &setMySQLConn($host = '', $user = '', $pw = '', $database = '') {
	static $conn;
	if (!isset($conn)) {
		$conn = new mysqli($host, $user, $pw, $database);
 		$result = $conn->query('SET CHARACTER SET utf8');
	} // end if
	return $conn;
} // end function

/**
* translator class with MySQL as a backend
* @author Michael Wimmer <flaimo@gmail.com>
* @category flaimo-php
* @example  ../www_root/i18n_example_script.php  i18n example script
* @license GNU General Public License v3
* @link http://code.google.com/p/flaimo-php/
* @package i18n
* @version 2.3.1
*/
class I18NtranslatorMySQL extends I18NtranslatorBase implements I18NtranslatorInterface {

	/**#@+
	* @var string database settings
	*/
	protected $host = 'localhost';
	protected $user = 'root';
	protected $pw = 'maxmobil';
	protected $database = 'translator';
	protected $table = 'flp_translator';
	/**#@-*/

	/**#@+
	* @var array
	*/
	protected $translation_table;
	protected $prep_stat = array();
	/**#@-*/

	/**
	* @var boolean
	*/
	protected $use_filecache = FALSE;

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
	* @uses setMySQLConn()
	* @return resource
	*/
	protected function getConn() {
		return setMySQLConn($this->host, $this->user, $this->pw, $this->database);
	} // end function

	/**
	* retreive the available translator locales
	* @uses I18NtranslatorBase::$locales
	* @uses I18NtranslatorMySQL::getConn()
	* @uses I18Nbase::isValidLocaleCode()
	* @uses I18Nbase::getI18NfactoryLocale()
	* @return boolean
	*/
	protected function setLocales() {
		$this->locales = array();
		$sql = 'SHOW COLUMNS FROM ' . $this->table;
		$result = $this->getConn()->query($sql);
		//echo mysqli_error($this->getConn());
		while($row = $result->fetch_row()) {
			if (parent::isValidLocaleCode($row[0]) === TRUE) {
				$this->locales[$row[0]] =& parent::getI18NfactoryLocale($row[0]);
				$_SESSION['i18n']['translator_locales'][$row[0]] = $row[0];
			} // end if
		} // end while
		$result->close();
		return (count($this->locales) > 0) ? TRUE : FALSE;
	} // end function

	/**
	* fetch all translation files and transform them
	* @uses I18NtranslatorMySQL::$use_filecache
	* @uses I18Nbase::getFileCache()
	* @uses I18NtranslatorBase::$namespaces
	* @uses I18Nbase::getTranslatorLocale()
	* @uses I18NtranslatorMySQL::$translation_table
	* @return boolean
	*/
	protected function fetchAllTranslations() {
		if ($this->use_filecache === TRUE) {
			$cache =& parent::getFileCache();
			$cache_filename = implode('_', $this->namespaces) . parent::getTranslatorLocale()->getI18Nlocale();

			if ($cache->isCached($cache_filename) === TRUE) {
				$this->translation_table = unserialize($cache->returnCache($cache_filename));
				unset($cache);
				return (boolean) TRUE;
			} // end if
		} // end if

		$sql  = 'SELECT string, `' . parent::getTranslatorLocale()->getI18Nlocale() . '`';
		$sql .= ' FROM ' . $this->table;
		$sql .= ' WHERE namespace IN ("' . implode('","', $this->namespaces) . '")';
		$sql .= ' ORDER BY string ASC';
		$result =  $this->getConn()->query($sql);

		while($row = $result->fetch_row()) {
			if ((mb_strlen($row[0]) > 0) && (mb_strlen($row[1]) > 0)) {
				parent::checkForDuplicates($row[0], implode(', ', $this->namespaces));
				$this->translation_table[$row[0]] = $row[1];
			} // end if
		} // end while
		$result->close();

		if ($this->use_filecache === TRUE) {
				$cache->writeCache($cache_filename, serialize($this->translation_table));
				unset($cache);
		} // end if
		return (boolean) TRUE;
	} // end function

	/**
	* returns an array with $array[translationstring] = translation
	* @uses I18NtranslatorMySQL::fetchAllTranslations()
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
	* @uses I18NtranslatorMySQL::getTranslationTable()
	* @uses I18NtranslatorMySQL::$translation_table
	* @uses I18NtranslatorMySQL::getConn()
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

		if (!isset($this->prep_stat['string'])) {
			$sql  = 'SELECT `' . $this->getTranslatorLocale()->getI18Nlocale() . '`';
			$sql .= ' FROM ' . $this->table;
			$sql .= ' WHERE string = ? LIMIT 0,1';
			$this->prep_stat['string'] = $this->getConn()->prepare($sql);
			if ($this->prep_stat['string'] == FALSE) {
				if (parent::getI18Nsetting('show_errormessages')) {
					trigger_error('TRANSLATION ERROR: Could\'t query MySQL database for string "' . $string . '"', E_USER_WARNING);
				} // end if
				return $string;
			} // end if
		} // end if

		$translation = '';
		$this->prep_stat['string']->bind_param('s', $string);
		$this->prep_stat['string']->execute();
		$this->prep_stat['string']->bind_result($translation);

		if ($this->prep_stat['string']->fetch() == FALSE) {
			$this->prep_stat['string']->free_result();
			if (parent::getI18Nsetting('show_errormessages')) {
				trigger_error('TRANSLATION ERROR: String "' . $string . '" not found in translation table', E_USER_WARNING);
			} // end if
			return $string;
		} // end if

		$this->translation_table[$string] = $translation;
		$this->prep_stat['string']->free_result();
		if (strlen($translation) < 1) {
			if (parent::getI18Nsetting('show_errormessages')) {
				trigger_error('TRANSLATION ERROR: No translation available for string "' . $string . '"', E_USER_WARNING);
			} // end if
			return $string;
		} // end if
		return $translation;
	} // end function
} // end TranslatorMySQL
?>
