<?php
/**
* load base class which takes care of all the other includes via it's autoload function
*/
require_once 'class.I18Nbase.inc.php';

/**
* implements the php interator interface so a translator object outputs all translation strings and their translations when used with foreach
* @author Michael Wimmer <flaimo@gmx.net>
* @category FLP
* @copyright Copyright © 2002-2006, Michael Wimmer
* @example  ../www_root/i18n_example_script.php  i18n example script
* @license Free for non-commercial use
* @link http://flp.sf.net/
* @package I18N
* @version 2.2
*/
class I18NtranslatorIterator implements Iterator {
	/**
	* @var array halds the translationtable
	*/
	protected $translation_table;

	/**
	* @param object $translator
	* @uses I18Ntranslator::getTranslationTable()
	* @return void
	*/
	public function __construct(I18Ntranslator &$translator = NULL) {
		$this->translation_table = $this->translator->getTranslationTable();
	} // end constructor

	/**#@+
	* implemented iterator function
	*/
	public function rewind() {
		return reset($this->translation_table);
	} // end function

	public function key() {
		return key($this->translation_table);
	} // end function

	public function current() {
		return current($this->translation_table);
	} // end function

	public function next() {
		return next($this->translation_table);
	} // end function

	public function valid() {
		$tmp = next($this->translation_table);
		prev($this->translation_table);
		return $tmp;
	} // end function
	/**#@-*/
} // end TranslatorIterator
?>