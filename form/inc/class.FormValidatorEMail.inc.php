<?php
/**
* load base class which takes care of all the other includes via it's autoload function
*/
require_once 'class.FormBase.inc.php';

/**
* class that validates email addresses
* @author Michael Wimmer <flaimo@gmx.net>
* @category FLP
* @copyright Copyright © 2002-2006, Michael Wimmer
* @license Free for non-commercial use
* @link http://flp.sf.net/
* @package Form
* @version 1.0
*/
class FormValidatorEMail extends FormValidatorString {
	/**
	* constrcutor. sets the allowed entry-types
	*
	* @return void
	*/
	public function __construct() {
		$this->allowed_classes = array('Text', 'Select', 'MSelect', 'Textarea', 'Hidden', 'Password', 'Button', 'Checkbox', 'Radio');
	} // end constructor

	/**
	* checks if a string is a valid email address
	*
	* @param string $address
	* @return boolean
	*/
	protected static function isValidEMail($address = '') {
		$pattern  = '/^[a-z0-9!#$%&*+-=?^_`{|}~]+(\.[a-z0-9!#$%&*+-=?^_`{|}~]+)*';
		$pattern .= '@([-a-z0-9]+\.)+([a-z]{2,3}';
		$pattern .= '|info|arpa|aero|coop|name|museum)$/ix';
  	return preg_match($pattern, $address);
	} // end function

	/**#@+
	* validates an entry for the given type (text, radio, select, ...)
	*
	* @param object $entry
	* @return boolean
	*/
	protected function validateText(FormEntryBase &$entry) {
		$arguments = $entry->getArguments();
		if (parent::validateText($entry) == FALSE || $this->isValidEMail($_POST[$entry->getCodename() . '_1']) == FALSE) {
			$this->setErrorMessage($entry->getCodename(), $entry->getName(), $arguments[0], $arguments[1]);
			return FALSE;
		} // end if
		return TRUE;
	} // end function
	
	protected function validateMSelect(FormEntryBase &$entry) {
    $arguments = $entry->getArguments();
		if (!isset($_POST[$entry->getCodename() . '_1'])) {
			$this->setErrorMessage($entry->getCodename(), $entry->getName(), $arguments[0], $arguments[1]);
			return FALSE;
		} // end if
		
		foreach ($_POST[$entry->getCodename() . '_1'] as $value) {
	    $strlength = strlen(trim($value));
			if ($strlength < $arguments[0] || $strlength > $arguments[1] || $this->isValidEMail($value) == FALSE) {
        $this->setErrorMessage($entry->getCodename(), $entry->getName(), $arguments[0], $arguments[1]);
				return FALSE;
			} // end if
		} // end foreach
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
			parent::$errors[$code] = 'Bitte geben Sie für „' . $name . '“ eine gültige E-Mail-Adresse ein die zwischen ' . $min . ' und ' . $max . ' Zeichen lang ist.';
		} else {
			parent::$errors[$code] = 'Bitte geben Sie für „' . $name . '“ eine gültige E-Mail-Adresse ein.';
		} // end if
	} // end function
} // end class
?>
