<?php
/**
* function for automatic including of required classes
*/
function __autoload($class){
    require_once( 'class.' . $class . '.inc.php');
} // end function

/**
* factory method for returning a reference to a requested method
*/
function &getValidator($type = 0) {
	static $validators;
	if (!isset($validators[$type])) {
		$class = 'FormValidator' . $type;
		$validators[$type] = new $class;
	} // end if
	return $validators[$type];
} // end function

/**#@+
* @const types of entries that are possible. includes base types like textarea and select, but also "metatypes" like a date field
*/
define('F_TEXT', 'Text');
define('F_SELECT', 'Select');
define('F_MSELECT', 'MSelect');
define('F_TEXTAREA', 'Textarea');
define('F_BUTTON', 'Button');
define('F_RADIO', 'Radio');
define('F_CHECKBOX', 'Checkbox');
define('F_DATE', 'Date');
define('F_HIDDEN', 'Hidden');
define('F_PW', 'Password');
define('F_FILE', 'File');
/**#@-*/
/**#@+
* @const types if entry is required or not
*/
define('F_REQ', 1);
define('F_OPT', 0);
/**#@+
* @const types possible validators for the entries. note that not every validator works with every entry. please check the $allowed_classes var in each validator class fiel to see the possible combinations
*
* FV_NONE = no validation is done
* FV_STR = checks if input is a string. checks if it is between $min and $max bytes/characters long if given as an array for the $arguments var [array($min,$max)]
* FV_VL = checks if input is from the given list of values. values are handed over to the $arguments var as an array [array($possible value_1 => $name_for_possible_value_1,$possible value_2 => $name_for_possible_value_2, ...)]
* FV_FILE = checks if input is a file. arguments: array($maxsize_of_file_in_bytes, array(allowed_mimetype1 => allowed_fileextension1, allowed_mimetype2 => allowed_fileextension2, ...))
* FV_INT = checks if input is a integer number. arguments: array($minimum_number_of_digits, $maximum_number_of_digits)
* FV_DATE = checks if input is a string in form of an iso-formated date (YYYY-mm-dd). if argument is given, checks if the userinput is between the given dates: array($minimum_date, $maximum_date)
* FV_EMAIL = checks if input is a string in form of an valid e-mail address . argument: array($minimum_number_of_characters, $maximum_number_of_characters)
* FV_VC = same as FV_VL but with a different error message suited for confirmation checkboxes
*
*/
define('FV_NONE', 'None'); // ''
define('FV_STR', 'String'); // array(min, max)
define('FV_VL', 'ValueList'); // array (values)
define('FV_FILE', 'File'); // array (maxsize, array(mimetype1 => fileextension1, mimetype2 => fileextension2))
define('FV_INT', 'Integer'); // array(x, y) von bis stellig
define('FV_DATE', 'Date'); // array(from, until)
define('FV_EMAIL', 'EMail'); // array(min, max)
define('FV_VC', 'ValueConfirmation'); // array (values)
/**#@-*/

/**
* abstract base class for all form classes
* @author Michael Wimmer <flaimo@gmx.net>
* @category FLP
* @copyright Copyright © 2002-2006, Michael Wimmer
* @license Free for non-commercial use
* @link http://flp.sf.net/
* @package Access
* @version 1.0
*/
abstract class FormBase {
	/**
	* @var array $errors holds all validation errors
	*/
	protected static $errors = array();
} // end class
?>
