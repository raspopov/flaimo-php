<?php
/**
* load base class which takes care of all the other includes via it's autoload function
*/
require_once 'class.FormBase.inc.php';

/**
* creates a file-entry
* @author Michael Wimmer <flaimo@gmx.net>
* @category FLP
* @copyright Copyright © 2002-2006, Michael Wimmer
* @license Free for non-commercial use
* @link http://flp.sf.net/
* @package Form
* @version 1.0
*/
class FormEntryFile extends FormEntryBase {
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
		$this->default_value = $string;
		if(strlen(trim($string)) < 1) {
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
    $this->range = false;
    if (is_array($range) == TRUE && count($range) == 2) {
      $this->range[0] = (int) $range[0];
      $this->range[1] = (string) $range[1];
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
				$this->value = (isset($_FILES[$this->codename . '_1'])) ? $_FILES[$this->codename . '_1']['tmp_name'] : '';
			} // end if
		} // end if
		return TRUE;
	} // end function
} // end class
?>
