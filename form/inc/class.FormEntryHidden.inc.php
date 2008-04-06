<?php
/**
* load base class which takes care of all the other includes via it's autoload function
*/
require_once 'class.FormBase.inc.php';

/**
* creates a hidden-entry
* @author Michael Wimmer <flaimo@gmx.net>
* @category FLP
* @copyright Copyright © 2002-2006, Michael Wimmer
* @license Free for non-commercial use
* @link http://flp.sf.net/
* @package Form
* @version 1.0
*/
class FormEntryHidden extends FormEntryBase {
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
		return TRUE;
	} // end function

	/**
	* not required for this type of entry
	*
	* @return boolean
	*/
	protected function setRange($range = '') {
		$this->range = $range;
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
			$this->value = '';
			if ($this->validate() == TRUE) {
				$this->value = (isset($_POST[$this->codename . '_1'])) ? $_POST[$this->codename . '_1'] : '';
			} // end if
		} // end if
		return TRUE;
	} // end function
} // end class
?>
