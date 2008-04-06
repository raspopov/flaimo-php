<?php
/**
* Including the Language class is necessary for this class
*/
require_once('class.Language.inc.php');

/**
* Exdends the Language class for html dropdown menus
*
* @access public
* @author Michael Wimmer <flaimo@gmx.net>
* @copyright Michael Wimmer
* @link http://www.flaimo.com/  flaimo.com
* @see Language
* @package FLP
* @version 1.002
*/
class ChooseLanguage extends Language
  {
  
  /*-------------------*/
  /* V A R I A B L E S */
  /*-------------------*/
  
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
  function returnDropdownLang()
    {
    parent::LanguagesFilesList();
    if (count(parent::getLanguageFilesArray()) > 1)
      {
      $output_dropdown = (string) '<select name="' . parent::getLangSelectName() . '" id="' . parent::getLangSelectName() . '" class="' . parent::getSelectCSSClass() . '">';
      
      foreach (parent::getLanguageFilesArray() as $isolang)
        {
        $locale_lang = new Language($isolang);
        $langselect = (string) (($isolang == parent::getLang()) ? ' selected="selected"' : '' );
        $output_dropdown .= (string) '<option value="' . $isolang . '"' . $langselect . '>' . $locale_lang->_($isolang) . '</option>';
        } // end foreach    
      unset($langselect);
      unset($locale_lang);
      return (string) $output_dropdown . '</select>';
      }
    elseif (count(parent::getLanguageFilesArray()) == 1)
      {
      return (string) '<input type="hidden" value="' . parent::getLanguageFilesArray(0) . '" />';
      } // end if
    } // end return_dropdown_lang
  } // end class ChooseLanguage  
?>