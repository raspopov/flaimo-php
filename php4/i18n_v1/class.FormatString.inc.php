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
* We need the language so we know how long date/time strings have to be displayed
*/
@require_once 'class.Translator.inc.php';

/**
* Including the user class is necessary for getting the user-preferences
*/
@require_once 'class.I18NUser.inc.php';
/**
* Including the abstract base class
*/
@require_once 'class.I18N.inc.php';
/**
* Formats strings
*
* Mostly some helper functions. For example you have a “special words”
* file in one of your language subdirectories. These words have
* descriptions or whatever assigned to them in an array. A function now
* searches for those words in a string and replaces it with
* <abbr>words</abbr> for example. Also contains a “bad words” filter.
*
* Tested with WAMP (XP-SP1/1.3.24/4.0.12/4.3.0)
* Last change: 2003-06-14
*
* @desc Formats strings
* @access public
* @author Michael Wimmer <flaimo 'at' gmx 'dot' net>
* @copyright Michael Wimmer
* @link http://www.flaimo.com/
* @package i18n
* @category FLP
* @version 1.061
*/
class FormatString extends I18N {

	/*-------------------*/
	/* V A R I A B L E S */
	/*-------------------*/

	/**#@+
	* @access private
	*/
	/**
	* Array for special words
	*
	* @desc Array for special words
	* @var array
	*/
	var $specialwords;

	/**
	* Array for badwordlist
	*
	* @desc Array for badwordlist
	* @var array
	*/
	var $badwordlist;

	/**
	* See construstor
	*
	* @desc See construstor
	* @var array
	* @see FormatString()
	*/
	var $specialchar;

	/**
	* Holds the language object
	*
	* @desc Holds the language object
	* @var object
	* @see Language
	*/
	var $lg;

	/**
	* Filename for the file containing the special words
	*
	* @desc Filename for the file containing the special words
	* @var string
	*/
	var $wordsfile_name = 'words.ini';

	/**
	* character which replaces bad words
	*
	* @desc character which replaces bad words
	* @var string
	*/
	var $replace_char = '*';

	/**
	* Holds the user object
	*
	* @desc Holds the user object
	* @var object
	* @see User
	*/
	var $user;

	/**
	* Holds the settings for this class
	*
	* @desc Holds the settings for this class
	* @var array
	*/
	var $string_settings;

	/**
	* Holds the badwords string fom the l10n file
	*
	* @desc olds the badwords string fom the l10n file
	* @var string
	*/
	var $badwords = '';

	/**
	* size of the shared memory block for each words.ini file
	*
	* if you have a big words.ini file you might want to change this to a
	* bigger value. if it's to small PHP should create an error
	*
	* @desc size of the shared memory block for each words.ini file
	* @var int
	*/
	var $wordfile_shm_size = 2000;
	/**#@-*/

	/*-----------------------*/
	/* C O N S T R U C T O R */
	/*-----------------------*/

	/**#@+
	* @return void
	* @access private
	*/
	/**
	* Constructor
	*
	* @desc Constructor
	* @uses I18N::I18N()
	*/
	function FormatString() {
		parent::I18N();
	} // end function


	/*-------------------*/
	/* F U N C T I O N S */
	/*-------------------*/

	/**
	* Reads the default settings for numbers and dates from the settings file if necessary
	*
	* @desc Reads the default settings for numbers and dates from the settings file if necessary
	* @since 1.055 - 200-04-22
	*/
	function readDefaultL10NSettings() {
        $this->loadLanguageClass();
        parent::readL10NINIsettings($this->lg->getLocale());
        if (isset($GLOBALS[$this->l10n_globalname])) {
            $this->badwords 		=& $GLOBALS[$this->l10n_globalname]['String']['badwords'];
            $this->wordsfile_name 	=& $GLOBALS[$this->l10n_globalname]['String']['words_filename'];
    	} // end if
	} // end function

	/**
	* Set’s the language variable if it hasn’t been set before
	*
	* @desc Set’s the language variable if it hasn’t been set before
	* @see loadUserClass()
	* @uses Translator
	*/
	function loadLanguageClass() {
		if (!isset($this->lg)) {
			$this->lg =& new Translator('','');
		} // end if
	} // end function

	/**
	* Set’s the user variable if it hasn’t been set before
	*
	* @desc Set’s the user variable if it hasn’t been set before
	* @see loadLanguageClass()
	* @uses I18NUser
	*/
	function loadUserClass() {
		if (!isset($this->user)) {
			$this->user =& new I18NUser();
		} // end if
	} // end function
	/**#@-*/

	/**#@+
	* @access public
	*/
	/**
	* Returns filename for the file with the special words
	*
	* @desc Returns filename for the file with the special words
	* @return string $wordsfile_name
	* @see setWordsFileName()
	*/
	function &getWordsFileName() {
		$this->readDefaultL10NSettings();
		return (string) $this->wordsfile_name;
	} // end function

	/**
	* Returns an array with special characters
	*
	* @desc Returns an array with special characters
	* @return (array) $specialchar  Special characters
	* @static
	*/
	function &getSpecialChar() {
		if (!isset($this->specialchar)) {
			$this->specialchar = (array) array(
			'¡'=>'&#161;','¢'=>'&#162;','£'=>'&#163;','¤'=>'&#164;','¥'=>'&#165;','¦'=>'&#166;',
			'§'=>'&#167;','¨'=>'&#168;','©'=>'&#169;','ª'=>'&#170;','«'=>'&#171;','¬'=>'&#172;',
			'­'=>'&#173;','®'=>'&#174;','°'=>'&#176;','±'=>'&#177;','²'=>'&#178;','³'=>'&#179;',
			'´'=>'&#180;','µ'=>'&#181;','¶'=>'&#182;','·'=>'&#183;','¸'=>'&#184;','¹'=>'&#185;',
			'º'=>'&#186;','»'=>'&#187;','¼'=>'&#188;','½'=>'&#189;','¾'=>'&#190;','¿'=>'&#191;',
			'À'=>'&#192;','Á'=>'&#193;','Â'=>'&#194;','Ã'=>'&#195;','Ä'=>'&#196;','Å'=>'&#197;',
			'Æ'=>'&#198;','Ç'=>'&#199;','È'=>'&#200;','É'=>'&#201;','Ê'=>'&#202;','Ë'=>'&#203;',
			'Ì'=>'&#204;','Í'=>'&#205;','Î'=>'&#206;','Ï'=>'&#207;','Ð'=>'&#208;','Ñ'=>'&#209;',
			'Ò'=>'&#210;','Ó'=>'&#211;','Ô'=>'&#212;','Õ'=>'&#213;','Ö'=>'&#214;','×'=>'&#215;',
			'Ø'=>'&#216;','Ù'=>'&#217;','Ú'=>'&#218;','Û'=>'&#219;','Ü'=>'&#220;','Ý'=>'&#221;',
			'Þ'=>'&#222;','ß'=>'&#223;','à'=>'&#224;','á'=>'&#225;','â'=>'&#226;','ã'=>'&#227;',
			'ä'=>'&#228;','å'=>'&#229;','æ'=>'&#230;','ç'=>'&#231;','è'=>'&#232;','é'=>'&#233;',
			'ê'=>'&#234;','ë'=>'&#235;','ì'=>'&#236;','í'=>'&#237;','î'=>'&#238;','ï'=>'&#239;',
			'ð'=>'&#240;','ñ'=>'&#241;','ò'=>'&#242;','ó'=>'&#243;','ô'=>'&#244;','õ'=>'&#245;',
			'ö'=>'&#246;','÷'=>'&#247;','ø'=>'&#248;','ù'=>'&#249;','ú'=>'&#250;','û'=>'&#251;',
			'ü'=>'&#252;','ý'=>'&#253;','þ'=>'&#254;','ÿ'=>'&#255;','~'=>'&#8764;','˜'=>'&#8776;',
			'•'=>'&#8226;','…'=>'&#8230;','™'=>'&#8482;','€'=>'&#8364;','–'=>'&#8211;',
			'—'=>'&#8212;','‘'=>'&#8216;','’'=>'&#8217;','‚'=>'&#8218;','“'=>'&#8220;',
			'”'=>'&#8221;','„'=>'&#8222;','†'=>'&#8224;','‡'=>'&#8225;','‰'=>'&#8240;',
			'‹'=>'&#8249;','›'=>'&#8250;');
		} // end if
		return (array) $this->specialchar;
	} // end function

	/**
	* Changes the language of the translator object
	*
	* @desc Changes the language of the translator object
	* @param string $locale  iso-code
	* @return void
	* @uses loadLanguageClass(), Translator::changeLocale()
	* @since 1.055 - 2003-04-20
	*/
	function changeLocale($locale) {
		$this->loadLanguageClass();
		$this->lg->changeLocale($locale);
		$this->readL10NINIsettings();
		$this->loadSpecialWordsFile();
		$this->readBadwordListFile();
	} // end function

	/**
	* Start where the htmlentities functions ends and html-encodes some more
	* characters
	*
	* Must be used BEFORE htmlentities/nl2br/wordwrap
	*
	* @desc html-encodes some more characters that “htmlentities”
	* @param string $input
	* @return string $input  Transformed input-string
	* @uses getSpecialChar()
	*/
	function specialChar($input) {
		return strtr($input, $this->getSpecialChar());
	} // end function

	/**
	* Loops through the special words array
	*
	* Optional 2nd variable can define that only one of the 3 tags
	* should be encodes into the string. Can be defined seperatly
	* for each language (see {@link loadSpecialWordsFile()} ). Must be used
	* AFTER htmlentities/nl2br/wordwrap
	*
	* @desc Loops through the special words array
	* @param string $text
	* @param string $what  1st dimension of the array (acronym|abbr|dfn) [optional]
	* @return string $text  Transformed input-string
	* @uses loadSpecialWordsFile()
	* @uses loadUserClass()
	* @uses I18NUser::getSpecialWordsStatus()
	* @uses loopSpecialWords(),
	*/
	function filterSpecialWords($text, $what = '') {
		$this->loadSpecialWordsFile();
		$this->loadUserClass();

		if (isset($this->specialwords) && $this->user->getSpecialWordsStatus() === TRUE) {
			if (strlen(trim($what)) > 0) {
				$arraylist[] = $what;
			} else {
				$arraylist = (array) array_keys($this->specialwords);
			} // end if
			foreach ($arraylist as $what) {
				if (preg_match('(^(acronym|abbr|dfn)$)', $what)) {
					$text = (string) $this->loopSpecialWords($text, &$what);
				} // end if
			} // end foreach
		} // end if
	return (string) $text;
	} // end function

	/**
	* Replaces “bad” words in a string
	*
	* For filtering “bad” words. List is in the “words.inc.php” file.
	* Can be defined seperatly for each language. Must be used BEFORE
	* htmlentities/nl2br/wordwrap
	*
	* @desc Replaces bad words in a string
	* @param string $text
	* @param int $exact whether only hole words should be replaced or parts of a string
	* @return string $text  Transformed input-string
	* @uses readBadwordListFile()
	*/
	function wordFilter($text = '', $exact = 0) {
		if (!isset($this->badwordlist)) {
			$this->readBadwordListFile();
		} // end if

		if ($exact == 0) {
			return (string) strtr($text, $this->badwordlist);
		} else {
			foreach ($this->badwordlist as $word => $replace) {
				$text = preg_replace('/(^|\b)' . $word . '(\b|!|\?|\.|,|$)/i', $replace, $text);
			} // end foreach
			return (string) $text;
		} // end if
	} // end function
	/**#@-*/

	/**
	* The ini file with the definitions for acronym|abbr|dfn are loaded into an
	* 2D array
	*
	* @desc loads file with the definitions for acronym|abbr|dfn
	* @return boolean if data is set
	* @access private
	* @see SpecialWords()
	* @uses loadLanguageClass()
	* @uses Translator::getLanguageFilePath()
	* @uses Language::getLang()
	* @uses getWordsFileName()
	*/
	function loadSpecialWordsFile() {
		$this->loadLanguageClass();

		if (isset($this->specialwords)) {
			return (boolean) TRUE;
		} // end if

		if (isset($GLOBALS['specialwords' . $this->lg->getRealLocale($this->lg->getLocale())])) {
			$this->specialwords = (array) $GLOBALS['specialwords' . $this->lg->getRealLocale($this->lg->getLocale())];
			return (boolean) TRUE;
		} // end if

		if (!extension_loaded('shmop')) {
			@dl('shmop');
		} // end if

		/* if NOT able to use shared memory */
		if ($this->use_shared_mem === FALSE || !extension_loaded('shmop')) {
			$this->specialwords = (array) parse_ini_file($this->lg->getLanguageFilePath() . '/' . $this->lg->getRealLocale($this->lg->getLocale()) . '/' . $this->getWordsFileName(), TRUE);
			$GLOBALS['specialwords' . $this->lg->getRealLocale($this->lg->getLocale())] = $this->specialwords;
			return (boolean) TRUE;
		} // end if

		/* if able to use shared memory */
		$shm3_id = $this->memSegSpot(substr( $this->lg->getRealLocale($this->lg->getLocale()), 0, 3) . 'W');
		$shm3 = shmop_open($shm3_id, 'c', 0644, $this->wordfile_shm_size);
		$sm_content3 = trim(shmop_read($shm3, 0, $this->wordfile_shm_size));
		if(isset($sm_content3) && strlen($sm_content3) > 0) {
			$this->specialwords = (array) unserialize($sm_content3);
		} else { // if nothing is there, write file to shared mem
			$this->specialwords = (array) parse_ini_file($this->lg->getLanguageFilePath() . '/' . $this->lg->getRealLocale($this->lg->getLocale()) . '/' . $this->getWordsFileName(), TRUE);
			$inifile = (string) serialize($this->specialwords);
			$shm3_bytes_written = shmop_write($shm3, $inifile, 0);
		} // end if
		if ($this->flush_sm === TRUE) {
			shmop_delete($shm3);
		} // end if
		shmop_close($shm3);
		$GLOBALS['specialwords' . $this->lg->getRealLocale($this->lg->getLocale())] = $this->specialwords;
		return (boolean) TRUE;
	} // end function

	/**
	* Loops through the 2nd dimension of the special words array
	*
	* Adds acronym, abbr and dfn tags for the all the words defined in
	* the “words.inc.php” file. has to be used after the text has been transformed
	* with htmlentities und such
	*
	* @desc Loops through the 2nd dimension of the special words array
	* @param string $text
	* @param string $what  1st dimension of the array
	* @return string $text  Transformed input-string
	* @access private
	* @see filterSpecialWords()
	*/
	function loopSpecialWords($text, &$what) {
		foreach ($this->specialwords[$what] as $list_name => $list_value) {
			$valuearray = (array) explode(' | ',$list_value);
			if ($this->lg->isUTFencoded($this->lg->getLocale())) {
				$list_name = $this->lg->utf2html(quotemeta($list_name));
				$valuearray[0] = $this->lg->utf2html($valuearray[0]);
			} else {
				$list_name = $this->specialChar(htmlentities(quotemeta($list_name)));
				$valuearray[0] = $this->specialChar(htmlentities($valuearray[0]));
			} // end if
			$text = (string) preg_replace('*' . $list_name . '*', '<'.$what.' title="' . $valuearray[0] . '" xml:lang="' . $valuearray[1] . '">' . $list_name . '</'.$what.'>', $text , 1);
		} // end foreach
		unset($valuearray);
		return (string) $text;
	} // end function

	/**
	* Loads file with bad words
	*
	* If a file with bad words exists for the specified language in the
	* Language class, it’s loaded into an array
	*
	* @desc Loads file with bad words
	* @return void
	* @access private
	* @see wordFilter()
	* @uses loadLanguageClass()
	* @uses readDefaultL10NSettings()
	*/
	function readBadwordListFile() {
		$this->loadLanguageClass();
		$this->readDefaultL10NSettings();
		$templist = (array) explode(',',$this->badwords);
		$this->badwordlist = (array) array();
		$templist = array_map('trim', $templist);
		foreach ($templist as $word) {
			$this->badwordlist[$word] = str_repeat($this->replace_char, strlen($word));
		} // end foreach
		unset($templist);
	}  // end function
} // end class FormatString
?>