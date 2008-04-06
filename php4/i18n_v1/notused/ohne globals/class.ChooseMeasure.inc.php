<?php
/**
* @package i18n
*/
/**
* Including the Measure class is necessary for this class
*/
@include_once('class.Measure.inc.php');

/**
* Extends the Measure class for a html dropdown menu
*
* Tested with WAMP (XP-SP1/1.3.24/4.0.4/4.3.0)
* Last change: 2003-02-26
*
* @desc Extends the Measure class for a html dropdown menu
* @access public
* @author Michael Wimmer <flaimo@gmx.net>
* @copyright Michael Wimmer
* @link http://www.flaimo.com/
* @see Measure
* @package i18n
* @version 1.053
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
	* @param (string) $input  Measuring system for the input values
	* @param (string) $output  Measuring system for the returned output values
	* @return (void)
	* @access private
	* @uses Measure::Measure(), checkClass()
	* @since 1.043 - 2003/02/13
	*/
	function ChooseMeasure($input = '', $output = '') {
        $this->checkClass('Measure', __LINE__);
        parent::Measure($input, $output);
	} // end constructor

	/*-------------------*/
	/* F U N C T I O N S */
	/*-------------------*/

	/**
	* Creates dropdown-element for the measure systems
	*
	* @desc Creates dropdown-element for the measure systems
	* @return (string)  returns a HTML <select> element
	* @access public
	* @uses Measure::getSystems(), getMeasureSelectName(), getSelectCSSClass()
	* @since 1.043 - 2003/02/13
	*/
	function returnDropdownMeasure() {
		$systems =& parent::getSystems();
		$user_setting =& $this->user->getPreferedMeasureSystem();
		if (count($systems) > 1) {
            $output_dropdown = (string) '<select name="' . $this->getMeasureSelectName() . '" id="' .  $this->getMeasureSelectName() . '" class="' .  $this->getSelectCSSClass() . '">';
            foreach ($systems as $code => $name) {
				$langselect 		 = (string) (($code === $user_setting) ? ' selected="selected"' : '' );
				$output_dropdown 	.= (string) '<option value="' . $code . '"' . $langselect . '>' . htmlentities($name) . '</option>';
			} // end foreach
			return (string) $output_dropdown . '</select>';
		} // end if
	} // end function

	/**
	* Returns the name for the measure <select> tag
	*
	* @desc Returns the name for the measure <select> tag
	* @return (string) $measureselectname
	* @access public
	* @see getMeasureSessionName(), getMeasureCookieName()
	* @uses loadUserClass(), User::getMeasureSelectName()
	* @since 1.043 - 2003/02/13
	*/
	function &getMeasureSelectName() {
		parent::loadUserClass();
		return (string) $this->user->getMeasureSelectName();
	} // end function

	/**
	* Returns the name for the measure session variable
	*
	* @desc Returns the name for the measure session variable
	* @return (string) $measuresessionname
	* @access public
	* @see getMeasureSelectName(), getMeasureCookieName()
	* @uses loadUserClass(), User::getMeasureSessionName()
	* @since 1.043 - 2003/02/13
	*/
	function &getMeasureSessionName() {
		parent::loadUserClass();
		return (string) $this->user->getMeasureSessionName();
	} // end function

	/**
	* Returns the name for the measure cookie variable
	*
	* @desc Returns the name for the measure cookie variable
	* @return (string) $measurecookiename
	* @access public
	* @see getMeasureSelectName(), getMeasureSessionName()
	* @uses loadUserClass(), User::getMeasureCookieName()
	* @since 1.043 - 2003/02/13
	*/
	function &getMeasureCookieName() {
		parent::loadUserClass();
		return (string) $this->user->getMeasureCookieName();
	} // end function


	/**
	* Returns the name for the css-class attribute for form elements
	*
	* @desc Returns the name for the css-class attribute for form elements
	* @return (string) $SelectCSSClass
	* @access public
	* @see setSelectCSSClass()
	* @since 1.043 - 2003/02/13
	*/
	function &getSelectCSSClass() {
		parent::loadUserClass();
		return (string) $this->user->getSelectCSSClass();
	} // end function

	/**
	* Checks if a class is available
	*
	* @desc Checks if a class is available
	* @return (object) $user  User
	* @access private
	* @since 1.043 - 2003/02/13
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
} // end class ChooseMeasure
?>
