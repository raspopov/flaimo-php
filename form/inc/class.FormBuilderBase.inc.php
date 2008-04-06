<?php
/**
* load base class which takes care of all the other includes via it's autoload function
*/
require_once 'class.FormBase.inc.php';

/**
* abstract base class for the builder classes
* @author Michael Wimmer <flaimo@gmx.net>
* @category FLP
* @copyright Copyright © 2002-2006, Michael Wimmer
* @license Free for non-commercial use
* @link http://flp.sf.net/
* @package Form
* @version 1.0
*/
abstract class FormBuilderBase extends FormBase {
	/**#@+
	* @var object
	*/
	protected $form;
	protected $xml;
	/**#@-*/
	
	/**
	* constrctor
	*
	* @param object $form_hands the form object to the builder object to parse the information for building the output
	* @return void
	*/
	public function __construct(Form &$form) {
		$this->setForm($form);
	} // end constructor

	/**
	* set the form class var
	*
	* @param object $form
	* @return void
	*/
	protected function setForm(Form &$form) {
		$this->form =& $form;
	} // end if

	/**
	* generates a new XML object
	*
	* @return void
	*/
	protected function generateXML() {
		$this->xml = new DomDocument('1.0', 'UTF-8');
		$this->xml->appendChild($this->xml->createComment('Form created by Flaimo.com FormBuilder [' .  date('Y-m-d H:i:s')  .']'));
	} // end function

	/**
	* returns the output of the generated xml object
	*
	* @return void
	*/
	public function getCode() {
		if (!isset($this->xml)) {
			$this->generateXML();
		} // end if
		return $this->xml->saveXML();
	} // function
} // end class
?>
