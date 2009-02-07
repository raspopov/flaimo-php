<?php
/**
* load base class which takes care of all the other includes via it's autoload function
*/
require_once 'class.I18Nbase.inc.php';

/**
* implements the php interator interface so a translator object outputs all translation strings and their translations when used with foreach
* @author Michael Wimmer <flaimo@gmail.com>
* @category flaimo-php
* @example  ../www_root/i18n_example_script.php  i18n example script
* @license GNU General Public License v3
* @link http://code.google.com/p/flaimo-php/
* @package i18n
* @version 2.3.1
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