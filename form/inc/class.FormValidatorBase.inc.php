<?php
/**
* load base class which takes care of all the other includes via it's autoload function
*/
require_once 'class.FormBase.inc.php';

/**
* abstract base class for a validator class
* @author Michael Wimmer <flaimo@gmx.net>
* @category FLP
* @copyright Copyright © 2002-2006, Michael Wimmer
* @license Free for non-commercial use
* @link http://flp.sf.net/
* @package Form
* @version 1.0
*/
abstract class FormValidatorBase extends FormBase {
	/**
	* @var array $allowed_classes list of allowed entry types that can use this validator
	*/
	protected $allowed_classes;

	/**
	* validates the userinput for a given entry object
	*
	* @param object $entry
	* @return boolean
	*/
	public function validate(FormEntryBase &$entry) {
		$classname = get_class($entry);
		$classname_prefix =  substr($classname, strlen('FormEntry'), strlen($classname));

		if (!in_array($classname_prefix, $this->allowed_classes) == TRUE) {
			trigger_error('FORMS ERROR: Validation of class "' . $classname . '" is not defined in class "' . get_class($this) . '"', E_USER_WARNING);
		  return FALSE;
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
		} elseif (isset($_FILES[$entry->getCodename() . '_1']) && isset($_POST[$entry->getCodename() . '_1']['name']) && strlen(trim($_POST[$entry->getCodename() . '_1']['name'])) > 0) {
		  	$passedval = TRUE;
		} elseif (isset($_POST[$entry->getCodename() . '_1_y']) || isset($_POST[$entry->getCodename() . '_1_m']) || isset($_POST[$entry->getCodename() . '_1_d'])) {
		  	$passedval = TRUE;
		} // end if

		if ($entry->isRequired() == FALSE && $passedval == FALSE) {
			return TRUE;
		} // end if

		$valmethod = 'validate' . $classname_prefix;
		return $this->$valmethod($entry);
	} // end function
} // end class
?>
