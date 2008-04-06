<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
//+----------------------------------------------------------------------+
//| WAMP (XP-SP1/1.3.24/4.0.12/4.3.0)                                    |
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
* @package Framework
* @category FLP
*/
/**
* different string functions
*
* Tested with WAMP (XP-SP1/1.3.24/4.0.12/4.3.0)
* Last change: 2003-05-20
*
* @desc different string functions
* @access public
* @author Michael Wimmer <flaimo@gmx.net>
* @copyright Michael Wimmer
* @link http://www.flaimo.com/
* @package i18n
* @category FLP
* @version 1.000
*/
class StringFunctions {

	/*-------------------*/
	/* V A R I A B L E S */
	/*-------------------*/

	/**#@+
	* @access private
	*/
	/**
	* The minimum of characters a text must have before the AbstractText function starts working
	*
	* @var int
	* @see AbstractText()
	*/
	var $string_minimum_length = 800;

	/**
	* The maximum of characters that should be displayed then
	*
	* @var int
	* @see AbstractText()
	*/
	var $maximum_length_cutstring = 500;

	/**
	* Minimum length the shortend text is allowed to have
	*
	* @var int
	* @see AbstractText()
	*/
	var $minimum_length_cutstring = 50;

	/**
	* broatend the min and max string length if no point was found
	*
	* @var int
	* @see AbstractText()
	*/
	var $step = 10;

	/**
	* +/- how many characters (starting from the first searchterm appearance) should be shown in the search result text
	*
	* @var int
	* @see SearchResult()
	*/
	var $search_display_area = 150;

	/**
	* Character(s) to be displayed between the shortend text and the "more"
	*
	* @var string
	* @see SearchResult()
	* @see AbstractText()
	*/
	var $seperator = '&#8230;';


	/*-----------------------*/
	/* C O N S T R U C T O R */
	/*-----------------------*/

	/**
	* Constructor
	*
	* @return (void)
	*/
  	function StringFunctions() {
	} // end function
	/**#@-*/

	/*-------------------*/
	/* F U N C T I O N S */
	/*-------------------*/

	/**
	* returns the first few sentences of a longer string ending with a …
	*
	* @desc returns the first few sentences of a longer string ending with a …
	* @param string $text the long string
	* @return string the abstract text
	* @access public
	*/
	function abstractText($text = '') {
	    if (strlen($text) <= $this->string_minimum_length) {
	    	return $text;
	    } // end if

	    $minimum_length_cutstring = $this->minimum_length_cutstring;
	    $maximum_length_cutstring = $this->maximum_length_cutstring;

	    $firstdot = FALSE;
	    $text_raw = $text = trim($text);

		do {
			$text_raw  = (string) substr($text, 0, $maximum_length_cutstring);
			$firstdot = strpos($text_raw, '. ', $minimum_length_cutstring);
			$maximum_length_cutstring += $this->step;
			$minimum_length_cutstring -= $this->step;
		} while ($firstdot == FALSE && $maximum_length_cutstring < $this->string_minimum_length); // end do

		if ($firstdot == FALSE || strlen($text) == strlen($text_raw)) { // if no dot was found use spaces...
	    	$minimum_length_cutstring = $this->minimum_length_cutstring;
	    	$maximum_length_cutstring = $this->maximum_length_cutstring;
			do {
				$text_raw  = (string) substr($text, 0, $maximum_length_cutstring);
				$firstdot = strpos($text_raw, ' ', $minimum_length_cutstring);
				$maximum_length_cutstring += $this->step;
				$minimum_length_cutstring -= $this->step;
			} while ($firstdot == FALSE && $maximum_length_cutstring < $this->string_minimum_length); // end do
		} // end if

		if ($firstdot == FALSE || strlen($text) == strlen($text_raw)) {
			return $text; // vielleicht nach leezeichen cutten
		} else {
			$text = (string) substr($text_raw, 0, $firstdot);
			return $text . $this->seperator;
		} // end if
	} // end function


	/**
	* returns +/- a couple of words starting from the first appearence of a keyword
	*
	* has to be used after all htmentites/wordwrap/…
	*
	* @desc returns +/- a couple of words starting from the first appearence of a keyword
	* @param string $text the long string
	* @param string $searchstring the keyword
	* @return string the abstract text
	* @access public
	*/
	function searchResultAbstract($text = '', $searchstring = '') {
		if (strlen(trim($searchstring)) == 0 || strlen(trim($text)) == 0) {
			return (boolean) FALSE;
		} elseif (($first_occurrence = strpos($text, $searchstring)) === FALSE) {
			$text_raw = (string) substr($text, 0, $this->search_display_area);
			return (string) substr($text, 0, strrpos($text_raw, ' ')) . $this->seperator;
		} // end if

		$text_raw = $text;
		$searchstring_length = strlen($searchstring);
		$text_length = strlen($text);

		$startcut = (int) ((($first_occurrence - $this->search_display_area) <= 0) ? 0 : ($first_occurrence - $this->search_display_area)); // +/- characters from the searchterm
		$endcut = (int) ((($first_occurrence + $this->search_display_area) >= ($text_length - $searchstring_length)) ? $text_length : ($first_occurrence + $this->search_display_area));
		$text_raw = (string) substr($text, $startcut, ($endcut - $startcut));

		$firstend = (int) (($startcut == 0) ? 0 : strpos($text_raw, ' ')); // Looks for space character
		$lastend = (int) ((($first_occurrence + $this->search_display_area) >= ($text_length - $searchstring_length)) ? $endcut : strrpos($text_raw,' '));
		$text_raw = (string) substr($text_raw, $firstend, ($lastend - $firstend));

		$firstdots = (string) (($startcut != 0) ? $this->seperator . ' ' : ''); // Adds dots in front of the shortend string
		$lastdots = (string) (($lastend != $endcut) ? ' ' . $this->seperator : ''); // Adds dots at the end of the shortend string
		return (string) $firstdots . str_replace($searchstring, '<span class="highlightsearchstring">' . $searchstring . '</span>', $text_raw) . $lastdots;
	} // end function
} // end class StringFunctions
?>