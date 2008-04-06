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
* Tested with Apache 1.3.24 and PHP 4.2.3
* Last change: 2003-02-13
*
* @desc Formats strings
* @access public
* @author Michael Wimmer <flaimo@gmx.net>
* @copyright Michael Wimmer
* @link http://www.flaimo.com/  flaimo.com
* @package i18n
* @version 1.043
*/
class FormatString {

	/*-------------------*/
	/* V A R I A B L E S */
	/*-------------------*/

	/**#@+
	* @access private
	* @var array
	*/
	/**
	* Array for special words
	*
	* @desc Array for special words
	*/
	var $specialwords;

	/**
	* Array for badwordlist
	*
	* @desc Array for badwordlist
	*/
	var $badwordlist;

	/**
	* See construstor
	*
	* @desc See construstor
	* @see FormatString()
	*/
	var $specialchar;

	/**
	* Holds the settings for this class
	*
	* @desc Holds the settings for this class
	*/
	var $string_settings;
	/**#@-*/

	/**#@+
	* @access private
	* @var string
	*/
	/**
	* CSS-Class and variable-name for highlighted text
	*
	* @desc CSS-Class and variable-name for highlighted text
	*/
	var $css_highlight;

	/**
	* For adding a session string to links
	*
	* @desc For adding a session string to links
	*/
	var $sessionstring;

	/**
	* Holds the (optional) searchterm
	*
	* @desc Holds the (optional) searchterm
	*/
	var $highlightword;

	/**
	* Filename for the file containing the special words
	*
	* @desc Filename for the file containing the special words
	*/
	var $wordsfile_name;

	/**
	* Filename for the file containing the bad words
	*
	* @desc Filename for the file containing the bad words
	*/
	var $badwordlist_filename;
	/**#@-*/

	/**#@+
	* @access private
	* @var object
	*/
	/**
	* Holds the language object
	*
	* @desc Holds the language object
	* @see Language
	*/
	var $lg;

	/**
	* Holds the user object
	*
	* @desc Holds the user object
	* @see User
	*/
	var $user;
	/**#@-*/

	/*-----------------------*/
	/* C O N S T R U C T O R */
	/*-----------------------*/

	/**
	* Constructor
	*
	* @desc Constructor
	* @param (string) $input_highlight  String that should be highlighted with html tags
	* @return (void)
	* @access private
	* @uses setWordsFileName(), setBadwordlistFilename(), setCSSHighlight(), setWordsFileName(), setBadwordlistFilename(), setCSSHighlight(), setWordsFileName(), setBadwordlistFilename(), setCSSHighlight(), setHighlightWord()
	* @since 1.000 - 2002-10-10
	*/
	function FormatString($input_highlight = '') {
		$this->setWordsFileName('words.ini');
		$this->setBadwordlistFilename('badwords.inc');
		$this->setCSSHighlight('nshighlight');
        if (file_exists('flp_settings.ini')) {
            $this->string_settings = (array) parse_ini_file('flp_settings.ini', TRUE);
            $this->setWordsFileName($this->string_settings['FormatString']['words_filename']);
            $this->setBadwordlistFilename($this->string_settings['FormatString']['badwords_filename']);
            $this->setCSSHighlight($this->string_settings['FormatString']['css_highlight_classname']);
        } else {
            $this->setWordsFileName('words.ini');
            $this->setBadwordlistFilename('badwords.inc');
            $this->setCSSHighlight('nshighlight');
        } // end if
		$this->setHighlightWord((((!isset($input_highlight)) || (strlen(trim($input_highlight)) == 0)) ? '' : $input_highlight));
	} // end function


	/*-------------------*/
	/* F U N C T I O N S */
	/*-------------------*/

	/**
	* For adding a “&PHPSESSION=…” to URLs
	*
	* @desc For adding a “&PHPSESSION=…” to URLs
	* @param (string) $sessionname
	* @param (string) $sessionid
	* @return (void)
	* @access public
	* @since 1.000 - 2002-10-10
	*/
	function SetSessionURL($sessionname,$sessionid) {
		$temp_string 			= (string) ((strlen(trim($sessionname)) == 0 || strlen(trim($sessionid)) == 0) ? '' : '&#38;' . $sessionname . '=' . $sessionid);
		$this->sessionstring 	= $temp_string;
	} // end function

//------------------------------------------------

	/**
	* Returns string that should be highlighted
	*
	* @desc Returns string that should be highlighted
	* @return (string) $highlightword
	* @access public
	* @see setHighlightWord()
	* @since 1.000 - 2002-10-10
	*/
	function getHighlightWord() {
		return (string) $this->highlightword;
	} // end function

	/**
	* Returns the “more” string (translated to the current language)
	*
	* @desc Returns the “more” string (translated to the current language)
	* @return (string) $more
	* @access public
	* @since 1.000 - 2002-10-10
	*/
	function getMore() {
		return (string) $this->more;
	} // end function

	/**
	* Returns class name for highlighting words with a <span> tag
	*
	* @desc Returns class name for highlighting words with a <span> tag
	* @return (string) $css_highlight
	* @access public
	* @see setCSSHighlight()
	* @since 1.000 - 2002-10-10
	*/
	function getCSSHighlight() {
		return (string) $this->css_highlight;
	} // end function

	/**
	* Returns filename for the file with the special words
	*
	* @desc Returns filename for the file with the special words
	* @return (string) $wordsfile_name
	* @access public
	* @see setWordsFileName()
	* @since 1.000 - 2002-10-10
	*/
	function getWordsFileName() {
		return (string) $this->wordsfile_name;
	} // end function

	/**
	* Returns filename for the file with the bad words
	*
	* @desc Returns filename for the file with the bad words
	* @return (string) $badwordlist_filename
	* @access public
	* @see setBadwordlistFilename()
	* @since 1.000 - 2002-10-10
	*/
	function getBadwordlistFilename() {
		return (string) $this->badwordlist_filename;
	} // end function

	/**
	* Returns variable name for the specialwords-status
	*
	* @desc Returns variable name for the specialwords-status
	* @return (string) $specialwordsstatusname
	* @access public
	* @see setSpecialWordsStatusName()
	* @since 1.000 - 2002-10-10
	*/
	function getSpecialWordsStatusName() {
		return (string) $this->specialwordsstatusname;
	} // end function

	/**
	* Returns the status for displaying special words
	*
	* @desc Returns the status for displaying special words
	* @return (boolean) $specialWordsStatus  ON/OFF
	* @access public
	* @since 1.000 - 2002-10-10
	*/
	function getSpecialWordsStatus() {
		return (boolean) $this->specialWordsStatus;
	} // end function

	/**
	* Returns an array with special characters
	*
	* @desc Returns an array with special characters
	* @return (array) $specialchar  Special characters
	* @access public
	* @since 1.000 - 2002-10-10
	*/
	function getSpecialChar() {
		return (array) $this->specialchar;
	} // end function

	/**
	* Sets the css-class name for the highlight <span> tag
	*
	* @desc Sets the css-class name for the highlight <span> tag
	* @param (string) $string  Class-name
	* @return (void)
	* @access private
	* @see getCSSHighlight()
	* @since 1.000 - 2002-10-10
	*/
	function setCSSHighlight($string) {
		$this->css_highlight = (string) $string;
	} // end function

	/**
	* Sets the string to be highlighted in strings
	*
	* @desc Sets the string to be highlighted in strings
	* @param (string) $string
	* @return (void)
	* @access private
	* @see getHighlightWord()
	* @since 1.000 - 2002-10-10
	*/
	function setHighlightWord($string) {
		$this->highlightword = (string) $string;
	} // end function

	/**
	* Sets the filename for the file with the special words
	*
	* @desc Sets the filename for the file with the special words
	* @param (string) $string
	* @return (void)
	* @access private
	* @see getWordsFileName()
	* @since 1.000 - 2002-10-10
	*/
	function setWordsFileName($string) {
		$this->wordsfile_name = (string) $string;
	} // end function

	/**
	* Sets the filename for the file with the list of bad words
	*
	* @desc Sets the filename for the file with the list of bad words
	* @param (string) $string
	* @return (void)
	* @access private
	* @see getWordsFileName()
	* @since 1.000 - 2002-10-10
	*/
	function setBadwordlistFilename($string) {
		$this->badwordlist_filename = (string) $string;
	} // end function

	/**
	* Sets the variable name for the specialwords-status
	*
	* @desc Sets the variable name for the specialwords-status
	* @param (string) $string
	* @return (void)
	* @access private
	* @see getSpecialWordsStatusName()
	* @since 1.000 - 2002-10-10
	*/
	function setSpecialWordsStatusName($string) {
		$this->specialwordsstatusname = (string) $string;
	} // end function

//------------------------------------------------

	/**
	* Doesn’t work well. Don’t use it.
	*
	* @desc Doesn’t work well. Don’t use it.
	* @param (string) $attribute  Attribute
	* @param (string) $value  Value
	* @param (string) $sign  ?|&
	* @return (string) $urlstring  GET variables
	* @access public
	* @deprec  1.000 - 2002-10-10
	* @since 1.000 - 2002/10/09
	*/
	function SetURLencoder($attribute, $value, $sign = '') {
		if (strlen(trim($attribute)) == 0 || strlen(trim($value)) == 0) {
			$urlstring = (string) '';
		} else {
			$sign 		= (string) ((!isset($sign) || $sign='' || $sign == ' ' || $sign == '?' || $sign == 1) ? '?' : '&#38;');
			$urlstring 	= (string) $sign . $attribute . '=' . $value;
		} // end if
		return (string) $urlstring;
	} // end function

	/**
	* Set’s the language variable if it hasn’t been set before
	*
	* @desc Set’s the language variable if it hasn’t been set before
	* @return (object) $lg Language object
	* @access private
	* @see loadUserClass()
	* @uses checkClass(), Translator
	* @since 1.000 - 2002-10-10
	*/
	function loadLanguageClass() {
		if (!isset($this->lg)) {
			$this->checkClass('Translator', __LINE__);
			$this->lg = (object) new Translator('','lang_classFormatDate.inc');
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
	* @since 1.000 - 2002-10-10
	*/
	function loadUserClass() {
		if (!isset($this->user)) {
			$this->checkClass('User', __LINE__);
			$this->user = (object) new User();
		} // end if
	} // end function

//------------------------------------------------

	/**
	* Array is loaded if the variable hasn’t been set before
	*
	* Must be used BEFORE htmlentities/nl2br/wordwrap
	*
	* @desc Array is loaded if the variable hasn’t been set before
	* @return (void)
	* @access private
	* @see SpecialChar()
	* @since 1.000 - 2002-10-10
	*/
	function LoadSpecialChar() {
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
	* @since 1.000 - 2002-10-10
	*/
	function SpecialChar($input) {
		if (!isset($this->specialchar)) {
			$this->LoadSpecialChar();
		} // end if
		foreach ($this->specialchar as $list_name => $list_value) {
			$input = (string) str_replace($list_name,$list_value,$input);
		} // end foreach
		return (string) $input;
	} // end function

//------------------------------------------------

	/**
	* For alternating rowcolors
	*
	* For alternating rowcolors. Use it as
	* “<tag' . $object->AltRowCol() . '>” in loops with a
	* counter $i
	*
	* @desc For alternating rowcolors
	* @staticvar (int) $i counter
	* @return (string)  CSS-class name
	* @access public
	* @since 1.000 - 2002-10-10
	*/
	function AltRowCol() {
		static $i = 0;
		return (string) ' class="' . (($i++ % 2) ? 'darkrow"' : 'lightrow"');
	} // end function

//------------------------------------------------

	/**
	* The ini file with the definitions for acronym|abbr|dfn are loaded into an
	* 2D array
	*
	* @desc loads file with the definitions for acronym|abbr|dfn
	* @return (void)
	* @access private
	* @see SpecialWords()
	* @uses loadLanguageClass(), Translator::getLanguageFilePath(), Language::getLang(), getWordsFileName()
	* @since 1.000 - 2002-10-10
	*/
	function loadSpecialWordsFile() {
		$this->loadLanguageClass();
		if (!isset($this->specialwords) && file_exists($this->lg->getLanguageFilePath() . '/' . $this->lg->getLang() . '/' . $this->getWordsFileName())) {
			$this->specialwords = (array) parse_ini_file($this->lg->getLanguageFilePath() . '/' . $this->lg->getLang() . '/' . $this->getWordsFileName(), TRUE);
		} // end if
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
	* @since 1.000 - 2002-10-10
	*/
	function loopSpecialWords($text, $what = '') {
		foreach ($this->specialwords[$what] as $list_name => $list_value) {
			$valuearray = (array) explode(' | ',$list_value);
			$text 		= (string) preg_replace('*' . htmlentities(quotemeta($list_name)) . '*', '<'.$what.' title="' . htmlentities($valuearray[0]) . '" lang="' . $valuearray[1] . '" xml:lang="' . $valuearray[1] . '">' . htmlentities($list_name) . '</'.$what.'>', $text , 1);
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
	* @since 1.000 - 2002-10-10
	*/
	function SpecialWords($text, $what = '') {
		$this->loadSpecialWordsFile();
		$this->loadUserClass();
		if ($this->user->getSpecialWordsStatus() == TRUE && isset($this->specialwords)) {
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

//------------------------------------------------

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
	* @since 1.000 - 2002-10-10
	*/
	function ReadBadwordListFile() {
		$this->loadLanguageClass();
		if ($badwordlist_raw = (array) file($this->lg->getLanguageFilePath() . '/' . $this->lg->getLang() . '/' . $this->badwordlist_filename)) {
			foreach ($badwordlist_raw as $value) {
				$this->badwordlist[] = trim($value);
			} // end foreach
		} else {
			$this->badwordlist = null;
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
	* @since 1.000 - 2002-10-10
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


//------------------------------------------------

	/**
	* Highlights the (optional) searchterm in a string
	*
	* Must be used AFTER htmlentities/nl2br/wordwrap
	*
	* @desc Highlights the (optional) searchterm in a string
	* @param (string) $text
	* @return (string) $text  Transformed input-string
	* @access public
	* @see HighlightError(), setHighlightWord(), getHighlightWord
	* @since 1.000 - 2002-10-10
	*/
	function Highlight($text = '') {
		if (strlen(trim($this->highlightword)) > 0) {
			$text = (string) str_replace(htmlentities($this->highlightword),'<span class="' . $this->css_highlight . '">' . htmlentities($this->highlightword) . '</span>',$text);
		} // end if
		return (string) $text;
	} // end function

	/**
	* Highlights errors
	*
	* @desc
	* @param (string) $string
	* @return (string)  Errormessage
	* @access private
	* @uses Highlight(), setHighlightWord()
	* @see getHighlightWord()
	* @since 1.000 - 2002-10-10
	*/
	function HighlightError($string) {
		$this->setHighlightWord($string);
		return (string) $this->Highlight('<small>Error: <var>[' . $string . ']</var></small>');
	} // end function

//------------------------------------------------

	/**
	* Makes Links out of certain words in a string
	*
	* Not one of my function. Somehow crashes the php engine sometimes.
	* Don’t use it! Must be used AFTER htmlentities/nl2br/wordwrap
	*
	* @desc Makes Links out of certain words in a string
	* @param (string) $string
	* @return (string) $string  Transformed input-string
	* @access public
	* @deprec  1.000 - 2002-10-10
	* @since 1.000 - 2002/10/09
	*/
	function HighlightURLs($string) {
		for($n = (int) 0,$strlength = (int) strlen($string); $n < $strlength; $n++) {
			if (strtolower($string[$n]) == 'h') {
				if (!strcmp('http://', strtolower($string[$n]) . strtolower($string[$n+1]) . strtolower($string[$n+2]) . strtolower($string[$n+3]) . $string[$n+4] . $string[$n+5] . $string[$n+6])) {
					$startpos 	= (int) $n;

					while ($n < strlen($string) && eregi("[a-z0-9\.\:\?\/\~\_\&\=\%\+\'\"-]", $string[$n])) {
						$n++;
					} // end while
					if (!eregi("[a-z0-9]", $string[$n-1])) {
						$n--;
					} // end if

					$link 		 = (string) substr($string, $startpos, ($n-$startpos+1));
					$link 		 = (string) $link;
					$string_tmp  = (string) $string;
					$string 	 = (string) substr($string_tmp, 0, $startpos);
					$string 	.= (string) '<a href="' . $link . '" taget="_blank">' . $link . '</a>';
					$string 	.= (string) substr($string_tmp, $n+1, strlen($string_tmp));
					$n += (int) 15;
				} // end while
			} // end if
		} // end for
		return $string;
	} // end function

//------------------------------------------------

	/**
	* Check if a string is a mail address or a link
	*
	* Checks if there’s a “@” in the string. If so, then the string
	* is encodes as a mail address (mailto:) and “encypted” to
	* protect the address from spambots. Else it’s formated as a
	* normal link. Must be used AFTER htmlentities/nl2br/wordwrap
	*
	* @desc Check if a string is a mail address or a link
	* @param (string) $string
	* @return (string) $string  Transformed input-string (normal link or mailto:)
	* @access public
	* @uses EncodeMailAddress()
	* @see DecodeMailAddress()
	* @since 1.000 - 2002-10-10
	*/
	function CheckMailHomepage($string) {
		if (strstr($string,'@')) {
			return (string) $this->EncodeMailAddress('mailto:' .  $string);
		}
		elseif (strstr($string,'http') || strstr($string,'ftp')) {
			return (string) $string;
		} else {
			return (string) '';
		} // end if
	} // end function

	/**
	* Does some string formating to prevent spambots from grabbing the address.
	*
	* @desc Does some string formating to prevent spambots from grabbing the address
	* @param (string) $string
	* @return (string)  Transformed input-string ('sendmail.php?mail=' $address)
	* @access public
	* @see CheckMailHomepage(), DecodeMailAddress()
	* @since 1.000 - 2002-10-10
	*/
	function EncodeMailAddress($string) {
		if (strstr($string,'@')) {
			$string = (string) str_replace('@','@at@',$string);
			$string = (string) str_replace('.','@dot@',$string);
			$string = (string) strrev($string);
			$string = (string) urlencode($string);
			return (string) 'sendmail.php?mail=' . $string;
		} // end if
	} // end function

	/**
	* Reverses the function that does some string formating to prevent spambots
	* from grabbing the address.
	*
	* @desc Reverses the function that does some string formating to prevent spambots
	* @param (string) $string
	* @return (string) string  Transformed back to original string
	* @access public
	* @see CheckMailHomepage(), EncodeMailAddress()
	* @since 1.000 - 2002-10-10
	*/
	function DecodeMailAddress($string) {
		if (strstr($string,'@')) {
			$string = (string) urldecode($string);
			$string = (string) strrev($string);
			$string = (string) str_replace('@at@','@',$string);
			$string = (string) str_replace('@dot@','.',$string);
			return (string) $string;
	  } // end if
	} // end function


	/**
	* Formats phone numbers according to the country given. Only supports
	* de, at, ch and us at the moment. feel free to mail me formating rules for
	* other countries
	*
	* @desc Formats phone numbers according to the country given
	* @param (string) $number The telephone number
	* @param (string) $area The area code (optional)
	* @param (string) $area The extention (otional)
	* @param (boolean) $int_area Should the international code be displayed or not
	* @param (string) $country For which country should the number be formated. Normally depends on where the phone number is located and not the display language of the homepage
	* @return (string) The formated phone number
	* @access public
	* @since 1.010 - 2002/12/30
	*/
	function formatTelNumber($number = FALSE, $area = FALSE, $extention = FALSE, $int_area = FALSE, $country = 'us') {
		if ($number != FALSE) {
			$pos 				= (int) 0;
			$int_area_codes 	= (array) array(
												'de' => '49',
												'at' => '43',
												'ch' => '41',
												'us' =>  '1',
												'au' => '61',
												'fr' => '33',
												'uk' => '44',
												'it' => '39',
												'ca' =>  '1',
												'pl' => '48',
												'es' => '34'
											   );
			$output_int_area 	= $output_area = $output_number = $output_extention = (string) '';
			$number 			= (string) trim($number);
			$number_length 		= (int) strlen($number);

			for ($i = 0; $i < $number_length; $i++) {
				$numbers[] = (int) substr($number, $i, 1);
			} // end for
			if ($area != FALSE) {
				$area 		 = (string) trim($area);
				$area_length = (int) strlen($area);
				for ($i = 0; $i < $area_length; $i++) {
					$areacode[] = (int) substr($area, $i, 1);
				} // end for
			} // end if
			if ($extention != FALSE) {
				$extention 	= (string) trim($extention);
				$ext_length = (int) strlen($extention);
				for ($i = 0; $i < $ext_length; $i++) {
					$ext[] = (int) substr($extention, $i, 1);
				} // end for
			} // end if
			if ($int_area == TRUE && array_key_exists($country, $int_area_codes)) {
				$output_int_area = (string) '+' . $int_area_codes[$country] . ' ';
			} // end if

			switch ($country) {
				case 'de':
					if ($area != FALSE) {
						if ($int_area == TRUE && $areacode[0] == 0) {
							array_shift($areacode);
							$area_length = (int) count($areacode);
						} // end if
						for ($i = $area_length-1; $i >= 0; $i--) {
							if ($pos == 2) {
								$output_area = (string) ' ' . $output_area;
								$pos = (int) 0;
							} // end if
							$output_area = (string) $areacode[$i] . $output_area;
							$pos++;
						} // end for
						$output_area = (string) '(' . $output_area . ') ';
					} else {
						$output_area = (string) '';
					} // end if

					$pos = (int) 0;
					for ($i = $number_length-1; $i >= 0; $i--) {
						if ($pos == 2) {
							$output_number = (string) ' ' . $output_number;
							$pos = (int) 0;
						} // end if
						$output_number = (string) $numbers[$i] . $output_number;
						$pos++;
					} // end for

					$pos = (int) 0;
					if ($extention != FALSE) {
						for ($i = $ext_length-1; $i >= 0; $i--) {
							if ($ext_length % 2 == 0 && $ext_length > 3 && $pos == 2) {
								$output_extention = (string) ' ' . $output_extention;
								$pos = (int) 0;
							} // end if
							$output_extention = (string) $ext[$i] . $output_extention;
							$pos++;
						} // end for
						$output_extention = (string) '-' . $output_extention;
					} // end if
					break;
				case 'at':
				case 'ch':
					if ($area != FALSE) {
						if ($int_area == TRUE && $areacode[0] == 0) {
							array_shift($areacode);
							$area_length = (int) count($areacode);
						} // end if
						for ($i = $area_length-1; $i >= 0; $i--) {
							$output_area = (string) $areacode[$i] . $output_area;
						} // end for
						$output_area = (string) '(' . $output_area . ') ';
					} else {
						$output_area = (string) '';
					} // end if

					for ($i = $number_length-1; $i >= 0; $i--) {
						if ($pos == 2) {
							if (($number_length % 2 == 0) ||
								(($number_length % 2 != 0) && $number_length > 4 && $i > 1)) {
								$output_number = (string) ' ' . $output_number;
							} // end if
							$pos = (int) 0;
						} // end if
						$output_number = (string) $numbers[$i] . $output_number;
						$pos++;
					} // end for

					$pos = (int) 0;
					if ($extention != FALSE) {
						for ($i = $ext_length-1; $i >= 0; $i--) {
							if ($ext_length % 2 == 0 && $ext_length > 3 && $pos == 2) {
								$output_extention = (string) ' ' . $output_extention;
								$pos = (int) 0;
							} // end if
							$output_extention = (string) $ext[$i] . $output_extention;
							$pos++;
						} // end for
						$output_extention = (string) '-' . $output_extention;
					} // end if
					break;
				case 'us':
				default:
					if ($area != FALSE) {
						$output_area = (string) $area . '-';
					} else {
						$output_area = (string) '';
					} // end if

					for ($i = $number_length-1; $i >= 0; $i--) {
						if ($i == 2) {
							$output_number = (string) '-' . $output_number;
						} // end if
						$output_number = (string) $numbers[$i] . $output_number;
						$pos++;
					} // end for


					if ($extention != FALSE) {
						$output_extention = (string) ' x ' . $extention;
					} else {
						$output_extention = (string) '';
					} // end if
					break;
			} // end switch
			return (string) $output_int_area . $output_area . $output_number . $output_extention;
		} else {
			return (string) '';
		} // end if
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
