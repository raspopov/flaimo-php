<?php
/**
* load base class which takes care of all the other includes via it's autoload function
*/
require_once 'class.FormBase.inc.php';

/**
* class that validates integer numbers
* @author Michael Wimmer <flaimo@gmx.net>
* @category FLP
* @copyright Copyright © 2002-2006, Michael Wimmer
* @license Free for non-commercial use
* @link http://flp.sf.net/
* @package Form
* @version 1.0
*/
class FormValidatorInteger extends FormValidatorBase {
	/**
	* constrcutor. sets the allowed entry-types
	*
	* @return void
	*/
	public function __construct() {
		$this->allowed_classes = array('Text', 'Select', 'MSelect', 'Textarea', 'Hidden', 'Password', 'Button', 'Checkbox', 'Radio');
	} // end constructor

	/**
	* checks if a string is an integer number
	*
	* @return boolean
	*/
	protected static function isNumber($string = '') {
		return ((string) $string === (string) (int) $string);
	} // end function

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
		$is_number = ((string) $_POST[$entry->getCodename() . '_1'] === (string) (int) $_POST[$entry->getCodename() . '_1']);
		
		if ($strlength < $arguments[0] || $strlength > $arguments[1] || $this->isNumber($_POST[$entry->getCodename() . '_1']) == FALSE) {
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
			if ($strlength < $arguments[0] || $strlength > $arguments[1] || $this->isNumber($value) == FALSE) {
        $this->setErrorMessage($entry->getCodename(), $entry->getName(), $arguments[0], $arguments[1]);
				return FALSE;
			} // end if
		} // end foreach
		return TRUE;
	} // end function

	protected function validateCheckbox(FormEntryCheckbox &$entry) {
		return $this->validateMSelect($entry);
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
		if ($min == $max) {
      parent::$errors[$code] = '„' . $name . '“ muß eine ' . $max . '-stellige Zahl sein.';
		} else {
			parent::$errors[$code] = '„' . $name . '“ muß eine ' . $min . '- bis ' . $max . '-stellige Zahl sein.';
		} // end if
	} // end function
} // end class
?>
