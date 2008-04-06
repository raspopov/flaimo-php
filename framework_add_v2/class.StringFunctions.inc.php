<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
//+----------------------------------------------------------------------+
//| WAMP (XP-SP1/1.3.27/4.0.12/5.0.0b2-dev)                                    |
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
* Tested with WAMP (XP-SP1/1.3.27/4.0.12/5.0.0b2-dev)
* Last change: 2003-07-05
*
* @desc different string functions
* @access public
* @author Michael Wimmer <flaimo@gmx.net>
* @copyright Michael Wimmer
* @link http://www.flaimo.com/
* @package i18n
* @category FLP
* @version 2.000
*/
class StringFunctions {

	/*-------------------*/
	/* V A R I A B L E S */
	/*-------------------*/

	/**#@+
	* @const int
	* @see AbstractText()
	*/
	/**
	* The minimum of characters a text must have before the AbstractText function starts working
	*/
	CONST STRING_MINIMUM_LENGTH = 800;

	/**
	* The maximum of characters that should be displayed then
	*/
	CONST MAXIMUM_LENGTH_CUTSTRING = 500;

	/**
	* Minimum length the shortend text is allowed to have
	*/
	CONST MINIMUM_LENGTH_CUTSTRING = 50;

	/**
	* broatend the min and max string length if no point was found
	*/
	CONST STEP = 10;
	/**#@-*/

	/**
	* +/- how many characters (starting from the first searchterm appearance) should be shown in the search result text
	*
	* @const int
	* @see SearchResult()
	*/
	CONST SEARCH_DISPLAY_AREA = 150;

	/**
	* Character(s) to be displayed between the shortend text and the "more"
	*
	* @const string
	* @see SearchResult()
	* @see AbstractText()
	*/
	CONST SEPERATOR = '&#8230;';

	/*-------------------*/
	/* F U N C T I O N S */
	/*-------------------*/

	/**
	* returns the first few sentences of a longer string ending with a …
	*
	* @param string $text the long string
	* @return string the abstract text
	*/
	public function abstractText($text = '') {
	    if (strlen($text) <= self::STRING_MINIMUM_LENGTH) {
	    	return $text;
	    } // end if

	    $minimum_length_cutstring = self::MINIMUM_LENGTH_CUTSTRING;
	    $maximum_length_cutstring = self::MAXIMUM_LENGTH_CUTSTRING;

	    $firstdot = FALSE;
	    $text_raw = $text = trim($text);

		do { // find end of sentence
			$text_raw  = (string) substr($text, 0, $maximum_length_cutstring);
			$firstdot = strpos($text_raw, '. ', $minimum_length_cutstring);
			$maximum_length_cutstring += self::STEP;
			$minimum_length_cutstring -= self::STEP;
		} while ($firstdot == FALSE && $maximum_length_cutstring < self::STRING_MINIMUM_LENGTH); // end do

		if ($firstdot == FALSE || strlen($text) == strlen($text_raw)) {
	    	$minimum_length_cutstring = self::MINIMUM_LENGTH_CUTSTRING;
	    	$maximum_length_cutstring = self::MAXIMUM_LENGTH_CUTSTRING;
			do { // if no dot was found use spaces...
				$text_raw  = (string) substr($text, 0, $maximum_length_cutstring);
				$firstdot = strpos($text_raw, ' ', $minimum_length_cutstring);
				$maximum_length_cutstring += self::STEP;
				$minimum_length_cutstring -= self::STEP;
			} while ($firstdot == FALSE && $maximum_length_cutstring < self::STRING_MINIMUM_LENGTH); // end do
		} // end if

		if ($firstdot == FALSE || strlen($text) == strlen($text_raw)) {
			return $text; // vielleicht nach leezeichen cutten
		} else {
			$text = (string) substr($text_raw, 0, $firstdot);
			return $text . self::SEPERATOR;
		} // end if
	} // end function

	/**
	* returns +/- a couple of words starting from the first appearence of a keyword
	*
	* has to be used after all htmentites/wordwrap/…
	*
	* @param string $text the long string
	* @param string $searchstring the keyword
	* @return string the abstract text
	*/
	public function searchResultAbstract($text = '', $searchstring = '') {
		if (strlen(trim($searchstring)) == 0 || strlen(trim($text)) == 0) {
			return (boolean) FALSE;
		} elseif (($first_occurrence = strpos($text, $searchstring)) === FALSE) {
			$text_raw = (string) substr($text, 0, self::SEARCH_DISPLAY_AREA);
			return (string) substr($text, 0, strrpos($text_raw, ' ')) . self::SEPERATOR;
		} // end if

		$text_raw = $text;
		$searchstring_length = strlen($searchstring);
		$text_length = strlen($text);

		$startcut = (int) ((($first_occurrence - self::SEARCH_DISPLAY_AREA) <= 0) ? 0 : ($first_occurrence - self::SEARCH_DISPLAY_AREA)); // +/- characters from the searchterm
		$endcut = (int) ((($first_occurrence + self::SEARCH_DISPLAY_AREA) >= ($text_length - $searchstring_length)) ? $text_length : ($first_occurrence + self::SEARCH_DISPLAY_AREA));
		$text_raw = (string) substr($text, $startcut, ($endcut - $startcut));

		$firstend = (int) (($startcut == 0) ? 0 : strpos($text_raw, ' ')); // Looks for space character
		$lastend = (int) ((($first_occurrence + self::SEARCH_DISPLAY_AREA) >= ($text_length - $searchstring_length)) ? $endcut : strrpos($text_raw,' '));
		$text_raw = (string) substr($text_raw, $firstend, ($lastend - $firstend));

		$firstdots = (string) (($startcut != 0) ? self::SEPERATOR . ' ' : ''); // Adds dots in front of the shortend string
		$lastdots = (string) (($lastend != $endcut) ? ' ' . self::SEPERATOR : ''); // Adds dots at the end of the shortend string
		return (string) $firstdots . str_replace($searchstring, '<span class="highlightsearchstring">' . $searchstring . '</span>', $text_raw) . $lastdots;
	} // end function
} // end class StringFunctions
?>