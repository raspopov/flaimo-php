<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
//+----------------------------------------------------------------------+
//| WAMP (XP-SP1/1.3.27/4.0.12/4.3.2)                                    |
//+----------------------------------------------------------------------+
//| Copyright (c) 1992-2003 Michael Wimmer                               |
//+----------------------------------------------------------------------+
//| I don't have the time to read through all the licences to find out   |
//| what the exactly say. But it's simple. It's free for non commercial  |
//| projects, but as soon as you make money with it, i want my share :-) |
//| (License : Free for non-commercial use)                              |
//+----------------------------------------------------------------------+
//| Authors: Michael Wimmer <flaimo@gmx.net>                             |
//+----------------------------------------------------------------------+
//
// $Id$
/**
* @package i18n
* @category FLP
*/
/**
* Including the parent class
*/
@require_once 'class.Language.inc.php';
/**
* for encoding translated strings
*/
@require_once 'class.FormatString.inc.php';

/**
* Everything concerning the osm/content-language and the country for a user
*
* Best used for internationalisation. You create a subdirectory
* “language” on your webspace, which contains iso-standard named
* subdirectories again. in each of those directories you put a
* translation file. In your php script you create an object like this:
* <code>
* “$lg = (object) new Translator(language,translationfile_1,translationfile_1,...);”
* </code>
* and translate a variable:
* <code>
* “echo $lg->__('no_records_found');”
* </code>
* Since it’s a class you can create multible objects from it and even use more
* that one language on one page.
* If you want to use gettext or a mysql database instead of inc-files you can
* change the modus in the constructor.
* I did a quick performance test translating one string and the gettext
* modus was about 15% faster than the one with including files. I didn’t do
* a performance test with the mysql modus, but it seems way slower than the
* other two options, even though translation requests are partly cached.
*
* Tested with WAMP (XP-SP1/1.3.24/4.0.12/4.3.0)
* Last change: 2003-05-04
*
* @desc Everything concerning the osm/content-language and the country for a user
* @access public
* @author Michael Wimmer <flaimo 'at' gmx 'dot' net>
* @copyright Michael Wimmer
* @link http://www.flaimo.com/
* @package i18n
* @category FLP
* @version 1.061
*/
class Translator extends Language {

	/*-------------------*/
	/* V A R I A B L E S */
	/*-------------------*/

	/**#@+
	* @access private
	*/
	/**
	* path to the iso-named subdirectories. “locale” by default
	*
	* @desc path to the iso-named subdirectories. “locale” by default
	* @var string
	*/
	var $languagefile_path = 'locale';

	/**
	* Array of names of translation files WITHOUT the file extention
	*
	* @desc Array of names of translation files WITHOUT the file extention
	* @var array
	*/
	var $languagefile_names = array();

	/**
	* No Information available
	*
	* @desc No Information available
	* @var array
	*/
	var $language_files_array;

	/**
	* No Information available
	*
	* @desc No Information available
	* @var string
	*/
	var $language; // PHP5: protected

	/**
	* “inc” or “gettext”
	*
	* @desc “inc” or “gettext”
	* @var string
	*/
	var $modus = 'inc'; // PHP5: protected

	/**
	* if modus is “inc”, what is the extention of the inc files
	*
	* @desc if modus is “inc”, what is the extention of the inc files
	* @var string
	*/
	var $inc_extension = 'inc'; // PHP5: protected

	/**
	* Holds the settings for showing errormessages or not
	*
	* @desc Holds the settings for this class
	* @var array
	*/
	var $show_errormessages = TRUE;

	/**
	* Name of the MySQL Table with the translation table
	*
	* @desc Name of the MySQL Table with the translation table
	* @var string
	*/
	var $db_table = 'flp_translator';

	/**
	* Name of the MySQL database
	*
	* @desc Name of the MySQL database
	* @var string
	*/
	var $database = 'translator_testdb';

	/**
	* Name of the MySQL connection
	*
	* @desc Name of the MySQL connection
	* @var string
	*/
	var $conn;

	/**
	* Default filename if no translation file is given
	*
	* @desc Default filename if no translation file is given
	* @var string
	*/
	var $default_lang_file = 'lang_main';

	/**
	* Holds all locales which require utf encoding
	*
	* @desc Holds all locales which require utf encoding
	* @var array
	*/
	var $utf_encoding;

	/**
	* Array with aliases for certain languages
	*
	* @desc Array with aliases for certain languages
	* @var array
	*/
	var $alias = array();

	/**
	* timestamp with last change date of the translations files / table
	*
	* @desc timestamp with last change date of the translations files / table
	* @var int
	*/
	var $lastchange;

	/**
	* whether a locale should be checked if it exists or not everytime a locale is choosen
	*
	* @desc whether a locale should be checked if it exists or not everytime a locale is choosen
	* @var boolean
	*/
	var $locale_checking = TRUE;

	/**
	* whether a locale should be checked if it is an alias language or not
	*
	* @desc whether a locale should be checked if it is an alias language or not
	* @var boolean
	*/
	var $use_alias_langs = TRUE;

	/**
	* The number of strings for the selected namespaces
	*
	* @desc The number of strings for the selected namespaces
	* @var int
	*/
	var $count_strings;

	/**
	* holds a FormatString object
	*
	* @desc holds a FormatString object
	* @var object
	*/
	var $st;
	/**#@-*/

    /*-----------------------*/
	/* C O N S T R U C T O R */
	/*-----------------------*/

	/**
	* Constructor
	*
	* @desc Constructor
	* @param string $inputlang  You can set a language manually and override all other settings (cookie, session, automatic detection)
	* @param string $translationfiles  names of the translation files (.inc or .mo) WITHOUT the fileextention. If you use MySQL modus this are the namespaces for preselecting translationstrings from the database into an array
	* @return void
	* @access private
	* @uses checkClass()
	* @uses Language::Language()
	* @uses readDefaultTranslatorSettings()
	* @uses chooseLocale()
	* @uses loadGettext()
	* @uses setLanguageFileName()
	*/
	function Translator($inputlang = '', $translationfiles = 'lang_main') {
		parent::Language($inputlang);
		$this->setLanguageFileName($translationfiles);
		$this->readDefaultTranslatorSettings();
		$this->chooseLocale();
		$this->loadGettext();
	} // end constructor

	/*-------------------*/
	/* F U N C T I O N S */
	/*-------------------*/

	/**
	* Reads the default settings from the settings file if necessary
	*
	* @desc Reads the default settings from the settings file if necessary
	* @return void
	* @access private
	* @uses setLanguageFilePath()
	* @uses setIncExtension()
	* @uses setModus()
	* @uses setShowErrormessages()
	* @uses Language::readINIsettings()
	* @since 1.045 - 2003-02-21
	*/
	function readDefaultTranslatorSettings() {
        parent::readINIsettings();
        if (isset($GLOBALS[$this->i18n_globalname])) {
			$this->setLanguageFilePath($GLOBALS[$this->i18n_globalname]['Translator']['languagefile_path']);
			$this->setModus($GLOBALS[$this->i18n_globalname]['Translator']['modus']);
            $this->setIncExtension($GLOBALS[$this->i18n_globalname]['Translator']['inc_extension']);
            $this->setShowErrormessages($GLOBALS[$this->i18n_globalname]['Translator']['show_errormessages']);
            $this->default_lang_file =& $GLOBALS[$this->i18n_globalname]['Translator']['default_languagefile_name'];
            $this->utf_encoding 	 = (array) explode(',',$GLOBALS[$this->i18n_globalname]['Translator']['utf_encoding']);
            $this->locale_checking   =& $GLOBALS[$this->i18n_globalname]['Translator']['locale_checking'];
            $this->use_alias_langs   =& $GLOBALS[$this->i18n_globalname]['Translator']['use_alias_langs'];
    	} // end if
	} // end function

	/**
	* Returns the pathname for the language directory
	*
	* @desc Returns the pathname for the language directory
	* @return string $languagefile_path
	* @access public
	* @see getLanguageFileName()
	*/
	function &getLanguageFilePath() {
		return (string) $this->languagefile_path;
	} // end function

	/**
	* Returns the filename for the language translation-file
	*
	* @desc Returns the filename for the language translation-file
	* @return string $languagefile_name
	* @access public
	* @see getLanguageFilePath()
	*/
	function &getLanguageFileName() {
		return (string) $this->languagefile_name;
	} // end function


	/**
	* Returns the modus how translations should be received
	*
	* @desc Returns the modus how translations should be received
	* @return string $modus
	* @access public
	* @see setModus()
	* @since 1.030 - 2003-01-03
	*/
	function &getModus() {
		return (string) $this->modus;
	} // end function

	/**
	* Returns the extention of the inc translation files
	*
	* @desc Returns the extention of the inc translation files
	* @return string $inc_extention
	* @access public
	* @since 1.030 - 2003-01-03
	*/
	function &getIncExtension() {
		return (string) $this->inc_extension;
	} // end function

	/**
	* Checks the users request-header for language informations
	*
	* Returns an array or an array value from the array with all the languages
	* found in the subdirectory “locale”
	*
	* @desc Checks the users request-header for language informations
	* @return mixed $languageFilesArray  String or Array
	* @access private
	* @see setLanguageFilesArray()
	*/
	function &getLanguageFilesArray($pos = '') {
		if (array_key_exists($pos, $this->language_files_array)) {
			return (string) $this->language_files_array[$pos];
		} else {
			return (array) $this->language_files_array;
		} // end if
	} // end function

	/**#@+
	* @return void
	* @access private
	*/
	/**
	* If MySQL modus is used, sets the database connection
	*
	* @desc If MySQL modus is used, sets the database connection
	* @since 1.040 - 2003-02-06
	*/
	function setConnection() {
		if (!isset($this->conn)) {
			parent::readINIsettings();
			if (isset($GLOBALS[$this->i18n_globalname])) {
				$host 			=& $GLOBALS[$this->i18n_globalname]['Translator']['host'];
				$user 			=& $GLOBALS[$this->i18n_globalname]['Translator']['user'];
				$password 		=& $GLOBALS[$this->i18n_globalname]['Translator']['password'];
				$this->database =& $GLOBALS[$this->i18n_globalname]['Translator']['database'];
				$this->db_table =& $GLOBALS[$this->i18n_globalname]['Translator']['translation_table'];
			} else {
				$host 			= (string) 'localhost';
				$user 			= (string) 'root';
				$password 		= (string) '';
			} // end if

			$this->conn = mysql_pconnect($host, $user, $password) or die ('Connection not possible! => ' . mysql_error());
			mysql_select_db(&$this->database) or die ('Couldn\'t connect to "' . $this->database . '" => ' . mysql_error());
		} // end if
	} // end function

	/**
	* Creates a FormatString object
	*
	* Needed for further htmlencoding of a translated string
	*
	* @desc Creates a FormatString object
	* @see loadUserClass()
	* @uses FormatString
	*/
	function loadStringClass() {
		if (!isset($this->st)) {
			$this->st =& new FormatString();
		} // end if
	} // end function

	/**
	* Changes the language of the object. Also loads the new translationfile
	*
	* @desc Changes the language of the object. Also loads the new translationfile
	* @param string $locale  iso-code
	* @see setLang(), Language::changeLang()
	* @uses loadGettext()
	* @uses readLanguageFile()
	* @uses Language::chooseLang()
	* @uses Language::setInputLang()
	* @since 1.005 - 2002-12-28
	*/
	function changeLocale($locale) {
		parent::setInputLang($locale);
		$this->chooseLocale();
		if ($this->modus === 'gettext') {
			$this->loadGettext();
		} else {
			$this->readLanguageFile();
		} // end if
	} // end function

	/**
	* Sets the name for the path to the language-directory
	*
	* @desc Sets the name for the path to the language-directory
	* @param string $path  String to be assigned to the class variable languagefile_path
	* @see setLanguageFileName()
	*/
	function setLanguageFilePath($path) {
		$this->languagefile_path = (string) $path;
	} // end function

	/**
	* Sets the name for the filename of the language translation-file
	*
	* @desc Sets the name for the filename of the language translation-file
	* @param string $string  String to be assigned to the class variable languagefile_name
	* @see setLanguageFilePath()
	*/
	function setLanguageFileName($names = 'lang_main') {
		if (strlen(trim($names)) < 1) {
			$this->languagefile_names[] =& $this->default_lang_file;
		} else {
			$this->languagefile_names = array_map('trim', explode(',', $names));
		} // end if
	} // end function

	/**
	* Adds an entry to the “available languages” array
	*
	* @desc Adds an entry to the “available languages” array
	* @param string $string  String to be added (ISO code)
	* @see getLanguageFilesArray()
	*/
	function setLanguageFilesArray($string) {
		$this->language_files_array[] = $string;
	} // end function

	/**
	* Sets the modus how translations should be received
	*
	* @desc Sets the modus how translations should be received
	* @param string $modus  inc | gettext | mysql
	* @since 1.030 - 2003-01-04
	*/
	function setModus($modus = 'inc') {
		if ($modus === 'gettext' || $modus === 'inc' || $modus === 'mysql') {
			$this->modus = (string) $modus;
		} //  end if
	} // end function

	/**
	* Sets the setting if a errormessage should be displayed if a string could not be translated
	*
	* @desc Sets the setting if a errormessage should be displayed if a string could not be translated
	* @param boolean $show
	* @since 1.031 - 2003-01-18
	*/
	function setShowErrormessages($show = TRUE) {
			$this->show_errormessages = (boolean) $show;
	} // end function

	/**
	* Sets the gettext parameters
	*
	* Linux systems sometimes show a weird behaviour when working with gettext.
	* Sometimes it is necessary for the putenv function to always use the
	* “language_country” form for the language subdirectories and not just only
	* the "language" version. In other cases it is necessary to write
	* “putenv("LANG=iso639_en")” instead of just “putenv("LANG=en")” for example.
	* If you experience problems with your Linux configuration, try playing
	* around with the settings in this function to make it work.
	*
	* @desc Sets the gettext parameters
	* @see changeLang()
	* @uses Language::getLang()
	* @since 1.030 - 2003-01-04
	*/
	function loadGettext() {
		if ($this->modus === 'gettext') {
			if (!extension_loaded('gettext')) {
				if (!dl('gettext')) {
					$this->setModus('inc');
					$this->chooseLocale();
				} // end if
			} // end if
			putenv('LANG=' . $this->getRealLocale(parent::getLocale()));
			//setlocale(LC_ALL, ''); // Enable this if you have problems with gettext. Maybe it helps
			$td_set = (boolean) FALSE;
			foreach ($this->languagefile_names as $lang_file) {
				bindtextdomain($lang_file, $this->languagefile_path . '/');

				if ($td_set === TRUE) {
					continue;
				} // end if
				textdomain($this->languagefile_names[0]);
				$td_set = (boolean) TRUE;
			} // end foreach
		} //  end if
	} // end function

	/**
	* Sets the extention of modus is “inc”
	*
	* @desc Sets the extention of modus is “inc”
	* @param string $ext
	* @since 1.030 - 2003-01-04
	*/
	function setIncExtension($ext = 'inc') {
		$this->inc_extension = (string) (($this->modus === 'gettext') ? 'mo' : $ext);
	} // end function

	/**
	* Sets the locale ISO code
	*
	* This is an extended version of the function found in the Language class.
	* Sets the locale (iso code) depending on the given information
	* available. first looks if the value is set by the GET method (GET not
	* working at the moment), then checkes if a prefered code has been set in
	* the user class. if still no value has been found, it takes the
	* language-array set by the {@link readUserHeader()} function.
	*
	* @desc Sets the locale ISO code
	* @see chooseLangContent()
	* @see chooseCountry()
	* @see User::getPreferedLanguage()
	* @uses Language::getUser()
	* @uses Language::getInputLang()
	* @uses Language::IsValidLanguageCode
	* @uses Language::setLang
	* @uses User::getPreferedLanguage()
	* @uses languagesFilesList()
	* @uses Language::getUserLangArray()
	* @uses Language::::getDefaultLanguage()
	* @since 1.044 - 2003-02-18
	*/
	function chooseLocale() {
		$user =& parent::getUser();
		if (parent::isValidLanguageCode(parent::getInputLang()) === TRUE &&
		$this->checkLocale($this->getRealLocale(parent::getInputLang())) === TRUE) {
			parent::setLocale(parent::getInputLang());
		} elseif (parent::isValidLanguageCode($user->getPreferedLocale()) === TRUE &&
		$this->checkLocale($this->getRealLocale($user->getPreferedLocale())) === TRUE) {
			parent::setLocale($user->getPreferedLocale());
		} else {
			$this->languagesFilesList();
			/* Compare each language from the UserLanguage array with each
			language from the LanguageFiles array and see if one matches */
			$locale_match = (array) array_intersect(parent::getUserRawArray(), $this->language_files_array);
			if (count(&$locale_match) < 1) { // if no locale was found, check only the languages
				$locale_match = (array) array_intersect(parent::getUserLangArray(), $this->language_files_array);
			} // end if
			(count(&$locale_match) > 0) ? parent::setLocale(array_shift($locale_match)) : parent::setLocale(parent::getDefaultLocale());
			unset($locale_match);
		} // end if
	} // end function

	/**
	* Sets the country ISO code
	*
	* sets the country (iso code) depending on the locale set.
	*
	* @desc Sets the country ISO code
	* @see Language::chooseCountry()
	* @uses Language::setCountry()
	* @since 1.052 - 2003-03-26
	*/
	function chooseCountry() {
		if (($cutat = (int) strpos(parent::getLocale(),'_')) == TRUE) {
			parent::setCountry(substr(parent::getLocale(), ($cutat+1), 2));
		} else {
			parent::chooseCountry();
		} // end if
	} // end function

	/**
	* Sets the (osm)language ISO code
	*
	* sets the (osm)language (iso code) depending on the locale set.
	*
	* @desc Sets the (osm)language ISO code
	* @see Language::chooseLang()
	* @uses Language::setLang()
	* @since 1.052 - 2003-03-26
	*/
	function chooseLang() {
		parent::setLang(substr(parent::getLocale(), 0, 2));
	} // end function

	/**
	* Sets an array with all available languages
	*
	* sets an array with all the available (osm)languages by reading
	* the subdirectories of the language directory or the MySQL table and
	* checks if a valid translationfile is found there
	*
	* @desc Sets an array with all available languages
	* @return boolean if list was created or not
	* @uses Language::isValidLanguageCode()
	* @uses setLanguageFilesArray()
	* @uses setConnection()
	*/
	function languagesFilesList() {
		if (isset($this->language_files_array)) {
			return (boolean) TRUE;
		} // end if

		if ($this->modus === 'mysql') {
			$this->setConnection();
			$fields = mysql_list_fields($this->database, $this->db_table, $this->conn); // get all column names
			$columns = (int) mysql_num_fields(&$fields);
			for ($i = 3; $i < $columns; $i++) {
				$name = (string) trim(mysql_field_name(&$fields, $i));
				if (parent::isValidLanguageCode(&$name) === TRUE) {
					$this->setLanguageFilesArray(strtolower($name));
				} // end if
			} // end for
		} else {
			$root = (string) $this->languagefile_path . '/';
			$handle = @opendir($root);
			/* get all valid language directories */
			$subdir = (string) (($this->modus === 'gettext') ? '/LC_MESSAGES' : '');

			while ($lang_dir = strtolower(trim(@readdir($handle)))) {
				if (!is_dir($root . $lang_dir . $subdir) ||
					parent::isValidLanguageCode(&$lang_dir) === FALSE ||
					$this->getRealLocale(&$lang_dir) === FALSE) {
					continue;
				} // end if
				$this->setLanguageFilesArray(strtolower($lang_dir));
			} // end while
			@closedir($handle);
			unset($root, $handle, $subdir);
		} // end if
		return (boolean) TRUE;
		// shared mem code here

	} // end function

	/**
	* Reads the language file into an array
	*
	* gets the right translation-file from a subdirectory in the “locale”
	* directory, depending on the language set
	*
	* @desc Reads the language file into an array
	* @uses Language::getLang()
	*/
	function readLanguageFile() {
		// PHP5: private (or public, depends on manual language change in php files)
		$this->language = (array) array();
		if ($this->modus === 'inc') {
			/* go though all tranlationfiles and read the strings into an array */
			foreach ($this->languagefile_names as $lang_file) {
				$filepath = (string) $this->languagefile_path . '/' . $this->getRealLocale(parent::getLocale()) . '/' . $lang_file . '.' . $this->inc_extension;
				if (($languagefile = @file($filepath)) == FALSE) {
					//unset($this->languagefile_names[$key]);
					continue;
				} // end if
				/* filter out non-valid translation strings */
				$languagefile = array_filter(array_map('trim', $languagefile), 'strlen');
				foreach ($languagefile as $string) {
					list($lang_array_key, $lang_array_value) = split('=', $string);
					if ((strlen(rtrim($lang_array_key)) > 0) && (strlen(ltrim($lang_array_value)) > 0)) {
						$this->language[rtrim($lang_array_key)] = ltrim($lang_array_value);
					} // end if
				} // end for
			} // end foreach
			unset($languagefile, $lang_array_key, $lang_array_value);
		} elseif ($this->modus === 'mysql') {
			$this->setConnection();
			$query  = (string) 'SELECT string, ' . parent::getLocale(); // get all translations for a given namespace
			$query .= (string) ' FROM ' . $this->db_table;
			$query .= (string) ' WHERE namespace = "' . implode('" OR namespace = "', $this->languagefile_names) . '"';
			$result =  mysql_query(&$query, $this->conn) or die ('Request not possible! SQL Statement: ' . $query . ' / ' . mysql_error());
			while ($row = mysql_fetch_row(&$result)) {
				if ((strlen(trim($row[0])) > 0) && (strlen(trim($row[0])) > 0)) {
					$this->language[trim($row[0])] = trim($row[1]);
				} // end if
			} // end while
            mysql_free_result($result);
		}// end if
        ksort($this->language);
        // shared mem code here
	} // end function
	/**#@-*/

	/**#@+
	* @access public
	*/
	/**
	* Checks if a certain locale is still available without loading the whole language array again
	*
	* @desc Checks if a certain locale is still available
	* @param string $isolang  iso languagecode
	* @return boolean
	* @uses setConnection()
	* @uses isValidLanguageCode()
	* @uses setLanguageFilesArray()
	* @since 1.043 - 2003-02-15
	*/
	function checkLocale($isolang) {
		$this->readDefaultTranslatorSettings();
		if ($this->locale_checking === FALSE) {
			return (boolean) TRUE;
		} // end if
		$this->languagesFilesList();
		return (boolean) ((in_array($isolang, $this->language_files_array)) ? TRUE : FALSE);
	} // end function

	/**
	* Returns an translated string
	*
	* Important Class! Is mostly called from the pages. Returns the
	* requested word in the langage that is set by this class.
	* If you use gettext, use double quotes for the function
	* [ _("variable") instead of _('variable') ] to prevent certain errors
	* with special characters
	*
	* @desc Returns an translated string
	* @param string $string  String that should be translated
	* @param string $domain  Optional. If modus is gettext and you want to translate a string that is not in the first translation file, include the name of translation file where it can be found
	* @return string  Translated string
	* @uses readLanguageFile()
	* @uses translateError()
	*/
	function &translate($string = '', $domain = '') {
		$string = (string) trim($string);

		if ($this->modus != 'gettext' && !isset($this->language)) {
			$this->readLanguageFile();
		} // end if

		if ($this->modus === 'inc') {
			return (string) ((array_key_exists(&$string, $this->language)) ? $this->language[$string] : $this->translateError(&$string));
		} elseif ($this->modus === 'mysql') {
			if (array_key_exists(&$string, $this->language)) { // checks if string has already been translated once this request
				return (string) $this->language[$string];
			} else { // if nothing was found fires a sql request at the database and writes translation to the translation array
				$this->setConnection();
				$query  = 'SELECT SQL_SMALL_RESULT ' . parent::getLocale();
				$query .= ' FROM ' . $this->db_table;
				$query .= ' WHERE string = "' . addslashes($string) . '"';
				$result =  mysql_query(&$query, $this->conn) or die ('Request not possible! SQL Statement: ' . $query . ' / ' . mysql_error());
				if (mysql_num_rows(&$result) > 0 && ($field = mysql_result(&$result, 0, 0)) != NULL) {
					$this->language[$string] = (string) trim($field);
					mysql_free_result($result);
					return (string) $this->language[$string];
				} else {
					mysql_free_result($result);
					return (string) $this->translateError(&$string);
				} // end if
            } // end if
		} elseif ($this->modus === 'gettext') {
			$output = (string) gettext(&$string);
			/* if not in primary gettext file, try other ones that were binded */
			if ($output === $string) {
				$output = (string) dgettext(&$domain, &$string);
			} // end if
			return (string) (($output === $string) ? $this->translateError(&$string) : $output);
		} // end if
	} // end function

	/**
	* If the selected iso code is only a alias for another language it returns the real language
	*
	* You need to have the "redirect" file (with the real language code in it)
	* in your iso code directory to make this work
	*
	* @desc If the selected iso code is only a alias for another language it returns the real language
	* @param string $locale  iso code of a locale
	* @return string $real_lang
	* @since 1.050 - 2003-02-24
	* @uses Language::isValidLanguageCode()
	* @uses Language::getLocale()
	*/
	function getRealLocale(&$locale) {
        if ($this->modus === 'mysql' || $this->use_alias_langs == FALSE) {
            return (string) $locale;
        } // end if
		if (array_key_exists(&$locale, $this->alias)) {
			return $this->alias[$locale];
		} elseif (($redirect_file = @file($this->languagefile_path . '/' .  $locale . '/redirect')) == FALSE) {
			$this->alias[$locale] = $locale;
			return (string) $locale;
		} // end if
		if (parent::isValidLanguageCode($redirect_file[0]) === TRUE) {
			$this->alias[$locale] = $redirect_file[0];
			return $this->alias[$locale];
		} // end if
		return (string) $locale;
	} // end function

	/**
	* Returns an translated string
	*
	* Short version of the Translate function. If you have problems
	* (for example because of the gettext function) use the long named version
	*
	* @desc Returns an translated string
	* @param string $string  String that should be translated
	* @param string $domain  Optional. If modus is gettext and you want to translate a string that is not in the first translation file, include the name of translation file where it can be found
	* @return string  Translated string
	* @uses translate()
	*/
	function &_($string = '', $domain = '') {
		return (string) $this->translate(&$string, &$domain);
	} // end function

	/**
	* Checks if a locale is utf encoded
	*
	* @desc Checks if a locale is utf encoded
	* @param string $locale
	* @return boolean  whether locale is utf or not
	* @since 1.046 - 2003-02-22
	* @author unknown <wielspm@xs4all.nl>
	*/
	function isUTFencoded(&$locale) {
		return (boolean) ((in_array(&$locale, $this->utf_encoding)) ? TRUE : FALSE);
	} // end function

	/**
	* Returns an HTML-encoded translated string
	*
	* Same as the Translate function + HTML encoding of special characters
	*
	* @desc Returns an HTML-encoded translated string
	* @param string $string  String that should be translated
	* @param string $domain  Optional. If modus is gettext and you want to translate a string that is not in the first translation file, include the name of translation file where it can be found
	* @return string  Translated and encoded string
	* @uses translate()
	*/
	function &translateEncode($string = '', $domain = '') {
		if ($this->isUTFencoded($this->locale) === TRUE) {
			return (string) $this->utf2html($this->translate(&$string, &$domain));
		} else {
			return (string) htmlentities($this->translate(&$string, &$domain));
		}
	} // end function

	/**
	* Returns an HTML-encoded translated string + encodes more special characters
	*
	* Same as the Translate function + HTML encoding of special characters
	*
	* @desc Returns an HTML-encoded translated string
	* @param string $string  String that should be translated
	* @param string $domain  Optional. If modus is gettext and you want to translate a string that is not in the first translation file, include the name of translation file where it can be found
	* @return string  Translated and encoded string
	* @uses translate()
	*/
	function &translateEncodePlus($string = '', $domain = '') {
			$this->loadStringClass();
			return (string) $this->st->specialChar($this->translateEncode(&$string, &$domain));
	} // end function

	/**
	* Returns an HTML-encoded translated string
	*
	* Short version of the TranslateEncode function. If you have problems
	* (for example because of the gettext function) use the long named version
	*
	* @desc Returns an HTML-encoded translated string
	* @param string $string  String that should be translated
	* @param string $domain  Optional. If modus is gettext and you want to translate a string that is not in the first translation file, include the name of translation file where it can be found
	* @return string  Translated and encoded string
	*/
	function &__($string = '', $domain = '') {
		return (string) $this->translateEncode($string, $domain);
	} // end function

	/**
	* Returns an HTML-encoded translated string + encodes more special characters
	*
	* Short version of the TranslateEncodePlus function. If you have problems
	* (for example because of the gettext function) use the long named version
	*
	* @desc Returns an HTML-encoded translated string
	* @param string $string  String that should be translated
	* @param string $domain  Optional. If modus is gettext and you want to translate a string that is not in the first translation file, include the name of translation file where it can be found
	* @return string  Translated and encoded string
	*/
	function &___($string = '', $domain = '') {
		return (string) $this->translateEncodePlus($string, $domain);
	} // end function

	/**
	* Sets a session variable with the last update timestamp for a single namespace
	*
	* @desc Sets a session variable with the last update timestamp for a single namespace
	* @param string $namespace  namespace/languagefile.
	* @param int $timestamp  unix timestamp
	* @return void
	* @since 1.061 - 2003-06-21
	*/
	function setLastUpdateSession($namespace = '', $timestamp = 0) {
		$_SESSION['i18n_last_update'][$this->getRealLocale(parent::getLocale())][$namespace] = (int) $timestamp;
	} // end function

	/**
	* returns a session variable with the last update timestamp for a single namespace
	*
	* @desc returns a session variable with the last update timestamp for a single namespace
	* @param string $namespace  namespace/languagefile.
	* @return mixed Unix timestamp (int) if available or FALSE (boolean)
	* @since 1.061 - 2003-06-21
	*/
	function getLastUpdateSession($namespace = '') {
		if (isset($_SESSION['i18n_last_update'][$this->getRealLocale(parent::getLocale())][$namespace])) {
			return (int) $_SESSION['i18n_last_update'][$this->getRealLocale(parent::getLocale())][$namespace];
		} else {
			return (boolean) FALSE;
		} // end if
	} // end function

	/**
	* Returns the highest update-date for a single translationfile/namespace
	*
	* @desc Returns the highest update-date for a single translationfile/namespace
	* @param string $namespace  Get's the last update date for a namespace/languagefile.
	* @return mixed $lastchange Unix timestamp (int) or FALSE (boolean) if namespace wasn't found
	* @uses setConnection()
	* @uses getLastUpdateSession()
	* @uses setLastUpdateSession()
	* @uses getRealLocale()
	* @uses Language::getLocale()
	* @since 1.061 - 2003-06-21
	*/
	function getLastUpdateDateNS($namespace = '') { // basically an overloaded method...
		if (strlen(trim($namespace)) === 0) {
			return (boolean) FALSE;
		} elseif (($tmp_namespace = $this->getLastUpdateSession($namespace)) != FALSE) {
			return (int) $tmp_namespace;
		} // end if

	    if ($this->modus === 'mysql') {
		    $query  = (string) 'SELECT SQL_SMALL_RESULT namespace, MAX(lastupdate)';
	        $query .= (string) ' FROM ' . $this->db_table;
	        $query .= (string) ' WHERE namespace = ' . addslashes(trim($namespace));
	        $this->setConnection();
			$result =  mysql_query(&$query, $this->conn) or die ('Request not possible! SQL Statement: ' . $query . ' / ' . mysql_error());
			/* get the date for that namespace from the DB... */
			if (($lastchange = mysql_result(&$result, 0, 0)) != NULL) {
				mysql_free_result($result);
				/* ...and save it to the session */
				$this->setLastUpdateSession($namespace, $lastchange);
				return (int) $lastchange;
			} else { // if no date was found return -1 (error)
				mysql_free_result($result);
				return (boolean) FALSE;
			}// end if
	    } else {
			/* the translation files for gettext modus are is a subfolder */
			$subfolder = (string) '';
			if ($this->modus === 'gettext') {
				$subfolder = (string) 'LC_MESSAGES/';
			} // end if

			$path = (string) $this->languagefile_path . '/' . $this->getRealLocale(parent::getLocale()) . '/' . $subfolder . trim($namespace) . '.' . $this->inc_extension;
			if (($lastchange = @filemtime($path)) != FALSE) {
            	$this->setLastUpdateSession($namespace, $lastchange);
            	return (int) $lastchange;
			} // end if
			return (boolean) FALSE;
	    } // end if
	} // end function

	/**
	* Returns the highest update-date of the selected translationfiles/namespaces
	*
	* You want to use this function if you use some sort of caching and want
	* to check if one of the used translation strings/files has changed
	*
	* @desc Returns the highest update-date of the selected translationfiles/namespaces
	* @param string $namespaces  optional parameter: commaseperated string of namespaces/languagefiles. if empty, uses namespaces/languagefiles from the constructor
	* @return mixed $lastchange Unix timestamp (int) or FALSE (boolean) if namespace wasn't found
	* @uses getLastUpdateDateNS()
	* @since 1.054 - 2003-04-19
	*/
	function getLastUpdateDate($namespaces = '') {
		$lastchange = (boolean) FALSE;
		$namespaces = (array) ((strlen(trim($namespaces)) === 0) ? $this->languagefile_names : explode(',', $namespaces));

        foreach ($namespaces as $lang_file) {
            if (($currentdate = $this->getLastUpdateDateNS($lang_file)) === FALSE) {
				return (boolean) FALSE;
            } // end if

			/* if read languagefile-date is higher than the highes value, set it as highest value */
            if ($currentdate > $lastchange) {
                $lastchange = (int) $currentdate;
            } // end if
        } // end foreach
		return (int) $lastchange;
	} // end function

	/**
	* html encodes a UTF encodes string (experimental)
	*
	* Used for russian for example. Therefore, if your locale is utf encoded,
	* you have to ad the locale to the utf_encoding variable in the
	* i18n_settings.ini file
	*
	* @desc html encodes a UTF encodes string (experimental)
	* @param string $utf_string  utf encoded string
	* @return string  html encoded string
	* @static
	* @since 1.046 - 2003-02-22
	* @author unknown <wielspm@xs4all.nl>
	*/
	function utf2html($utf_string) {
		$utf2html_retstr = '';
		for ($utf2html_p = 0; $utf2html_p < strlen($utf_string); $utf2html_p++) {
			$utf2html_c = substr ($utf_string, $utf2html_p, 1);
			$utf2html_c1 = ord ($utf2html_c);
			if ($utf2html_c1>>5 == 6) { // 110x xxxx, 110 prefix for 2 bytes unicode
				$utf2html_p++;
				$utf2html_t = substr ($utf_string, $utf2html_p, 1);
				$utf2html_c2 = ord ($utf2html_t);
				$utf2html_c1 &= 31; // remove the 3 bit two bytes prefix
				$utf2html_c2 &= 63; // remove the 2 bit trailing byte prefix
				$utf2html_c2 |= (($utf2html_c1 & 3) << 6); // last 2 bits of c1 become first 2 of c2
				$utf2html_c1 >>= 2; // c1 shifts 2 to the right
				$utf2html_n = dechex($utf2html_c1).dechex($utf2html_c2);
				$utf2html_retstr .= sprintf ('&#%03d;', hexdec($utf2html_n));
			} else {
				$utf2html_retstr .= $utf2html_c;
			} // end if
		} // end for
		return $utf2html_retstr;
	} // end function

	/**
	* Returns the number of languages available
	*
	* @desc Returns the number of languages available
	* @return int $language_files_array
	* @since 1.041 - 2003-02-08
	*/
    function getCountLanguages() {
        return (int) count($this->getLanguageFilesArray());
    } // end function

	/**
	* Returns the number of translation strings for the selected namespace(s)
	*
	* @desc Returns the number of translation strings for the selected namespace(s)
	* @return int $count_strings -1 if error
	* @since 1.041 - 2003-02-08
	*/
    function getCountStrings() {
        if ($this->setCountStrings() === TRUE) {
        	return (int) $this->count_strings;
        } else {
        	return (int) -1;
    	} // end if
    } // end function
    /**#@-*/

	/**
	* Sets the number of strings available for the selected language
	*
	* @desc Sets the number of strings available for the selected language
	* @return boolean  if set or not
	* @access private
	* @since 1.041 - 2003-02-08
	*/
    function setCountStrings() {
        if(isset($this->count_strings) || (isset($this->language))) {
        	return (boolean) TRUE;
        } // end if

        if ($this->modus === 'inc' || $this->modus === 'mysql') {
			if (!isset($this->language)) {
				$this->readLanguageFile();
			} // end if
			$this->count_strings = (int) count($this->language);
			return (boolean) TRUE;
		} // end if

	    $counter = (int) 0;
		foreach ($this->languagefile_names as $lang_file) {
	        $path = (string) $this->languagefile_path . '/' . parent::getLocale() . '/LC_MESSAGES/' . $lang_file . '.po';
	        if (($gettextfile = @file($path)) == FALSE) {
	            continue;
	        } // end if
            foreach ($gettextfile as $value) {
                if (trim(substr($value, 0, 5)) == 'msgid') {
                    $counter++;
                } // end if
            } // end foreach
		} // end foreach
        unset($gettextfile);
        $this->count_strings = (int) ($counter - 1); // -1 because of the msgid "" at the beginning of each file
		return (boolean) TRUE;
    } // end function

	/**
	* Returns an errormessage
	*
	* If a string that should be translated was not found in the translation
	* array, a error message is displayed instead of the translation
	*
	* @desc Returns an errormessage
	* @param string $string  String that could not be translated
	* @return string  Complete errormessage
	* @access protected
	*/
	function &translateError(&$string) {
		return (string) (($this->show_errormessages === TRUE) ? trigger_error('ERROR TRANSLATING: »» ' . $string . ' ««', E_USER_WARNING) : $string);
	} // end function
} // end class Translator
?>