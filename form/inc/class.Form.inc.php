<?php
/**
* load base class which takes care of all the other includes via it's autoload function
*/
require_once 'class.FormBase.inc.php';

/**
* public class to create a new form
* @author Michael Wimmer <flaimo@gmx.net>
* @category FLP
* @copyright Copyright © 2002-2006, Michael Wimmer
* @license Free for non-commercial use
* @link http://flp.sf.net/
* @package Form
* @version 1.0
*/
class Form extends FormBase {

	/**#@+
	* @var array
	*/
	protected $entries;
	/**#@+
	* @var string
	*/
	protected $follow_up_url;
	protected $session_name;
	protected $method;
	/**#@-*/
	/**#@+
	* @var int
	*/
	protected $max_file_size;
	/**#@-*/
	/**#@+
	* @var boolean
	*/
	protected $show_descriptions;
	/**#@-*/
	/**#@+
	* @var object
	*/
	protected $builder;
	/**#@-*/
	
	/**
	* @const string path where temporary files are stored after the form has successfully been submited
	*/
	const UFILE_TEMP_PATH = 'C:\Dokumente und Einstellungen\michael.wimmer\Eigene Dateien\HTML\forms\tmp';
	/**
	* @const string delimiter for paths (windows or unix style)
	*/
	const UFILE_FOLDER = '\\';

	/**
	* constrctor for the form class
	*
	* @param string $session_name name of the session var where the data will be stored
	* @param string $follow_up_url url to redirect to after formdata has successfully been validated
	* @return void
	*/
	public function __construct($session_name = 'formdata', $follow_up_url = '') {
		$this->setSessionName($session_name);
		$this->setFollowUpURL($follow_up_url);
		$this->setShowDescriptions(false);
		$this->setMethod('post');
	} // end constructor

	/**
	* sets the name of the session var. takes "formdata" if string is empty
	*
	* @param string $session_name name of the session var where the data will be stored
	* @return void
	*/
	protected function setSessionName($session_name = 'formdata') {
		if (!isset($this->session_name)) {
      $this->session_name = 'formdata';
			if (strlen(trim($session_name)) > 0) {
			  $this->session_name = $session_name;
			} // end if
		} // end if
	} // end function

	/**
	* sets the name of the url where the user should be redirected to after a successful validation of the formdata. if string is empty, redirect goes to current page
	*
	* @param string $url url to redirect to after formdata has successfully been validated
	* @return void
	*/
	protected function setFollowUpURL($url = '') {
		if (!isset($this->follow_up_url)) {
      $this->follow_up_url = $_SERVER['REQUEST_URI'];
			if (strlen(trim($url)) > 0) {
			  $this->follow_up_url = (string) $url;
			} // end if
		} // end if
	} // end function

	/**
	* sets the class var whether to show description <span>'s in the HTML output or not
	*
	* @param boolean whether to show description <span>'s in the HTML output or not
	* @return boolean
	*/
	public function setShowDescriptions($boolean = TRUE) {
		$this->show_descriptions = (boolean) $boolean;
		return TRUE;
	} // end function

	/**
	* NOT YET IMPLEMENTED. sets the form method to POST or GET
	*
	* @param string "post" or "get". default is "post"
	* @return boolean
	*/
	protected function setMethod($string = 'post') {
		$this->method = (string) 'post';
		if ($string == 'get') {	$this->method = (string) 'get'; }
		return TRUE;
	} // end function

	/**
	* starts a new group of input fields. result is a <fieldset> and <legend> field in the html output
	*
	* @param string $name name of the group to start
	* @return boolean
	*/
	public function startGroup($name = '') {
	  $this->entries[] = array('type' => 'groupstart', 'name' => $name);
	  return TRUE;
	} // end function

	/**
	* ends the last group. result is a </fieldset> in the html output
	*
	* @param string $name name of the group to end
	* @return boolean
	*/
	public function endGroup($name = '') {
 		$this->entries[] = array('type' => 'groupend', 'name' => $name);
 		return TRUE;
	} // end function

	/**
	* adds a new entry to the form
	*
	* @param string $name_name of the form entry
	* @param string $description description of the form entry
	* @param string $type which kind of entry is it (see F_* constants in the FormBase file)
	* @param int $required whether the user has to fill out the entry or not
	* @param string $validator who should the input of this entry be validated (see FV_* constants in the FormBase file)
	* @param mixed $default_value default value(s) which should be selected when the form ist rendered for the first time
	* @param mixed $range additional values for rendering the entry
	* @param mixed $arguments arguments for the validator
	* @return boolean
	*/
	public function addEntry($name = '', $description = '', $type = '', $required = FALSE, $validator = '', $default_value = '', $range = '', $arguments = '') {
		return $this->addCustomEntry('', $name, $description, $type, $required, $validator, $default_value, $range, $arguments);
	} // end function


	/**
	* adds a new entry to the form with a custom id attribute
	*
	* @param string $id_id of the form entry
	* @param string $name_name of the form entry
	* @param string $description description of the form entry
	* @param string $type which kind of entry is it (see F_* constants in the FormBase file)
	* @param int $required whether the user has to fill out the entry or not
	* @param string $validator who should the input of this entry be validated (see FV_* constants in the FormBase file)
	* @param mixed $default_value default value(s) which should be selected when the form ist rendered for the first time
	* @param mixed $range additional values for rendering the entry
	* @param mixed $arguments arguments for the validator
	* @return boolean
	*/
	public function addCustomEntry($id = '', $name = '', $description = '', $type = '', $required = FALSE, $validator = '', $default_value = '', $range = '', $arguments = '') {
		if (strlen(trim($validator)) < 1) { $validator = FV_NONE; } // end if
		$val = getValidator($validator);

		if ($validator == FV_FILE && isset($range[0]) && $range[0] != '' && is_numeric($range[0]) && (!isset($this->max_file_size) || $this->max_file_size < $range[0])) {
				$this->max_file_size = $range[0];
		} // end if

		$classname = 'FormEntry' . $type;
		$entry = new $classname($id, $name, $description, $required, $val, $default_value, $range, $arguments, $this->session_name);

		$this->entries[] = array('type' => 'entry', 'entry' => $entry);
	  return TRUE;
	} // end function

	/**
	* validated the submitted userdata and writes errors to the static class var. redirects to follow up url if no errors were found.
	*
	* @return boolean
	*/
	public function validateForm() {
	  foreach ($this->entries as $entry_id => $entry) {
			if ($entry['type'] != 'entry') { continue; }
			$entry['entry']->setValue();
		} // end foreach
	
	  if (isset($_POST['form_' . $this->session_name]) && count(parent::$errors) < 1) {
			$count = 1;
			foreach ($this->entries as $entry_id => $entry) {
				if ($entry['type'] != 'entry') { continue; }

				if ($entry['entry'] instanceOf FormEntryFile) {
					$dir = Form::UFILE_TEMP_PATH . Form::UFILE_FOLDER . $this->session_name . Form::UFILE_FOLDER . session_id();
					if (!is_dir($dir)) {
            if (!mkdir($dir, 0700, TRUE)) {
							unset($_SESSION[$this->session_name]['data']);
							parent::$errors[] = 'Fehler beim Speichern der Dateien: Upload-Verzeichnis konnte nicht erstellt werden. Bitte versuchen Sie es erneut. Sollte das Problem weiter bestehen kontaktieren Sie bitte den Administrator.';
							return FALSE;
						} // end if
					} // end if
					
          $uploadfile = $dir . Form::UFILE_FOLDER . basename($_FILES[$entry['entry']->getCodename() . '_1']['name']);
          if (!move_uploaded_file($_FILES[$entry['entry']->getCodename() . '_1']['tmp_name'], $uploadfile)) {
            unset($_SESSION[$this->session_name]['data']);
						parent::$errors[] = 'Fehler beim Kopieren der upgeloadeten Dateien. Bitte versuchen Sie es erneut. Sollte das Problem weiter bestehen kontaktieren Sie bitte den Administrator.';
						return FALSE;
					} // end if
          $_SESSION[$this->session_name]['data'][$count]['name'] = $entry['entry']->getName();
          $_SESSION[$this->session_name]['data'][$count]['value'] = $uploadfile;
				} else {
          $_SESSION[$this->session_name]['data'][$count]['name'] = $entry['entry']->getName();
          $_SESSION[$this->session_name]['data'][$count]['value'] = $entry['entry']->getValue();
				} // end if
				$count++;
			} // end foreach
			
			//header('Location: ' . $this->follow_up_url);
		} // end if
		return FALSE;
	} // end function

	/**
	* creates a new builder class for the selected output method. only XHTML1 is supported at the moment
	*
	* @param string $version only "XHTML1" so far
	* @return void
	*/
	protected function setBuilder($version = 'XHTML1') {
		if (!isset($this->builder)) {
			$classname = 'FormBuilder_'. $version;
			$this->builder = new $classname($this);
		} // end if
	} // end function

	/**
	* returns the output code for the validation errors for the selected output method. only XHTML1 is supported at the moment
	*
	* @param string $version only "XHTML1" so far
	* @return string
	*/
	public function getErrors($version = 'XHTML1') {
		$this->setBuilder($version);
		return $this->builder->getErrorCode();
	} // end function

	/**
	* returns the number of errors for the last validation
	*
	* @return int
	*/
	public function countErrors() {
		return count(parent::$errors);
	} // end function

	/**
	* returns the output code for the form for the selected output method. only XHTML1 is supported at the moment
	*
	* @param string $version only "XHTML1" so far
	* @return string
	*/
	public function getCode($version = 'XHTML1') {
		$this->setBuilder($version);
		return $this->builder->getCode();
	} // end function

	/**#@+
	* getter methods for user vars
	* @return mixed
	*/
	public function getEntries() {
	  return $this->entries;
	} // end function
	
	public function showDescriptions() {
		return $this->show_descriptions;
	} // end function
	
	public function getMethod() {
		return $this->method;
	} // end function
	
	public function getFollowUpURL() {
		return $this->follow_up_url;
	} // end function
	
	public function getSessionName() {
		return $this->session_name;
	} // end function
	
	public function getMaxFileSize() {
  	return $this->max_file_size;
	} // end function
	/**#@-*/
} // end class
?>
