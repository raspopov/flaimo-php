<?php
/**
* load base class which takes care of all the other includes via it's autoload function
*/
require_once 'class.FormBase.inc.php';

/**
* class that validates that an entry is from a list of values.
* @author Michael Wimmer <flaimo@gmx.net>
* @category FLP
* @copyright Copyright © 2002-2006, Michael Wimmer
* @license Free for non-commercial use
* @link http://flp.sf.net/
* @package Form
* @version 1.0
*/
class FormValidatorValueList extends FormValidatorBase {
	/**
	* constrcutor. sets the allowed entry-types
	*
	* @return void
	*/
	public function __construct() {
		$this->allowed_classes = array('Text', 'Select', 'MSelect', 'Textarea', 'Hidden', 'Password', 'Button', 'Checkbox', 'Radio', 'Date');
	} // end constructor

	/**
	* takes the arguments from the entry and transforms them for internal use
	*
	* @param array $arguments
	* @return array
	*/
	protected function getArguments($arguments = array()) {
		$args = $arguments;
		if (count($arguments) == 2 && array_key_exists(0, $arguments) && array_key_exists(1, $arguments) && is_int($arguments[0]) && is_int($arguments[1])) {
      $args = array();
			for ($i = $arguments[0]; $i <= $arguments[1]; $i++) {
        $args[$i] = $i;
			} // end for
		} // end if
		return $args;
	} // end function

	/**#@+
	* validates an entry for the given type (text, radio, select, ...)
	*
	* @param object $entry
	* @return boolean
	*/
	protected function validateText(FormEntryBase &$entry) {
		if (!isset($_POST[$entry->getCodename() . '_1']) || !array_key_exists($_POST[$entry->getCodename() . '_1'], $this->getArguments($entry->getArguments()))) {
			$this->setErrorMessage($entry->getCodename(), $entry->getName(), $entry->getArguments());
			return FALSE;
		} // end if
		return TRUE;
	} // end function
	
	protected function validatePassword(FormEntryPassword &$entry) {
		return $this->validateText($entry);
	} // end function

	protected function validateTextarea(FormEntryTextarea &$entry) {
		return $this->validateText($entry);
	} // end function

	protected function validateButton(FormEntryButton &$entry) {
		return $this->validateText($entry);
	} // end function
	
	protected function validateHidden(FormEntryHidden &$entry) {
		return $this->validateText($entry);
	} // end function

	protected function validateSelect(FormEntrySelect &$entry) {
		return $this->validateText($entry);
	} // end function

	protected function validateRadio(FormEntryRadio &$entry) {
		return $this->validateText($entry);
	} // end function

	protected function validateMSelect(FormEntryBase &$entry) {
		if (!isset($_POST[$entry->getCodename() . '_1'])) {
      $this->setErrorMessage($entry->getCodename(), $entry->getName(), $entry->getArguments());
			return FALSE;
		} // end if

		$args = $this->getArguments($entry->getArguments());
		foreach ($_POST[$entry->getCodename() . '_1'] as $value) {
			if (!array_key_exists($value, $args)) {
        $this->setErrorMessage($entry->getCodename(), $entry->getName(), $entry->getArguments());
				return FALSE;
			} // end if
		} // end foreach
		return TRUE;
	} // end function

	protected function validateCheckbox(FormEntryCheckbox &$entry) {
		return $this->validateMSelect($entry);
	} // end function

	protected function validateDate(FormEntryDate &$entry) {
		if (!isset($_POST[$entry->getCodename() . '_1_y']) || !isset($_POST[$entry->getCodename() . '_1_m']) || !isset($_POST[$entry->getCodename() . '_1_d'])) {
      $this->setErrorMessage($entry->getCodename(), $entry->getName(), $entry->getArguments());
			return FALSE;
		} // end if

		$tmp_date = $_POST[$entry->getCodename() . '_1_y'] . '-' . $_POST[$entry->getCodename() . '_1_m'] . '-' . $_POST[$entry->getCodename() . '_1_d'];
		if (!array_key_exists($tmp_date, $entry->getArguments())) {
      $this->setErrorMessage($entry->getCodename(), $entry->getName(), $entry->getArguments());
			return FALSE;
		} // end if
		return TRUE;
	} // end function
	/**#@-*/

	/**
	* adds an error message to the static error class var
	*
	* @param string $code codename of the entry
	* @param string $name name of the entry
	* @param array $arguments
	* @return boolean
	*/
	protected function setErrorMessage($code = '', $name = '', $arguments = array()) {
		$message = '„' . $name . '“ darf nur Werte aus der Liste haben.';
		if (count($arguments) == 2 && array_key_exists(0, $arguments) && array_key_exists(1, $arguments) && is_int($arguments[0]) && is_int($arguments[1])) {
			$message = '„' . $name . '“ muß zwischen: ' . $arguments[0] . ' und ' . $arguments[1] . ' liegen.';
		} elseif (count($arguments) < 11) {
			$message = '„' . $name . '“ darf nur folgende Werte haben: „' . implode('“, „', $arguments) . '“.';
		} // end if
		parent::$errors[$code] = $message;
	} // end function
} // end class
?>
