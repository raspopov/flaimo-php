<?php
/**
* We need the language so we know how long date/time strings have to be displayed
*/
include_once('class.Language.inc.php'); 

/**
* Including the user class is necessary for getting the user-preferences
*/
include_once('class.User.inc.php'); 

/**
* Formats dates/times based on Language
*
* Formats date and/or timestrings in a) swatch time, b) iso time, c) local time.
* Local time means that it's formated by looking at the language set in the
* Language class above and choosing the right format. So if the language would
* be en a short formated version of a date would look like mm/dd/yy. But for
* language de it would look like dd.mm.yy. The clas is not complete in terms of
* countries. I've only added a few which i know the date formats of. feel free
* to ad yours and mail it to me.
*
* @access public
* @author Michael Wimmer <flaimo@gmx.net>
* @copyright Michael Wimmer
* @link http://www.flaimo.com/  flaimo.com
* @package FLP
* @version 1.000
*/
class FormatDate
  {
  
  /*-------------------*/
  /* V A R I A B L E S */
  /*-------------------*/
  
  /**
  * No information available
  *
  * @var unknown
  * @access private
  */ 
  var $month_name;
  
  /**
  * No information available
  *
  * @var unknown
  * @access private
  */ 
  var $day_name; 
  
  /**
  * No information available
  *
  * @var unknown
  * @access private
  */ 
  var $swatch;
  
  /**
  * No information available
  *
  * @var unknown
  * @access private
  */ 
  var $SelectCSSClass;
  
  /**
  * String Parser (needs other class)
  *
  * @var object
  * @access private
  */ 
  var $sp;
  
  /**
  * For Translation of date names (needs other class)
  *
  * @var object
  * @access private
  * @see FormatString
  */ 
  var $lg;
  
  /**
  * Holds all monthnames in english
  *
  * @var array
  * @access private
  * @see Language
  */ 
  var $month_array;
  
  /**
  * Holds all daynames in english
  *
  * @var array
  * @access private
  */ 
  var $day_array;
  
  /**
  * Holds all possible date formats (swatch, iso, default)
  *
  * @var array
  * @access private
  */ 
  var $whattimes;
  
  /**
  * For getting user settings
  *
  * @var object
  * @access private
  * @see User
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
  * @return (void)
  * @access private
  * @since 1.000 - 2002/10/10   
  */
  function FormatDate()
    {
    $this->setSelectCSSClass('ns'); // CSS-class for the dropdown menues
    $this->now_timestamp = (int) time();
    } // end function

  /*-------------------*/    
  /* F U N C T I O N S */
  /*-------------------*/
  
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
  * Returns a timestamp based on the current time
  *
  * @return (int) $now_timestamp
  * @access public
  * @since 1.000 - 2002/10/10   
  */ 
  function getNowTimestamp()
    {
    return (int) $this->now_timestamp;
    } // end function
  
  /**
  * Returns the preferred display format for date/timestrings set in the User class
  *
  * @return (int) $preferedtime
  * @access public
  * @see User::getPreferedTime()
  * @since 1.000 - 2002/10/10   
  */ 
  function getTimeset()
    {
    $this->loadUserClass();
    return (int) $this->user->getPreferedTime();
    } // end function
  
  /**
  * Returns the name for the time <select> tag
  *
  * @return (string) $timeselectname
  * @access public
  * @see getTimeSelectSessionName(), getTimeSelectCookieName(), User::getTimeSelectName()
  * @since 1.000 - 2002/10/10   
  */
  function getTimeSelectName()
    {
    $this->loadUserClass();
    return (string) $this->user->getTimeSelectName();
    } // end function 
  
  /**
  * Returns the name for the timeselect session variable
  *
  * @return (string) $timeselectsessionname
  * @access public
  * @see getTimeSelectName(), getTimeSelectCookieName(), User::getTimeSelectSessionName()
  * @since 1.000 - 2002/10/10   
  */
  function getTimeSelectSessionName()
    {
    $this->loadUserClass();
    return (string) $this->user->getTimeSelectSessionName();
    } // end function
  
  /**
  * Returns the name for the timeselect cookie variable
  *
  * @return (string) $timeselectcookiename
  * @access public
  * @see getTimeSelectName(), getTimeSelectSessionName(), User::getTimeSelectCookieName()
  * @since 1.000 - 2002/10/10   
  */
  function getTimeSelectCookieName()
    {
    $this->loadUserClass();
    return (string) $this->user->getTimeSelectCookieName();
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
 
//------------------------------------------------ 
  
  /**
  * IsValidTimeCode
  *
  * some small helper function for validating inputs as
  * valid time formats
  *
  * @param (int) $int  Has to be 0, 1 or 2
  * @return (boolean) $isvalid
  * @access public
  * @since 1.000 - 2002/10/10 
  */
  function IsValidTimeCode($int)
    {
    $isvalid = (boolean) false;
    if (preg_match('(^[0-3]{1}$)',$int) > 0)
      {
      $isvalid = (boolean) true;
      } // end if
    return (boolean) $isvalid;  
    } // end function
  
  /**
  * IsValidISODate
  *
  * some small helper function for validating inputs as
  * valid time formats
  *
  * @param (string) $string  Has to be yyyy-mm-dd
  * @return (boolean) $isvalid
  * @access public
  * @see IsValidISODateTime(), IsValidUnixTimeStamp()
  * @since 1.000 - 2002/10/10 
  */
  function IsValidISODate($string)
    {
    $isvalid = (boolean) false;
    if (preg_match('(^\d{4}-\d{2}-\d{2}$)',$string) > 0)
      {
      $isvalid = (boolean) true;
      } // end if
    return (boolean) $isvalid;  
    } // end function
   
  /**
  * IsValidISODateTime
  *
  * some small helper function for validating inputs as
  * valid time formats
  *
  * @param (string) $string  Has to be yyyy-mm-dd h:i:s
  * @return (boolean) $isvalid
  * @access public
  * @see IsValidISODate(), IsValidUnixTimeStamp()
  * @since 1.000 - 2002/10/10 
  */
  function IsValidISODateTime($string)
    {
    $isvalid = (boolean) false;
    if (preg_match('(^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$)',$string) > 0)
      {
      $isvalid = (boolean) true;
      } // end if
    return (boolean) $isvalid;  
    } // end function

  /**
  * IsValidUnixTimeStamp
  *
  * some small helper function for validating inputs as
  * valid time formats
  *
  * @param (int) $int  Has to be a timestamp
  * @return (boolean) $isvalid
  * @access public
  * @see IsValidISODate(), IsValidISODateTime()
  * @since 1.000 - 2002/10/10 
  */
  function IsValidUnixTimeStamp($int)
    {
    $isvalid = (boolean) false;
    if (preg_match('(^\d{1,10}$)',$int) > 0)
      {
      $isvalid = (boolean) true;
      } // end if
    return (boolean) $isvalid;  
    } // end function
    
//------------------------------------------------ 
  
  /**
  * ISOdateToUnixtimestamp
  *
  * some small helper function for converting different timeformats
  *
  * @param (string) $string  Has to be a ISO date
  * @return (int)  Timestamp
  * @access public
  * @see ISOdatetimeToUnixtimestamp(), UnixtimestampToISOdate(), UnixtimestampToISOtime(), UnixtimestampToISOdatetime()
  * @since 1.000 - 2002/10/10 
  */
  function ISOdateToUnixtimestamp($string = '1900-01-01')
    {
    list($year,$month,$day) = split('-',$string);
    return (int) mktime(0, 0, 0, $month, $day, $year);
    } // end function
  
  /**
  * ISOdatetimeToUnixtimestamp
  *
  * some small helper function for converting different timeformats
  *
  * @param (string) $string  Has to be a ISO date + ISO time
  * @return (int)  Timestamp
  * @access public
  * @see ISOdateToUnixtimestamp(), UnixtimestampToISOdate(), UnixtimestampToISOtime(), UnixtimestampToISOdatetime()
  * @since 1.000 - 2002/10/10 
  */
  function ISOdatetimeToUnixtimestamp($string = '1900-01-01 00:00:00')
    {
    list($date,$time) = split(' ',$string);
    list($year,$month,$day) = split('-',$date);
    list($hour,$min,$sec) = split(':',$time);
    return (int) mktime($hour, $min, $sec, $month, $day, $year);
    } // end function
  
  /**
  * UnixtimestampToISOdate
  *
  * some small helper function for converting different timeformats
  *
  * @param (int) $timestamp  Has to be a timestamp
  * @return (string)  ISO date
  * @access public
  * @see ISOdateToUnixtimestamp(), ISOdatetimeToUnixtimestamp(), UnixtimestampToISOtime(), UnixtimestampToISOdatetime()
  * @since 1.000 - 2002/10/10 
  */
  function UnixtimestampToISOdate($timestamp = 0)
    {
    if ($timestamp == 0) { $timestamp = (int) $this->getNowTimestamp() ; }
    return (string) date('Y-m-d',$timestamp);
    } // end function
  
  /**
  * UnixtimestampToISOtime
  *
  * some small helper function for converting different timeformats
  *
  * @param (int) $timestamp  Has to be a timestamp
  * @return (string)  ISO time
  * @access public
  * @see ISOdateToUnixtimestamp(), ISOdatetimeToUnixtimestamp(), UnixtimestampToISOdate(), UnixtimestampToISOdatetime()
  * @since 1.000 - 2002/10/10 
  */
  function UnixtimestampToISOtime($timestamp = 0)
    {
    if ($timestamp == 0) { $timestamp = (int) $this->getNowTimestamp() ; }
    return (string) date('H:i:s',$timestamp);
    } // end function
  
  /**
  * UnixtimestampToISOdatetime
  *
  * some small helper function for converting different timeformats
  *
  * @param (int) $timestamp  Has to be a timestamp
  * @return (string)  ISO date + time
  * @access public
  * @see ISOdateToUnixtimestamp(), ISOdatetimeToUnixtimestamp(), UnixtimestampToISOdate(), UnixtimestampToISOtime()
  * @since 1.000 - 2002/10/10 
  */
  function UnixtimestampToISOdatetime($timestamp = 0)
    {
    if ($timestamp == 0) { $timestamp = (int) $this->getNowTimestamp() ; }
    return (string) date('Y-m-d H:i:s',$timestamp);
    } // end function

    
  /* MySQL Timestamp
  function ISOdateToMySQLtimestamp($timestamp)
    {
    return date('Ymd',$timestamp);
    }  
  function ISOdatetimeToMySQLtimestamp($timestamp)
    {
    return date('YmdHis',$timestamp);
    }   
  */
  
//------------------------------------------------ 
  
  /**
  * Formats float to percent
  *
  * formats a float variable to percent depending
  * on the language set in the language class
  *
  * @param (float) $float  The number
  * @param (int) $digits  number of digits to be displayed
  * @return (string) $output  formated percent number
  * @access public
  * @since 1.000 - 2002/10/10 
  */
  function Percent($float, $digits = 1) // formating percent numbers depending on the language set
    { 
    $this->loadUserClass();
    if (settype($float, 'double') == false) { return (string) $this->returnError($float, __LINE__); }
    $float = (double) ($float * 100);
    if ($this->user->getPreferedTime() == 1) // swatch time
      {
      $output = (string) number_format($float, $digits,'.',',');
      }
    elseif ($this->user->getPreferedTime() == 2) // ISO time
      {
      $output = (string) number_format($float, $digits,',','.');
      }
    else // standard-time
      {
      $this->loadLanguageClass();
      switch($this->lg->getLang())
        {
        case 'de':
        case 'es':
        case 'fr':
        case 'it':
          $output = (string) number_format($float, $digits,',','.'); 
          break;
        
        case 'en':
        default:
          $output = (string) number_format($float, $digits,'.',',');
          break;   
        } // end switch
      } // end if
    return (string) $output . '%';
    } // end function
  
//------------------------------------------------ 
  
  /**
  * Returns the name of a month depending on the Language set
  *
  * Gets the month from a given timestamp and returns
  * a translated name of it from the language class
  *
  * @param (int) $timestamp   Timestamp
  * @return (string)  Name of the month
  * @access public
  * @see DayName()
  * @since 1.000 - 2002/10/10 
  */
  function MonthName($timestamp = 0)
    {
    if ($timestamp == 0) { $timestamp = (int) $this->getNowTimestamp() ; }
    if ($this->IsValidUnixTimeStamp($timestamp) == false) { return (string) $this->returnError($timestamp, __LINE__); }
    
    if (!isset($this->month_array))
      {
      $this->month_array = (array) array('1' => 'january',
                                         '2' => 'february',
                                         '3' => 'march',
                                         '4' => 'april',
                                         '5' => 'may',
                                         '6' => 'june',
                                         '7' => 'july',
                                         '8' => 'august',
                                         '9' => 'september',
                                         '10' => 'october',
                                         '11' => 'november',
                                         '12' => 'december'); 
      } // end if    
    
    $this->loadLanguageClass();
    switch ($this->lg->getLang())
      {
      case 'de':
      case 'es':
      case 'fr':
      case 'it':
      default:
        $month = (int) date('m',$timestamp);
        $month = (int) ((substr($month,0,1) == 0) ? substr($month,1,1) : $month );
        return (string) htmlentities($this->lg->_($this->month_array[$month]));
        break;
      } // end switch
    } // end function
  
  /**
  * Returns the name of a day depending on the Language set
  *
  * Gets the day from a given timestamp and returns
  * a translated name of it from the language class
  *
  * @param (int) $timestamp  Timestamp
  * @return (string)  Name of the day
  * @access public
  * @see MonthName()
  * @since 1.000 - 2002/10/10 
  */
  function DayName($timestamp = 0)
    {
    if ($timestamp == 0) { $timestamp = (int) $this->getNowTimestamp() ; }
    if ($this->IsValidUnixTimeStamp($timestamp) == false) { return (string) $this->returnError($timestamp, __LINE__); }
    
    if (!isset($this->day_array))
      {
      $this->day_array = (array) array('0' => 'sunday',
                                       '1' => 'monday',
                                       '2' => 'tuesday',
                                       '3' => 'wednesday',
                                       '4' => 'thursday',
                                       '5' => 'friday',
                                       '6' => 'saturday');    
      } // end if    
    
    $this->loadLanguageClass();
    switch ($this->lg->getLang())
      {
      case 'de':
      case 'es':
      case 'fr':
      case 'it':
      default:
        $day = (int) date('w',$timestamp);
        return (string) htmlentities($this->lg->_($this->day_array[$day]));
        break;
      } // end switch
    } // end function
    
//------------------------------------------------ 
  
  /**
  * Returns a formated datestring from a timestamp
  *
  * Returns a formated datestring from a timestamp
  * depending on the language set by the user in the language class
  *
  * @param (int) $timestamp  Timestamp
  * @return (string)  Datestring
  * @access public
  * @see TimeString(), User::getPreferedTime()
  * @since 1.000 - 2002/10/10 
  */
  function DateString($timestamp = 0) 
    {
    $this->loadUserClass(); 
    if ($timestamp == 0) { $timestamp = (int) $this->getNowTimestamp() ; }
    if ($this->IsValidUnixTimeStamp($timestamp) == false) { return (string) $this->returnError($timestamp, __LINE__); }
    
    if ($this->user->getPreferedTime() == 1) // swatch time
      {
      return (string) $this->swatchdate($timestamp);
      }
    elseif ($this->user->getPreferedTime() == 2) // ISO time
      {
      return (string) $this->ShortDate($timestamp);
      }
    else// standard-time
      {
      $this->loadLanguageClass();
      switch($this->lg->getLang())
        {
        case 'de':
          return (string) $this->DayName($timestamp) . ', ' . date('d',$timestamp) . '. ' . $this->MonthName($timestamp) . ' ' . date('Y',$timestamp); 
          break;
        
        case 'es':
          return (string) $this->DayName($timestamp) . ', ' . date('d',$timestamp) . ' de ' . strtolower($this->MonthName($timestamp)) . ' de ' . date('Y',$timestamp); 
          break;
        
        case 'fr': case 'it':
          return (string) $this->DayName($timestamp) . ', ' . date('d',$timestamp) . ' ' . strtolower($this->MonthName($timestamp)) . ' ' . date('Y',$timestamp); 
          break;
        
        case 'en':
        default:
          return (string) preg_replace('*(st |nd |rd |th )*', '<sup>\\1</sup>',date('l, F jS Y',$timestamp), 1);
          break;   
        } // end switch
      } // end if
    } // end function
  
//------------------------------------------------ 
  
  /**
  * Returns a formated timestring from a timestamp
  *
  * Returns a formated timestring from a timestamp
  * depending on the language set by the user in the language class
  *
  * @param (int) $timestamp  Timestamp
  * @return (string)  Timestring
  * @access public
  * @see DateString(), User::getPreferedTime()
  * @since 1.000 - 2002/10/10 
  */
  function TimeString($timestamp = 0) 
    {
    $this->loadUserClass();
    if ($timestamp == 0) { $timestamp = (int) $this->getNowTimestamp() ; }
    if ($this->IsValidUnixTimeStamp($timestamp) == false) { return (string) $this->returnError($timestamp, __LINE__); }
        
    if ($this->user->getPreferedTime() == 1) // swatch time
      {
      return (string) $this->swatchtime($timestamp);
      }
    elseif ($this->user->getPreferedTime() == 2) // ISO time
      {
      return (string) date("H:i:s",$timestamp); 
      }
    else // standard-time
      {
      $this->loadLanguageClass();
      switch ($this->lg->getLang())
        {
        case 'de':
          return (string) htmlentities(date("H:i ",$timestamp) . $this->lg->_('hour')); 
          break;
        
        case 'es':
        case 'fr':
        case 'it':
          return (string) date("H:i ",$timestamp); 
          break;
        
        case 'en':
        default:
          return (string) date('h:i a',$timestamp);
          break;   
        } // end switch          
      } // end if
    } // end function
  
//------------------------------------------------ 
  
  /**
  * Returns swatch time
  *
  * @param (int) $timestamp  Timestamp
  * @return (string)  Timestring
  * @access public
  * @see swatchdate()
  * @since 1.000 - 2002/10/10
  * @author http://www.ypass.net/crap/internettime/ <http://www.ypass.net/crap/internettime/>
  */
  function swatchtime($timestamp = 0) 
    {
    if ($timestamp == 0)
      {
      $timestamp = (int) $this->getNowTimestamp();
      } // end if
    if ($this->IsValidUnixTimeStamp($timestamp) == false)
      {
      return (string) $this->returnError($timestamp, __LINE__);
      } // end if
    $rawbeat = (array) explode('.',(($timestamp % 86400) / 86.4));
    $beat = (string) sprintf('%03d', $rawbeat[0]);
    $centibeat = (string) substr($rawbeat[1], 0, 2);
    return (string) '@' . $beat . '://' . $centibeat;    
    } // end function

  /**
  * Returns swatch date
  *
  * @param (int) $timestamp  Timestamp
  * @return (string)  Datetring
  * @access public
  * @see swatchtime()
  * @since 1.000 - 2002/10/10 
  */
  function swatchdate($timestamp = '') 
    {
    if ($timestamp == 0) { $timestamp = (int) $this->getNowTimestamp() ; }
    if ($this->IsValidUnixTimeStamp($timestamp) == false)
      {
      return 'Wrong timestamp input: ' . $timestamp;
      } // end if    
    
    return (string) '@d' . date('j.m.y',$timestamp);
    } // end function
    
//------------------------------------------------ 

  /**
  * Returns long date/time string
  *
  * For displaying a combination of a long datestring and a long
  * timestring. for example, 2002-12-24 (iso) or 12/24/02 (u.s.a.)
  * depending on the language set by the user in the language class
  * 
  * @param (int) $timestamp  Timestamp
  * @return (string)  Date/time string
  * @access public
  * @see DateString(), TimeString(), ShortDate()
  * @since 1.000 - 2002/10/10 
  */
  function LongDate($timestamp = 0) 
    {
    $this->loadUserClass();
    if ($timestamp == 0) { $timestamp = (int) $this->getNowTimestamp() ; }
    if ($this->IsValidUnixTimeStamp($timestamp) == false) { return (string) $this->returnError($timestamp, __LINE__); }
    
    if ($this->user->getPreferedTime() == 1)// swatch time
      {
      return (string) $this->DateString($timestamp) . ' &#8211; ' . $this->TimeString($timestamp); 
      }
    elseif ($this->user->getPreferedTime() == 2)// ISO time
      {
      return (string) $this->DateString($timestamp) . ' &#8211; ' . $this->TimeString($timestamp); 
      }
    else
      {
      $this->loadLanguageClass();
      switch ($this->lg->getLang())// standard-time
        {
        case 'es':
          return (string) $this->DateString($timestamp) . ' a las ' . $this->TimeString($timestamp); 
          break;
        
        default:
          return (string) $this->DateString($timestamp) . ' &#8211; ' . $this->TimeString($timestamp); 
          break;
        } // end switch
      } // end if
    } // end function
   
//------------------------------------------------ 
  
  /**
  * Returns short date string
  *
  * For displaying a short datestring. for example,
  * 2002-12-24 (iso) or 12/24/02 (u.s.a.) depending
  * on the language set by the user in the language
  * class
  * 
  * @param (int) $timestamp  Timestamp
  * @return (string)  Date/time string
  * @access public
  * @see LongDate(), User::getPreferedTime()
  * @since 1.000 - 2002/10/10 
  */
  function ShortDate($timestamp = 0) 
    {
    $this->loadUserClass();
    if ($timestamp == 0) { $timestamp = (int) $this->getNowTimestamp() ; }
    if ($this->IsValidUnixTimeStamp($timestamp) == false) { return (string) $this->returnError($timestamp, __LINE__); }
    
    if ($this->user->getPreferedTime() == 1) // swatch time
      {
      return (string) $this->swatchdate($timestamp);
      }
    elseif ($this->user->getPreferedTime() == 2) // ISO time
      {
      return (string) date('Y-m-d',$timestamp); 
      }
    else // standard-time
      {
      $this->loadLanguageClass();
      switch ($this->lg->getLang())
        {
        case 'de':
        case 'es':
        case 'fr':
        case 'it':
          return (string) date('d.m.Y',$timestamp); 
          break;
        
        case 'en':
        default:
          return (string) date('n/j/y',$timestamp);
          break;   
        } // end switch
      } // end if
    } // end function
    
//------------------------------------------------ 
  
  /**
  * Returns an array with possible time formats
  *
  * Sets an array with possible variations of how the time can be displayed.
  * key is the number we need for the rest of the script, value is the name
  * of the kind of time displayed (translated)
  * 
  * @return (array) $whattimes  Possible time formats
  * @access public
  * @since 1.000 - 2002/10/10 
  */
  function getPossibleDisplayTimes()
    {
    if (!isset($this->whattimes))
      {
      $this->loadLanguageClass();
      $this->whattimes = (array) array(
                                       0 => $this->lg->__('standard_time'),
                                       1 => $this->lg->__('swatch_time'),
                                       2 => $this->lg->__('ISO_time')
                                      );      
      } // end if
    return (array) $this->whattimes;
    } // end function

  
  /**
  * Set's the language variable if it hasn't been set before
  * 
  * @return (object) $lg  Language
  * @access private
  * @see loadUserClass(), Language
  * @since 1.000 - 2002/10/10 
  */
  function loadLanguageClass()
    {
    if (!isset($this->lg))
      {
      $this->lg = (object) new Language('','lang_classFormatDate.inc');   
      } // end if
    } // end function
  

  /**
  * Set's the user variable if it hasn't been set before
  * 
  * @return (object) $user  User
  * @access private
  * @see loadLanguageClass(), User
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
  * Catches errors and displays the line if the input was somehow wrong
  * 
  * @param (mixed) $input  String that causes the error
  * @param (int) $line  Line where Error accures
  * @return (string)  Errormessage
  * @access private
  * @since 1.000 - 2002/10/10 
  */  
  function returnError($input, $line = '')
    {
    $return = (string) 'Wrong input: ' . $input . ' (' . basename(__FILE__, '.php');
    return (string) $return . ((strlen(trim($line)) < 1) ? ')' : ' | L: ' . $line . ')' );
    } // end function    
    
//------------------------------------------------ 
  
  /**
  * Input is a date in iso format. output is the age as an integer
  * 
  * @param (string) $birthday  date (ISO format)
  * @return (int)  Age
  * @access public
  * @since 1.000 - 2002/10/10 
  * @author grparks@mptek.net <grparks@mptek.net>
  */ 
  function getAge($birthday = '1900-01-01')
    {
    $today = (array) getdate();
    $month = (int) $today['mon']; 
    $day = (int) $today['mday']; 
    $year = (int) $today['year']; 
    list($byear, $bmonth, $bday) = split('-', $birthday);
    $rawage = (int) $year-$byear;
    
    if (($month < $bmonth) || (($month == $bmonth) && ($day < $bday)))
      {
      return (int) $rawage - 1;
      }
    elseif ($byear == 0)
      {
      $this->loadLanguageClass();
      return (string) ucfirst($this->lg->_('unknown'));
      }
    else
      {
      return (int) $rawage;
      } // end if
    } // end function
  } // end class FormatDate
?>