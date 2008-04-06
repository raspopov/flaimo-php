<?php
/**
* @package i18n
*/
/**
* Including the Translation class is necessary for this class
*/
@include_once('class.Translator.inc.php');

/**
* Extends the Translator class for html dropdown menus
*
* Tested with WAMP (XP-SP1/1.3.24/4.0.4/4.3.0)
* Last change: 2003-02-26
*
* @desc
* @access public
* @author Michael Wimmer <flaimo@gmx.net>
* @copyright Michael Wimmer
* @link http://www.flaimo.com/
* @see Language
* @package i18n
* @version 1.053
*/
class ChooseLanguage extends Translator {

	/*-----------------------*/
	/* C O N S T R U C T O R */
	/*-----------------------*/

	/**
	* Constructor
	*
	* Calls the constructor of the parent class
	*
	* @desc Constructor
	* @param (string) $inputlang  You can set a language manually and override all other settings
	* @param (string) $translationfiles  names of the translation files (.inc or .mo) WITHOUT the fileextention. If you use MySQL modus this are the namespaces for preselecting translationstrings from the database into an array
	* @return (void)
	* @access private
	* @uses Language::Language(), checkClass()
	* @since 1.000 - 2002/10/10
	*/
	function ChooseLanguage($inputlang = '', $translationfiles = 'lang_main') {
        $this->checkClass('Translator', __LINE__);
        parent::Translator($inputlang, $translationfiles);
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
	* @desc Creates dropdown-element for the languages
	* @return (string)  returns a HTML <select> element (or a hidden field if there's only one value)
	* @access public
	* @uses Language::LanguagesFilesList(), Language::getLanguageFilesArray(), Language::getLang(), Language::getLangSelectName(), Language::getSelectCSSClass(), Language::changeLang(), Language::__()
	* @since 1.000 - 2002/10/10
	*/
	function &returnDropdownLang() {
		parent::LanguagesFilesList();
        if (count(parent::getLanguageFilesArray()) > 1) {
			$temp_lang = (string) parent::getLocale();
            $output_dropdown = (string) '<select name="' . $this->getLocaleSelectName() . '" id="' . $this->getLocaleSelectName() . '" class="' . $this->getSelectCSSClass() . '">';
            foreach (parent::getLanguageFilesArray() as $isolang) {
				parent::changeLocale($isolang);
				$langselect 		 = (string) (($isolang === $temp_lang) ? ' selected="selected"' : '' );
				$output_dropdown 	.= (string) '<option value="' . $isolang . '"' . $langselect . '>' . str_replace('&shy;','',parent::__($isolang)) . '</option>';
			} // end foreach
			parent::changeLocale($temp_lang);
            unset($langselect);
			return (string) $output_dropdown . '</select>';
		} elseif (count(parent::getLanguageFilesArray()) === 1) {
			return (string) '<input type="hidden" value="' . parent::getLanguageFilesArray(0) . '" />';
		} // end if
	} // end return_dropdown_lang


	/**
	* Returns the number of strings available for the selected language
	*
	* @desc Returns the number of strings available for the selected language
	* @return (int)  Number of Strings, -1 if error
	* @access public
	* @since 1.041 - 2003/02/08
	*/
    function &countStrings() {
        if (parent::getModus() == 'inc') {
            return (int) count($this->language);
        } elseif (parent::getModus() == 'gettext') {
            $counter = (int) 0;
			foreach ($this->languagefile_names as $lang_file) {
		        if ($gettextfile = (array) file($this->languagefile_path . '/' . parent::getLocale() . '/LC_MESSAGES/' . $lang_file . '.po')) {
		            foreach ($gettextfile as $key => $value) {
		                if (trim(substr($value, 0, 5)) == 'msgid') {
		                    $counter++;
		                } // end if
		            } // end foreach
		            unset($gettextfile);
		            return (int) ($counter - 1); // -1 because of the msgid "" at the beginning of each file
		        } // end if
			} // end foreach
        } elseif (parent::getModus() == 'mysql') {
            parent::setConnection();
            $query = 'SELECT COUNT(*) FROM ' .  $this->db_table;
            if ($result = mysql_query($query, $this->conn)) {
                return (int) mysql_result($result, 0, 0);
                mysql_free_result($result);
            } else {
                return (int) -1;
            } // end if
        } // end if
    } // end function

	/**
	* Returns the number of languages available
	*
	* @desc Returns the number of languages available
	* @return (int) $languageFilesArray
	* @access public
	* @since 1.041 - 2003/02/08
	*/
    function countLanguages() {
        return (int) count($this->languageFilesArray);
    } // end function


	/**
	* Returns the name for the language <select> tag
	*
	* @desc Returns the name for the language <select> tag
	* @return (string) $langselectname
	* @access public
	* @see getLangSessionName(), getLangCookieName(), User::getLangSelectName()
	* @uses loadUserClass()
	* @since 1.000 - 2002/10/10
	*/
	function &getLocaleSelectName() {
		parent::loadUserClass();
		return (string) $this->user->getLocaleSelectName();
	} // end function

	/**
	* Returns the name for the language session variable
	*
	* @desc Returns the name for the language session variable
	* @return (string) $langsessionname
	* @access public
	* @see getLangSelectName(), getLangCookieName(), User::getLangSessionName()
	* @uses loadUserClass(),
	* @since 1.000 - 2002/10/10
	*/
	function &getLocaleSessionName() {
		parent::loadUserClass();
		return (string) $this->user->getLocaleSessionName();
	} // end function

	/**
	* Returns the name for the language cookie variable
	*
	* @desc Returns the name for the language cookie variable
	* @return (string) $langcookiename
	* @access public
	* @see getLangSelectName(), getLangSessionName(), User::getLangCookieName()
	* @uses loadUserClass()
	* @since 1.000 - 2002/10/10
	*/
	function &getLocaleCookieName() {
		parent::loadUserClass();
		return (string) $this->user->getLocaleCookieName();
	}

	/**
	* Returns the name for the content-language <select> tag
	*
	* @desc Returns the name for the content-language <select> tag
	* @return (string) $langcontentselectname
	* @access public
	* @see getLangContentCookieName()
	* @uses loadUserClass(), getLangContentSessionName()
	* @since 1.000 - 2002/10/10
	*/
	function &getLangContentSelectName() {
		parent::loadUserClass();
		return (string) $this->user->getLangContentSelectName();
	}

	/**
	* Returns the name for the content-language session variable
	*
	* @desc Returns the name for the content-language session variable
	* @return (string) $langcontentsessionname
	* @access public
	* @see getLangContentSelectName(), getLangContentCookieName()
	* @uses loadUserClass(), User::getLangContentSessionName()
	* @since 1.000 - 2002/10/10
	*/
	function &getLangContentSessionName() {
		parent::loadUserClass();
		return (string) $this->user->getLangContentSessionName();
	} // end function

	/**
	* Returns the name for the content-language cookie variable
	*
	* @desc Returns the name for the content-language cookie variable
	* @return (string) $langcontentcookiename
	* @access public
	* @see getLangContentSelectName(), getLangContentSessionName()
	* @uses loadUserClass(), User::getLangContentCookieName()
	* @since 1.000 - 2002/10/10
	*/
	function &getLangContentCookieName() {
		parent::loadUserClass();
		return (string) $this->user->getLangContentCookieName();
	} // end function

	/**
	* Returns the name for the css-class attribute for form elements
	*
	* @desc Returns the name for the css-class attribute for form elements
	* @return (string) $SelectCSSClass
	* @access public
	* @see setSelectCSSClass()
	* @since 1.000 - 2002/10/10
	*/
	function &getSelectCSSClass() {
		parent::loadUserClass();
		return (string) $this->user->getSelectCSSClass();
	} // end function

	/**
	* Checks if a class is available
	*
	* @desc Checks if a class is available
	* @return (void)
	* @access private
	* @since 1.001 - 2002/11/30
	*/
	function checkClass($classname = '', $line = '') {
		if (strlen(trim($classname)) > 0) {
			if (!class_exists($classname)) {
				if (strlen(trim($line)) > 0) {
					$lineinfo = (string) 'at Line ' .$line;
				} // end if
				die('Class "' . get_class($this) . '": Class "' . $classname . '" not found' .$lineinfo . '!');
			} // end if
		} // end if
	} // end function
} // end class ChooseLanguage
?>
