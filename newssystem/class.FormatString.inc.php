<?php
/**
* We need the language so we know how long date/time strings have to be displayed
*/
include_once('class.Language.inc.php');

/**
* Including the user class is necessary for getting the user-preferences
*/
include_once('class.User.inc.php');

/**
* Formats strings
*
* Mostly some helper functions. For example you have a “special words”
* file in one of your language subdirectories. These words have
* descriptions or whatever assigned to them in an array. A function now
* searches for those words in a string and replaces it with
* <abbr>words</abbr> for example. Also contains a “bad words” filter.
*
* @access public
* @author Michael Wimmer <flaimo@gmx.net>
* @copyright Michael Wimmer
* @link http://www.flaimo.com/  flaimo.com
* @package FLP
* @version 1.000
*/
class FormatString
  {
  
  /*-------------------*/
  /* V A R I A B L E S */
  /*-------------------*/
  
  /**
  * Language-dimension defines the language of the term not the language set in the SelectLanguage class
  *
  * @var array
  * @access private
  */
  var $specialwords;
  
  /**
  * Array for badwordlist
  *
  * @var array
  * @access private
  */  
  var $badwordlist;
  
  /**
  * See construstor
  *
  * @var array
  * @access private
  * @see FormatString()
  */ 
  var $specialchar;

  /**
  * CSS-Class and variable-name for highlighted text
  *
  * @var string
  * @access private
  */ 
  var $css_highlight;
  
  /**
  * For adding a session string to links
  *
  * @var string
  * @access private
  */ 
  var $sessionstring;
  
  /**
  * Holds the (optional) searchterm
  *
  * @var string
  * @access private
  */ 
  var $highlightword;
  
  /**
  * Holds the language object
  *
  * @var object
  * @access private
  * @see Language
  */ 
  var $lg;
  
  /**
  * Filename for the file containing the special words
  *
  * @var string
  * @access private
  */   
  var $wordsfile_name;
  
  /**
  * Filename for the file containing the bad words
  *
  * @var string
  * @access private
  */  
  var $badwordlist_filename;
  
  /**
  * Holds the user object
  *
  * @var object
  * @access private
  * @see User
  */ 
  var $user;
 
  /*-----------------------*/
  /* C O N S T R U C T O R */
  /*-----------------------*/
 
  /**
  * Constructor
  *
  * @param (string) $input_highlight  String that should be highlighted with html tags
  * @return (void)
  * @access private
  * @since 1.000 - 2002/10/10   
  */
  function FormatString($input_highlight = '')
    {
    $this->setWordsFileName('words.ini');
    $this->setBadwordlistFilename('badwords.inc'); 
    $this->setCSSHighlight('nshighlight');
    $this->setHighlightWord((((!isset($input_highlight)) || (strlen(trim($input_highlight)) == 0)) ? '' : $input_highlight));
    } // end function

    
  /*-------------------*/    
  /* F U N C T I O N S */
  /*-------------------*/

  /**
  * For adding a "&PHPSESSION=..." to URLs
  *
  * @param (string) $sessionname
  * @param (string) $sessionid
  * @return (void)
  * @access public
  * @since 1.000 - 2002/10/10   
  */
  function SetSessionURL($sessionname,$sessionid)
    {
    $temp_string = (string) ((strlen(trim($sessionname)) == 0 || strlen(trim($sessionid)) == 0) ? '' : '&#38;' . $sessionname . '=' . $sessionid); 
    $this->sessionstring = $temp_string;
    } // end function

//------------------------------------------------
  
  /**
  * Returns string that should be highlighted
  *
  * @return (string) $highlightword
  * @access public
  * @see setHighlightWord()
  * @since 1.000 - 2002/10/10   
  */
  function getHighlightWord()
    {
    return (string) $this->highlightword;
    } // end function
  
  /**
  * Returns the "more" string (translated to the current language)
  *
  * @return (string) $more
  * @access public
  * @since 1.000 - 2002/10/10   
  */
  function getMore()
    {
    return (string) $this->more;
    } // end function
  
  /**
  * Returns class name for highlighting words with a <span> tag
  *
  * @return (string) $css_highlight
  * @access public
  * @see setCSSHighlight()
  * @since 1.000 - 2002/10/10   
  */
  function getCSSHighlight()
    {
    return (string) $this->css_highlight;
    } // end function 
  
  /**
  * Returns filename for the file with the special words
  *
  * @return (string) $wordsfile_name
  * @access public
  * @see setWordsFileName()
  * @since 1.000 - 2002/10/10   
  */
  function getWordsFileName()
    {
    return (string) $this->wordsfile_name;
    } // end function 
  
  /**
  * Returns filename for the file with the bad words
  *
  * @return (string) $badwordlist_filename
  * @access public
  * @see setBadwordlistFilename()
  * @since 1.000 - 2002/10/10   
  */
  function getBadwordlistFilename()
    {
    return (string) $this->badwordlist_filename;
    } // end function
  
  /**
  * Returns variable name for the specialwords-status
  *
  * @return (string) $specialwordsstatusname
  * @access public
  * @see setSpecialWordsStatusName()
  * @since 1.000 - 2002/10/10   
  */
  function getSpecialWordsStatusName()
    {
    return (string) $this->specialwordsstatusname;
    } // end function
  
  /**
  * Returns the status for displaying special words
  *
  * @return (boolean) $specialWordsStatus  ON/OFF
  * @access public
  * @since 1.000 - 2002/10/10   
  */
  function getSpecialWordsStatus()
    {
    return (boolean) $this->specialWordsStatus;
    } // end function
  
  /**
  * Returns an array with special characters
  *
  * @return (array) $specialchar  Special characters
  * @access public
  * @since 1.000 - 2002/10/10   
  */
  function getSpecialChar()
    {
    return (array) $this->specialchar;
    } // end function
  
  /**
  * Sets the css-class name for the highlight <span> tag
  *
  * @param (string) $string  Class-name
  * @return (void)
  * @access private
  * @see getCSSHighlight()
  * @since 1.000 - 2002/10/10   
  */
  function setCSSHighlight($string)
    {
    $this->css_highlight = (string) $string;
    } // end function
  
  /**
  * Sets the string to be highlighted in strings
  *
  * @param (string) $string
  * @return (void)
  * @access private
  * @see getHighlightWord()
  * @since 1.000 - 2002/10/10   
  */
  function setHighlightWord($string)
    {
    $this->highlightword = (string) $string;
    } // end function 
  
  /**
  * Sets the filename for the file with the special words
  *
  * @param (string) $string
  * @return (void)
  * @access private
  * @see getWordsFileName()
  * @since 1.000 - 2002/10/10   
  */
  function setWordsFileName($string)
    {
    $this->wordsfile_name = (string) $string;
    } // end function
  
  /**
  * Sets the filename for the file with the list of bad words
  *
  * @param (string) $string
  * @return (void)
  * @access private
  * @see getWordsFileName()
  * @since 1.000 - 2002/10/10   
  */
  function setBadwordlistFilename($string)
    {
    $this->badwordlist_filename = (string) $string;
    } // end function
  
  /**
  * Sets the variable name for the specialwords-status
  *
  * @param (string) $string
  * @return (void)
  * @access private
  * @see getSpecialWordsStatusName()
  * @since 1.000 - 2002/10/10   
  */  
  function setSpecialWordsStatusName($string)
    {
    $this->specialwordsstatusname = (string) $string;
    } // end function

//------------------------------------------------

  /**
  * Doesn't work well. Don't use it.
  *
  * @param (string) $attribute  Attribute
  * @param (string) $value  Value
  * @param (string) $sign  ?|&
  * @return (string) $urlstring  GET variables
  * @access public
  * @deprec  1.000 - 2002/10/10
  * @since 1.000 - 2002/10/09   
  */  
  function SetURLencoder($attribute, $value, $sign = '')
    {
    if (strlen(trim($attribute)) == 0 || strlen(trim($value)) == 0)
      {
      $urlstring = (string) '';
      }
    else
      {
      $sign = (string) ((!isset($sign) || $sign='' || $sign == ' ' || $sign == '?' || $sign == 1) ? '?' : '&#38;');
      $urlstring = (string) $sign . $attribute . '=' . $value;
      } // end if
    return (string) $urlstring;
    } // end function
  
  /**
  * Set's the language variable if it hasn't been set before
  * 
  * @return (object) $lg Language object
  * @access private
  * @see loadUserClass(), Language
  * @since 1.000 - 2002/10/10 
  */
  function loadLanguageClass()
    {
    if (!isset($this->lg))
      {
      $this->lg = (object) new Language();   
      } // end if
    } // end function
  
  /**
  * Set's the user variable if it hasn't been set before
  * 
  * @return (object) $user  User object
  * @access private
  * @see loadLanguageClass(), User
  * @since 1.000 - 2002/10/10 
  */
  function loadUserClass()
    {
    if (!isset($this->user))
      {
      $this->user = (object) new User();   
      } // end if
    } // end function  
    
//------------------------------------------------

  /**
  * Array is loaded if the variable hasn't been set before
  *
  * Must be used BEFORE htmlentities/nl2br/wordwrap
  * 
  * @return (void)
  * @access private
  * @see SpecialChar()
  * @since 1.000 - 2002/10/10 
  */
  function LoadSpecialChar()
    {
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
                                      '·' => '&#8901;'
                                      );
    } // end function
  
  /**
  * Start where the htmlentities functions ends and html-encodes some more characters
  * 
  * Must be used BEFORE htmlentities/nl2br/wordwrap
  *
  * @param (string) $input
  * @return (string) $input  Transformed input-string
  * @access public
  * @see LoadSpecialChar()
  * @since 1.000 - 2002/10/10 
  */
  function SpecialChar($input)
    {
    if (!isset($this->specialchar)) { $this->LoadSpecialChar(); }
    foreach ($this->specialchar as $list_name => $list_value)
      { 
      $input = (string) str_replace($list_name,$list_value,$input);
      } // end foreach
    return (string) $input;
    } // end function

//------------------------------------------------
    
  /**
  * For alternating rowcolors
  *
  * For alternating rowcolors. Use it as
  * <tag class="' . $object->ChangeRowCSS($i) . '"> in loops with a
  * counter $i
  * 
  * @param (int) $i  Rownumber
  * @return (string)  CSS-class name
  * @access public
  * @since 1.000 - 2002/10/10 
  */
  function ChangeRowCSS($i = 0)
    {
    return (string) (($i % 2) ? 'darkrow' : 'lightrow');
    } // end function
    
//------------------------------------------------
  
  /**
  * The ini file with the definitions for acronym|abbr|dfn are loaded into an 2D array
  * 
  * @return (void)
  * @access private
  * @see SpecialWords()
  * @since 1.000 - 2002/10/10 
  */
  function loadSpecialWordsFile()
    {
    $this->loadLanguageClass();
    if (!isset($this->specialwords) && file_exists($this->lg->getLanguageFilePath() . '/' . $this->lg->getLang() . '/' . $this->getWordsFileName()))
      {
      $this->specialwords = (array) parse_ini_file($this->lg->getLanguageFilePath() . '/' . $this->lg->getLang() . '/' . $this->getWordsFileName(), TRUE);
      } // end if
    } // end function
  
  /**
  * Loops through the 2nd dimension of the special words array
  *
  * Adds acronym, abbr and dfn tags for the all the words defined in
  * the 'words.inc.php' file.
  * 
  * @param (string) $text
  * @param (string) $what  1st dimension of the array
  * @return (string) $text  Transformed input-string
  * @access private
  * @see SpecialWords()
  * @since 1.000 - 2002/10/10 
  */  
  function loopSpecialWords($text, $what = '')
    {
    foreach ($this->specialwords[$what] as $list_name => $list_value)
      { 
      $valuearray = (array) explode(' | ',$list_value);
      $text = (string) preg_replace('*' . htmlentities(quotemeta($list_name)) . '*', '<'.$what.' title="' . htmlentities($valuearray[0]) . '" lang="' . $valuearray[1] . '" xml:lang="' . $valuearray[1] . '">' . htmlentities($list_name) . '</'.$what.'>', $text , 1);
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
  * @param (string) $text
  * @param (string) $what  1st dimension of the array (acronym|abbr|dfn) [optional]
  * @return (string) $text  Transformed input-string
  * @access public
  * @see loopSpecialWords()
  * @since 1.000 - 2002/10/10 
  */  
  function SpecialWords($text, $what = '')
    {
    $this->loadSpecialWordsFile();
    $this->loadUserClass();
    if ($this->user->getSpecialWordsStatus() == true && isset($this->specialwords))
      {
      if (preg_match('(^(acronym|abbr|dfn)$)',$what))
        {
        $text = (string) $this->loopSpecialWords($text, $what);
        }
      else
        {
        $arraylist = (array) array_keys($this->specialwords);
        foreach ($arraylist as $what)
          {
          if (preg_match('(^(acronym|abbr|dfn)$)',$what))
            {
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
  * Language class, it's loaded into an array
  * 
  * @return (void)
  * @access public
  * @see WordFilter()
  * @since 1.000 - 2002/10/10 
  */  
  function ReadBadwordListFile()
    {
    $this->loadLanguageClass();
    if ($badwordlist_raw = (array) file($this->lg->getLanguageFilePath() . '/' . $this->lg->getLang() . '/' . $this->badwordlist_filename))
      {
      foreach ($badwordlist_raw as $value)
        { 
        $this->badwordlist[] = trim($value);
        } // end foreach
      }
    else
      {
      $this->badwordlist = null;
      } // end if
    }  // end function
  
  /**
  * Replaces bad words in a string
  *
  * For filtering "bad" words. List is in the "words.inc.php" file.
  * Can be defined seperatly for each language. Must be used BEFORE
  * htmlentities/nl2br/wordwrap
  * 
  * @param (string) $text
  * @return (string) $text  Transformed input-string
  * @access public
  * @see ReadBadwordListFile()
  * @since 1.000 - 2002/10/10 
  */  
  function WordFilter($text = '')
    {
    if (!isset($this->badwordlist)) { $this->ReadBadwordListFile(); }
    
    if ($this->badwordlist != null)
      {
      for ($i = (int) 0,$max = (int) sizeof($this->badwordlist)-1; $i <= $max; $i++)
        {
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
  * @param (string) $text
  * @return (string) $text  Transformed input-string
  * @access public
  * @see HighlightError(), setHighlightWord(), getHighlightWord
  * @since 1.000 - 2002/10/10 
  */ 
  function Highlight($text = '')
    {
    if (strlen(trim($this->highlightword)) > 0)
      {
      $text = (string) str_replace(htmlentities($this->highlightword),'<span class="' . $this->css_highlight . '">' . htmlentities($this->highlightword) . '</span>',$text);
      } // end if
    return (string) $text;
    } // end function

  /**
  * Highlights errors
  *
  * @param (string) $string
  * @return (string)  Errormessage
  * @access private
  * @see Highlight(), setHighlightWord(), getHighlightWord()
  * @since 1.000 - 2002/10/10 
  */ 
  function HighlightError($string)
    {
    $this->setHighlightWord($string);
    return (string) $this->Highlight('<small>Error: <var>[' . $string . ']</var></small>');
    } // end function   
  
//------------------------------------------------
  
  /**
  * Makes Links out of certain words in a string
  *
  * Not one of my function. Somehow crashes the php engine sometimes.
  * Don't use it! Must be used AFTER htmlentities/nl2br/wordwrap
  * 
  * @param (string) $string
  * @return (string) $string  Transformed input-string
  * @access public
  * @deprec  1.000 - 2002/10/10
  * @since 1.000 - 2002/10/09   
  */  
  function HighlightURLs($string)
    {
    for($n = (int) 0,$strlength = (int) strlen($string); $n < $strlength; $n++)
      { 
  		if (strtolower($string[$n]) == 'h')
        { 
  			if (!strcmp('http://', strtolower($string[$n]) . strtolower($string[$n+1]) . strtolower($string[$n+2]) . strtolower($string[$n+3]) . $string[$n+4] . $string[$n+5] . $string[$n+6]))
          { 
  				$startpos = (int) $n; 
  				
          while ($n < strlen($string) && eregi("[a-z0-9\.\:\?\/\~\_\&\=\%\+\'\"-]", $string[$n]))
            {
            $n++;
            }
  					
            if (!eregi("[a-z0-9]", $string[$n-1])) { $n--; }
            $link = (string) substr($string, $startpos, ($n-$startpos+1)); 
  					$link = (string) $link; 
  					$string_tmp = (string) $string; 
  					$string = (string) substr($string_tmp, 0, $startpos); 
  					$string .= (string) '<a href="' . $link . '" taget="_blank">' . $link . '</a>'; 
  					$string .= (string) substr($string_tmp, $n+1, strlen($string_tmp)); 
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
  * Checks if there's a "@" in the string. If so, then the string
  * is encodes as a mail address (mailto:) and "encypted" to
  * protect the address from spambots. Else it's formated as a
  * normal link. Must be used AFTER htmlentities/nl2br/wordwrap
  *
  * @param (string) $string
  * @return (string) $string  Transformed input-string (normal link or mailto:)
  * @access public
  * @see EncodeMailAddress(), DecodeMailAddress()
  * @since 1.000 - 2002/10/10 
  */ 
  function CheckMailHomepage($string) // must be used AFTER htmlentities/nl2br/wordwrap
    {
    if (strstr($string,'@'))
      {
      return (string) $this->EncodeMailAddress('mailto:' .  $string);
      }
    elseif (strstr($string,'http') || strstr($string,'ftp'))
      {
      return (string) $string;
      }
    else
      {
      return (string) '';
      } // end if
    } // end function
  
  /**
  * Does some string formating to prevent spambots from grabbing the address.
  *
  * @param (string) $string
  * @return (string)  Transformed input-string ('sendmail.php?mail=' $address)
  * @access public
  * @see CheckMailHomepage(), DecodeMailAddress()
  * @since 1.000 - 2002/10/10 
  */ 
  function EncodeMailAddress($string)
    {
    if (strstr($string,'@'))
      {
      $string = (string) str_replace('@','@at@',$string);
      $string = (string) str_replace('.','@dot@',$string);
      $string = (string) strrev($string);
      $string = (string) urlencode($string);
      return (string) 'sendmail.php?mail=' . $string;
      } // end if
    } // end function

  /**
  * Reverses the function that does some string formating to prevent spambots from grabbing the address.
  *
  * @param (string) $string
  * @return (string) string  Transformed back to original string
  * @access public
  * @see CheckMailHomepage(), EncodeMailAddress()
  * @since 1.000 - 2002/10/10 
  */ 
  function DecodeMailAddress($string)
    {
    if (strstr($string,'@'))
      {
      $string = (string) urldecode($string);
      $string = (string) strrev($string);
      $string = (string) str_replace('@at@','@',$string);
      $string = (string) str_replace('@dot@','.',$string);
      return (string) $string;
      } // end if
    } // end function
  } // end class FormatString  
?>