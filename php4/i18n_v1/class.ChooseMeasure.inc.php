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
* Including the Measure class is necessary for this class
*/
@require_once 'class.Measure.inc.php';

/**
* Extends the Measure class for a html dropdown menu
*
* Tested with WAMP (XP-SP1/1.3.24/4.0.12/4.3.0)
* Last change: 2003-05-02
*
* @desc Extends the Measure class for a html dropdown menu
* @access public
* @author Michael Wimmer <flaimo 'at' gmx 'dot' net>
* @copyright Michael Wimmer
* @link http://www.flaimo.com/
* @see Measure
* @package i18n
* @category FLP
* @version 1.061
*/
class ChooseMeasure extends Measure {

	/*-----------------------*/
	/* C O N S T R U C T O R */
	/*-----------------------*/

	/**
	* Constructor
	*
	* Calls the constructor of the parent class
	*
	* @desc Constructor
	* @param string $input  Measuring system for the input values
	* @param string $output  Measuring system for the returned output values
	* @return void
	* @access private
	* @uses Measure::Measure()
	* @since 1.043 - 2003-02-13
	*/
	function ChooseMeasure($input = '', $output = '') {
        parent::Measure($input, $output);
	} // end constructor

	/*-------------------*/
	/* F U N C T I O N S */
	/*-------------------*/

	/**
	* Creates dropdown-element for the measure systems
	*
	* @desc Creates dropdown-element for the measure systems
	* @return string  returns a HTML <select> element
	* @access public
	* @uses Measure::getSystems()
	* @uses getMeasureSelectName()
	* @uses getSelectCSSClass()
	* @since 1.043 - 2003-02-13
	*/
	function returnDropdownMeasure() {
		parent::loadUserClass();
		$systems =& parent::getSystems();
		$user_setting =& $this->user->getPreferedMeasureSystem();
		if (count($systems) > 1) {
            $output_dropdown 		 = (string) '<select name="' . $this->user->getMeasureSelectName() . '" id="' .  $this->user->getMeasureSelectName() . '" class="' .  $this->user->getSelectCSSClass() . '">';
            foreach ($systems as $code => $name) {
				$langselect 		 = (string) (($code === $user_setting) ? ' selected="selected"' : '' );
				$output_dropdown 	.= (string) '<option value="' . $code . '"' . $langselect . '>' . htmlentities($name) . '</option>';
			} // end foreach
			return (string) $output_dropdown . '</select>';
		} // end if
	} // end function
} // end class ChooseMeasure
?>
