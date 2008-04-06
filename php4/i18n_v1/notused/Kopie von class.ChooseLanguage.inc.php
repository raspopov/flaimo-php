<?php
/**
* Including the Language class is necessary for this class
*/
@include_once('class.Language.inc.php');

/**
* Exdends the Language class for html dropdown menus
*
* Tested with Apache 1.3.24 and PHP 4.2.3
*
* @access public
* @author Michael Wimmer <flaimo@gmx.net>
* @copyright Michael Wimmer
* @link http://www.flaimo.com/  flaimo.com
* @see Language
* @package FLP
* @version 1.002
*/
class ChooseLanguage extends Language {

	/*-----------------------*/
	/* C O N S T R U C T O R */
	/*-----------------------*/

	/**
	* Constructor
	*
	* Calls the constructor of the parent class
	*
	* @param (string) $inputlang  You can set a language manually and override all other settings
	* @return (void)
	* @access private
	* @see Language::Language()
	* @since 1.000 - 2002/10/10
	*/
	function ChooseLanguage($inputlang = '', $translationfile = 'lang_main.inc')
	{
	$this->checkClass('Language', __LINE__);
	parent::Language($inputlang, $translationfile);
	} // end constructor

	/*-------------------*/
	/* F U N C T I O N S */
	/*-------------------*/

	/**
	* Creates dropdown-element for the languages
	*
	* Creates a dropdown form element from the languagefile array
	* for choosing the (osm) language languages are displayed in
	* their own language by creating a new Language object for
	* each entry again.
	*
	* @return (string)  returns a HTML <select> element (or a hidden field if there's only one value)
	* @access public
	* @since 1.000 - 2002/10/10
	*/
	function returnDropdownLang() {
		parent::LanguagesFilesList();
		if (count(parent::getLanguageFilesArray()) > 1) {
			$output_dropdown = (string) '<select name="' . parent::getLangSelectName() . '" id="' . parent::getLangSelectName() . '" class="' . parent::getSelectCSSClass() . '">';
			foreach (parent::getLanguageFilesArray() as $isolang) {
				$locale_lang 		 = new Language($isolang);
				$langselect 		 = (string) (($isolang == parent::getLang()) ? ' selected="selected"' : '' );
				$output_dropdown 	.= (string) '<option value="' . $isolang . '"' . $langselect . '>' . $locale_lang->_($isolang) . '</option>';
			} // end foreach
			unset($langselect);
			unset($locale_lang);
			return (string) $output_dropdown . '</select>';
		}
		elseif (count(parent::getLanguageFilesArray()) == 1) {
			return (string) '<input type="hidden" value="' . parent::getLanguageFilesArray(0) . '" />';
		} // end if
	} // end return_dropdown_lang

	/**
	* Checks if a class is available
	*
	* @return (object) $user  User
	* @access private
	* @since 1.001 - 2002/11/30
	*/
	function checkClass($classname = '', $line = '') {
		if (strlen(trim($classname)) > 0) {
			if (!class_exists($classname)) {
				if (strlen(trim($line)) > 0) {
					$lineinfo = (string) 'at Line ' .$line;
				} // end if
				echo 'Class "' . get_class($this) . '": Class "' . $classname . '" not found' .$lineinfo . '!';
				die();
			} // end if
		} // end if
	} // end function
} // end class ChooseLanguage
?>