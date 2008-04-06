<?php
/**
* load base class which takes care of all the other includes via it's autoload function
*/
require_once 'class.FormBase.inc.php';

/**
* class that validates files
* @author Michael Wimmer <flaimo@gmx.net>
* @category FLP
* @copyright Copyright © 2002-2006, Michael Wimmer
* @license Free for non-commercial use
* @link http://flp.sf.net/
* @package Form
* @version 1.0
*/
class FormValidatorFile extends FormValidatorBase {
	/**
	* constrcutor. sets the allowed entry-types
	*
	* @return void
	*/
	public function __construct() {
		$this->allowed_classes = array('File');
	} // end constructor

	/**#@+
	* validates an entry for the given type (text, radio, select, ...)
	*
	* @param object $entry
	* @return boolean
	*/
	protected function validateFile(FormEntryFile &$entry) {
    $arguments = $entry->getArguments();
		if (!isset($_FILES[$entry->getCodename() . '_1']) ||
				strlen(trim($_FILES[$entry->getCodename() . '_1']['name'])) == '' ||
				$_FILES[$entry->getCodename() . '_1']['size'] == 0 ||
				!is_uploaded_file($_FILES[$entry->getCodename() . '_1']['tmp_name']) ||
				$_FILES[$entry->getCodename() . '_1']['error'] == 3 ||
				$_FILES[$entry->getCodename() . '_1']['error'] == 4) {
      $this->setErrorMessage($entry->getCodename(), $entry->getName(), '', '');
			return FALSE;
		} // end if
		
		$arguments = $entry->getArguments();
		$size =& $_FILES[$entry->getCodename() . '_1']['size'];

		if (isset($arguments[0]) && strlen(trim($arguments[0])) > 0 && is_numeric($arguments[0]) && $size > $arguments[0]) {
      $this->setErrorMessage($entry->getCodename(), $entry->getName(), $arguments[0], '');
      return FALSE;
		} elseif ($_FILES[$entry->getCodename() . '_1']['error'] == 1 || $_FILES[$entry->getCodename() . '_1']['error'] == 2) {
      $this->setErrorMessage($entry->getCodename(), $entry->getName(), $arguments[0], '');
      return FALSE;
		} // end if

		if ($_FILES[$entry->getCodename() . '_1']['error'] > 0) {
      $this->setErrorMessage($entry->getCodename(), $entry->getName(), '', '');
      return FALSE;
		} // end if
		
		if (isset($arguments[1]) && is_array($arguments[1])) {
			if (!isset($_FILES[$entry->getCodename() . '_1']['type']) || strlen(trim($_FILES[$entry->getCodename() . '_1']['type'])) == 0 || !array_key_exists($_FILES[$entry->getCodename() . '_1']['type'], $arguments[1])) {
      $this->setErrorMessage($entry->getCodename(), $entry->getName(), '', $arguments[1]);
      return FALSE;
			} // end if
		} // end if
		return TRUE;
	} // end function
	/**#@-*/

	/**
	* formats a number (as bytes) to kb, mb, gb, ...
	*
	* @param int $byte
	* @return int
	*/
	protected static function formatFileSize($bytes = '') {
		if ($bytes < 1024) {
			return $bytes . ' Byte';
		} elseif ($bytes < (1024*1024)) {
			return floor($bytes/1024) . ' Kilobyte';
		} elseif ($bytes < (1024*1024*1024)) {
			return floor($bytes/(1024*1024)) . ' Megabyte';
		} elseif ($bytes < (1024*1024*1024*1024)) {
			return floor($bytes/(1024*1024*1024)) . ' Gigabyte';
		} // end if
	} // end if

	/**
	* adds an error message to the static error class var
	*
	* @param string $code codename of the entry
	* @param string $name name of the entry
	* @param int $maxsize in bytes
	* @param array $mimetypes => fileextension
	* @return void
	*/
	protected function setErrorMessage($code = '', $name = '', $maxsize = '', $mimetypes = '') {
		if ($maxsize != '' && is_array($mimetypes)) {
      parent::$errors[$code] = '„' . $name . '“ darf maximal ' .  $this->formatFileSize($maxsize) . ' groß sein und muß eine der folgenden Dateiendungen haben: „' . implode('“, „', $mimetypes) . '“.';
		} elseif ($maxsize != '') {
      parent::$errors[$code] = '„' . $name . '“ darf maximal ' .  $this->formatFileSize($maxsize) . ' groß sein.';
		} elseif (is_array($mimetypes)) {
      parent::$errors[$code] = '„' . $name . '“ muß eine der folgenden Dateiendungen haben: „' . implode('“, „', $mimetypes) . '“.';
		} else {
      parent::$errors[$code] = 'Sie müssen bei „' . $name . '“ eine Datei angeben.';
		} // end if
	} // end function
} // end class
?>
