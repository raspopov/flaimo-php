<?php
/**
* load base class which takes care of all the other includes via it's autoload function
*/
require_once 'class.FormBase.inc.php';

/**
* builder class which takes care of generating the output code for forms
* @author Michael Wimmer <flaimo@gmx.net>
* @category FLP
* @copyright Copyright © 2002-2006, Michael Wimmer
* @license Free for non-commercial use
* @link http://flp.sf.net/
* @package Form
* @version 1.0
*/
class FormBuilder_XHTML1 extends FormBuilderBase {
	/**
	* @var array $allowed_classes list of entry types which can be rendered with type of outputgenerator
	*/
	protected $allowed_classes;

	/**
	* constrctor
	*
	* @param object $form_hands the form object to the builder object to parse the information for building the output
	* @return void
	*/
	function __construct(Form &$form) {
		parent::__construct($form);
		$this->allowed_classes = array('Text', 'Button', 'Select', 'MSelect', 'Textarea', 'Checkbox', 'Radio', 'Date', 'Hidden', 'Password', 'File');
	} // end constructor

	/**
	* creates a surrounding div element for an entry with the correct class attribute depending on the state of the entry
	*
	* @param object $entry
	* @return object
	*/
	protected function getElementDiv(FormEntryBase &$entry) {
		$div = $this->xml->createElement('div');
		$attr_name = 'formentry';
		if ($entry->hasError() == TRUE) {$attr_name .= '_invalid'; }
		if ($entry->isRequired() == TRUE) {$attr_name .= '_required'; }
		$div->setAttribute('class', $attr_name);
    $div->setAttribute('id', $entry->getID());
    return $div;
	} // end function

	/**
	* creates a select element
	*
	* @param string $id id of the select tag
	* @param string $name name of the select tag
	* @param boolean $multi whether the select is a multiselect or not
	* @return object
	*/
	protected function getElementSelect($id = '', $name = '', $multi = FALSE) {
		$select = $this->xml->createElement('select');
		$select->setAttribute('id', $id);
		$select->setAttribute('name', $name);
		if ($multi == TRUE) {
			$select->setAttribute('multiple', 'multiple');
		} // end if
    return $select;
	} // end function

	/**
	* creates an option element
	*
	* @param string $name name of the option tag
	* @param string $value value of the option tag
	* @param boolean $multi whether the option tag is selected or not
	* @return object
	*/
	protected function getElementOption($name = '', $value = '', $selected = FALSE) {
		$option = $this->xml->createElement('option');
		$option->setAttribute('value', $value);
    $option->appendChild($this->xml->createTextNode($name));
		if ($selected == TRUE) {
			$option->setAttribute('selected', 'selected');
		} // end if
    return $option;
	} // end function

	/**
	* creates a hidden element
	*
	* @param string $name name of the hidden input tag
	* @param string $value value of the hidden input tag
	* @param string $id id of the hidden input tag
	* @return object
	*/
	protected function getElementHidden($name = '', $value = '', $id = '') {
		$hidden = $this->xml->createElement('input');
		$hidden->setAttribute('type', 'hidden');
		$hidden->setAttribute('name', $name);
		$hidden->setAttribute('value', $value);
		if (strlen(trim($id)) > 0) {
			$hidden->setAttribute('id', $id);
		} // end if
    return $hidden;
	} // end function

	/**
	* creates a label element
	*
	* @param string $text text between label tags
	* @param string $for for-attribute of the label tag
	* @param string $title optional title attribute for the label tag
	* @return object
	*/
	protected function getElementLabel($text = '', $for = '', $title = '') {
		$label = $this->xml->createElement('label');
		$label->setAttribute('for', $for);
    $label->appendChild($this->xml->createTextNode($text));
		if (strlen(trim($title)) > 0) {
			$label->setAttribute('title', $title);
		} // end if
    return $label;
	} // end function

	/**
	* adds a "*" to the name if it is a required entry
	*
	* @param string $name
	* @param boolean $req is the entry required or not
	* @return string
	*/
	protected static function generateLabelName($name = '', $req = FALSE) {
		$name = $name;
		if ($req == TRUE) { $name .= '*'; }
		$name .= ':';
		return $name;
	} // end function

	/**
	* generates a description <span>-tag with the givven text
	*
	* @param string $text
	* @return object
	*/
	protected function generateDescriptionTag($text = '') {
		if ($this->form->showDescriptions() == FALSE || (is_string($text) && strlen(trim($text)) < 1)) {
		  return FALSE;
		} // end if

		if ($text instanceOf DomElement) {
			$tag =& $text;
		} else {
      $tag = $this->xml->createTextNode($text);
		} // end if


		$span = $this->xml->createElement('span');
  	$span->appendChild($tag);
  	return $span;
	} // end function

	/**
	* adds an entry of type "Text" to the given parentnode
	*
	* @param object $entry FormEntry* object
	* @param object $parentnode
	* @return boolean
	*/
	protected function getEntryText(FormEntryText &$entry, $parentnode) {
		$div = $this->getElementDiv($entry);

		if (($span = $this->generateDescriptionTag($entry->getDescription())) != FALSE) {
	    	$div->appendChild($span);
		} // end if

		$label = $this->getElementLabel($this->generateLabelName($entry->getName(), $entry->isRequired()), $entry->getCodename() . '_1', $entry->getDescription());
		$div->appendChild($label);

  	$range = $entry->getRange();
		$element = $this->xml->createElement('input');
		$element->setAttribute('type', 'text');
    $element->setAttribute('id', $entry->getCodename() . '_1');
    $element->setAttribute('name', $entry->getCodename() . '_1');
    $element->setAttribute('value',  $entry->getValue());
    $element->setAttribute('maxlength', $range[1]);
    $div->appendChild($element);
		$parentnode->appendChild($div);
		return TRUE;
	} // end function

	/**
	* adds an entry of type "Password" to the given parentnode
	*
	* @param object $entry FormEntry* object
	* @param object $parentnode
	* @return boolean
	*/
	protected function getEntryPassword(FormEntryPassword &$entry, &$parentnode) {
		$div = $this->getElementDiv($entry);

		if (($span = $this->generateDescriptionTag($entry->getDescription())) != FALSE) {
	    	$div->appendChild($span);
		} // end if

		$label = $this->getElementLabel($this->generateLabelName($entry->getName(), $entry->isRequired()), $entry->getCodename() . '_1', $entry->getDescription());
		$div->appendChild($label);

		$range = $entry->getRange();
		$element = $this->xml->createElement('input');
		$element->setAttribute('type', 'password');
    $element->setAttribute('id', $entry->getCodename() . '_1');
    $element->setAttribute('name', $entry->getCodename() . '_1');
    $element->setAttribute('value',  $entry->getValue());
    $element->setAttribute('maxlength', $range[1]);
    $div->appendChild($element);
		$parentnode->appendChild($div);
		return TRUE;
	} // end function

	/**
	* adds an entry of type "File" to the given parentnode
	*
	* @param object $entry FormEntry* object
	* @param object $parentnode
	* @return boolean
	*/
	protected function getEntryFile(FormEntryFile &$entry, &$parentnode) {

		$div = $this->getElementDiv($entry);

		if (($span = $this->generateDescriptionTag($entry->getDescription())) != FALSE) {
	    	$div->appendChild($span);
		} // end if

		$label = $this->getElementLabel($this->generateLabelName($entry->getName(), $entry->isRequired()), $entry->getCodename() . '_1', $entry->getDescription());
		$div->appendChild($label);

		$range = $entry->getRange();
		$element = $this->xml->createElement('input');
		$element->setAttribute('type', 'file');
    $element->setAttribute('id', $entry->getCodename() . '_1');
    $element->setAttribute('name', $entry->getCodename() . '_1');
		if (($range =$entry->getRange()) != FALSE) {
	  	$element->setAttribute('maxlength', $range[0]);
    	$element->setAttribute('accept', $range[1]);
		} // end if

    $div->appendChild($element);
		$parentnode->appendChild($div);
		return TRUE;
	} // end function

	/**
	* adds an entry of type "Hidden" to the given parentnode
	*
	* @param object $entry FormEntry* object
	* @param object $parentnode
	* @return boolean
	*/
	protected function getEntryHidden(FormEntryHidden &$entry, &$parentnode) {
		$element = $this->getElementHidden($entry->getCodename() . '_1', $entry->getValue(), $entry->getCodename() . '_1');
		$parentnode->appendChild($element);
		return TRUE;
	} // end function

	/**
	* adds an entry of type "Textarea" to the given parentnode
	*
	* @param object $entry FormEntry* object
	* @param object $parentnode
	* @return boolean
	*/
	protected function getEntryTextarea(FormEntryTextarea &$entry, &$parentnode) {
		$div = $this->getElementDiv($entry);

		if (($span = $this->generateDescriptionTag($entry->getDescription())) != FALSE) {
	    	$div->appendChild($span);
		} // end if

		$label = $this->getElementLabel($this->generateLabelName($entry->getName(), $entry->isRequired()), $entry->getCodename() . '_1', $entry->getDescription());
		$div->appendChild($label);
// ev in eigene funktion auslagern
		$element = $this->xml->createElement('textarea');
		$element->setAttribute('type', 'text');
    $element->setAttribute('id', $entry->getCodename() . '_1');
    $element->setAttribute('name', $entry->getCodename() . '_1');
		$element->appendChild($this->xml->createTextNode($entry->getValue()));

    $range = $entry->getRange();

    if ($range != FALSE) { // ev ändern wenn typ = int und artgumente array
    	$element->setAttribute('maxlength', $range[1]);
		} // end if

    $div->appendChild($element);
		$parentnode->appendChild($div);
		return TRUE;
	} // end function

	/**
	* scans through an array recursivly and generates optgroups or option tags depending on the array
	*
	* @param array $range the array with the range values
	* @param mixed $val the value to be selected
	* @param object $parentnode the xml parent node to which the list should be attached
	* @param boolean $multiple whether there is only one value in $val or a couple (array)
	* @return void
	*/
	protected function generateOptionList($range, $val, &$parentnode, $multiple = FALSE) {
		foreach ($range as $r_id => $r_val) {
			if (!is_array($r_val)) {
				if ($multiple == TRUE) {
					$sel = (in_array($r_id , $val)) ? TRUE : FALSE;
				} else {
					$sel = ($r_id == $val) ? TRUE : FALSE;
				} // end if
				$subelement = $this->getElementOption($r_val, $r_id, $sel);
				$parentnode->appendChild($subelement);
			} else {
				$optgroup = $this->xml->createElement('optgroup');
				$optgroup->setAttribute('label', $r_id);
        $parentnode->appendChild($optgroup);
				$this->generateOptionList($r_val, $val, $optgroup, $multiple); // recursive for sub-sub-elements a.s.o.
			} // end if
		} // end foreach
	} // end function

	/**
	* adds an entry of type "Select" to the given parentnode
	*
	* @param object $entry FormEntry* object
	* @param object $parentnode
	* @return boolean
	*/
	protected function getEntrySelect(FormEntrySelect &$entry, &$parentnode) {
		$div = $this->getElementDiv($entry);

		if (($span = $this->generateDescriptionTag($entry->getDescription())) != FALSE) {
	    	$div->appendChild($span);
		} // end if

		$label = $this->getElementLabel($this->generateLabelName($entry->getName(), $entry->isRequired()), $entry->getCodename() . '_1', $entry->getDescription());
		$div->appendChild($label);
		
		$element = $this->getElementSelect($entry->getCodename() . '_1', $entry->getCodename() . '_1', FALSE);
		$this->generateOptionList($entry->getRange(), $entry->getValue(), $element, FALSE);
    $div->appendChild($element);
		$parentnode->appendChild($div);
		return TRUE;
	} // end function

	/**
	* adds an entry of type "MSelect" to the given parentnode
	*
	* @param object $entry FormEntry* object
	* @param object $parentnode
	* @return boolean
	*/
	protected function getEntryMSelect(FormEntryMSelect &$entry, &$parentnode) {
		$div = $this->getElementDiv($entry);

		if (($span = $this->generateDescriptionTag($entry->getDescription())) != FALSE) {
	    	$div->appendChild($span);
		} // end if

		$label = $this->getElementLabel($this->generateLabelName($entry->getName(), $entry->isRequired()), $entry->getCodename() . '_1', $entry->getDescription());
		$div->appendChild($label);
		
		$element = $this->getElementSelect($entry->getCodename() . '_1', $entry->getCodename() . '_1[]', TRUE);
		$this->generateOptionList($entry->getRange(), $entry->getValue(), $element, TRUE);
    $div->appendChild($element);
		$parentnode->appendChild($div);
		return TRUE;
	} // end function

	/**
	* adds an entry of type "Checkbox" to the given parentnode
	*
	* @param object $entry FormEntry* object
	* @param object $parentnode
	* @return boolean
	*/
	protected function getEntryCheckbox(FormEntryCheckbox &$entry, &$parentnode) {
		$div = $this->getElementDiv($entry);

		if (($span = $this->generateDescriptionTag($entry->getDescription())) != FALSE) {
	    	$div->appendChild($span);
		} // end if

		$label = $this->getElementLabel($this->generateLabelName($entry->getName(), $entry->isRequired()), $entry->getCodename() . '_1', $entry->getDescription());
		$div->appendChild($label);

		$element = $this->xml->createElement('div');
    $element->setAttribute('class', 'valuelist-checkbox');

		$val = $entry->getValue();
    $range = $entry->getRange();
		foreach ($range as $r_id => $r_val) {
// ev in eigene funktion auslagern
      $subelement = $this->xml->createElement('input');
      $subelement->setAttribute('type', 'checkbox');
      $subelement->setAttribute('id', $entry->getCodename() . '_' . $r_id);
      $subelement->setAttribute('name', $entry->getCodename() . '_1[' . $r_id . ']');
      $subelement->setAttribute('value', htmlspecialchars($r_id));

			if (in_array($r_id , $val)) {
    		$subelement->setAttribute('checked', 'checked');
			} // end if
 			$element->appendChild($subelement);
			$sublabel = $this->getElementLabel($r_val, $entry->getCodename() . '_' . $r_id, '');
			$element->appendChild($sublabel);
		} // end foreach

    $div->appendChild($element);
		$parentnode->appendChild($div);
		return TRUE;
	} // end function

	/**
	* adds an entry of type "Radio" to the given parentnode
	*
	* @param object $entry FormEntry* object
	* @param object $parentnode
	* @return boolean
	*/
	protected function getEntryRadio(FormEntryRadio &$entry, &$parentnode) {
		$div = $this->getElementDiv($entry);

		if (($span = $this->generateDescriptionTag($entry->getDescription())) != FALSE) {
	    	$div->appendChild($span);
		} // end if
		
		$label = $this->getElementLabel($this->generateLabelName($entry->getName(), $entry->isRequired()), $entry->getCodename() . '_1', $entry->getDescription());
		$div->appendChild($label);

		$element = $this->xml->createElement('div');
    $element->setAttribute('class', 'valuelist-radio');

		$val = $entry->getValue();
    $range = $entry->getRange();
		foreach ($range as $r_id => $r_val) {
// ev in eigene funktion auslagern
		  $subelement = $this->xml->createElement('input');
      $subelement->setAttribute('type', 'radio');
      $subelement->setAttribute('id', $entry->getCodename() . '_' . htmlspecialchars($r_id));
      $subelement->setAttribute('name', $entry->getCodename() . '_1');
      $subelement->setAttribute('value', $r_id);

			if ($r_id == $val) {
    		$subelement->setAttribute('checked', 'checked');
			} // end if
 			$element->appendChild($subelement);
			$sublabel = $this->getElementLabel($r_val, $entry->getCodename() . '_' . $r_id, '');
			$element->appendChild($sublabel);
		} // end foreach

    $div->appendChild($element);
		$parentnode->appendChild($div);
		return TRUE;
	} // end function

	/**
	* adds an entry of type "Date" to the given parentnode
	*
	* @param object $entry FormEntry* object
	* @param object $parentnode
	* @return boolean
	*/
	protected function getEntryDate(FormEntryDate &$entry, &$parentnode) {
		$div = $this->getElementDiv($entry);

		if (($span = $this->generateDescriptionTag($entry->getDescription())) != FALSE) {
	    	$div->appendChild($span);
		} // end if

		$label = $this->getElementLabel($this->generateLabelName($entry->getName(), $entry->isRequired()), $entry->getCodename() . '_1', $entry->getDescription());
		$div->appendChild($label);

		$element = $this->xml->createElement('div');
    $element->setAttribute('class', 'valuelist-date');
		$datevalues = explode('-', $entry->getValue());

    $range = $entry->getRange();
		$r_start = explode('-', $range[0]);
		$r_end = explode('-', $range[1]);
		$label_y = $this->getElementLabel('Jahr:', $entry->getCodename() . '_1_y', '');
	 	$element->appendChild($label_y);
		$select_y = $this->getElementSelect($entry->getCodename() . '_1_y', $entry->getCodename() . '_1_y', FALSE);

		for ($y = $r_start[0]; $y <= $r_end[0]; $y++) {
			$sel = ($y== $datevalues[0]) ? TRUE : FALSE;
			$option_y = $this->getElementOption($y, $y, $sel);
			$select_y->appendChild($option_y);
		} // end for
 		$element->appendChild($select_y);
 		
		$label_m = $this->getElementLabel('Monat:', $entry->getCodename() . '_1_m', '');
	 	$element->appendChild($label_m);
		$select_m = $this->getElementSelect($entry->getCodename() . '_1_m', $entry->getCodename() . '_1_m', FALSE);

		for ($m = 1; $m <= 12; $m++) {
			$m_str = (string) ($m < 10) ? '0' . $m : $m;
			$sel = (isset($datevalues[1]) && $m_str == $datevalues[1]) ? TRUE : FALSE;
			$option_m = $this->getElementOption($m_str, $m_str, $sel);
			$select_m->appendChild($option_m);
		} // end for
 		$element->appendChild($select_m);
 		
		$label_d = $this->getElementLabel('Tag:', $entry->getCodename() . '_1_d', '');
		$element->appendChild($label_d);
		$select_d = $this->getElementSelect($entry->getCodename() . '_1_d', $entry->getCodename() . '_1_d', FALSE);

		for ($d = 1; $d <= 31; $d++) {
			$d_str = (string) ($d < 10) ? '0' . $d : $d;
			$sel = (isset($datevalues[2]) && $d_str == $datevalues[2]) ? TRUE : FALSE;
			$option_d = $this->getElementOption($d_str, $d_str, $sel);
			$select_d->appendChild($option_d);
		} // end for
 		$element->appendChild($select_d);
 		
    $div->appendChild($element);
		$parentnode->appendChild($div);
		return TRUE;
	} // end function

	/**
	* adds an entry of type "Button" to the given parentnode
	*
	* @param object $entry FormEntry* object
	* @param object $parentnode
	* @return boolean
	*/
	protected function getEntryButton(FormEntryButton &$entry, &$parentnode) {
		$div = $this->xml->createElement('div');
		$attr_name = 'formentry-button';
		$div->setAttribute('class', $attr_name);
    $div->setAttribute('id', $entry->getCodename());

		$element = $this->xml->createElement('input');
		$element->setAttribute('type', 'submit');
    $element->setAttribute('id', $entry->getCodename() . '_1');
    $element->setAttribute('name', $entry->getCodename() . '_1');
    $element->setAttribute('value',  $entry->getName());

		if (($span = $this->generateDescriptionTag($entry->getDescription())) != FALSE) {
    	$element->setAttribute('title',  $entry->getDescription());
			$div->appendChild($span);
		} // end if

    $div->appendChild($element);
		$parentnode->appendChild($div);
		return TRUE;
	} // end function

	/**
	* deletes the first line of the xml output so only the XHTML code is returned
	*
	* @param string $string
	* @param object $parentnode
	* @return boolean
	*/
	protected function filterOutput($string = '') {
    	$string = str_replace('/>', ' />', $string);
    	$string = substr($string, 38, strlen($string));
			return $string;
	} // end function

	/**
	* generates the xhtml output and returns it
	*
	* @return string
	*/
	public function getCode() {
		parent::getCode();
		$formular = $this->xml->createElement('form');
		$formular->setAttribute('method', $this->form->getMethod());
		$formular->setAttribute('id', $this->form->getSessionName());
		$formular->setAttribute('accept-charset', 'UTF-8');
		$formular->setAttribute('action', $_SERVER['PHP_SELF']);
    $this->xml->appendChild($formular);
		$formname = $this->getElementHidden('form_' . $this->form->getSessionName(), 1);
    $formular->appendChild($formname);
		$sid = $this->getElementHidden(session_name(), session_id());
    $formular->appendChild($sid);
    if ($this->form->getMaxFileSize() != FALSE) {
			$maxfile = $this->getElementHidden('MAX_FILE_SIZE', $this->form->getMaxFileSize());
			$formular->appendChild($maxfile);
		} // end if

  	$notice = $this->xml->createElement('p');
		$notice->setAttribute('class', 'notice');
		$notice->appendChild($this->xml->createTextNode('Mit einem Stern-Symbol (*) gekennzeichnete Felder müssen ausgefüllt werden.'));
    $formular->appendChild($notice);
    
		$levels = array();
		$levels[] =& $formular;
		$i = 0;
		$fileupload = FALSE;
		foreach ($this->form->getEntries() as $entry_id => $entry) {
			$last_level = end($levels);
      reset($levels);

			if ($entry['type'] == 'groupstart') {
				$i++;
				$fieldsetname = 'fs_' . $i;
				$legendname = 'le_' . $i;
				$$fieldsetname = $this->xml->createElement('fieldset');
        $$legendname = $this->xml->createElement('legend');
  			$$legendname->appendChild($this->xml->createTextNode($entry['name']));
        $$fieldsetname->appendChild($$legendname);
				$last_level->appendChild($$fieldsetname);
				$levels[] =& $$fieldsetname;
			} elseif ($entry['type'] == 'groupend') {
        $tmp = array_pop($levels);
			} elseif ($entry['type'] == 'entry') {
				$classname = get_class($entry['entry']);
				$classname_prefix =  substr($classname, strlen('FormEntry'), strlen($classname));
				if ($classname_prefix == 'File') { $fileupload = TRUE; }
				if (in_array($classname_prefix, $this->allowed_classes) == TRUE) {
					$parsemethod = 'getEntry' . $classname_prefix;
					$this->$parsemethod($entry['entry'], $last_level); // zum letzten eintrag dazugeben
				} else {
					trigger_error('FORMS ERROR: No code-generating method definded for class "' . $classname . '" in class "' . get_class($this) . '"', E_USER_WARNING);
				} // end if
			} // end if
		} // end foreach

		if ($fileupload == TRUE) {
			$formular->setAttribute('enctype', 'multipart/form-data');
		} // end if
		return $this->filterOutput($this->xml->saveXML());
	} // function

	/**
	* generates the xhtml output for the errors and returns it
	*
	* @return string
	*/
	public function getErrorCode() {
		if (count(parent::$errors) < 1) { return ''; }
		$xml = new DomDocument('1.0', 'UTF-8');
		$ul = $xml->createElement('ul');
		$ul->setAttribute('class', 'formerrors');
		$xml->appendChild($ul);
		foreach (parent::$errors as $id => $message) {
			$li = $xml->createElement('li');
			$a = $xml->createElement('a');
			$a->setAttribute('href', '#' . $id);
    	$a->appendChild($xml->createTextNode($message));
    	$li->appendChild($a);
    	$ul->appendChild($li);
		} // end foreach
		return $this->filterOutput($xml->saveXML());
	} // end function
} // end class
?>
