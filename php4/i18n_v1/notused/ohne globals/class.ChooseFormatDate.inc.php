<?php
/**
* @package i18n
*/
/**
* base class
*/
@include_once('class.FormatDate.inc.php');

/**
* Creates a dropdown menu for selecting the time format
*
* Tested with WAMP (XP-SP1/1.3.24/4.0.4/4.3.0)
* Last change: 2003-02-26
*
* @desc Creates a dropdown menu for selecting the time format
* @access public
* @author Michael Wimmer <flaimo@gmx.net>
* @copyright Michael Wimmer
* @link http://www.flaimo.com/
* @see FormatDate
* @package i18n
* @version 1.051
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
	* @return (void)
	* @access private
	* @uses FormatDate::FormatDate(), checkClass()
	* @since 1.000 - 2002/10/10
	*/
	function ChooseFormatDate($language = '') {
		$this->checkClass('FormatDate', __LINE__);
		parent::FormatDate($language);
	} // end function

	/*-------------------*/
	/* F U N C T I O N S */
	/*-------------------*/

	/**
	* Creates a dropdown menu for selecting the time format
	*
	* @desc Creates a dropdown menu for selecting the time format
	* @return (string) $output_dropdown  + <select> tag with possible time formats
	* @access public
	* @uses getTimeSelectName(), getSelectCSSClass(), getPossibleDisplayTimes(), FormatDate::getTimeset()
	* @since 1.000 - 2002/10/10
	*/
	function &returnDropdownSelecttime() {
		$output_dropdown = (string) '<select name="' . $this->getTimeSelectName() . '" id="' . $this->getTimeSelectName() . '" class="' . $this->getSelectCSSClass() . '">';
		foreach (parent::getPossibleDisplayTimes() as $list_times => $list_times_names) {
			$timeselect 		 = (string) (($list_times === parent::getTimeset()) ? ' selected="selected"' : '');
			$output_dropdown 	.= (string) '<option value="' . $list_times . '"' . $timeselect . '>' . $list_times_names . '</option>';
		} // end foreach
		unset($timeselect);
		return (string) $output_dropdown . '</select>';
	} // end function

	/**
	* Returns the name for the time <select> tag
	*
	* @desc Returns the name for the time <select> tag
	* @return (string) $timeselectname
	* @access public
	* @see getTimeSelectSessionName(), getTimeSelectCookieName()
	* @uses loadUserClass(), User::getTimeSelectName()
	* @since 1.043 - 2003/02/13
	*/
	function &getTimeSelectName() {
		parent::loadUserClass();
		return (string) $this->user->getTimeSelectName();
	} // end function

	/**
	* Returns the name for the timeselect session variable
	*
	* @desc Returns the name for the timeselect session variable
	* @return (string) $timeselectsessionname
	* @access public
	* @see getTimeSelectName(), getTimeSelectCookieName()
	* @uses loadUserClass(), User::getTimeSelectSessionName()
	* @since 1.043 - 2003/02/13
	*/
	function &getTimeSelectSessionName() {
		parent::loadUserClass();
		return (string) $this->user->getTimeSelectSessionName();
	} // end function

	/**
	* Returns the name for the timeselect cookie variable
	*
	* @desc Returns the name for the timeselect cookie variable
	* @return (string) $timeselectcookiename
	* @access public
	* @see getTimeSelectName(), getTimeSelectSessionName()
	* @uses loadUserClass(), User::getTimeSelectCookieName()
	* @since 1.043 - 2003/02/13
	*/
	function &getTimeSelectCookieName() {
		parent::loadUserClass();
		return (string) $this->user->getTimeSelectCookieName();
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
} // end class ChooseFormatDate
?>
