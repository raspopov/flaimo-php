<?php
/**
* @package i18n
*/
/**
* Including the parent class
*/
@include_once('class.Language.inc.php');

/**
* Everything concerning the osm/content-language and the country for a user
*
* Best used for internationalisation. You create a subdirectory
* “language” on your webspace, which contains iso-standard named
* subdirectories again. in each of those directories you put a
* translation file. In your php script you create an object like this:
* “$lg = (object) new Translator(language,translationfile_1,translationfile_1,...);”
* and translate a variable:
* “echo $lg->__('no_records_found');”. Since it’s a class
* you can create multible objects from it and even use more that one
* language on one page.
* If you want to use gettext or a mysql database instead of inc-files you can
* change the modus in the constructor.
* I did a quick performance test translating one string and the gettext
* modus was about 15% faster than the one with including files. I didn’t do
* a performance test with the mysql modus, but it seems way slower than the
* other two options, even though translation requests are partly cached.
*
* Tested with WAMP (XP-SP1/1.3.24/4.0.4/4.3.0)
* Last change: 2003-03-26
*
* @desc Everything concerning the osm/content-language and the country for a user
* @access public
* @author Michael Wimmer <flaimo@gmx.net>
* @copyright Michael Wimmer
* @link http://www.flaimo.com/
* @package i18n
* @version 1.053
*/
class Translator extends Language {

	/*-------------------*/
	/* V A R I A B L E S */
	/*-------------------*/

	/**
	* path to the iso-named subdirectories. “locale” by default
	*
	* @desc path to the iso-named subdirectories. “locale” by default
	* @var string
	* @access private
	*/
	var $languagefile_path = 'locale';

	/**
	* Array of names of translation files WITHOUT the file extention
	*
	* @desc Array of names of translation files WITHOUT the file extention
	* @var array
	* @access private
	*/
	var $languagefile_names;


	/**
	* No Information available
	*
	* @desc No Information available
	* @var array
	* @access private
	*/
	var $languageFilesArray;

	/**
	* No Information available
	*
	* @desc No Information available
	* @var string
	* @access private
	*/
	var $language; // PHP5: protected

	/**
	* “inc” or “gettext”
	*
	* @desc “inc” or “gettext”
	* @var string
	* @access private
	*/
	var $modus = 'inc'; // PHP5: protected

	/**
	* if modus is “inc”, what is the extention of the inc files
	*
	* @desc if modus is “inc”, what is the extention of the inc files
	* @var string
	* @access private
	*/
	var $inc_extension = 'inc'; // PHP5: protected

	/**
	* Holds the settings for this class
	*
	* @desc Holds the settings for this class
	* @var array
	* @access private
	*/
	var $translator_settings;

	/**
	* Holds the settings for showing errormessages or not
	*
	* @desc Holds the settings for this class
	* @var array
	* @access private
	*/
	var $show_errormessages = TRUE;

	/**
	* Name of the MySQL Table with the translation table
	*
	* @desc Name of the MySQL Table with the translation table
	* @var string
	* @access private
	*/
	var $db_table = 'flp_translator';

	/**
	* Name of the MySQL database
	*
	* @desc Name of the MySQL database
	* @var string
	* @access private
	*/
	var $database = 'translator_testdb';

	/**
	* Name of the MySQL connection
	*
	* @desc Name of the MySQL connection
	* @var string
	* @access private
	*/
	var $conn;

	/**
	* Default filename if no translation file is given
	*
	* @desc Default filename if no translation file is given
	* @var string
	* @access private
	*/
	var $default_lang_file = 'lang_main';

	/**
	* Holds the settings for this class
	*
	* @desc Holds the settings for this class
	* @var array
	* @access private
	*/
	var $translator_settings;

	/**
	* Holds all locales which require utf encoding
	*
	* @desc Holds all locales which require utf encoding
	* @var array
	* @access private
	*/
	var $utf_encoding;

	/**
	* Array with aliases for certain languages
	*
	* @desc Array with aliases for certain languages
	* @var array
	* @access private
	*/
	var $alias = array();

	/*-----------------------*/
	/* C O N S T R U C T O R */
	/*-----------------------*/

	/**
	* Constructor
	*
	* @desc Constructor
	* @param (string) $inputlang  You can set a language manually and override all other settings (cookie, session, automatic detection)
	* @param (string) $translationfiles  names of the translation files (.inc or .mo) WITHOUT the fileextention. If you use MySQL modus this are the namespaces for preselecting translationstrings from the database into an array
	* @return (void)
	* @access private
	* @uses checkClass(), Language::Language(), readDefaultTranslatorSettings(), ChooseLocale(), loadGettext(), setLanguageFileName()
	* @since 1.000 - 2002/10/10
	*/
	function Translator($inputlang = '', $translationfiles = 'lang_main') {
		$this->checkClass('Language', __LINE__);
		parent::Language($inputlang);
		$this->setLanguageFileName($translationfiles);
		$this->readDefaultTranslatorSettings();
		$this->ChooseLocale();
		$this->loadGettext();
	} // end constructor

	/*-------------------*/
	/* F U N C T I O N S */
	/*-------------------*/

	/**
	* Reads the default settings from the settings file if necessary
	*
	* @desc Reads the default settings from the settings file if necessary
	* @return (void)
	* @access private
	* @uses setLanguageFilePath(), setIncExtension(), setModus(), setShowErrormessages()
	* @since 1.045 - 2003/02/21
	*/
	function readDefaultTranslatorSettings() {
        if (!isset($this->translator_settings) && file_exists('i18n_settings.ini')) {
			$this->translator_settings = (array) parse_ini_file('i18n_settings.ini', TRUE);
			$this->setLanguageFilePath($this->translator_settings['Translator']['languagefile_path']);
			$this->setIncExtension($this->translator_settings['Translator']['inc_extension']);
			$this->setModus($this->translator_settings['Translator']['modus']);
            $this->setShowErrormessages($this->translator_settings['Translator']['show_errormessages']);
            $this->default_lang_file =& $this->translator_settings['Translator']['default_languagefile_name'];
            $this->utf_encoding = (array) explode(',',$this->translator_settings['Translator']['utf_encoding']);
    	} // end if
	} // end function

	/**
	* Returns the pathname for the language directory
	*
	* @desc Returns the pathname for the language directory
	* @return (string) $languagefile_path
	* @access public
	* @see getLanguageFileName()
	* @since 1.000 - 2002/10/10
	*/
	function &getLanguageFilePath() {
		return (string) $this->languagefile_path;
	} // end function

	/**
	* Returns the filename for the language translation-file
	*
	* @desc Returns the filename for the language translation-file
	* @return (string) $languagefile_name
	* @access public
	* @see getLanguageFilePath()
	* @since 1.000 - 2002/10/10
	*/
	function &getLanguageFileName() {
		return (string) $this->languagefile_name;
	} // end function


	/**
	* Returns the modus how translations should be received
	*
	* @desc Returns the modus how translations should be received
	* @return (string) $modus
	* @access public
	* @see setModus()
	* @since 1.030 - 2003/01/03
	*/
	function &getModus() {
		return (string) $this->modus;
	} // end function

	/**
	* Returns the extention of the inc translation files
	*
	* @desc Returns the extention of the inc translation files
	* @return (string) $inc_extention
	* @access public
	* @since 1.030 - 2003/01/03
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
	* @return (mixed) $languageFilesArray  String or Array
	* @access private
	* @see setLanguageFilesArray()
	* @since 1.000 - 2002/10/10
	*/
	function &getLanguageFilesArray($pos = '') {
		if (array_key_exists($pos, $this->languageFilesArray)) {
			return (string) $this->languageFilesArray[$pos];
		} else {
			return (array) $this->languageFilesArray;
		} // end if
	} // end function

	/**
	* If MySQL modus is used, sets the database connection
	*
	* @desc If MySQL modus is used, sets the database connection
	* @return (void)
	* @access private
	* @since 1.040 - 2003/02/06
	*/
	function setConnection() {
		if (!isset($this->conn)) {
			if (isset($this->translator_settings)) {
				$host =& 						$this->translator_settings['Translator']['host'];
				$user =& 						$this->translator_settings['Translator']['user'];
				$password =& 					$this->translator_settings['Translator']['password'];
				$this->database =& 				$this->translator_settings['Translator']['database'];
				$this->db_table =& 				$this->translator_settings['Translator']['translation_table'];
			} else {
				$host = 					(string) 'localhost';
				$user = 					(string) 'root';
				$password = 				(string) '';
			} // end if

			$this->conn = mysql_pconnect($host, $user, $password) or die ('Connection not possible! => ' . mysql_error());
			mysql_select_db(&$this->database) or die ('Couldn\'t connect to "' . $this->database . '" => ' . mysql_error());
		} // end if
	} // end function

	/**
	* Changes the language of the object. Also loads the new translationfile
	*
	* @desc Changes the language of the object. Also loads the new translationfile
	* @param (string) $language  iso-code
	* @return (void)
	* @access private
	* @see setLang(), Language::changeLang()
	* @uses loadGettext(), ReadLanguageFile(), Language::ChooseLang(), Language::setInputLang()
	* @since 1.005 - 2002/12/28
	*/
	function changeLocale($locale) {
		parent::setInputLang($locale);
		parent::ChooseLocale();
		if ($this->modus === 'inc') {
			$this->ReadLanguageFile();
		} elseif ($this->modus === 'gettext') {
			$this->loadGettext();
		} elseif ($this->modus === 'mysql') {
			$this->ReadLanguageFile();
		}
	} // end function


	/**
	* Sets the name for the path to the language-directory
	*
	* @desc Sets the name for the path to the language-directory
	* @param (string) $path  String to be assigned to the class variable languagefile_path
	* @return (void)
	* @access private
	* @see setLanguageFileName()
	* @since 1.000 - 2002/10/10
	*/
	function setLanguageFilePath($path) {
		$this->languagefile_path = (string) $path;
	} // end function

	/**
	* Sets the name for the filename of the language translation-file
	*
	* @desc Sets the name for the filename of the language translation-file
	* @param (string) $string  String to be assigned to the class variable languagefile_name
	* @return (void)
	* @access private
	* @see setLanguageFilePath()
	* @since 1.000 - 2002/10/10
	*/
	function setLanguageFileName($names) {
		if (strlen(trim($names)) > 0) {
			$tmp_array = (array) explode(',',$names);
			foreach ($tmp_array as $filename) {
				$this->languagefile_names[] = $filename;
			} // end foreach
			if (count($this->languagefile_names) == 0){
				$this->languagefile_names[] =& $this->default_lang_file;
			} // end if
		} else {
			$this->languagefile_names[] =& $this->default_lang_file;
		} // end if
	} // end function

	/**
	* Adds an entry to the “available languages” array
	*
	* @desc Adds an entry to the “available languages” array
	* @param (string) $string  String to be added (ISO code)
	* @return (void)
	* @access private
	* @see getLanguageFilesArray()
	* @since 1.000 - 2002/10/10
	*/
	function setLanguageFilesArray($string) {
		$this->languageFilesArray[] = $string;
	} // end function

	/**
	* Sets the modus how translations should be received
	*
	* @desc Sets the modus how translations should be received
	* @param (string) $modus  inc | gettext | mysql
	* @return (void)
	* @access private
	* @since 1.030 - 2003/01/04
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
	* @param (boolean) $show
	* @return (void)
	* @access private
	* @since 1.031 - 2003/01/18
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
	* @return (void)
	* @access private
	* @see changeLang()
	* @uses Language::getLang()
	* @since 1.030 - 2003/01/04
	*/
	function loadGettext() {
		if ($this->modus === 'gettext') {
			if (!extension_loaded('gettext')) {
				die('gettext extension not installed');
			} // end if
			putenv('LANG=' . $this->getRealLocale(parent::getLocale()));
			//setlocale(LC_ALL, ''); // Enable this if you have problems with gettext. Maybe it helps
			$td_set = (boolean) FALSE;
			foreach ($this->languagefile_names as $lang_file) {
				bindtextdomain($lang_file, $this->languagefile_path . '/');
				/*if ($this->isUTFencoded($this->locale) == TRUE) {
					bind_textdomain_codeset($lang_file, 'UTF-8');
				} // end if */
				if ($td_set === FALSE) {
					textdomain($lang_file);
					$td_set = (boolean) TRUE;
				} // end if
			} // end foreach
		} //  end if
	} // end function

	/**
	* Sets the extention of modus is “inc”
	*
	* @desc Sets the extention of modus is “inc”
	* @param (string) $ext
		* @return (void)
	* @access private
	* @since 1.030 - 2003/01/04
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
	* language-array set by the readUserHeader() function.
	*
	* @desc Sets the locale ISO code
	* @return (void)
	* @access protected
	* @see ChooseLangContent(), ChooseCountry(), User::getPreferedLanguage()
	* @uses Language::getUser(), Language::getInputLang(), Language::IsValidLanguageCode, Language::setLang, User::getPreferedLanguage(), LanguagesFilesList(), Language::getUserLangArray(), Language::::getDefaultLanguage()
	* @since 1.044 - 2003/02/18
	*/
	function ChooseLocale() {
		$user =& parent::getUser();
		if (parent::IsValidLanguageCode(parent::getInputLang()) === TRUE &&
		$this->checkLocale($this->getRealLocale(parent::getInputLang())) === TRUE) {
			parent::setLocale(parent::getInputLang());
		} elseif (parent::IsValidLanguageCode($user->getPreferedLocale()) === TRUE &&
		$this->checkLocale($this->getRealLocale($user->getPreferedLocale())) === TRUE) {
			parent::setLocale($user->getPreferedLocale());
		} else {
			if (!isset($this->languageFilesArray)) {
				$this->LanguagesFilesList();
			} // end if
			$localeSet = (boolean) FALSE;
			/* Compare each language from the UserLanguage array with each
			language from the LanguageFiles array and see if one matches */
			if (count($this->languageFilesArray) > 0) {
				foreach (parent::getUserRawArray() as $locale) {
					foreach ($this->languageFilesArray as $lang_file) {
						if ($locale === $lang_file) {
							parent::setLocale($locale);
							$localeSet = (boolean) TRUE;
							break 2;
						} // end if
					} // end foreach
				} // end foreach
				if ($localeSet === FALSE) { // if no locale was found, check only the languages
					foreach (parent::getUserLangArray() as $locale) {
						foreach ($this->languageFilesArray as $lang_file) {
							if ($locale === $lang_file) {
								parent::setLocale($locale);
								$localeSet = (boolean) TRUE;
								break 2;
							} // end if
						} // end foreach
					} // end foreach
				} // end if
			} // end if
			if ($localeSet === FALSE) {
				parent::setLocale(parent::getDefaultLocale());
			} // end if
			unset($localeSet);
		} // end if
	} // end function

	/**
	* Sets the country ISO code
	*
	* sets the country (iso code) depending on the locale set.
	*
	* @desc Sets the country ISO code
	* @return (void)
	* @access protected
	* @see Language::ChooseCountry()
	* @uses Language::setCountry()
	* @since 1.052 - 2003/03/26
	*/
	function ChooseCountry() {
		if (($cutat = (int) strpos($this->getLocale(),'_')) == TRUE) {
			parent::setCountry(substr($this->getLocale(), ($cutat+1), 2));
		} else {
			parent::ChooseCountry();
		} // end if
	} // end function

	/**
	* Sets the (osm)language ISO code
	*
	* sets the (osm)language (iso code) depending on the locale set.
	*
	* @desc Sets the (osm)language ISO code
	* @return (void)
	* @access protected
	* @see Language::ChooseLang()
	* @uses Language::setLang()
	* @since 1.052 - 2003/03/26
	*/
	function ChooseLang() {
		parent::setLang(substr($this->getLocale(), 0, 2));
	} // end function

	/**
	* Checks if a certain locale is still available without loading the whole language array again
	*
	* @desc Checks if a certain locale is still available
	* @param (string) $isolang  iso languagecode
	* @return (boolean)
	* @access public
	* @uses setConnection(), IsValidLanguageCode(), setLanguageFilesArray()
	* @since 1.043 - 2003/02/15
	*/
	function checkLocale($isolang) {
		if (isset($this->languageFilesArray)) {
			return (boolean) ((in_array($isolang, $this->languageFilesArray)) ? TRUE : FALSE);
		} elseif ($this->modus === 'mysql') {
			$this->setConnection();
			$return = (boolean) FALSE;
			$fields = mysql_list_fields($this->database, $this->db_table, $this->conn);
			$columns = (int) mysql_num_fields(&$fields);
			for ($i = 2; $i < $columns; $i++) {
				if ($isolang == mysql_field_name(&$fields, $i)) {
					$return = (boolean) TRUE;
					break;
				} // end if
			} // end for
			return (boolean) $return;
		} elseif ($this->modus === 'gettext') {
			return (boolean) ((is_dir($this->languagefile_path . '/' . $isolang. '/LC_MESSAGES')) ? TRUE : FALSE);
		} elseif ($this->modus === 'inc') {
			return (boolean) ((is_dir($this->languagefile_path . '/' . $isolang)) ? TRUE : FALSE);
		}// end if
	} // end function

	/**
	* Sets an array with all available languages
	*
	* sets an array with all the available (osm)languages by reading
	* the subdirectories of the language directory or the MySQL table and
	* checks if a valid translationfile is found there
	*
	* @desc Sets an array with all available languages
	* @return (void)
	* @access protected
	* @uses Language::IsValidLanguageCode(), setLanguageFilesArray(), setConnection()
	* @since 1.000 - 2002/10/10
	*/
	function LanguagesFilesList() {
		if (!isset($this->languageFilesArray)) {
			if ($this->modus === 'mysql') {
				$this->setConnection();
				$fields = mysql_list_fields($this->database, $this->db_table, $this->conn);
				$columns = (int) mysql_num_fields(&$fields);
				for ($i = 2; $i < $columns; $i++) {
					$name = (string) trim(mysql_field_name(&$fields, $i));
   					if (parent::IsValidLanguageCode(&$name) === TRUE) {
   						$this->setLanguageFilesArray(strtolower($name));
   					} // end if
				} // end for
			} else {
				$root = (string) $this->languagefile_path . '/';
				$handle = @opendir($root);
				while ($lang_dir = strtolower(trim(@readdir($handle)))) {
					if (parent::IsValidLanguageCode($lang_dir) === FALSE || !is_dir($root . $lang_dir)) {
						continue;
					} // end if
					if ($this->checkLocale($this->getRealLocale($lang_dir)) === TRUE) {
						$temp_langs[] = $lang_dir;
					} // end if
				} // end while
				$this->languageFilesArray = array_merge($this->languageFilesArray, $temp_langs);
				@closedir($handle);
				unset($root);
				unset($handle);
			} // end if
		} // end if
	} // end function


	/**
	* Reads the language file into an array
	*
	* gets the right translation-file from a subdirectory in the “locale”
	* directory, depending on the language set
	*
	* @desc Reads the language file into an array
	* @return (void)
	* @access private
	* @uses Language::getLang()
	* @since 1.000 - 2002/10/10
	*/
	function ReadLanguageFile() {
		// PHP5: private (or public, depends on manual language change in php files)
		$this->language = (array) array();
		if ($this->modus === 'inc') {
			foreach ($this->languagefile_names as $lang_file) {
				if (file_exists($this->languagefile_path . '/' . $this->getRealLocale(parent::getLocale()) . '/' . $lang_file . '.' . $this->inc_extension)) {
					$languagefile = (array) file($this->languagefile_path . '/' . $this->getRealLocale(parent::getLocale()) . '/' . $lang_file . '.' . $this->inc_extension);
					$strings = (array) array_keys($languagefile);
					$size = (int) sizeOf($strings);
					for ($i = 0; $i < $size; $i++) {
						if (strlen(trim($languagefile[$strings[$i]])) === 0) {
							continue;
						} // end if
						list($lang_array_key, $lang_array_value) = split('=', $languagefile[$strings[$i]]);
						if ((strlen(trim($lang_array_key)) > 0) && (strlen(trim($lang_array_value)) > 0)) {
							$this->language[trim($lang_array_key)] = trim($lang_array_value);
						} // end if
					} // end for
				} // end if
			} // end foreach
			unset($languagefile);
			unset($strings);
			unset($size);
		} elseif ($this->modus === 'mysql') {
			$this->setConnection();
			$query  = (string) 'SELECT string, ' . parent::getLocale();
			$query .= (string) ' FROM ' . $this->db_table;
			$query .= (string) ' WHERE';
			foreach ($this->languagefile_names as $lang_file) {
				$query .= (string) ' namespace = "' . addslashes($lang_file) . '" OR';
			} // end foreach
			$query = (string) substr($query, 0, strlen($query)-2);
			$result =  mysql_query(&$query, $this->conn) or die ('Request not possible! SQL Statement: ' . $query . ' / ' . mysql_error());
			while ($row = mysql_fetch_array(&$result, MYSQL_NUM)) {
		    	$this->language[trim($row[0])] = trim($row[1]);
			} // end while
            mysql_free_result($result);
		}// end if
	} // end function

	/**
	* Returns an translated string
	*
	* Important Class! Is mostly called from the pages. Returns the
	* requested word in the langage that is set by this class. The
	* dictionary is the “language.inc.php” file
	* If you use gettext, use double quotes for the function
	* [ _("variable") instead of _('variable') ] to prevent certain errors
	* with special characters
	*
	* @desc Returns an translated string
	* @param (string) $string  String that should be translated
	* @param (string) $domain  Optional. If modus is gettext and you want to translate a string that is not in the first translation file, include the name of translation file where it can be found
	* @return (string)  Translated string
	* @access public
	* @uses ReadLanguageFile(), TranslateError()
	* @since 1.000 - 2002/10/10
	*/
	function &Translate($string = '', $domain = '') {
		$string = (string) trim($string);
		if ($this->modus === 'inc') {
			if (!isset($this->language)) {
				$this->ReadLanguageFile();
			} // end if
			return (string) ((array_key_exists(&$string, $this->language)) ? $this->language[$string] : $this->TranslateError(&$string));
		} elseif ($this->modus === 'gettext') {
			$output = (string) gettext(&$string);
			if ($output === $string) {
				$output = (string) dgettext(&$domain, &$string);
			} // end if
			return (string) (($output === $string) ? $this->TranslateError(&$string) : $output);
		} elseif ($this->modus === 'mysql') {
			if (!isset($this->language)) {
				$this->ReadLanguageFile();
			} // end if
			if (array_key_exists(&$string, $this->language)) { // checks if string has already been translated once this request
				return (string) $this->language[$string];
			} else { // fires a sql request at the database and writes translation to the translation array
				$this->setConnection();
				$query  = 'SELECT SQL_SMALL_RESULT ' . parent::getLocale();
				$query .= ' FROM ' . $this->db_table;
				$query .= ' WHERE string = "' . addslashes($string) . '"';
				$result =  mysql_query(&$query, $this->conn) or die ('Request not possible! SQL Statement: ' . $query . ' / ' . mysql_error());
				if (mysql_num_rows(&$result) > 0 && ($field = mysql_result(&$result, 0, 0)) != NULL) {
					$translation = $this->language[$string] = (string) trim($field);
					return (string) $translation;
				} else {
					return (string) $this->TranslateError(&$string);
				} // end if
                mysql_free_result($result);
            } // end if
		} // end if
	} // end function

	/**
	* If the selected iso code is only a alias for another language it returns the real language
	*
	* You need to have the "redirect" file (with the real language code in it)
	* in your iso code directory to make this work
	*
	* @desc If the selected iso code is only a alias for another language it returns the real language
	* @param (string) $locale  iso code of a locale
	* @return (string) $real_lang
	* @access public
	* @since 1.050 - 2003/02/24
	* @uses Language::IsValidLanguageCode(), Language::getLocale()
	*/
	function getRealLocale($locale) {
		$real_lang = $locale;
		if (array_key_exists($locale, $this->alias)) {
			$real_lang =& $this->alias[$locale];
		} else {
			if (file_exists($this->languagefile_path . '/' .  $locale . '/redirect')) {
				$redirect_file = (array) file($this->languagefile_path . '/' .  $locale . '/redirect');
				if (parent::IsValidLanguageCode($redirect_file[0]) == TRUE) {
					$this->alias[$locale] = $redirect_file[0];
					$real_lang =& $redirect_file[0];
				} // end if
			} else {
				$this->alias[$locale] = $locale;
			} // end if
		} // end if
		return (string) $real_lang;
	} // end function

	/**
	* Returns an translated string
	*
	* Short version of the Translate function. If you have problems
	* (for example because of the gettext function) use the long named version
	*
	* @desc Returns an translated string
	* @param (string) $string  String that should be translated
	* @param (string) $domain  Optional. If modus is gettext and you want to translate a string that is not in the first translation file, include the name of translation file where it can be found
	* @return (string)  Translated string
	* @access public
	* @since 1.000 - 2002/10/10
	* @uses Translate()
	*/
	function &_($string = '', $domain = '') {
		return (string) $this->Translate(&$string, &$domain);
	} // end function

	/**
	* Checks if a locale is utf encoded
	*
	* @desc Checks if a locale is utf encoded
	* @param (string) $locale
	* @return (boolean)  whether locale is utf or not
	* @access public
	* @since 1.046 - 2003/02/22
	* @author unknown <wielspm@xs4all.nl>
	*/
	function isUTFencoded($locale = '') {
		return (boolean) ((in_array($locale, $this->utf_encoding)) ? TRUE : FALSE);
	} // end function

	/**
	* Returns an HTML-encoded translated string
	*
	* Same as the Translate function + HTML encoding of special characters
	*
	* @desc Returns an HTML-encoded translated string
	* @param (string) $string  String that should be translated
	* @param (string) $domain  Optional. If modus is gettext and you want to translate a string that is not in the first translation file, include the name of translation file where it can be found
	* @return (string)  Translated and encoded string
	* @access public
	* @since 1.000 - 2002/10/10
	* @uses Translate()
	*/
	function &TranslateEncode($string = '', $domain = '') {
		if ($this->isUTFencoded($this->locale) == TRUE) {
			return (string) $this->utf2html($this->Translate(&$string, &$domain));
		} else {
			return (string) htmlentities($this->Translate(&$string, &$domain));
		}
	} // end function

	/**
	* Returns an HTML-encoded translated string
	*
	* Short version of the TranslateEncode function. If you have problems
	* (for example because of the gettext function) use the long named version
	*
	* @desc Returns an HTML-encoded translated string
	* @param (string) $string  String that should be translated
	* @param (string) $domain  Optional. If modus is gettext and you want to translate a string that is not in the first translation file, include the name of translation file where it can be found
	* @return (string)  Translated and encoded string
	* @access public
	* @since 1.000 - 2002/10/10
	*/
	function &__($string = '', $domain = '') {
		return (string) $this->TranslateEncode($string, $domain);
	} // end function

	/**
	* Returns an errormessage
	*
	* If a string that should be translated was not found in the translation
	* array, a error message is displayed instead of the translation
	*
	* @desc Returns an errormessage
	* @param (string) $string  String that could not be translated
	* @return (string)  Complete errormessage
	* @access protected
	* @since 1.000 - 2002/10/10
	*/
	function &TranslateError($string = '') {
		return (string) (($this->show_errormessages === TRUE) ? 'ERROR TRANSLATING: »» ' . $string . ' ««' : $string);
	} // end function

	/**
	* html encodes a UTF encodes string (experimental)
	*
	* Used for russian for example. Therefore, if your locale is utf encoded,
	* you have to ad the locale to the utf_encoding variable in the
	* i18n_settings.ini file
	*
	* @desc html encodes a UTF encodes string (experimental)
	* @param (string) $utf_string  utf encoded string
	* @return (string)  html encoded string
	* @access public
	* @since 1.046 - 2003/02/22
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
	* Checks if a class is available
	*
	* @desc Checks if a class is available
	* @return (object) $user  User
	* @access private
	* @since 1.001 - 2002/11/30
	*/
	function checkClass($classname = '', $line = '') {
		if (strlen(trim($classname)) > 0) {
			if (!class_exists($classname)) {
				if (strlen(trim($line)) > 0) {
					$lineinfo = (string) 'at Line ' .$line;
				} // end if
				die('Class "' . get_class($this) . '": Class "' . $classname . '" not found' .$lineinfo . '!');
			} // end if
		} // end if
	} // end function
} // end class Translator
?>