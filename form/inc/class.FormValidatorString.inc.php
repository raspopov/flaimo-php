<?php
/**
* load base class which takes care of all the other includes via it's autoload function
*/
require_once 'class.FormBase.inc.php';

/**
* class that validates strings
* @author Michael Wimmer <flaimo@gmx.net>
* @category FLP
* @copyright Copyright © 2002-2006, Michael Wimmer
* @license Free for non-commercial use
* @link http://flp.sf.net/
* @package Form
* @version 1.0
*/
class FormValidatorString extends FormValidatorBase {
	/**
	* constrcutor. sets the allowed entry-types
	*
	* @return void
	*/
	public function __construct() {
		$this->allowed_classes = array('Text', 'Select', 'MSelect', 'Textarea', 'Hidden', 'Password', 'Button', 'Checkbox', 'Radio', 'Date');
	} // end constructor

	/**#@+
	* validates an entry for the given type (text, radio, select, ...)
	*
	* @param object $entry
	* @return boolean
	*/
	protected function validateText(FormEntryBase &$entry) {
    $arguments = $entry->getArguments();
		if (!isset($_POST[$entry->getCodename() . '_1'])) {
      $this->setErrorMessage($entry->getCodename(), $entry->getName(), $arguments[0], $arguments[1]);
			return FALSE;
		} // end if
		
		$strlength = strlen(trim($_POST[$entry->getCodename() . '_1']));
		if ($strlength < $arguments[0] || $strlength > $arguments[1]) {
      $this->setErrorMessage($entry->getCodename(), $entry->getName(), $arguments[0], $arguments[1]);
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
    $arguments = $entry->getArguments();
		if (!isset($_POST[$entry->getCodename() . '_1'])) {
			$this->setErrorMessage($entry->getCodename(), $entry->getName(), $arguments[0], $arguments[1]);
			return FALSE;
		} // end if
		
		foreach ($_POST[$entry->getCodename() . '_1'] as $value) {
	    $strlength = strlen(trim($value));
			if ($strlength < $arguments[0] || $strlength > $arguments[1]) {
        $this->setErrorMessage($entry->getCodename(), $entry->getName(), $arguments[0], $arguments[1]);
				return FALSE;
			} // end if
		} // end foreach
		return TRUE;
	} // end function

	protected function validateCheckbox(FormEntryCheckbox &$entry) {
		return $this->validateMSelect($entry);
	} // end function

	protected function validateDate(FormEntryDate &$entry) {
    $arguments = $entry->getArguments();
		if (!isset($_POST[$entry->getCodename() . '_1_y']) || !isset($_POST[$entry->getCodename() . '_1_m']) || !isset($_POST[$entry->getCodename() . '_1_d'])) {
			$this->setErrorMessage($entry->getCodename(), $entry->getName(), $arguments[0], $arguments[1]);
			return FALSE;
		} // end if
		
		$tmp_date = $_POST[$entry->getCodename() . '_1_y'] . '-' . $_POST[$entry->getCodename() . '_1_m'] . '-' . $_POST[$entry->getCodename() . '_1_d'];
		$strlength = strlen(trim($tmp_date));

		if ($strlength < $arguments[0] || $strlength > $arguments[1]) {
			$this->setErrorMessage($entry->getCodename(), $entry->getName(), $arguments[0], $arguments[1]);
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
	* @param int $min maximal length of string in characters/bytes
	* @param int $max maximal length of string in characters/bytes
	* @return void
	*/
	protected function setErrorMessage($code = '', $name = '', $min = 0, $max = 99999) {
		if (isset($min) && isset($max) && $min >= 0 && $max < 10000) {
			parent::$errors[$code] = '„' . $name . '“ muß zwischen ' . $min . ' und ' . $max . ' Zeichen lang sein.';
		} else {
			parent::$errors[$code] = 'Bitte geben Sie einen Wert für „' . $name . '“ ein.';
		} // end if
	} // end function
} // end class
?>
