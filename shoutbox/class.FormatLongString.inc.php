<?php
/**
* Base class
*/
include_once('class.FormatString.inc.php');

/**
* Some batch functions for long strings based on the FormatString class
*
* @access public
* @author Michael Wimmer <flaimo@gmx.net>
* @copyright Michael Wimmer
* @link http://www.flaimo.com/  flaimo.com
* @package FLP
* @see FormatString
* @version 1.000
*/
class FormatLongString extends FormatString
  {
  
  
  /*-------------------*/
  /* V A R I A B L E S */
  /*-------------------*/
  
  /**
  * Startingpoint where the displayed string should start. You can leave this at 0 if you want the text to start from the beginning
  *
  * @var int
  * @access private
  * @see AbstractText()
  */ 
  var $slashdot_start;
  
  /**
  * The minimum of characters a text must have before the AbstractText function starts working
  *
  * @var int
  * @access private
  * @see AbstractText()
  */ 
  var $max_length_start;
  
  /**
  * The maximum of characters that should be displayed then
  *
  * @var int
  * @access private
  * @see AbstractText()
  */ 
  var $counter_start;
  
  /**
  * Minimum length the shortend text must have
  *
  * @var int
  * @access private
  * @see AbstractText()
  */ 
  var $min_length_start;
  
  /**
  * +/- how many characters (starting from the first searchterm appearance) should be shown in the search result text
  *
  * @var int
  * @access private
  * @see SearchResult()
  */ 
  var $walk_start;
  
  /**
  * Character(s) to be displayed between the shortend text and the "more"
  *
  * @var string
  * @access private
  * @see SearchResult(), AbstractText()
  */ 
  var $seperator;
  
  /**
  * Name for the variable to be attached to the "more" link
  *
  * @var mixed
  * @access private
  * @see SearchResult(), AbstractText()
  */ 
  var $id;
  
  /**
  * Name of the page where the full text is displayed
  *
  * @var string
  * @access private
  * @see SearchResult(), AbstractText()
  */ 
  var $showpage;

  
  /*-----------------------*/
  /* C O N S T R U C T O R */
  /*-----------------------*/
 
  /**
  * Constructor
  *
  * Calls the constructor of the base class + sets some basic values
  *
  * @param (string) $input_highlight  String that should be highlighted with html tags
  * @return (void)
  * @access private
  * @see FormatString::FormatString()
  * @since 1.000 - 2002/10/10   
  */
  function FormatLongString($input_highlight = '')
    {
    parent::FormatString($input_highlight); // passes the optional searchterm to the parent class.
    $this->max_length_start = (int) 800;
    $this->counter_start = (int) 500;
    $this->min_length_start = (int) 50;
    $this->walk_start = (int) 150;
    $this->slashdot_start = 0;    
    $this->seperator = (string) '&#8230;';
    $this->id = (string) 'id';
    $this->showpage = (string) $_SERVER['PHP_SELF'];
    include('ads.inc.php');
    } // end function

    
  /*-------------------*/    
  /* F U N C T I O N S */
  /*-------------------*/

  /**
  * +/- how many characters (starting from the first searchterm appearance) should be shown in the search result text
  *
  * @return (int) $walk_start
  * @access public
  * @see $walk_start
  * @since 1.000 - 2002/10/10 
  */ 
  function getWalkStart()
    {
    return (int) $this->walk_start;
    } // end function
  
  /**
  * The minimum of characters a text must have before the AbstractText function starts working
  *
  * @return (int) $max_length_start
  * @access public
  * @see $max_length_start
  * @since 1.000 - 2002/10/10 
  */ 
  function getMaxLengthStart()
    {
    return (int) $this->max_length_start;
    } // end function
    
  /**
  * The maximum of characters that should be displayed then
  *
  * @return (int) $counter_start
  * @access public
  * @see $counter_start
  * @since 1.000 - 2002/10/10 
  */     
  function getCounterStart()
    {
    return (int) $this->counter_start;
    } // end function
  
  /**
  * Minimum length the shortend text must have
  *
  * @return (int) $min_length_start
  * @access public
  * @see $min_length_start
  * @since 1.000 - 2002/10/10 
  */ 
  function getMinLengthStart()
    {
    return (int) $this->min_length_start;
    } // end function 
  
  /**
  * Startingpoint where the displayed string should start
  *
  * @return (int) $slashdot_start
  * @access public
  * @see $slashdot_start
  * @since 1.000 - 2002/10/10 
  */ 
  function getSlashDotStart()
    {
    return (int) $this->slashdot_start;
    } // end function
  
  /**
  * Returns the "more" in the langage set
  *
  * @return (string) $more
  * @access public
  * @see setMore(); $more
  * @since 1.000 - 2002/10/10 
  */ 
  function getMore()
    {
    return (string) $this->more;
    } // end function
  
  /**
  * Name of the page where the full text is displayed
  *
  * @return (string) $showpage
  * @access public
  * @see $showpage
  * @since 1.000 - 2002/10/10 
  */ 
  function getShowpage()
    {
    return (string) $this->showpage;
    } // end function  
  
  /**
  * Name for the variable to be attached to the "more" link
  *
  * @return (mixed) $id
  * @access public
  * @see $id
  * @since 1.000 - 2002/10/10 
  */ 
  function getID()
    {
    return (string) $this->id;
    } // end function
  
  /**
  * Character(s) to be displayed between the shortend text and the "more"
  *
  * @return (string) $seperator
  * @access public
  * @see $seperator
  * @since 1.000 - 2002/10/10 
  */ 
  function getSeperator()
    {
    return (string) $this->seperator;
    } // end function
  
  /**
  * Sets the "more" in the langage set
  *
  * @param (string) $string
  * @return (void)
  * @access public
  * @see getMore(), $more
  * @since 1.000 - 2002/10/10 
  */ 
  function setMore($string)
    {
    $this->more = (string) $string;
    } // end function
  
//------------------------------------------------ 
 
  /**
  * Combines a buch of functions from the parent class to format long strings
  *
  * @param (string) $text
  * @return (string)  Transformed input-string
  * @access public
  * @since 1.000 - 2002/10/10 
  */ 
  function FormatLongText($text) // PHP5: public
    {
    return (string) parent::SpecialChar(wordwrap(parent::SpecialWords(parent::Highlight(nl2br(htmlentities(parent::WordFilter(strip_tags($text)))))),75));
    } // end function

  
  /**
  * Combines a buch of functions from the parent class to format one-liners
  *
  * @param (string) $string
  * @return (string)  Transformed input-string
  * @access public
  * @since 1.000 - 2002/10/10 
  */ 
  function FormatShortText($string)
    {
    return (string) trim(parent::SpecialChar(nl2br(parent::Highlight(htmlentities(strip_tags($string))))));
    } // end function
 
//------------------------------------------------
  
  /**
  * Displays only a small part of a longer string
  *
  * then searches for the last dot in this string. There the text
  * is cut again and and a "... [more] is attached with a link to the
  * full version of the text.
  * To change the "more" to your language, please take a look at my
  * SelectLanguage Class that is required in the FormatString class
  * anyway.
  *
  * @param (string) $text
  * @param (int) $id  ID number of the article
  * @return (string) $text  Transformed input-string (shortend text ... [more])
  * @access public
  * @since 1.000 - 2002/10/10 
  */ 
  function AbstractText($text,$id = 1) // PHP5: public
    {
    $lastdot = (int) $this->getSlashDotStart();
    $counter = (int) $this->getCounterStart();
    $text_raw = $text = (string) parent::WordFilter($text); // First use "badwords" filter
        
    if (strlen($text) > $this->getMaxLengthStart()) // Checks if text is long enough fur cutting
      {
      while ($lastdot < $this->getMinLengthStart())
        {
        $shorttext_raw  = (string) substr($text, 0, $counter);
        $lastdot = (int) strrpos($shorttext_raw,'.'); // Searches for the last do in the string
        $counter += (int) 50; // Stringsize is encreased by 50 characters if no dot is found or the shortend text is below 50 characters
        } // end while
       
      $text = (string) substr($text, 0, $lastdot); // Final cutting of the original text
      } // end if

    // Bunch of Stringfilters.... strlen(trim($id)) 
    $text = (string) parent::SpecialChar(wordwrap(parent::SpecialWords(parent::Highlight(nl2br(htmlentities(strip_tags($text))))),75));
    
    if (strlen($text_raw) > $this->getMaxLengthStart() and strlen(trim($id)) > 0) { $text .= (string) ' ' . $this->getSeperator() . ' <a href="' . $this->getShowpage() . '?' . $this->getID() . '=' . $id . '">' . $this->lg->__('more_news') . '</a>'; } // Adds a link for the full text and the "...[more]"
    return (string) $text;
    } // end function 
  
//------------------------------------------------
  
  /**
  * Displays only a small part of the string where a serachterm was found
  *
  * Takes a string, looks for the first appearance of the search term
  * and shortens the text +/- a certain amount of characters from that
  * point. Also adds a "more" like in the AbstractText function
  *
  * @param (string) $text
  * @param (int) $id  ID number of the article
  * @return (string)  Transformed input-string (shortend text ... [more])
  * @access public
  * @since 1.000 - 2002/10/10 
  */ 
  function SearchResult($text, $id = 1)
    {
    $text_raw = (string) $text;
    $temp = stristr($text, parent::getHighlightWord());
    
    $searchfound = (int) strlen($text) - strlen($temp);
    
    $startcut = (int) ((($searchfound - $this->getWalkStart()) <= 0) ? 0 : ($searchfound - $this->getWalkStart())); // +/- characters from the searchterm
    $endcut = (int) ((($searchfound + $this->getWalkStart()) >= (strlen($text) - strlen(parent::getHighlightWord()))) ? strlen($text) : ($searchfound+$this->getWalkStart()));
    
    $text_raw = (string) substr($text, $startcut, ($endcut-$startcut));
    
    $firstend = (int) (($startcut == 0) ? 0 : strpos($text_raw,' ')); // Looks for space character
    $lastend = (int) ((($searchfound + $this->getWalkStart()) >= (strlen($text) - strlen(parent::getHighlightWord()))) ? $endcut : strrpos($text_raw,' '));
  
    $text_raw = (string) substr($text_raw, $firstend, ($lastend-$firstend));
    
    $firstdots = (string) (($startcut != 0) ? $this->getSeperator() . ' ' : ''); // Adds dots in front of the shortend string
    $lastdots = (string) (($lastend != $endcut) ? ' ' . $this->getSeperator() : ''); // Adds dots at the end of the shortend string
    
    $text_raw = (string) parent::SpecialChar(wordwrap(parent::SpecialWords(parent::Highlight(nl2br(htmlentities(strip_tags($text_raw))))),75));
    
    return (string) $firstdots . $text_raw . $lastdots . ' <a href="' . $this->getShowpage() . '?' . $this->getID() . '=' . $id .  parent::SetURLencoder(parent::getCSSHighlight(),addslashes(urlencode(parent::getHighlightWord())),'&')  . '">' . $this->lg->__('more_news') . '</a>';
    } // end function
 
//------------------------------------------------
  
  /**
  * Adds text-ads to strings
  *
  * This function should check a string for certain words, make a
  * link out of them and mark them as an ad. Somehow crashes the php
  * engine sometimes. Must be used AFTER htmlentities/nl2br/wordwrap
  *
  * @param (string) $text
  * @return (string) $string  Transformed input-string
  * @access public
  * @deprec  1.000 - 2002/10/10
  * @since 1.000 - 2002/10/09   
  */ 
  function HighlightAds($string) // must be used AFTER htmlentities/nl2br/wordwrap // Bringt Apache zum abstürzten // PHP5: public
    {
    if (isset($this->adlist[$this->lg->getLangContent()]))
      {  
      foreach ($this->adlist[$this->lg->getLangContent()] as $ad => $link)
        {
        $string = (string) str_replace(htmlentities($ad), '<a href="' . $link . '" class="ad" title="Paid Advertisement Link" target="_blank">' . htmlentities($ad) . '<span class="ad">°</span></a>', $string , 1);
        } // end foreach
      } // end if
    return (string) $string;
    } // end function
  } // end class FormatLongString 
?>