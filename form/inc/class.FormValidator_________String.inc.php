<?php

require_once 'class.FormBase.inc.php';


class FormValidatorString extends FormValidatorBase {
	protected $allowed_classes;
	
	public function __construct() {
//		$this->allowed_classes = array('Date');
		$this->allowed_classes = array('Text', 'Select', 'MSelect', 'Textarea', 'Hidden', 'Password', 'Button', 'Checkbox', 'Radio', 'Date');
	} // end constructor

	public function validate(FormEntryBase &$entry) {
		$classname = get_class($entry);
		$classname_prefix =  substr($classname, strlen('FormEntry'), strlen($classname));

		if (!in_array($classname_prefix, $this->allowed_classes) == TRUE) {
			trigger_error('FORMS ERROR: Validation of class "' . $classname . '" is not defined in class "' . get_class($this) . '"', E_USER_WARNING);
		  return FALSE;
		} // end if
		
		if ($entry->getArguments() == FALSE) {
			return TRUE;
		} // end if

		$passedval = FALSE;
		if (isset($_POST[$entry->getCodename() . '_1'])) {
			if (is_string($_POST[$entry->getCodename() . '_1'])) {
	      $strlength = strlen(trim($_POST[$entry->getCodename() . '_1']));
	      if ($strlength > 0) { $passedval = TRUE; }
			} elseif (is_array($_POST[$entry->getCodename() . '_1'])) {
				$size = count($_POST[$entry->getCodename() . '_1']);
	      if ($size > 0) { $passedval = TRUE; }
			} // end if
		} // end if

		if ($entry->isRequired() == FALSE && (!isset($_POST[$entry->getCodename() . '_1']) || $passedval == FALSE)) {
			return TRUE;
		} // end if

		$valmethod = 'validate' . $classname_prefix;
		return $this->$valmethod($entry);
	} // end function
	
	protected function validateText(FormEntryBase &$entry) {
		$strlength = strlen(trim($_POST[$entry->getCodename() . '_1']));
    $arguments = $entry->getArguments();
		if ($strlength < $arguments[0] || $strlength > $arguments[1]) {
			parent::$errors[$entry->getCodename()] = '„' . $entry->getName() . '“ muß zwischen ' . $arguments[0] . ' und ' . $arguments[1] . ' Zeichen lang sein.';
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


// button und hidden eigentlich nur auf str (min, max) überprüfen. eigene value val klasse machen die auf einen genauen string überprüft.
	protected function validateButton(FormEntryButton &$entry) {
		if (!isset($_POST[$entry->getCodename() . '_1'])) {
      parent::$errors[$entry->getCodename()] = 'Sie müssen die Schaltfläche „' . $entry->getName() . '“ drücken.';
			return FALSE;
		} // end if
		
		if ($_POST[$entry->getCodename() . '_1'] != $entry->getArguments()) {
			parent::$errors[$entry->getCodename()] = 'Die Schaltfläche „' . $entry->getName() . '“ muß den Wert „' . $entry->getArguments() . '“ haben.';
			return FALSE;
		} // end if
		return TRUE;
	} // end function
	
	protected function validateHidden(FormEntryHidden &$entry) {
		if (!isset($_POST[$entry->getCodename() . '_1']) || $_POST[$entry->getCodename() . '_1'] != $entry->getArguments()) {
			parent::$errors[$entry->getCodename()] = '„' . $entry->getName() . '“ muß den Wert „' . $entry->getArguments() . '“ enthalten.';
			return FALSE;
		} // end if
		return TRUE;
	} // end function
	
	protected function validateSelect(FormEntrySelect &$entry) {
		$message = '„' . $entry->getName() . '“ muß ein Wert aus der Auswahlliste sein.';
		if (!isset($_POST[$entry->getCodename() . '_1'])) {
      parent::$errors[$entry->getCodename()] = $message;
			return FALSE;
		} // end if

		$strlength = strlen(trim($_POST[$entry->getCodename() . '_1']));
		if ($strlength < 1 || !array_key_exists($_POST[$entry->getCodename() . '_1'], $entry->getArguments())) {
			parent::$errors[$entry->getCodename()] = $message;
			return FALSE;
		} // end if
		return TRUE;
	} // end function
	
	protected function validateMSelect(FormEntryMSelect &$entry) {
		$message = '„' . $entry->getName() . '“ muß Werte aus der Auswahlliste enthalten.';
		
		if (!isset($_POST[$entry->getCodename() . '_1'])) {
      parent::$errors[$entry->getCodename()] = $message;
			return FALSE;
		} // end if
		
		foreach ($_POST[$entry->getCodename() . '_1'] as $value) {
	    $strlength = strlen(trim($value));
			if ($strlength < 1 || !array_key_exists($value, $entry->getArguments())) {
        parent::$errors[$entry->getCodename()] = $message;
				return FALSE;
			} // end if
		} // end foreach
		return TRUE;
	} // end function
	// given value val klassse machen
	protected function validateCheckbox(FormEntryCheckbox &$entry) {
		$message = '„' . $entry->getName() . '“ muß Werte aus der Liste enthalten.';
		if (!isset($_POST[$entry->getCodename() . '_1'])) {
      parent::$errors[$entry->getCodename()] = $message;
			return FALSE;
		} // end if

		foreach ($_POST[$entry->getCodename() . '_1'] as $value) {
	    $strlength = strlen(trim($value));
			if ($strlength < 1 || !array_key_exists($value, $entry->getArguments())) {
        parent::$errors[$entry->getCodename()] = $message;
				return FALSE;
			} // end if
		} // end foreach
		return TRUE;
	} // end function
	
	protected function validateRadio(FormEntryRadio &$entry) {
		$message = '„' . $entry->getName() . '“ muß ein Wert aus der Liste sein.';
		if (!isset($_POST[$entry->getCodename() . '_1'])) {
      parent::$errors[$entry->getCodename()] = $message;
			return FALSE;
		} // end if

		$strlength = strlen(trim($_POST[$entry->getCodename() . '_1']));
		if ($strlength < 1 || !array_key_exists($_POST[$entry->getCodename() . '_1'], $entry->getArguments())) {
			parent::$errors[$entry->getCodename()] = $message;
			return FALSE;
		} // end if
		return TRUE;
	} // end function
// date eigentlich nur auf str (min, max) überprüfen. eigene date val klasse machen die auf einen genauen string überprüft.
	protected function validateDate(FormEntryDate &$entry) {
		$message = 'Bitte geben Sie für „' . $entry->getName() . '“ ein Datum an.';
		if (!isset($_POST[$entry->getCodename() . '_1_y']) || !isset($_POST[$entry->getCodename() . '_1_m']) || !isset($_POST[$entry->getCodename() . '_1_d'])) {
      parent::$errors[$entry->getCodename()] = $message;
			return FALSE;
		} // end if
		
		$tmp_date = $_POST[$entry->getCodename() . '_1_y'] . '-' . $_POST[$entry->getCodename() . '_1_m'] . '-' . $_POST[$entry->getCodename() . '_1_d'];
		$reg = "/([0-9]{4})-([0-9]{1,2})-([0-9]{1,2})/";
		if (!preg_match($reg, $tmp_date)) {
      parent::$errors[$entry->getCodename()] = $message;
			return FALSE;
		} // end if

		return TRUE;
	} // end function
} // end class
?>


		} elseif (count($arguments) == 2 && $this->is_date($arguments[0]) && $this->is_date($arguments[1])) {
			//$start_ts = strtotime($arguments[0]);
			//$end_ts = strtotime($arguments[1]);
//hier schleife  array erzeugen

	protected function is_date($string) {
		$reg = "/([0-9]{4})-([0-9]{1,2})-([0-9]{1,2})/";
		if (!preg_match($reg, $string)) {
			return FALSE;
		} // end if
		return TRUE;
	} // end function
