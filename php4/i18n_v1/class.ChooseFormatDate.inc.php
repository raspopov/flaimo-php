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
* base class
*/
@require_once 'class.FormatDate.inc.php';

/**
* Creates a dropdown menu for selecting the time format
*
* Tested with WAMP (XP-SP1/1.3.24/4.0.12/4.3.0)
* Last change: 2003-05-04
*
* @desc Creates a dropdown menu for selecting the time format
* @access public
* @author Michael Wimmer <flaimo 'at' gmx 'dot' net>
* @copyright Michael Wimmer
* @link http://www.flaimo.com/
* @see FormatDate
* @package i18n
* @category FLP
* @version 1.061
*/
class ChooseFormatDate extends FormatDate {

	/*-------------------*/
	/* V A R I A B L E S */
	/*-------------------*/

	/*-----------------------*/
	/* C O N S T R U C T O R */
	/*-----------------------*/

	/**
	* Constructor
	*
	* Calls the constructor of the base class
	*
	* @desc Constructor
	* @param string $locale  You can set a language manually and override all other settings
	* @param string $namespaces  names of the translation files (.inc or .mo) WITHOUT the fileextention. If you use MySQL modus this are the namespaces for preselecting translationstrings from the database into an array
	* @return void
	* @access private
	* @uses FormatDate::FormatDate()
	* @uses checkClass()
	*/
	function ChooseFormatDate($language = '') {
		parent::FormatDate($language);
	} // end function

	/*-------------------*/
	/* F U N C T I O N S */
	/*-------------------*/

	/**
	* Creates a dropdown menu for selecting the time format
	*
	* @desc Creates a dropdown menu for selecting the time format
	* @return string $output_dropdown  + <select> tag with possible time formats
	* @access public
	* @uses getTimeSelectName()
	* @uses getSelectCSSClass()
	* @uses getPossibleDisplayTimes()
	* @uses FormatDate::getTimeset()
	*/
	function &returnDropdownSelecttime() {
		parent::loadUserClass();
		$output_dropdown = (string) '<select name="' . $this->user->getTimeSelectName() . '" id="' . $this->user->getTimeSelectName() . '" class="' . $this->user->getSelectCSSClass() . '">';
		foreach (parent::getPossibleDisplayTimes() as $list_times => $list_times_names) {
			$timeselect 		 = (string) (($list_times === parent::getTimeset()) ? ' selected="selected"' : '');
			$output_dropdown 	.= (string) '<option value="' . $list_times . '"' . $timeselect . '>' . str_replace('&shy;','',$list_times_names) . '</option>';
		} // end foreach
		unset($timeselect);
		return (string) $output_dropdown . '</select>';
	} // end function
} // end class ChooseFormatDate
?>
