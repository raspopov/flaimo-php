<?php
/**
* load base class which takes care of all the other includes via it's autoload function
*/
require_once 'class.FormBase.inc.php';

/**
* creates a date-entry
* @author Michael Wimmer <flaimo@gmx.net>
* @category FLP
* @copyright Copyright © 2002-2006, Michael Wimmer
* @license Free for non-commercial use
* @link http://flp.sf.net/
* @package Form
* @version 1.0
*/
class FormEntryDate extends FormEntryBase {
	/**
	* constructor
	*
	* @param string $name_name of the form entry
	* @param string $description description of the form entry
	* @param int $required whether the user has to fill out the entry or not
	* @param object $validator who should the input of this entry be validated (see FV_* constants in the FormBase file)
	* @param mixed $default_value default value(s) which should be selected when the form ist rendered for the first time
	* @param mixed $range additional values for rendering the entry
	* @param mixed $arguments arguments for the validator
	* @param string $session_name
	* @return void
	*/
	function __construct($id = '', $name = '', $description = '', $required = 0, FormValidatorBase &$val, $default_value = '', $range = '', $arguments = '', $session_name = '') {
		parent::__construct($id, $name, $description, $required, $val, $session_name);
		$this->setDefaultValue($default_value);
		$this->setArguments($arguments);
		$this->setRange($range);
	} // end constructor

	/**
	* sets the default value
	*
	* @return boolean
	*/
	protected function setDefaultValue($string = '') {
		$this->default_value = '0000-00-00';
		$ts = explode('-', $string);
		if (count($ts) == 3) {
			$valid = checkdate($ts[1], $ts[2], $ts[0]);
			if ($valid == TRUE) {
				$this->default_value = $string;
			} // end if
		} // end if
		return TRUE;
	} // end function

	/**
	* sets the range class var
	*
	* @return boolean
	*/
	protected function setRange($range = array('1901-01-01', '2038-12-31')) {
    $this->range = array('1901-01-01', '2999-12-31');
		if (!is_array($range) || count($range) != 2) {
      return FALSE;
		} // end if

		$start = explode('-', $range[0]);
		$end = explode('-', $range[1]);

		if (checkdate($start[1], $start[2], $start[0]) == TRUE) {
      $this->range[0] = $range[0];
		} // end if

		if (checkdate($end[1], $end[2], $end[0]) == TRUE) {
      $this->range[1] = $range[1];
		} // end if
		return TRUE;
	} // end function

	/**
	* sets the value class var. if userdata is subbmited, validates it
	*
	* @return boolean
	*/
	public function setValue() {
		$this->value = $this->default_value;
		if (isset($_POST['form_' . $this->session_name])) {
			$this->value = '';
			if ($this->validate() == TRUE) {
			  if (isset($_POST[$this->codename . '_1_y']) && isset($_POST[$this->codename . '_1_m']) && isset($_POST[$this->codename . '_1_d'])) {
					$this->value = $_POST[$this->codename . '_1_y'] . '-' . $_POST[$this->codename . '_1_m'] . '-' . $_POST[$this->codename . '_1_d'];
				} // end if
			} // end if
		} // end if
		return TRUE;
	} // end function
} // end class
?>
