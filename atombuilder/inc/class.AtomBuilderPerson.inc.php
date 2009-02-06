<?php
require_once 'class.AtomBuilderBase.inc.php';

/**
* Class for creating an Atom-Feed
* @author Michael Wimmer <flaimo@gmail.com>
* @category flaimo-php
* @copyright Copyright © 2002-2009, Michael Wimmer
* @license GNU General Public License v3
* @link http://code.google.com/p/flaimo-php/
* @package Atom
* @version 1.03
*/
class AtomBuilderPerson extends AtomBuilderBase {
	protected $name;
	protected $url;
	protected $email;

	function __construct($name = '') {
		parent::__construct();
		$this->setName($name);
	} // end constructor

	protected function isValidMail($mail = '') {
		$regex = '/^([a-z0-9])(([-a-z0-9._])*([a-z0-9]))*\@([a-z0-9])' . '(([a-z0-9-])*([a-z0-9]))+' . '(\.([a-z0-9])([-a-z0-9_-])?([a-z0-9])+)+$/i';
		return preg_match ($regex, $mail);
	} // end function

	public function setName($string = '') {
		return parent::setVar($string, 'name', 'string');
	} // end function

	public function setURL($string = '') {
		return parent::setVar($string, 'url', 'string');
	} // end function

	public function setEmail($string = '') {
		if ($this->isValidMail($string) == TRUE) {
			return parent::setVar($string, 'email', 'string');
		} // end if
	} // end function

	public function getName() {
		return parent::getVar('name');
	} // end function

	public function getURL() {
		return parent::getVar('url');
	} // end function

	public function getEmail() {
		return parent::getVar('email');
	} // end function
} // end class

?>