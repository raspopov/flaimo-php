<?php
/**
* load base class which takes care of all the other includes via it's autoload function
*/
require_once 'class.FormBase.inc.php';

/**
* class that validates that an entry is from a list of values. returns a special message usable as confirmation dialogs
* @author Michael Wimmer <flaimo@gmx.net>
* @category FLP
* @copyright Copyright © 2002-2006, Michael Wimmer
* @license Free for non-commercial use
* @link http://flp.sf.net/
* @package Form
* @version 1.0
*/
class FormValidatorValueConfirmation extends FormValidatorValueList {
	/**
	* constrcutor. sets the allowed entry-types
	*
	* @return void
	*/
	public function __construct() {
		$this->allowed_classes = array('Text', 'Textarea', 'Button', 'Checkbox');
	} // end constructor

	/**#@+
	* validates an entry for the given type (text, radio, select, ...)
	*
	* @param object $entry
	* @return boolean
	*/
	protected function validateText(FormEntryBase &$entry) {
		if (!isset($_POST[$entry->getCodename() . '_1']) || $entry->getArguments() == FALSE || $entry->getArguments() != $_POST[$entry->getCodename() . '_1']) {
			$this->setErrorMessage($entry->getCodename(), $entry->getName(), $entry->getArguments());
			return FALSE;
		} // end if
		return TRUE;
	} // end function
	
	protected function validateTextarea(FormEntryTextarea &$entry) {
		return $this->validateText($entry);
	} // end function

	protected function validateButton(FormEntryButton &$entry) {
		return $this->validateText($entry);
	} // end function
	
	protected function validateCheckbox(FormEntryCheckbox &$entry) {
		if (!isset($_POST[$entry->getCodename() . '_1'][1]) || $entry->getArguments() == FALSE || $entry->getArguments() != $_POST[$entry->getCodename() . '_1'][1]) {
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
	* @return void
	*/
	protected function setErrorMessage($code = '', $name = '', $arguments = array()) {
		parent::$errors[$code] = 'Bitte bestätigen Sie ihre Eingaben unter „' . $name . '“.';
	} // end function
} // end class
?>
