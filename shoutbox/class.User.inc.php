<?php
/**
* For grouping all the user-preferences used in the other classes
*
* @access public
* @author Michael Wimmer <flaimo@gmx.net>
* @copyright Michael Wimmer
* @link http://www.flaimo.com/  flaimo.com
* @package FLP
* @version 1.001
*/
class User
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
  var $nickname;
  
  /**
  * No Information available
  *
  * @var string
  * @access private
  */  
  var $nickname_cookiename;
  
  /**
  * No Information available
  *
  * @var string
  * @access private
  */
  var $nickname_sessionname;

  /**
  * No Information available
  *
  * @var string
  * @access private
  */
  var $email;
  
  /**
  * No Information available
  *
  * @var string
  * @access private
  */  
  var $email_cookiename;
  
  /**
  * No Information available
  *
  * @var string
  * @access private
  */
  var $email_sessionname;  
  
  /**
  * No Information available
  *
  * @var string
  * @access private
  */
  var $form_name;
  
  /**
  * No Information available
  *
  * @var string
  * @access private
  */
  var $form_mail;
  
  /**
  * No Information available
  *
  * @var string
  * @access private
  */
  var $last_visit;
  
  /**
  * No Information available
  *
  * @var string
  * @access private
  */
  var $last_visit_cookiename;
  
  /**
  * No Information available
  *
  * @var string
  * @access private
  */
  var $last_visit_sessionname;
  
  /**
  * No Information available
  *
  * @var string
  * @access private
  */  
  var $specialwordsstatusname;
  
  /**
  * No Information available
  *
  * @var string
  * @access private
  */
  var $specialWordsStatus;
  
  /**
  * No Information available
  *
  * @var int
  * @access private
  */
  var $timeset;
  
  /**
  * No Information available
  *
  * @var string
  * @access private
  */
  var $TimeSelectName;
  
  /**
  * No Information available
  *
  * @var string
  * @access private
  */
  var $TimeSelectSessionName;
  
  /**
  * No Information available
  *
  * @var string
  * @access private
  */
  var $TimeSelectCookieName;
  
  /**
  * No Information available
  *
  * @var string
  * @access private
  */
  var $LangSelectName;
  
  /**
  * No Information available
  *
  * @var string
  * @access private
  */
  var $LangSessionName;
  
  /**
  * No Information available
  *
  * @var string
  * @access private
  */
  var $LangCookieName;
  
  /**
  * No Information available
  *
  * @var string
  * @access private
  */
  var $LangContentSelectName;
  
  /**
  * No Information available
  *
  * @var string
  * @access private
  */
  var $LangContentSessionName;
  
  /**
  * No Information available
  *
  * @var string
  * @access private
  */
  var $LangContentCookieName;
  
  /**
  * No Information available
  *
  * @var string
  * @access private
  */
  var $CountrySelectName;
  
  /**
  * No Information available
  *
  * @var string
  * @access private
  */
  var $CountrySessionName;
  
  /**
  * No Information available
  *
  * @var string
  * @access private
  */
  var $CountryCookieName;
  
  /**
  * No Information available
  *
  * @var string
  * @access private
  */
  var $PreferedLanguage;
  
  /**
  * No Information available
  *
  * @var string
  * @access private
  */
  var $PreferedContentLanguage;
  
  /**
  * No Information available
  *
  * @var string
  * @access private
  */
  var $PreferedCountry;
  
  
  /**
  * Preferred timezone
  *
  * @var string
  * @access private
  */
  var $timezone;
  
  /**
  * Name of the <select> element
  *
  * @var string
  * @access private
  */
  var $timezoneSelectName;
  
  /**
  * No Information available
  *
  * @var string
  * @access private
  */
  var $timezoneCookieName;
  
  /**
  * No Information available
  *
  * @var string
  * @access private
  */
  var $timezoneSessionName;
   
  
  /**
  * Preferred Measure System
  *
  * @var string
  * @access private
  */
  var $measure_system;
  
  /**
  * Name of the <select> element
  *
  * @var string
  * @access private
  */
  var $measureSelectName;
  
  /**
  * No Information available
  *
  * @var string
  * @access private
  */
  var $measureCookieName;
  
  /**
  * No Information available
  *
  * @var string
  * @access private
  */
  var $measureSessionName;
  
  
  /*-----------------------*/
  /* C O N S T R U C T O R */
  /*-----------------------*/
  
  /**
  * Constructor
  *
  * Only job is to set all the variablesnames
  *
  * @return (void)
  * @access private
  * @since 1.000 - 2002/10/10   
  */
  function User() 
    {
    $this->nickname_cookiename = (string) 'cook_name';
    $this->nickname_sessionname = (string) 'sess_name';
    
    $this->email_cookiename = (string) 'cook_email';
    $this->email_sessionname = (string) 'sess_email';  
    
    $this->form_name = (string) 'email';
    $this->form_name = (string) 'name';  
    
    $this->last_visit_cookiename = (string) 'lastvisit';
    $this->last_visit_sessionname = (string) 'lastvisit';
    $this->specialwordsstatusname = (string) 'specialwordsstatus';
    
    $this->TimeSelectName = (string) 'timeset';
    $this->TimeSelectSessionName = (string) 'sess_timeset';
    $this->TimeSelectCookieName = (string) 'cookie_timeset'; 
    
    $this->LangSelectName = (string) 'lang'; 
    $this->LangSessionName = (string) 'sess_lang';
    $this->LangCookieName = (string) 'cookie_lang';
    
    $this->LangContentSelectName = (string) 'lang_content'; 
    $this->LangContentSessionName = (string) 'sess_lang_content';
    $this->LangContentCookieName = (string) 'cookie_lang_content';
    
    $this->CountrySelectName = (string) 'country'; 
    $this->CountrySessionName = (string) 'sess_country';
    $this->CountryCookieName = (string) 'cookie_country';
    
    $this->timezoneSelectName = (string) 'timezone';
    $this->timezoneCookieName = (string) 'cookie_timezone';
    $this->timezoneSessionName = (string) 'session_timezone';
    
    $this->measureSelectName = (string) 'measure_system';
    $this->measureCookieName = (string) 'cookie_measure';
    $this->measureSessionName = (string) 'session_measure';
    } // end constructor
  
  
  /*-------------------*/    
  /* F U N C T I O N S */
  /*-------------------*/
  
  /**
  * Sets the users nickname
  *
  * Sets the users nickname by looking if a cookie or session was
  * set with the name. If no string was found, returns an empty string
  *
  * @return (void)
  * @access private
  * @see loadMail()
  * @since 1.000 - 2002/10/10   
  */
  function loadNickname()
    {
    if (!isset($this->nickname))
      {    
      if (isset($_COOKIE[$this->nickname_cookiename])) // Überprüfung für das Vorausfüllen des Formulares
        {
        $this->nickname = (string) $_COOKIE[$this->nickname_cookiename];
        }
      elseif(isset($_SESSION[$this->nickname_sessionname]))
        {
        $this->nickname = (string) $_SESSION[$this->nickname_sessionname];
        }
      else
        {
        $this->nickname = (string) '';
        } // end if
      } // end if
    } // end function
  
  /**
  * Sets the users mail address
  *
  * Sets the users mail address by looking if a cookie or session was
  * set with the mail. If no string was found, returns an empty string
  *
  * @return (void)
  * @access private
  * @see loadNickname()
  * @since 1.000 - 2002/10/10   
  */
  function loadMail()
    {
    if (!isset($this->email))
      {
      if (isset($_COOKIE[$this->email_cookiename])) // Überprüfung für das Vorausfüllen des Formulares
        {
        $this->email = (string) $_COOKIE[$this->email_cookiename];
        }
      elseif(isset($_SESSION[$this->email_sessionname]))
        {
        $this->email = (string) $_SESSION[$this->email_sessionname];
        }
      else
        {
        $this->email = (string) '';
        } // end if
      } // end if
    } // end function

  /**
  * Saves the users nickname
  *
  * Saves the users nickname by setting a cookie and a session
  *
  * @param (string) $string  Input is a string with the nickname
  * @return (void)
  * @access private
  * @see setMail()
  * @since 1.000 - 2002/10/10   
  */
  function setNickname($string)
    {
    $this->nickname = (string) $string;
    $_SESSION[$this->nickname_sessionname] = (string) $string;
    session_register($this->nickname_sessionname);
    setcookie($this->nickname_cookiename, $string, time()+31536000);
    } // end function
 
  /**
  * Saves the users mail address
  *
  * Saves the users mail address by setting a cookie and a session
  *
  * @param (string) $string  Input is a string with the mail address
  * @return (void)
  * @access private
  * @see setNickname()
  * @since 1.000 - 2002/10/10   
  */
  function setMail($string)
    {
    $this->email = (string) $string;
    $_SESSION[$this->email_sessionname] = (string) $string;
    session_register($this->email_sessionname);
    setcookie($this->email_cookiename, $string, time()+31536000);
    } // end function

  /**
  * Sets and saves the users last visit to a webpage (ISO date - time)
  *
  * @return (void)
  * @access private
  * @since 1.000 - 2002/10/10   
  */
  function setLastVisit()
    {
    $this->last_visit = (string) date('Y-m-d H:i:s');
    setcookie($this->last_visit_cookiename, '', time()-3600);
    setcookie($this->last_visit_cookiename, $this->last_visit, time()+31536000);
    $_SESSION[$this->last_visit_sessionname] = $this->last_visit;
    session_register($this->last_visit_sessionname);   
    } // end function  
  
  /**
  * Returns the users nickname
  *
  * @return (string) $nickname  String with the nickname in it
  * @access public
  * @see getMail()
  * @since 1.000 - 2002/10/10   
  */
  function getNickname()
    {
    $this->loadNickname();
    return (string) $this->nickname;
    } // end function
  
  /**
  * Returns the users mail address
  *
  * @return (string) $email  String with the mail address in it
  * @access public
  * @see getNickname()
  * @since 1.000 - 2002/10/10   
  */
  function getMail()
    {
    $this->loadMail();
    return (string) $this->email;
    } // end function  
  
  /**
  * Returns the users last visit to a webpage (ISO date - time)
  *
  * @return (string)  String with the ISO date - time in it
  * @access public
  * @see setLastVisit()
  * @since 1.000 - 2002/10/10   
  */
  function getLastVisit() // DO TO
    {
    if (isset($_COOKIE[$this->last_visit_cookiename]))
      {
      return (string) $_COOKIE[$this->last_visit_cookiename];
      }
    elseif (isset($_SESSION[$this->last_visit_sessionname])) 
      {
      return (string) $_SESSION[$this->last_visit_sessionname];
      }    
    else
      {
      return (string) '';
      } // end if
    } // end function 
  
//------------------------------------------------ 
  
  /**
  * Set SpecialWords ON/OFF
  *
  * Defines the status if a string should be parsed though the
  * SpecialWords function
  * 
  * @return (void)
  * @access public
  * @see getSpecialWordsStatus()
  * @since 1.000 - 2002/10/10   
  */
  function setSpecialWordsStatus() // activates or deactivates underlining of special words
    {
    if (!isset($this->specialWordsStatus))
      {
      if (isset($_POST[$this->specialwordsstatusname]))
        {
        $this->specialWordsStatus = (boolean) $_POST[$this->specialwordsstatusname];
        setcookie($this->specialwordsstatusname, $this->specialWordsStatus, time()+31536000);
        $_SESSION[$this->specialwordsstatusname] = (boolean) $GLOBALS[$this->specialwordsstatusname] = (boolean) $this->specialWordsStatus;
        session_register($this->specialwordsstatusname);
        }
      elseif (isset($_COOKIE[$this->specialwordsstatusname]))
        {
        $this->specialWordsStatus = (boolean) $_COOKIE[$this->specialwordsstatusname];
        }
      elseif (isset($_SESSION[$this->specialwordsstatusname]))
        {
        $this->specialWordsStatus = (boolean) $_SESSION[$this->specialwordsstatusname];
        }
      else
        {
        $this->specialWordsStatus = (boolean) true;
        } // end if
      } // end if
    } // end function 
  
  /**
  * Show SpecialWords ON/OFF
  *
  * Returns the status if a string should be parsed though the
  * SpecialWords function
  * 
  * @return (boolean) $specialWordsStatus  Show SpecialWords YES/NO
  * @access public
  * @see setSpecialWordsStatus()
  * @since 1.000 - 2002/10/10   
  */
  function getSpecialWordsStatus()
    {
    $this->setSpecialWordsStatus();
    return (boolean) $this->specialWordsStatus;
    } // end function
  
//------------------------------------------------ 
  
  /**
  * Sets display-format for time values
  *
  * Sets the timeformat (swatch/iso/standard) depending
  * on POST/GET/COOKIE/SESSION input or else uses standard time
  * 
  * @return (void)
  * @access private
  * @see getPreferedTime()
  * @since 1.000 - 2002/10/10   
  */
  function setPreferedTime()
    {
    if (!isset($this->timeset))
      {
      if (isset($_POST[$this->TimeSelectName]))
        {
        $this->timeset = (int) $_POST[$this->TimeSelectName];
        $_SESSION[$this->TimeSelectSessionName] = $GLOBALS[$this->TimeSelectSessionName] = (int) $this->timeset;
        setcookie($this->TimeSelectCookieName, $this->timeset, time()+31536000);
        session_register($this->TimeSelectCookieName);
        }
      elseif (isset($_COOKIE[$this->TimeSelectCookieName]))
        {
        $this->timeset = (int) $_COOKIE[$this->TimeSelectCookieName];
        }
      elseif (isset($_SESSION[$this->TimeSelectSessionName]))
        {
        $this->timeset = (int) $_SESSION[$this->TimeSelectSessionName];
        }
      else
        {
        $this->timeset = (int) 0;
        } // end if
      } // end if
    } // end function

  /**
  * Returns display-format for time values
  *
  * Returns the integer code for the prefered timeformat (swatch, iso, standard)
  * 
  * @return (int) $timeset  0|1|2
  * @access public
  * @see setPreferedTime(), FormatDate::getTimeset()
  * @since 1.000 - 2002/10/10   
  */
  function getPreferedTime()
    {
    $this->setPreferedTime();
    return (int) $this->timeset;
    } // end function
  
  /**
  * Returns the name for the <select> html-element for choosing the display-format for time values
  *
  * @return (string) $TimeSelectName
  * @access public
  * @see FormatDate::getTimeSelectName()
  * @since 1.000 - 2002/10/10   
  */
  function getTimeSelectName()
    {
    return (string) $this->TimeSelectName;
    } // end function 
    
  /**
  * Returns the name for session variable for saving the display-format for time values
  *
  * @return (string) $TimeSelectSessionName
  * @access public
  * @see FormatDate::getTimeSelectSessionName()
  * @since 1.000 - 2002/10/10   
  */
  function getTimeSelectSessionName()
    {
    return (string) $this->TimeSelectSessionName;
    } // end function
    
  /**
  * Returns the name for cookie variable for saving the display-format for time values
  *
  * @return (string) $TimeSelectCookieName
  * @access public
  * @see FormatDate::getTimeSelectCookieName()
  * @since 1.000 - 2002/10/10   
  */
  function getTimeSelectCookieName()
    {
    return (string) $this->TimeSelectCookieName;
    } // end function

//------------------------------------------------ 
  
  /**
  * Looks up post/session/cookies for a transmitted iso-code
  *
  * @return (void)
  * @access private
  * @see getPreferedLanguage()
  * @since 1.000 - 2002/10/10   
  */
  function setPreferedLanguage()
    {
    if(!isset($this->PreferedLanguage))
      {
      if (isset($_POST[$this->LangSelectName]))
        {
        $this->PreferedLanguage = (string) $_POST[$this->LangSelectName];
        setcookie($this->LangCookieName, $this->PreferedLanguage, time()+31536000);
        $_SESSION[$this->LangSessionName] = (string) $GLOBALS[$this->LangSessionName] = (string) $this->PreferedLanguage;
        session_register($this->LangSessionName);
        }
      elseif (isset($_COOKIE[$this->LangCookieName]))
        {
        $this->PreferedLanguage = (string) $_COOKIE[$this->LangCookieName];
        }
      elseif (isset($_SESSION[$this->LangSessionName]))
        {
        $this->PreferedLanguage = (string) $_SESSION[$this->LangSessionName];
        }
      else
        {
        $this->PreferedLanguage = (string) '';
        } // end if
      } // end if   
    } // end function
  

  /**
  * Returns the iso-code
  *
  * @return (string) $PreferedLanguage  iso-code
  * @access public
  * @see setPreferedLanguage(), Language::ChooseLang()
  * @since 1.000 - 2002/10/10   
  */
  function getPreferedLanguage()
    {
    $this->setPreferedLanguage();
    return (string) $this->PreferedLanguage;
    } // end function
  

  /**
  * Looks up post/session/cookies for a transmitted iso-code
  *
  * @return (void)
  * @access private
  * @see getPreferedContentLanguage()
  * @since 1.000 - 2002/10/10   
  */
  function setPreferedContentLanguage() // PHP5: private
    {
    if(!isset($this->PreferedContentLanguage))
      {
      if (isset($_POST[$this->LangContentSelectName]))
        {
        $this->PreferedContentLanguage = (string) $_POST[$this->LangContentSelectName];
        setcookie($this->LangContentCookieName, $this->PreferedContentLanguage, time()+31536000);
        $_SESSION[$this->LangContentSessionName] = (string) $GLOBALS[$this->LangContentSessionName] = (string) $this->PreferedContentLanguage;
        session_register($this->LangContentSessionName);
        }
      elseif (isset($_COOKIE[$this->LangContentCookieName]))
        {
        $this->PreferedContentLanguage = (string) $_COOKIE[$this->LangContentCookieName];
        }
      elseif (isset($_SESSION[$this->LangContentSessionName]))
        {
        $this->PreferedContentLanguage = (string) $_SESSION[$this->LangContentSessionName];
        }
      else
        {
        $this->PreferedContentLanguage = (string) '';
        } // end if
      } // end if   
    } // end function

  /**
  * Returns the iso-code
  *
  * @return (string) $PreferedContentLanguage  iso-code
  * @access public
  * @see setPreferedContentLanguage(), Language::ChooseLangContent()
  * @since 1.000 - 2002/10/10   
  */
  function getPreferedContentLanguage()// PHP5: public
    {
    $this->setPreferedContentLanguage();
    return (string) $this->PreferedContentLanguage;
    } // end function

  /**
  * Looks up post/session/cookies for a transmitted iso-code
  *
  * @return (void)
  * @access private
  * @see getPreferedCountry()
  * @since 1.000 - 2002/10/10   
  */
  function setPreferedCountry()
    {
    if(!isset($this->PreferedCountry))
      {
      if (isset($_POST[$this->CountrySelectName]))
        {
        $this->PreferedCountry = (string) $_POST[$this->CountrySelectName];
        setcookie($this->CountryCookieName, $this->PreferedCountry, time()+31536000);
        $_SESSION[$this->CountrySessionName] = (string) $GLOBALS[$this->CountrySessionName] = (string) $this->PreferedCountry;
        session_register($this->CountrySessionName);
        }
      elseif (isset($_COOKIE[$this->CountryCookieName]))
        {
        $this->PreferedCountry = (string) $_COOKIE[$this->CountryCookieName];
        }
      elseif (isset($_SESSION[$this->CountrySessionName]))
        {
        $this->PreferedCountry = (string) $_SESSION[$this->CountrySessionName];
        }
      else
        {
        $this->PreferedCountry = (string) '';
        } // end if
      } // end if   
    } // end function
  
  /**
  * Returns the iso-code
  *
  * @return (string) $PreferedCountry  iso-code
  * @access public
  * @see setPreferedCountry(), Language::ChooseCountry()
  * @since 1.000 - 2002/10/10   
  */
  function getPreferedCountry()
    {
    $this->setPreferedCountry();
    return (string) $this->PreferedCountry;
    } // end function
  
  
  /**
  * Looks up post/session/cookies for a transmitted timezone
  *
  * @return (void)
  * @access private
  * @see getPreferedTimezone()
  * @since 1.001 - 2002/11/09   
  */
  function setPreferedTimezone()
    {
    if(!isset($this->timezone))
      {
      if (isset($_POST[$this->timezoneSelectName]))
        {
        $this->timezone = (string) $_POST[$this->timezoneSelectName];
        setcookie($this->timezoneCookieName, $this->timezone, time()+31536000);
        $_SESSION[$this->timezoneSessionName] = (string) $GLOBALS[$this->timezoneSessionName] = (string) $this->timezone;
        session_register($this->timezoneSessionName);
        }
      elseif (isset($_COOKIE[$this->timezoneCookieName]))
        {
        $this->timezone = (string) $_COOKIE[$this->timezoneCookieName];
        }
      elseif (isset($_SESSION[$this->timezoneSessionName]))
        {
        $this->timezone = (string) $_SESSION[$this->timezoneSessionName];
        }
      else
        {
        $this->timezone = (string) '';
        } // end if
      } // end if   
    } // end function
    
  /**
  * Returns the user timezone
  *
  * @return (string) $timezone
  * @access public
  * @see setPreferedTimezone()
  * @since 1.001 - 2002/11/09   
  */
  function getPreferedTimezone()
    {
    $this->setPreferedTimezone();
    return (string) $this->timezone;
    } // end function
  
  /**
  * Sets display-format for measure values
  *
  * Sets the format (metric, us) depending
  * on POST/GET/COOKIE/SESSION input
  * 
  * @return (void)
  * @access private
  * @see Measure, getPreferedMeasureSystem()
  * @since 1.001 - 2002/11/13   
  */
  function setPreferedMeasureSystem()
    {
    if (!isset($this->measure_system))
      {
      if (isset($_POST[$this->measureSelectName]))
        {
        $this->measure_system = (string) $_POST[$this->measureSelectName];
        $_SESSION[$this->measureSessionName] = $GLOBALS[$this->measureSessionName] = (string) $this->measure_system;
        setcookie($this->measureCookieName, $this->measure_system, time()+31536000);
        session_register($this->measureCookieName);
        }
      elseif (isset($_COOKIE[$this->measureCookieName]))
        {
        $this->measure_system = (string) $_COOKIE[$this->measureCookieName];
        }
      elseif (isset($_SESSION[$this->measureSessionName]))
        {
        $this->measure_system = (string) $_SESSION[$this->measureSessionName];
        }
      else
        {
        $this->measure_system = (string) '';
        } // end if
      } // end if
    } // end function

  /**
  * Returns display-format for measure values
  *
  * Returns the string code for the prefered measure system (metric, us)
  * 
  * @return (string) $measure_system  si|uscs
  * @access public
  * @see Measure, setPreferedMeasureSystem(), Measure::setOutput()
  * @since 1.001 - 2002/11/13   
  */
  function getPreferedMeasureSystem()
    {
    $this->setPreferedMeasureSystem();
    return (string) $this->measure_system;
    } // end function  
  
  
  /**
  * Returns the name for the timezone <select> tag
  *
  * @return (string) $timezoneSelectName
  * @access public
  * @see getTimezoneSessionName(), getTimezoneCookieName()
  * @since 1.001 - 2002/11/09   
  */
  function getTimezoneSelectName()
    {
    return (string) $this->timezoneSelectName;
    } // end function 
  
  /**
  * Returns the name for the timezone session variable
  *
  * @return (string) $timezoneSessionName
  * @access public
  * @see getTimezoneSelectName(), getTimezoneCookieName()
  * @since 1.001 - 2002/11/09   
  */
  function getTimezoneSessionName()
    {
    return (string) $this->timezoneSessionName;
    } // end function 
  
  /**
  * Returns the name for the timezone cookie variable
  *
  * @return (string) $timezoneCookieName
  * @access public
  * @see getTimezoneSelectName(), getTimezoneSessionName()
  * @since 1.001 - 2002/11/09   
  */  
  function getTimezoneCookieName()
    {
    return (string) $this->timezoneCookieName;
    } // end function
  
  
  /**
  * Returns the name for the language <select> tag
  *
  * @return (string) $LangSelectName
  * @access public
  * @see getLangSessionName(), getLangCookieName()
  * @since 1.000 - 2002/10/10   
  */
  function getLangSelectName()
    {
    return (string) $this->LangSelectName;
    } // end function 
  
  /**
  * Returns the name for the language session variable
  *
  * @return (string) $LangSessionName
  * @access public
  * @see getLangSelectName(), getLangCookieName()
  * @since 1.000 - 2002/10/10   
  */
  function getLangSessionName()
    {
    return (string) $this->LangSessionName;
    } // end function 
  
  /**
  * Returns the name for the language cookie variable
  *
  * @return (string) $LangCookieName
  * @access public
  * @see getLangSelectName(), getLangSessionName()
  * @since 1.000 - 2002/10/10   
  */  
  function getLangCookieName()
    {
    return (string) $this->LangCookieName;
    } // end function
  
  /**
  * Returns the name for the content-language <select> tag
  *
  * @return (string) $LangContentSelectName
  * @access public
  * @see getLangContentSessionName(), getLangContentCookieName()
  * @since 1.000 - 2002/10/10   
  */
  function getLangContentSelectName()
    {
    return (string) $this->LangContentSelectName;
    } // end function  
  
  /**
  * Returns the name for the content-language session variable
  *
  * @return (string) $LangContentSessionName
  * @access public
  * @see getLangContentSelectName(), getLangContentCookieName()
  * @since 1.000 - 2002/10/10   
  */
  function getLangContentSessionName()
    {
    return (string) $this->LangContentSessionName;
    } // end function 
  
  /**
  * Returns the name for the content-language cookie variable
  *
  * @return (string) $LangContentCookieName
  * @access public
  * @see getLangContentSelectName(), getLangContentSessionName()
  * @since 1.000 - 2002/10/10   
  */  
  function getLangContentCookieName()
    {
    return (string) $this->LangContentCookieName;
    } // end function
  
  /**
  * Returns the name for the country <select> tag
  *
  * @return (string) $CountrySelectName
  * @access public
  * @see getCountrySessionName(), getCountryCookieName()
  * @since 1.000 - 2002/10/10   
  */
  function getCountrySelectName()
    {
    return (string) $this->CountrySelectName;
    } // end function  
  
  /**
  * Returns the name for the country session variable
  *
  * @return (string) $CountrySessionName
  * @access public
  * @see getCountrySelectName(), getCountryCookieName()
  * @since 1.000 - 2002/10/10   
  */  
  function getCountrySessionName()
    {
    return (string) $this->CountrySessionName;
    } // end function 
  
  /**
  * Returns the name for the country cookie variable
  *
  * @return (string) $CountryCookieName
  * @access public
  * @see getCountrySelectName(), getCountrySessionName() 
  * @since 1.000 - 2002/10/10   
  */  
  function getCountryCookieName()
    {
    return (string) $this->CountryCookieName;
    } // end function
  } // end class User
?>