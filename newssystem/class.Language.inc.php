<?php
/**
* Including the user class is necessary for getting the user-preferences
*/
require_once('class.User.inc.php');

/**
* Everything concerning the osm/content-language and the country for a user
*
* Best used for internationalisation. You create a subdirectory
* “language” on your webspace, which contains iso-standard named
* subdirectories again. in each of those directories you put a
* translation file. In your php script you create an object like this:
* "$lg = (object) new Language();" and translate a variable:
* "echo $lg->__('no_records_found');" There are a couple of different
* ways to define the language that should be used for translation. First
* the script looks out for a variable given by POST or GET, then tries
* to read from a cookie or session. If still no variable has been found
* it checks the prefered language set by the user's browser and at last
* trys to get the domain from the users ip number. Since it's a class
* you can create multible objects from it and even use more that one
* language on one page.
* Last change: 2002-09-25
*
* @access public
* @author Michael Wimmer <flaimo@gmx.net>
* @copyright Michael Wimmer
* @link http://www.flaimo.com/  flaimo.com
* @package FLP
* @version 1.002
*/
class Language
  {
  
  /*-------------------*/
  /* V A R I A B L E S */
  /*-------------------*/
  
  /**
  * No Information available
  *
  * @var string
  * @access private
  */
  var $input_lang; // PHP5: protected
 
  /**
  * No Information available
  *
  * @var string
  * @access public
  */
  var $lang;
  
  /**
  * No Information available
  *
  * @var string
  * @access public
  */
  var $country;
  
  /**
  * No Information available
  *
  * @var string
  * @access private
  */
  var $language; // PHP5: protected
  
  /**
  * No Information available
  *
  * @var string
  * @access public
  */
  var $lang_content;

  /**
  * No Information available
  *
  * @var string
  * @access private
  */
  var $SelectCSSClass;
  
  /**
  * No Information available
  *
  * @var string
  * @access private
  */
  var $languagefile_path;
  
  /**
  * No Information available
  *
  * @var string
  * @access private
  */
  var $languagefile_name;
  
  /**
  * No Information available
  *
  * @var array
  * @access private
  */
  var $userRawArray;
  
  /**
  * No Information available
  *
  * @var array
  * @access private
  */
  var $userLangArray;
  
  /**
  * No Information available
  *
  * @var array
  * @access private
  */
  var $userCountryArray;
  
  /**
  * No Information available
  *
  * @var array
  * @access private
  */
  var $languageFilesArray;
  
  /**
  * No Information available
  *
  * @var string
  * @access private
  */
  var $defaultLanguage; // PHP5: protected
  
  /**
  * Holds the user object
  *
  * @var object
  * @access private
  */
  var $user;
  
  /*-----------------------*/
  /* C O N S T R U C T O R */
  /*-----------------------*/
  
  /**
  * Constructor
  *
  * No information available
  *
  * @param (string) $inputlang  You can set a language manually and override all other settings
  * @return (void)
  * @access private
  * @since 1.000 - 2002/10/10   
  */
  function Language($inputlang = '', $translationfile = 'lang_main.inc')
    { 
    $this->setSelectCSSClass('ns');
    $this->setLanguageFilePath('languages');
    $this->setLanguageFileName($translationfile);
    $this->defaultLanguage = (string) 'en';
  
    $this->inputlang = (string) $inputlang;
    $this->inputcountry = (string) (((isset($_GET[$this->getCountrySelectName()])) && (strlen(trim($_GET[$this->getCountrySelectName()])) > 0)) ? $_GET[$this->getCountrySelectName()] : '');
  
    $this->ChooseLang();
    $this->ChooseLangContent();
    $this->ChooseCountry();    
    } // end constructor

  /*-------------------*/    
  /* F U N C T I O N S */
  /*-------------------*/
  
  /**
  * Returns the ISO code for the content
  *
  * @return (string) $lang_content
  * @access public
  * @see getLang(), getCountry()
  * @since 1.000 - 2002/10/10   
  */
  function getLangContent()
    {
    return (string) $this->lang_content;
    } // end function
  
  /**
  * Returns the ISO code for the (osm)language
  *
  * @return (string) $lang
  * @access public
  * @see getLangContent(), getCountry()
  * @since 1.000 - 2002/10/10   
  */
  function getLang()
    {
    return (string) $this->lang;
    } // end function
  
  /**
  * Returns the ISO code for the country
  *
  * @return (string) $country
  * @access public
  * @see getLangContent(), getLang()
  * @since 1.000 - 2002/10/10   
  */
  function getCountry()
    {
    return (string) $this->country; 
    } // end function
  
  /**
  * Returns the ISO code for the input language
  *
  * @return (string) $inputlang
  * @access public
  * @see getInputCountry()
  * @since 1.000 - 2002/10/10   
  */
  function getInputLang()
    {
    return (string) $this->inputlang;
    } // end function
  
  /**
  * Returns the ISO code for the input country
  *
  * @return (string) $inputcountry
  * @access public
  * @see getInputLang()
  * @since 1.000 - 2002/10/10   
  */
  function getInputCountry()
    {
    return (string) $this->inputcountry;
    } // end function 

  /**
  * Returns the name for the language <select> tag
  *
  * @return (string) $langselectname
  * @access public
  * @see getLangSessionName(), getLangCookieName(), User::getLangSelectName()
  * @since 1.000 - 2002/10/10   
  */
  function getLangSelectName()
    {
    $this->loadUserClass();
    return (string) $this->user->getLangSelectName();
    } // end function 
  
  /**
  * Returns the name for the language session variable
  *
  * @return (string) $langsessionname
  * @access public
  * @see getLangSelectName(), getLangCookieName(), User::getLangSessionName()
  * @since 1.000 - 2002/10/10   
  */
  function getLangSessionName()
    {
    $this->loadUserClass();
    return (string) $this->user->getLangSessionName();
    } // end function
  
  /**
  * Returns the name for the language cookie variable
  *
  * @return (string) $langcookiename
  * @access public
  * @see getLangSelectName(), getLangSessionName(), User::getLangCookieName()
  * @since 1.000 - 2002/10/10   
  */  
  function getLangCookieName()
    {
    $this->loadUserClass();
    return (string) $this->user->getLangCookieName();
    }
  
  /**
  * Returns the name for the content-language <select> tag
  *
  * @return (string) $langcontentselectname
  * @access public
  * @see getLangContentSessionName(), getLangContentCookieName()
  * @since 1.000 - 2002/10/10   
  */
  function getLangContentSelectName()
    {
    $this->loadUserClass();
    return (string) $this->user->getLangContentSelectName();
    }  
  
  /**
  * Returns the name for the content-language session variable
  *
  * @return (string) $langcontentsessionname
  * @access public
  * @see getLangContentSelectName(), getLangContentCookieName(), User::getLangContentSessionName()
  * @since 1.000 - 2002/10/10   
  */
  function getLangContentSessionName()
    {
    $this->loadUserClass();
    return (string) $this->user->getLangContentSessionName();
    } // end function
  
  /**
  * Returns the name for the content-language cookie variable
  *
  * @return (string) $langcontentcookiename
  * @access public
  * @see getLangContentSelectName(), getLangContentSessionName(), User::getLangContentCookieName()
  * @since 1.000 - 2002/10/10   
  */  
  function getLangContentCookieName()
    {
    $this->loadUserClass();
    return (string) $this->user->getLangContentCookieName();
    } // end function
  
  /**
  * Returns the name for the country <select> tag
  *
  * @return (string) $countryselectname
  * @access public
  * @see getCountrySessionName(), getCountryCookieName(), User::getCountrySelectName()
  * @since 1.000 - 2002/10/10   
  */
  function getCountrySelectName()
    {
    $this->loadUserClass();
    return (string) $this->user->getCountrySelectName();
    } // end function
  
  /**
  * Returns the name for the country session variable
  *
  * @return (string) $countrysessionname
  * @access public
  * @see getCountrySelectName(), getCountryCookieName(), User::getCountrySessionName()
  * @since 1.000 - 2002/10/10   
  */  
  function getCountrySessionName()
    {
    $this->loadUserClass();
    return (string) $this->user->getCountrySessionName();
    } // end function
  
  /**
  * Returns the name for the country cookie variable
  *
  * @return (string) $countrycookiename
  * @access public
  * @see getCountrySelectName(), getCountrySessionName(), User::getCountryCookieName()
  * @since 1.000 - 2002/10/10   
  */  
  function getCountryCookieName()
    {
    $this->loadUserClass();
    return (string) $this->user->getCountryCookieName();
    } // end function
  
  /**
  * Returns the name for the css-class attribute for form elements
  *
  * @return (string) $SelectCSSClass
  * @access public
  * @see setSelectCSSClass()
  * @since 1.000 - 2002/10/10   
  */  
  function getSelectCSSClass()
    {
    return (string) $this->SelectCSSClass;
    } // end function 
  
  /**
  * Returns the pathname for the language directory
  *
  * @return (string) $languagefile_path
  * @access public
  * @see getLanguageFileName()
  * @since 1.000 - 2002/10/10   
  */  
  function getLanguageFilePath()
    {
    return (string) $this->languagefile_path;
    } // end function
  
  /**
  * Returns the filename for the language translation-file
  *
  * @return (string) $languagefile_name
  * @access public
  * @see getLanguageFilePath()
  * @since 1.000 - 2002/10/10   
  */  
  function getLanguageFileName()
    {
    return (string) $this->languagefile_name;
    } // end function 
  
  /**
  * Returns the default language (kind of "backup" if no language ISO code wasn't found at all)
  *
  * @return (string) $defaultLanguage
  * @access public
  * @since 1.000 - 2002/10/10   
  */  
  function getDefaultLanguage()
    {
    return (string) $this->defaultLanguage;
    } // end function
  
  /**
  * Returns an array with the raw user-request header (de-at, de, en-us,...)
  *
  * @return (array) $userRawArray
  * @access public
  * @see getUserLangArray(), getUserCountryArray()
  * @since 1.000 - 2002/10/10   
  */  
  function getUserRawArray()
    {
    return (array) $this->userRawArray;
    } // end function
  
  /**
  * Returns an array with the languages extracted from the raw user-request header (de-xx, en-xx,...)
  *
  * @return (array) $userLangArray
  * @access public
  * @see getUserRawArray(), getUserCountryArray()
  * @since 1.000 - 2002/10/10   
  */  
  function getUserLangArray()
    {
    return (array) $this->userLangArray;
    } // end function
  
  /**
  * Returns an array with the countries extracted from the raw user-request header (xx-at, xx-us,...)
  *
  * @return (array) $userCountryArray
  * @access public
  * @see getUserRawArray(), getUserLangArray()
  * @since 1.000 - 2002/10/10   
  */  
  function getUserCountryArray()
    {
    return (array) $this->userCountryArray;
    } // end function
  
  /**
  * Creates a news User object if the class var user doesn't exists
  *
  * @return (void)
  * @access private
  * @see User
  * @since 1.000 - 2002/10/10   
  */  
  function loadUserClass()
    {
    if (!isset($this->user))
      {
      $this->user = (object) new User();   
      } // end if
    } // end function  

  
  /**
  * Checks the users request-header for language informations
  *
  * Returns an array or an array value from the array with all the languages
  * found in the subdirectory 'language'
  *
  * @return (mixed) $languageFilesArray  String or Array
  * @access private
  * @see setLanguageFilesArray()
  * @since 1.000 - 2002/10/10 
  */
  function getLanguageFilesArray($pos = '')
    {
    if (strlen(trim($pos)) > 0 && is_int($pos) && array_key_exists($pos, $this->languageFilesArray))
      {
      return (string) $this->languageFilesArray[$pos];
      }
    else
      {
      return (array) $this->languageFilesArray;
      } // end if
    } // end function
  

  /**
  * Sets the ISO code for the content-language
  *
  * @param (string) $string  String to be assigned to the class variable lang_content
  * @return (void)
  * @access private
  * @see setLang(), setCountry()
  * @since 1.000 - 2002/10/10   
  */ 
  function setLangContent($string)
    {
    $this->lang_content = (string) $string;
    } // end function  
  
  /**
  * Sets the ISO code for the (osm)language
  *
  * @param (string) $string  String to be assigned to the class variable lang
  * @return (void)
  * @access private
  * @see setLangContent(), setCountry()  
  * @since 1.000 - 2002/10/10   
  */ 
  function setLang($string)
    {
    $this->lang = (string) $string;
    } // end function
  
  /**
  * Sets the ISO code for the country
  *
  * @param (string) $string  String to be assigned to the class variable country
  * @return (void)
  * @access private
  * @see setLangContent(), setLang()
  * @since 1.000 - 2002/10/10   
  */ 
  function setCountry($string)
    {
    $this->country = (string) $string;
    } // end function
  
  /**
  * Sets the name for the css-class attribute for form elements
  *
  * @param (string) $string  String to be assigned to the class variable SelectCSSClass
  * @return (void)
  * @access private
  * @see getSelectCSSClass()
  * @since 1.000 - 2002/10/10   
  */ 
  function setSelectCSSClass($string)
    {
    $this->SelectCSSClass = (string) $string;
    } // end function
    
  /**
  * Sets the name for the path to the language-directory
  *
  * @param (string) $string  String to be assigned to the class variable languagefile_path
  * @return (void)
  * @access private
  * @see setLanguageFileName()
  * @since 1.000 - 2002/10/10   
  */ 
  function setLanguageFilePath($string)
    {
    $this->languagefile_path = (string) $string;
    } // end function 
  
  /**
  * Sets the name for the filename of the language translation-file
  *
  * @param (string) $string  String to be assigned to the class variable languagefile_name
  * @return (void)
  * @access private
  * @see setLanguageFilePath()
  * @since 1.000 - 2002/10/10   
  */ 
  function setLanguageFileName($string)
    {
    if (strlen(trim($string)) > 0)
      {
      $this->languagefile_name = (string) $string;
      }
    else
      {
      $this->languagefile_name = (string) 'lang_main.inc';
      } // end if
    } // end function
  
  /**
  * Adds an entry to the raw user-header array
  *
  * @param (string) $string  String to be added (ISO code)
  * @return (void)
  * @access private
  * @see setUserLangArray(), setUserCountryArray()
  * @since 1.000 - 2002/10/10   
  */ 
  function setUserRawArray($string)
    {
    $this->userRawArray[] = $string;
    } // end function
    
  /**
  * Adds an entry to the user-languages array
  *
  * @param (string) $string  String to be added (ISO code)
  * @return (void)
  * @access private
  * @see setUserRawArray(), setUserCountryArray()
  * @since 1.000 - 2002/10/10   
  */ 
  function setUserLangArray($string)
    {
    $this->userLangArray[] = $string;
    } // end function 
  
  /**
  * Adds an entry to the country array
  *
  * @param (string) $string  String to be added (ISO code)
  * @return (void)
  * @access private
  * @see setUserRawArray(), setUserLangArray()
  * @since 1.000 - 2002/10/10   
  */ 
  function setUserCountryArray($string)
    {
    $this->userCountryArray[] = $string;
    } // end function

  /**
  * Adds an entry to the "available languages" array
  *
  * @param (string) $string  String to be added (ISO code)
  * @return (void)
  * @access private
  * @see getLanguageFilesArray()
  * @since 1.000 - 2002/10/10   
  */ 
  function setLanguageFilesArray($string)
    {
    $this->languageFilesArray[] = $string;
    }   
  
  /**
  * Filters an array
  *
  * Filters all empty values and dublicates of a given array
  *
  * @param (array) $arrayname
  * @return (void)
  * @access private
  * @since 1.000 - 2002/10/10 
  */  
  function trimArray($arrayname) // PHP5: protected
    {
    if (count($this->$arrayname) > 0)
      {
      $this->$arrayname = array_values(array_unique($this->$arrayname));
      } // end if
    } // end function
    
//------------------------------------------------ 
  
  /**
  * Checks the users request-header for language informations
  *
  * Reads the header of the request and splits it up to 3 arrays:
  * one array with the raw data, one with all the possible
  * (osm)languages and one with all the possible countries. Depends on
  * how the user has set up his browser and/or the domain of his ip adress.
  *
  * @return (void)
  * @access private
  * @since 1.000 - 2002/10/10 
  */
  function readUserHeader() // PHP5: protected
    {
    $prefUserCodes = (array) explode(",",$_SERVER['HTTP_ACCEPT_LANGUAGE']);
    
    if (count($prefUserCodes) != 0) // get raw languages
      {
      foreach ($prefUserCodes as $rawEntry) // Strip ";"s
        { 
        if (strstr($rawEntry,';'))
          {
          $cutat = (int) strpos($rawEntry,';');
          $new_Raw_Entry = (string) substr($rawEntry, 0, $cutat);
          
          if ($this->IsValidLanguageCode($new_Raw_Entry))
            {
            $this->setUserRawArray(strtolower(trim($new_Raw_Entry)));
            } // end if
          }
        else
          {
          if ($this->IsValidLanguageCode($rawEntry))
            {
            $this->setUserRawArray(strtolower(trim($rawEntry)));
            } // end if
          } // end if
        } // end foreach
      }
    else
      {
      $this->setUserRawArray($this->defaultLanguage);
      } // end if
    $this->trimArray('userRawArray');
    
    foreach ($this->userRawArray as $rawEntry) // get Languages
      { 
      if (strstr($rawEntry,'-'))
        {
        $cutat = (int) strpos($rawEntry,'-');
        $new_Raw_Entry = (string) substr($rawEntry, 0, $cutat);
        $this->setUserLangArray(trim($new_Raw_Entry));
        }
      else
        {
        $this->setUserLangArray(trim($rawEntry));
        } // end if
      } // end foreach
      $this->trimArray('userLangArray');
      
    foreach ($this->userRawArray as $rawEntry) // get Countries
      { 
      if (strstr($rawEntry,'-'))
        {
        $cutat = (int) strpos($rawEntry,'-');
        $new_Raw_Entry = (string) substr($rawEntry, ($cutat+1), 2);
        $this->setUserCountryArray(trim($new_Raw_Entry));
        } // end if
      } // end foreach
    
    unset($new_Raw_Entry);
    unset($rawEntry);
      
    $hostname = (string) gethostbyaddr($_SERVER['REMOTE_ADDR']); // get country from IP address
    $lastdot = (int) strrpos($hostname,'.');

    if ($lastdot > 0)
      {
      $hostname_length = (int) strlen($hostname);
      $domain_length = (int) $hostname_length - ($lastdot+1);
      $domain_name = (string) substr($hostname, $lastdot+1);
      
      if ($domain_length > 2 || $domain_name == 'eu') // top level domains (com, org, gov, aero, info) or eu-domain are all english
        {
        $this->setUserCountryArray('en');
        } // end if        
      elseif ($domain_length == 2) // country domains
        {
        $domain = (string) substr($hostname, ($lastdot+1), $domain_length);
        $this->setUserCountryArray(trim($domain));
        }

      } // end if
    $this->trimArray('userCountryArray');
    
    if (count($this->userCountryArray) < 1) // if absolutely no country has been found
      {
      $this->setUserCountryArray($this->defaultLanguage);
      } // end if
    
    unset($hostname);
    unset($lastdot);
    } // end function

//------------------------------------------------
  

  /**
  * Checks if a given string is a valid iso-language-code
  *
  * @param (string) $code  String that should validated
  * @return (boolean) $isvalid  If string is valid or not
  * @access private
  * @since 1.000 - 2002/10/10 
  */
  function IsValidLanguageCode($code)  // PHP5: protected
    {
    $isvalid = (boolean) false;
    if (preg_match('(^([a-z]{2})$|^([a-z]{2}_[a-z]{2})$|^([a-z]{2}-[a-z]{2})$)',trim($code)) > 0)
      {
      $isvalid = (boolean) true;
      } // end if
    return (boolean) $isvalid;  
    } // end function

//------------------------------------------------
  
  /**
  * Sets an array with all available languages
  *
  * sets an array with all the available (osm)languages by reading
  * the subdirectories of the language directory and checks if a valid
  * translationfile is found there
  *
  * @return (void)
  * @access private
  * @since 1.000 - 2002/10/10 
  */
  function LanguagesFilesList()  // PHP5: protected
    {
    if (!isset($this->languageFilesArray))
      {    
      $root = (string) $this->languagefile_path . '/';
      $handle = @opendir($root);
  
      while ($lang_dir = @readdir($handle))
        {
        if (is_dir($root . $lang_dir))
          {
          if ($this->IsValidLanguageCode($lang_dir) == true && file_exists($this->languagefile_path . '/' . $lang_dir . '/' . $this->languagefile_name))
            {
            $this->setLanguageFilesArray(strtolower(trim($lang_dir)));
            } // end if
          } // end if
        } // end while
      @closedir($handle);
      unset($root);
      unset($handle);
      } // end if
    } // end function

//------------------------------------------------

  /**
  * Sets the (osm)language ISO code
  *
  * sets the (osm)language (iso code) depending on the given information available.
  * first looks if the value is set by the GET method (GET not working at the moment),
  * then checkes if a prefered code has been set in the user class. if still no value
  * has been found, it takes the language-array set by the readUserHeader() function.
  *
  * @return (void)
  * @access private
  * @see ChooseLangContent(), ChooseCountry(), User::getPreferedLanguage()
  * @since 1.000 - 2002/10/10 
  */
  function ChooseLang()  // PHP5: protected
    {
    $this->loadUserClass();
    if (strlen(trim($this->inputlang)) > 0 && $this->IsValidLanguageCode($this->inputlang) == true)
      {
      $this->setLang($this->inputlang);
      }
    elseif ($this->IsValidLanguageCode($this->user->getPreferedLanguage()) == true)
      {
      $this->setLang($this->user->getPreferedLanguage());
      }
    else
      {
      $languageSet = (boolean) false;
      /* Compare each language from the UserLanguage array with each language from the LanguageFiles array and see if one matches */
      if (!isset($this->languageFilesArray)) { $this->LanguagesFilesList(); }
      if (!isset($this->userLangArray)) { $this->readUserHeader(); }

      if (count($this->getLanguageFilesArray()) > 0)
        {      
        foreach ($this->userLangArray as $lang)
          { 
          foreach ($this->getLanguageFilesArray() as $lang_file)
            { 
            if ($lang == $lang_file && $languageSet == false)
              {
              $this->setLang($lang);
              $languageSet = (boolean) true;
              } // end if
            } // end foreach
          
          if ($languageSet == false)
            {
            $this->setLang($this->defaultLanguage);
            } // end if
          } // end foreach
        }
      else
        {
        $this->setLang($this->defaultLanguage);
        } // end if      
      
      unset($languageSet);
      } // end if
    } // end function

  /**
  * Sets the content-language ISO code
  *
  * sets the content-language (iso code) depending on the given information available.
  * first looks if the value is set by the GET method (GET not working at the moment),
  * then checkes if a prefered code has been set in the user class. if still no value
  * has been found, it takes the content-language-array set by the readUserHeader()
  * function.
  *
  * @return (void)
  * @access private
  * @see ChooseLang(), ChooseCountry(), User::getPreferedContentLanguage()
  * @since 1.000 - 2002/10/10 
  */
  function ChooseLangContent()  // PHP5: protected
    {
    $this->loadUserClass();
    if (strlen(trim($this->inputlang)) > 0 && $this->IsValidLanguageCode($this->inputlang) == true)
      {
      $this->setLangContent($this->inputlang);
      }
    elseif ($this->IsValidLanguageCode($this->user->getPreferedContentLanguage()) == true)
      {
      $this->setLangContent($this->user->getPreferedContentLanguage());
      }
    else
      {
      switch ($this->lang)
        {
        case 'de':
          $this->setLangContent($this->lang);
          break;
        
        default:
          $this->setLangContent($this->defaultLanguage);
          break;  
        } // end switch
      } // end if
    } // end function
  
  /**
  * Sets the country ISO code
  *
  * Sets the country (iso code) depending on the given information available.
  * first looks if the value is set by the GET method, then checkes if
  * if a prefered code has been set in the user class. if still no value has
  * been found, it takes the country array set by the readUserHeader() function.
  *
  * @return (void)
  * @access private
  * @see ChooseLang(), ChooseLangContent(), User::getPreferedCountry()
  * @since 1.000 - 2002/10/10 
  */
  function ChooseCountry() // PHP5: protected
    {
    $this->loadUserClass();
    if (strlen(trim($this->inputcountry)) > 0 && $this->IsValidLanguageCode($this->inputcountry) == true)
      {
      $this->setCountry($this->inputcountry);
      }
    elseif ($this->IsValidLanguageCode($this->user->getPreferedCountry()) == true)
      {
      $this->setCountry($this->user->getPreferedCountry());
      }
    else
      {
      $countrySet = (boolean) false;
      if (!isset($this->userCountryArray)) { $this->readUserHeader(); }
      
      foreach ($this->userCountryArray as $country)
        { 
        if ($countrySet == false)
          {
          switch ($country)
            {
            case 'de': case 'en':
              $this->setCountry($country);
              $countrySet = (boolean) true;
              break;     
            
            default:
              $this->setCountry($this->defaultLanguage);
              $countrySet = (boolean) true;
              break;  
            } // end switch         
          } // end if
        }  // end foreach
      } // end if
    } // end function
  
//------------------------------------------------
  
  /**
  * Reads the language file into an array
  *
  * gets the right translation-file from a subdirectory in the language
  * directory, depending on the language set
  *
  * @return (void)
  * @access private
  * @since 1.000 - 2002/10/10 
  */
  function ReadLanguageFile()  // PHP5: private (or public, depends on manual language change in php files)
    {
    if ($languagefile = (array) file($this->languagefile_path . '/' . $this->lang . '/' . $this->languagefile_name))
      {
      foreach ($languagefile as $key => $value)
        { 
        list($lang_array_key,$lang_array_value) = split(' = ', $value);
        $this->language[trim($lang_array_key)] = trim($lang_array_value);
        } // end foreach
      unset($languagefile);
      } // end if
    } // end function

  /**
  * Returns an translated string
  *
  * Important Class! Is mostly called from the pages. Returns the
  * requested word in the langage that is set by this class. The
  * dictionary is the "language.inc.php" file
  *
  * @param (string) $string  String that should be translated
  * @return (string)  Translated string
  * @access public
  * @since 1.000 - 2002/10/10 
  */
  function &Translate($string = '')  // PHP5: public
    {
    if (!isset($this->language)) { $this->ReadLanguageFile(); }
    
    if (array_key_exists(trim($string), $this->language))
      {
      return (string) $this->language[trim($string)];
      }
    else
      {
      return (string) $this->TranslateError(trim($string));
      } // end if
    } // end function

  /**
  * Returns an translated string
  *
  * Short version of the Translate function. If you have problems
  * (for example because of the gettext function) use the long named version
  *
  * @param (string) $string  String that should be translated
  * @return (string)  Translated string
  * @access public
  * @since 1.000 - 2002/10/10 
  * @sister Translate()
  */
  function &_($string = '') // PHP5: public
    {
    return (string) $this->Translate($string);
    } // end function  
  
  /**
  * Returns an HTML-encoded translated string
  *
  * Same as the Translate function + HTML encoding of special characters
  *
  * @param (string) $string  String that should be translated
  * @return (string)  Translated and encoded string
  * @access public
  * @since 1.000 - 2002/10/10 
  * @sister Translate()
  */
  function &TranslateEncode($string = '')
    {
    return (string) htmlentities($this->Translate($string));
    } // end function

  /**
  * Returns an HTML-encoded translated string
  *
  * Short version of the TranslateEncode function. If you have problems
  * (for example because of the gettext function) use the long named version
  *
  * @param (string) $string  String that should be translated
  * @return (string)  Translated and encoded string
  * @access public
  * @since 1.000 - 2002/10/10   
  * @sister TranslateEncode()
  */
  function &__($string = '')  // PHP5: public
    {
    return (string) $this->TranslateEncode($string);
    } // end function

  /**
  * Returns an errormessage
  *
  * If a string that should be translated was not found in the translation
  * array, a error message is displayed instead of the translation
  *
  * @param (string) $string  String that could not be translated
  * @return (string)  Complete errormessage
  * @access private
  * @since 1.000 - 2002/10/10 
  */
  function TranslateError($string = '') // PHP5: protected
    {
    return (string) 'Error Translating: "' . $string . '"';
    } // end function 

  } // end class Language  
?>
