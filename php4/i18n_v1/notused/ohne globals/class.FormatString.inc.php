<?php
/**
* @package i18n
*/
/**
* We need the language so we know how long date/time strings have to be displayed
*/
@include_once('class.Translator.inc.php');

/**
* Including the user class is necessary for getting the user-preferences
*/
@include_once('class.User.inc.php');

/**
* Formats strings
*
* Mostly some helper functions. For example you have a “special words”
* file in one of your language subdirectories. These words have
* descriptions or whatever assigned to them in an array. A function now
* searches for those words in a string and replaces it with
* <abbr>words</abbr> for example. Also contains a “bad words” filter.
*
* Tested with WAMP (XP-SP1/1.3.24/4.0.4/4.3.0)
* Last change: 2003-02-26
*
* @desc Formats strings
* @access public
* @author Michael Wimmer <flaimo@gmx.net>
* @copyright Michael Wimmer
* @link http://www.flaimo.com/
* @package i18n
* @version 1.053
*/
class FormatString {

	/*-------------------*/
	/* V A R I A B L E S */
	/*-------------------*/

	/**
	* Array for special words
	*
	* @desc Array for special words
	* @var array
	* @access private
	*/
	var $specialwords;

	/**
	* Array for badwordlist
	*
	* @desc Array for badwordlist
	* @var array
	* @access private
	*/
	var $badwordlist;

	/**
	* See construstor
	*
	* @desc See construstor
	* @var array
	* @access private
	* @see FormatString()
	*/
	var $specialchar;

	/**
	* Holds the language object
	*
	* @desc Holds the language object
	* @var object
	* @access private
	* @see Language
	*/
	var $lg;

	/**
	* Filename for the file containing the special words
	*
	* @desc Filename for the file containing the special words
	* @var string
	* @access private
	*/
	var $wordsfile_name = 'words.ini';

	/**
	* Filename for the file containing the bad words
	*
	* @desc Filename for the file containing the bad words
	* @var string
	* @access private
	*/
	var $badwordlist_filename = 'badwords.inc';

	/**
	* Holds the user object
	*
	* @desc Holds the user object
	* @var object
	* @access private
	* @see User
	*/
	var $user;

	/**
	* Holds the settings for this class
	*
	* @desc Holds the settings for this class
	* @var array
	* @access private
	*/
	var $string_settings;

	/*-----------------------*/
	/* C O N S T R U C T O R */
	/*-----------------------*/

	/**
	* Constructor
	*
	* @desc Constructor
	* @return (void)
	* @access private
	* @uses setWordsFileName(), setBadwordlistFilename()
	* @since 1.000 - 2002/10/10
	*/
	function FormatString() {

	} // end function


	/*-------------------*/
	/* F U N C T I O N S */
	/*-------------------*/

	/**
	* Reads the default settings from the settings file if necessary
	*
	* @desc Reads the default settings from the settings file if necessary
	* @return (void)
	* @access private
	* @uses setLanguageFilePath(), setIncExtention(), setModus(), setShowErrormessages()
	* @since 1.045 - 2003/02/21
	*/
	function readINIfile() {
		if (!isset($this->string_settings) && file_exists('i18n_settings.ini')) {
            $this->string_settings = (array) parse_ini_file('i18n_settings.ini', TRUE);
            $this->wordsfile_name =& $this->string_settings['FormatString']['words_filename'];
            $this->badwordlist_filename =& $this->string_settings['FormatString']['badwords_filename'];
    	} // end if
	} // end function

	/**
	* Returns filename for the file with the special words
	*
	* @desc Returns filename for the file with the special words
	* @return (string) $wordsfile_name
	* @access public
	* @see setWordsFileName()
	* @since 1.000 - 2002/10/10
	*/
	function &getWordsFileName() {
		$this->readINIfile();
		return (string) $this->wordsfile_name;
	} // end function

	/**
	* Returns filename for the file with the bad words
	*
	* @desc Returns filename for the file with the bad words
	* @return (string) $badwordlist_filename
	* @access public
	* @see setBadwordlistFilename()
	* @since 1.000 - 2002/10/10
	*/
	function &getBadwordlistFilename() {
		$this->readINIfile();
		return (string) $this->badwordlist_filename;
	} // end function

	/**
	* Returns an array with special characters
	*
	* @desc Returns an array with special characters
	* @return (array) $specialchar  Special characters
	* @access public
	* @since 1.000 - 2002/10/10
	*/
	function &getSpecialChar() {
		if (!isset($this->specialchar)) {
			$this->specialchar = (array) array(
												'…' => '&#8230;',
												'„' => '&#8222;',
												'”' => '&#8221;',
												'“' => '&#8220;',
												'‚' => '&#8218;',
												'’' => '&#8217;',
												'‘' => '&#8216;',
												'—' => '&#8212;',
												'–' => '&#8211;',
												'‹' => '&#8249;',
												'›' => '&#8250;',
												'‰' => '&#8240;',
												'†' => '&#8224;',
												'‡' => '&#8225;',
												'™' => '&#8482;',
												'€' => '&#8364;',
												'•' => '&#8226;',
												'µ' => '&#956;',
												'Ø' => '&#8709;',
												'~' => '&#8764;',
												'˜' => '&#8776;',
												'·' => '&#8901;',
												'¬' => '&#172;',
												' ' => '&#160;'
										  );
		}
		return (array) $this->specialchar;
	} // end function

	/**
	* Set’s the language variable if it hasn’t been set before
	*
	* @desc Set’s the language variable if it hasn’t been set before
	* @return (object) $lg Language object
	* @access private
	* @see loadUserClass()
	* @uses checkClass(), Translator
	* @since 1.000 - 2002/10/10
	*/
	function loadLanguageClass() {
		if (!isset($this->lg)) {
			$this->checkClass('Translator', __LINE__);
			$this->lg =& new Translator('','');
		} // end if
	} // end function


	/**
	* Set’s the user variable if it hasn’t been set before
	*
	* @desc Set’s the user variable if it hasn’t been set before
	* @return (object) $user  User object
	* @access private
	* @see loadLanguageClass()
	* @uses checkClass(), User
	* @since 1.000 - 2002/10/10
	*/
	function loadUserClass() {
		if (!isset($this->user)) {
			$this->checkClass('User', __LINE__);
			$this->user =& new User();
		} // end if
	} // end function

	/**
	* Start where the htmlentities functions ends and html-encodes some more
	* characters
	*
	* Must be used BEFORE htmlentities/nl2br/wordwrap
	*
	* @desc html-encodes some more characters that “htmlentities”
	* @param (string) $input
	* @return (string) $input  Transformed input-string
	* @access public
	* @uses LoadSpecialChar()
	* @since 1.000 - 2002/10/10
	*/
	function SpecialChar($input) {
		foreach ($this->getSpecialChar() as $list_name => $list_value) {
			$input = (string) str_replace($list_name,$list_value,$input);
		} // end foreach
		return (string) $input;
	} // end function

	/**
	* The ini file with the definitions for acronym|abbr|dfn are loaded into an
	* 2D array
	*
	* @desc loads file with the definitions for acronym|abbr|dfn
	* @return (void)
	* @access private
	* @see SpecialWords()
	* @uses loadLanguageClass(), Translator::getLanguageFilePath(), Language::getLang(), getWordsFileName()
	* @since 1.000 - 2002/10/10
	*/
	function loadSpecialWordsFile() {
		$this->loadLanguageClass();
		$this->specialwords = (array) @parse_ini_file($this->lg->getLanguageFilePath() . '/' . $this->lg->getRealLocale($this->lg->getLocale()) . '/' . $this->getWordsFileName(), TRUE);
	} // end function

	/**
	* Loops through the 2nd dimension of the special words array
	*
	* Adds acronym, abbr and dfn tags for the all the words defined in
	* the “words.inc.php” file.
	*
	* @desc Loops through the 2nd dimension of the special words array
	* @param (string) $text
	* @param (string) $what  1st dimension of the array
	* @return (string) $text  Transformed input-string
	* @access private
	* @see SpecialWords()
	* @since 1.000 - 2002/10/10
	*/
	function loopSpecialWords($text, $what = '') {
		foreach ($this->specialwords[$what] as $list_name => $list_value) {
			$valuearray = (array) explode(' | ',$list_value);
			if ($this->lg->isUTFencoded($this->lg->getLocale())) {
				$text = (string) preg_replace('*' . $this->lg->utf2html(quotemeta($list_name)) . '*', '<'.$what.' title="' . $this->lg->utf2html($valuearray[0]) . '" lang="' . $valuearray[1] . '" xml:lang="' . $valuearray[1] . '">' . $this->lg->utf2html($list_name) . '</'.$what.'>', $text , 1);
			} else {
				$text = (string) preg_replace('*' . htmlentities(quotemeta($list_name)) . '*', '<'.$what.' title="' . htmlentities($valuearray[0]) . '" lang="' . $valuearray[1] . '" xml:lang="' . $valuearray[1] . '">' . htmlentities($list_name) . '</'.$what.'>', $text , 1);
			} // end if
		} // end foreach
		unset($valuearray);
		return (string) $text;
	} // end function

	/**
	* Loops through the special words array
	*
	* Optional 2nd variable can define that only one of the 3 tags
	* should be encodes into the string. Can be defined seperatly
	* for each language (see loadSpecialWordsFile()). Must be used
	* AFTER htmlentities/nl2br/wordwrap
	*
	* @desc Loops through the special words array
	* @param (string) $text
	* @param (string) $what  1st dimension of the array (acronym|abbr|dfn) [optional]
	* @return (string) $text  Transformed input-string
	* @access public
	* @uses loadSpecialWordsFile(), loadUserClass(), User::getSpecialWordsStatus(), loopSpecialWords(),
	* @since 1.000 - 2002/10/10
	*/
	function SpecialWords($text, $what = '') {
		$this->loadSpecialWordsFile();
		$this->loadUserClass();
		if (isset($this->specialwords) && $this->user->getSpecialWordsStatus() === TRUE) {
			if (preg_match('(^(acronym|abbr|dfn)$)',$what)) {
				$text = (string) $this->loopSpecialWords($text, $what);
			} else {
				$arraylist = (array) array_keys($this->specialwords);
				foreach ($arraylist as $what) {
					if (preg_match('(^(acronym|abbr|dfn)$)',$what)) {
						$text = (string) $this->loopSpecialWords($text, $what);
					} // end if
				} // end foreach
			} // end if
		} // end if
	return (string) $text;
	} // end function

	/**
	* Loads file with bad words
	*
	* If a file with bad words exists for the specified language in the
	* Language class, it’s loaded into an array
	*
	* @desc Loads file with bad words
	* @return (void)
	* @access public
	* @see WordFilter()
	* @uses loadLanguageClass(), Translator::getLanguageFilePath(), Language::getLang(),
	* @since 1.000 - 2002/10/10
	*/
	function ReadBadwordListFile() {
		$this->loadLanguageClass();
		$this->readINIfile();
		if ($badwordlist_raw = (array) file($this->lg->getLanguageFilePath() . '/' . $this->lg->getRealLocale($this->lg->getLocale()) . '/' . $this->badwordlist_filename)) {
			foreach ($badwordlist_raw as $value) {
				$this->badwordlist[] = trim($value);
			} // end foreach
		} else {
			$this->badwordlist = NULL;
		} // end if
	}  // end function

	/**
	* Replaces “bad” words in a string
	*
	* For filtering “bad” words. List is in the “words.inc.php” file.
	* Can be defined seperatly for each language. Must be used BEFORE
	* htmlentities/nl2br/wordwrap
	*
	* @desc Replaces bad words in a string
	* @param (string) $text
	* @return (string) $text  Transformed input-string
	* @access public
	* @uses ReadBadwordListFile()
	* @since 1.000 - 2002/10/10
	*/
	function WordFilter($text = '') {
		if (!isset($this->badwordlist)) {
			$this->ReadBadwordListFile();
		} // end if
		if ($this->badwordlist != NULL) {
			for ($i = (int) 0,$max = (int) sizeof($this->badwordlist)-1; $i <= $max; $i++) {
				$text = (string) str_replace($this->badwordlist[$i],str_repeat('*', strlen($this->badwordlist[$i])),$text);
			} // end for
		} // end if
		return (string) $text;
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
} // end class FormatString
?>