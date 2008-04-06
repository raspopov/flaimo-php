<?php
/**
* load base class which takes care of all the other includes via it's autoload function
*/
require_once 'class.FormBase.inc.php';

/**
* class that validates dates
* @author Michael Wimmer <flaimo@gmx.net>
* @category FLP
* @copyright Copyright © 2002-2006, Michael Wimmer
* @license Free for non-commercial use
* @link http://flp.sf.net/
* @package Form
* @version 1.0
*/
class FormValidatorDate extends FormValidatorBase {

	/**
	* constrcutor. sets the allowed entry-types
	*
	* @return void
	*/
	public function __construct() {
		$this->allowed_classes = array('Text', 'Select', 'MSelect', 'Textarea', 'Hidden', 'Password', 'Button', 'Checkbox', 'Radio', 'Date');
	} // end constructor

	/**
	* checks if a string is a valid iso-date
	*
	* @param string $isodate
	* @return boolean
	*/
	protected static function isValidDate($isodate = '') {
		$reg = "/([0-9]{4})-([0-9]{1,2})-([0-9]{1,2})/";
		if (!preg_match($reg, $isodate)) {
			return FALSE;
		} // end if
		$splitted_date = explode('-', $isodate);
		if (checkdate($splitted_date[1], $splitted_date[2], $splitted_date[0]) == FALSE) {
			return FALSE;
		} // end if
		return TRUE;
	} // end function

	/**
	* checks if a string is a valid iso-date and between $from and $until
	*
	* @param string $isodate
	* @param string $from
	* @param string $until
	* @return boolean
	*/
	protected function isBetweenDates($isodate = '', $from = '', $until = '') {
		if ($this->isValidDate($from) == FALSE || $this->isValidDate($until) == FALSE || $this->isValidDate($isodate) == FALSE) {
		  return FALSE;
		} // end if
		$split_date = explode('-', $isodate);
		$split_from = explode('-', $from);
		$split_until = explode('-', $until);

		if ($split_date[0] < $split_from[0]) {
			return FALSE;
		} elseif ($split_date[0] == $split_from[0] && $split_date[1] < $split_from[1]) {
		  return FALSE;
		} elseif ($split_date[0] == $split_from[0] && $split_date[1] == $split_from[1] && $split_date[2] < $split_from[2]) {
		  return FALSE;
		}	// end if

		if ($split_date[0] > $split_until[0]) {
			return FALSE;
		} elseif ($split_date[0] == $split_until[0] && $split_date[1] > $split_until[1]) {
		  return FALSE;
		} elseif ($split_date[0] == $split_until[0] && $split_date[1] == $split_until[1] && $split_date[2] > $split_until[2]) {
		  return FALSE;
		}	// end if
		return TRUE;
	} // end function

	/**
	* creates an internal arguments array from the arguments given by the entry
	*
	* @param array $arguments
	* @return array
	*/
	protected function getArguments($arguments = array('', '')) {
		$args = array('','');
		if (is_array($arguments) && $this->isValidDate($arguments[0]) == TRUE) {
      $args[0] = $arguments[0];
		} // end if
    if (is_array($arguments) || $this->isValidDate($arguments[1]) == TRUE) {
      $args[1] = $arguments[1];
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
    $arguments = $this->getArguments($entry->getArguments());

		if (!isset($_POST[$entry->getCodename() . '_1'])) {
      $this->setErrorMessage($entry->getCodename(), $entry->getName(), $arguments[0], $arguments[1]);
			return FALSE;
		} // end if
		
		if ($arguments[0] == '' && $arguments[1] == '') {
			if ($this->isValidDate($_POST[$entry->getCodename() . '_1']) == FALSE) {
	      $this->setErrorMessage($entry->getCodename(), $entry->getName(), $arguments[0], $arguments[1]);
				return FALSE;
			} // end if
			return TRUE;
		} elseif ($arguments[0] == '') {
			if ($this->isBetweenDates($_POST[$entry->getCodename() . '_1'], '0001-01-01', $arguments[1]) == FALSE) {
	      $this->setErrorMessage($entry->getCodename(), $entry->getName(), $arguments[0], $arguments[1]);
				return FALSE;
			} // end if
			return TRUE;
		} elseif ($arguments[1] == '') {
			if ($this->isBetweenDates($_POST[$entry->getCodename() . '_1'], $arguments[0], '32767-12-31') == FALSE) {
	      $this->setErrorMessage($entry->getCodename(), $entry->getName(), $arguments[0], $arguments[1]);
				return FALSE;
			} // end if
			return TRUE;
		} else {
			if ($this->isBetweenDates($_POST[$entry->getCodename() . '_1'], $arguments[0], $arguments[1]) == FALSE) {
	      $this->setErrorMessage($entry->getCodename(), $entry->getName(), $arguments[0], $arguments[1]);
				return FALSE;
			} // end if
			return TRUE;
		}// end if
		return FALSE;
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
    $arguments = $this->getArguments($entry->getArguments());
    
		if (!isset($_POST[$entry->getCodename() . '_1'])) {
      $this->setErrorMessage($entry->getCodename(), $entry->getName(), $arguments[0], $arguments[1]);
			return FALSE;
		} // end if
		
		if ($arguments[0] == '' && $arguments[1] == '') {
			foreach ($_POST[$entry->getCodename() . '_1'] as $value) {
				if ($this->isValidDate($value) == FALSE) {
		      $this->setErrorMessage($entry->getCodename(), $entry->getName(), $arguments[0], $arguments[1]);
					return FALSE;
				} // end if
			} // end foreach
			return TRUE;
		} elseif ($arguments[0] == '') {
			foreach ($_POST[$entry->getCodename() . '_1'] as $value) {
				if ($this->isBetweenDates($value, '0001-01-01', $arguments[1]) == FALSE) {
		      $this->setErrorMessage($entry->getCodename(), $entry->getName(), $arguments[0], $arguments[1]);
					return FALSE;
				} // end if
			} // end foreach
			return TRUE;
		} elseif ($arguments[1] == '') {
			foreach ($_POST[$entry->getCodename() . '_1'] as $value) {
				if ($this->isBetweenDates($value, $arguments[0], '32767-12-31') == FALSE) {
		      $this->setErrorMessage($entry->getCodename(), $entry->getName(), $arguments[0], $arguments[1]);
					return FALSE;
				} // end if
			} // end foreach
			return TRUE;
		} else {
			foreach ($_POST[$entry->getCodename() . '_1'] as $value) {
				if ($this->isBetweenDates($value, $arguments[0], $arguments[1]) == FALSE) {
		      $this->setErrorMessage($entry->getCodename(), $entry->getName(), $arguments[0], $arguments[1]);
					return FALSE;
				} // end if
			} // end foreach
			return TRUE;
		}// end if
		return FALSE;
	} // end function

	protected function validateCheckbox(FormEntryCheckbox &$entry) {
		return $this->validateMSelect($entry);
	} // end function

	protected function validateDate(FormEntryDate &$entry) {

    $arguments = $this->getArguments($entry->getArguments());

		if (!isset($_POST[$entry->getCodename() . '_1_y']) || !isset($_POST[$entry->getCodename() . '_1_m']) || !isset($_POST[$entry->getCodename() . '_1_d'])) {
			$this->setErrorMessage($entry->getCodename(), $entry->getName(), $arguments[0], $arguments[1]);
			return FALSE;
		} // end if
		
		$tmp_date = $_POST[$entry->getCodename() . '_1_y'] . '-' . $_POST[$entry->getCodename() . '_1_m'] . '-' . $_POST[$entry->getCodename() . '_1_d'];

		if ($arguments[0] == '' && $arguments[1] == '') {
			if ($this->isValidDate($tmp_date) == FALSE) {
	      $this->setErrorMessage($entry->getCodename(), $entry->getName(), $arguments[0], $arguments[1]);
				return FALSE;
			} // end if
			return TRUE;
		} elseif ($arguments[0] == '') {
			if ($this->isBetweenDates($tmp_date, '0001-01-01', $arguments[1]) == FALSE) {
	      $this->setErrorMessage($entry->getCodename(), $entry->getName(), $arguments[0], $arguments[1]);
				return FALSE;
			} // end if
			return TRUE;
		} elseif ($arguments[1] == '') {
			if ($this->isBetweenDates($tmp_date, $arguments[0], '32767-12-31') == FALSE) {
	      $this->setErrorMessage($entry->getCodename(), $entry->getName(), $arguments[0], $arguments[1]);
				return FALSE;
			} // end if
			return TRUE;
		} else {
			if ($this->isBetweenDates($tmp_date, $arguments[0], $arguments[1]) == FALSE) {
	      $this->setErrorMessage($entry->getCodename(), $entry->getName(), $arguments[0], $arguments[1]);
				return FALSE;
			} // end if
			return TRUE;
		}// end if
		return FALSE;
	} // end function
	/**#@-*/

	/**
	* adds an error message to the static error class var
	*
	* @param string $code codename of the entry
	* @param string $name name of the entry
	* @param string $from iso-date
	* @param string $until iso-date
	* @return void
	*/
	protected function setErrorMessage($code = '', $name = '', $from = '', $until = '') {
		if ($from == '' && $until == '') {
			parent::$errors[$code] = '„' . $name . '“ muß ein gültiges Datum sein.';
		} elseif ($from == '') {
			parent::$errors[$code] = '„' . $name . '“ muß ein gültiges Datum sein welches vor ' . $until . ' liegt.';
		} elseif ($until == '') {
			parent::$errors[$code] = '„' . $name . '“ muß ein gültiges Datum sein welches nach ' . $from . ' liegt.';
		} else {
			parent::$errors[$code] = '„' . $name . '“ muß ein gültiges Datum zwischen ' . $from . ' und ' . $until . ' sein';
		} // end if
	} // end function
} // end class
?>
