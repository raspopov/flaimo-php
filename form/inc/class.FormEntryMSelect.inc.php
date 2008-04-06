<?php
/**
* load base class which takes care of all the other includes via it's autoload function
*/
require_once 'class.FormBase.inc.php';

/**
* creates a multiselect-entry
* @author Michael Wimmer <flaimo@gmx.net>
* @category FLP
* @copyright Copyright © 2002-2006, Michael Wimmer
* @license Free for non-commercial use
* @link http://flp.sf.net/
* @package Form
* @version 1.0
*/
class FormEntryMSelect extends FormEntryBase {
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
	protected function setDefaultValue($value = '') {
		$this->default_value = $value;
		if (!is_array($value)) {
			$this->default_value = FALSE;
		} // end if
		return TRUE;
	} // end function

	/**
	* sets the range class var
	*
	* @return boolean
	*/
	protected function setRange($range = '') {
		$this->range = FALSE;
		if (is_array($range)) {
		// dont show offset notice if array contains exactly 2 optgroups
			if (count($range) == 2 && @is_int($range[0]) && @is_int($range[1]) && $range[1] > $range[0]) {
        $this->range = array();
				for ($i = $range[0]; $i <= $range[1]; $i++) {
					$this->range[$i] = $i;
				} // end for
			} elseif (count($range) > 0) {
        $this->range = $range;
			} // end if
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
		if (isset($_POST['form_' . $this->session_name])) { // methode auslesen
			$this->value = array();
			if ($this->validate() == TRUE) {
				$this->value = (isset($_POST[$this->codename . '_1'])) ? $_POST[$this->codename . '_1'] : array();
			} // end if
		} // end if
		return TRUE;
	} // end function
} // end class
?>
