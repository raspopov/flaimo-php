<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
//+----------------------------------------------------------------------+
//| WAMP (XP-SP1/1.3.27/4.0.12/4.3.2)                                    |
//+----------------------------------------------------------------------+
//| Copyright (c) 1992-2003 Michael Wimmer                               |
//+----------------------------------------------------------------------+
//| I don't have the time to read through all the licences to find out   |
//| what the exactly say. But it's simple. It's free for non commercial  |
//| projects, but as soon as you make money with it, i want my share :-) |
//| (License : Free for non-commercial use)                              |
//+----------------------------------------------------------------------+
//| Authors: Michael Wimmer <flaimo@gmx.net>                             |
//+----------------------------------------------------------------------+
//
// $Id$

/**
* @package i18n
* @category FLP
*/
/**
* Including the Translation class is necessary for this class
*/
@require_once 'class.Translator.inc.php';

/**
* Extends the Translator class for html dropdown menus
*
* Tested with WAMP (XP-SP1/1.3.24/4.0.4/4.3.0)
* Last change: 2003-05-02
*
* @desc
* @access public
* @author Michael Wimmer <flaimo 'at' gmx 'dot' net>
* @copyright Michael Wimmer
* @link http://www.flaimo.com/
* @see Language
* @package i18n
* @category FLP
* @version 1.061
*/
class ChooseLanguage extends Translator {

	/*-----------------------*/
	/* C O N S T R U C T O R */
	/*-----------------------*/

	/**#@+
	* @return void
	* @access private
	*/
	/**
	* Constructor
	*
	* Calls the constructor of the parent class
	*
	* @desc Constructor
	* @param string $inputlang  You can set a language manually and override all other settings
	* @param string $translationfiles  names of the translation files (.inc or .mo) WITHOUT the fileextention. If you use MySQL modus this are the namespaces for preselecting translationstrings from the database into an array
	* @uses Language::Language()
	* @uses checkClass()
	*/
	function ChooseLanguage($inputlang = '', $translationfiles = 'lang_main') {
        parent::Translator($inputlang, $translationfiles);
	} // end constructor

	/*-------------------*/
	/* F U N C T I O N S */
	/*-------------------*/

	/**
	* Creates an array with available languages
	*
	* @desc Creates an array with available languages
	* @uses Language::LanguagesFilesList()
	* @uses Language::getLanguageFilesArray()
	* @uses Translator::getLang()
	* @uses Translator::changeLang()
	* @uses Translator::__()
	* @since 1.057 - 2003-05-04
	*/
	function &setLangOutput() {
		if (!isset($this->lang_output)) {
			parent::LanguagesFilesList();
	        if (count(parent::getLanguageFilesArray()) > 0) {
				$temp_lang = (string) parent::getLocale();
	            foreach (parent::getLanguageFilesArray() as $isolang) {
					parent::changeLocale($isolang);
					$this->lang_output[$isolang] = parent::__($isolang);
				} // end foreach
				parent::changeLocale($temp_lang);
	            unset($langselect);
			} // end if
		} // end if
	} // end return_dropdown_lang
	/**#@-*/

	/**#@+
	* @access public
	*/
	/**
	* Returns the available languages array
	*
	* looks like $var['en'] = 'English' for example;
	*
	* @desc Returns the available languages array
	* @return (array) $lang_output
	* @since 1.057 - 2003-05-04
	*/
	function getLangOutput() {
		$this->setLangOutput();
		return (array) $this->lang_output;
	} // end function

	/**
	* Creates dropdown-element for the languages
	*
	* Creates a dropdown form element from the languagefile array
	* for choosing the (osm) language languages are displayed in
	* their own language by creating a new Language object for
	* each entry again.
	*
	* @desc Creates dropdown-element for the languages
	* @return string  returns a HTML <select> element (or a hidden field if there's only one value)
	* @uses getLocaleSelectName()
	* @uses getSelectCSSClass()
	* @since 1.000 - 2002-10-10
	*/
	function &returnDropdownLang() {
		parent::LanguagesFilesList();
		parent::loadUserClass();
        if (count($this->getLangOutput()) > 0) {
            $output_dropdown = (string) '<select name="' . $this->user->getLocaleSelectName() . '" id="' . $this->user->getLocaleSelectName() . '" class="' . $this->user->getSelectCSSClass() . '">';
            foreach ($this->getLangOutput() as $isolang => $trans_lang) {
				$langselect 		 = (string) (($isolang === parent::getLocale()) ? ' selected="selected"' : '' );
				$output_dropdown 	.= (string) '<option value="' . $isolang . '"' . $langselect . '>' . str_replace('&shy;','',$trans_lang) . '</option>';
			} // end foreach
            unset($langselect);
			return (string) $output_dropdown . '</select>';
		} // end if
	} // end return_dropdown_lang
	/**#@-*/
} // end class ChooseLanguage
?>
