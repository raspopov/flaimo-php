<?php
/**
* Including the DB class is necessary for this class
*
* @include	Funktion: _require_once_
*/
require_once('class.DBclass.inc.php');

/**
* Including the Language class is necessary for this class
*
* @include	Funktion: _require_once_
*/
require_once('class.Language.inc.php');

/**
* Exdends the Language class for html dropdown menus
*
* @access public
* @author Michael Wimmer <flaimo@gmx.net>
* @copyright Michael Wimmer
* @link http://www.flaimo.com/  flaimo.com
* @version 1.000
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
  * @return void
  * @access private
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
  * @return string  returns a HTML <select> element (or a hidden field if there's only one value)
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
        $output_dropdown .= (string) '<option value="' . $isolang . '"' . $langselect . '>' . $locale_lang->Translate($isolang) . '</option>';
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
    
    
  /**
  * Creates dropdown-element for the content-languages (based on data from a database)
  *
  * @return string  returns a HTML <select> element (or a hidden field if there's only one value)
  * @access public
  * @since 1.000 - 2002/10/10 
  */
  function returnDropdownLangContent()
    {
    $db = (object) new DBclass();
    $rs_languages = $db->db_query("SELECT DISTINCT Language FROM " . TBL_NEWS . " WHERE Language <> ''"); 
    
    if ($db->rs_num_rows($rs_languages) > 0)
      {
      $output_dropdown = (string) '<select name="' . parent::getLangContentSelectName() . '" id="' . parent::getLangContentSelectName() . '" class="' . parent::getSelectCSSClass() . '">';
      while ($rs = $db->rs_fetch_assoc($rs_languages))
        {
        $langselect = (string) (($rs['Language'] == parent::getLangContent()) ? ' selected="selected"' : '' );
        $output_dropdown .= (string) '<option value="' . $rs['Language'] . '"' . $langselect . '>' . parent::translate($rs['Language']) . '</option>';        
        } // end while
      unset($langselect);
      return $output_dropdown . '</select>';
      } // end if
    
    $db->db_freeresult($rs_languages);
    $db->db_close();
    unset($db);
    }  // end function
  } // end class ChooseLanguage  
?>