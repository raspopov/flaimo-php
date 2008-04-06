<?php
/**
* Timezone Object
*
* Collects data from the ini file for a certain timezone.
*
* @access public
* @author Michael Wimmer <flaimo@gmx.net>
* @copyright Michael Wimmer
* @link http://www.flaimo.com/  flaimo.com
* @version 1.000
*/
class Timezone
  {
  
  /*-------------------*/
  /* V A R I A B L E S */
  /*-------------------*/
  
  /**
  * Short name of the timezone (CET, GMT, ...)
  *
  * @var (string) short_name
  * @access private
  */
  var $short_name;
  
  /**
  * Long name of the timezone (Central European Time, Greenwich Mean Time, ...)
  *
  * @var (string) long_name
  * @access private
  */
  var $long_name;
  
  /**
  * Difference in (+|-)seconds to GMT)
  *
  * @var (int) timedifference
  * @access private
  */
  var $timedifference;
  
  /**
  * Starting month for Daylight Saving Time
  *
  * @var (int) dst_month_start
  * @access private
  */
  var $dst_month_start;
  
  /**
  * Starting day for Daylight Saving Time
  *
  * @var (int) dst_day_start
  * @access private
  */
  var $dst_day_start;
  
  /**
  * Ending month for Daylight Saving Time
  *
  * @var (int) dst_month_end
  * @access private
  */
  var $dst_month_end;
  
  /**
  * Ending day for Daylight Saving Time
  *
  * @var (int) dst_day_end
  * @access private
  */
  var $dst_day_end;
  
  /**
  * Languages spoken in that timezone
  *
  * @var (array) languages
  * @access private
  */
  var $languages;
  
  /**
  * Daylight saving time offset in seconds
  *
  * @var (int) dst_offset
  * @access private
  */
  var $dst_offset;
  
  /**
  * Current timedifference between GMT and timezone (+|- DST offset)
  *
  * @var (int) current_timedif
  * @access private
  */
  var $current_timedif;
  
  /**
  * Is current date in DST?
  *
  * @var (boolean) dst
  * @access private
  */
  var $dst;
  
  /**
  * Array with all the timezonedata
  *
  * @var (array) timezonedata
  * @access private
  */
  var $timezonedata;

  /**
  * Name of the ini file with timezonedata
  *
  * @var (array) timezonesfile
  * @access private
  */
  var $timezonesfile;

  
  /*-----------------------*/
  /* C O N S T R U C T O R */
  /*-----------------------*/
 
  /**
  * Constructor
  *
  * @param (string) short_name  Short name for the timezone
  * @return (void)
  * @access private
  * @since 1.000 - 2002/11/10   
  */
  function Timezone($short_name = '')
    {
    $this->timezonesfile = (string) 'timezones.ini';
    $this->setData($short_name);
    } // end function


  /**
  * Loads the array with the raw timezone data
  *
  * @return (void)
  * @access private
  * @see setData()
  * @since 1.000 - 2002/11/10   
  */
  function loadTimezoneData()
    {
    if (!isset($this->timezonedata) && file_exists($this->timezonesfile))
      {
      $this->timezonedata = (array) parse_ini_file($this->timezonesfile, TRUE);
      } // end if
    } // end function
   
  /**
  * Sets all the variables based on the given timezone code
  *
  * @return (void)
  * @access private
  * @see loadTimezoneData()
  * @since 1.000 - 2002/11/10   
  */
  function setData($short_name)
    {
    $this->loadTimezoneData();
    if (array_key_exists($short_name, $this->timezonedata))
      {
      $this->short_name = (string) $short_name;
      $this->long_name = (string) $this->timezonedata[$short_name]['long_name'];
      $this->timedifference = (int) ($this->timezonedata[$short_name]['timedifference'] * 60);
      $dst_start = explode('-',$this->timezonedata[$short_name]['dst_start']);
      $dst_end = explode('-',$this->timezonedata[$short_name]['dst_end']);
      $this->dst_month_start = (int) $dst_start[0];
      $this->dst_day_start = (int) $dst_start[1];
      $this->dst_month_end = (int) $dst_end[0];
      $this->dst_day_end = (int) $dst_end[1];
      $this->dst_offset = (int) ($this->timezonedata[$short_name]['dst_offset'] * 60);
      $this->languages = (array) explode('|',$this->timezonedata[$short_name]['language']);
      
      $dst_start_ts = (int) mktime(0, 0, 0, $this->dst_month_start, $this->dst_day_start, date('Y'));
      $dst_end_ts = (int) mktime(0, 0, 0, $this->dst_month_end, $this->dst_day_end, (date('Y')+1));

      $this->current_timedif = (int) $this->timedifference;
      if (time() >= $dst_start_ts && time() <= $dst_end_ts)
        {
        $this->current_timedif += (int) $this->dst_offset;
        $this->dst = (boolean) true;
        } // end if

      unset($dst_start);
      unset($dst_end);
      unset($dst_start_ts);
      unset($dst_end_ts);
      unset($this->timezonedata);
      }
    else
      {
      die('Timezone Class: Timezone not found');
      exit;
      } // end if
    } // end function
  
  
  /**
  * Returns short_name variable
  *
  * @return (string) short_name
  * @access public
  * @see $short_name
  * @since 1.000 - 2002/11/10   
  */
  function getShortName()
    {
    return (string) $this->short_name;
    } // end function
  
  /**
  * Returns long_name variable
  *
  * @return (string) long_name
  * @access public
  * @see $long_name
  * @since 1.000 - 2002/11/10   
  */
  function getLongName()
    {
    return (string) $this->long_name;
    } // end function
 
  /**
  * Returns timedifference variable
  *
  * @return (int) timedifference
  * @access public
  * @see $timedifference
  * @since 1.000 - 2002/11/10   
  */
  function getTimeDifference()
    {
    return (int) $this->timedifference;
    } // end function
  
  /**
  * Returns current_timedif variable
  *
  * @return (int) current_timedif
  * @access public
  * @see $current_timedif
  * @since 1.000 - 2002/11/10   
  */
  function getCurrentTimeDifference()
    {
    return (int) $this->current_timedif;
    } // end function
  
  /**
  * Returns dst variable
  *
  * @return (boolean) dst
  * @access public
  * @see $dst
  * @since 1.000 - 2002/11/10   
  */
  function isDST()
    {
    return (boolean) $this->dst;
    } // end function
  
  /**
  * Returns dst_offset variable
  *
  * @return (int) dst_offset
  * @access public
  * @see $dst_offset
  * @since 1.000 - 2002/11/10   
  */
  function getDSToffset()
    {
    return (int) $this->dst_offset;
    } // end function  
  
  /**
  * Returns languages variable
  *
  * @return (array) language
  * @access public
  * @see $languages
  * @since 1.000 - 2002/11/10   
  */
  function getLanguages()
    {
    return (array) $this->languages;
    } // end function  
  } // end class Timezone 
?>