<?php
/**
* base class
*/
include_once('class.FormatDate.inc.php');

/**
* Creates a dropdown menu for selecting the time format
*
* @access public
* @author Michael Wimmer <flaimo@gmx.net>
* @copyright Michael Wimmer
* @link http://www.flaimo.com/  flaimo.com
* @see FormatDate
* @package FLP
* @version 1.000
*/
class ChooseFormatDate extends FormatDate
  {
  
  /*-------------------*/
  /* V A R I A B L E S */
  /*-------------------*/
  
  /**
  * Contains the type of the displayformat
  *
  * @var int
  * @access private
  */ 
  var $whattimes;
 
  /*-----------------------*/
  /* C O N S T R U C T O R */
  /*-----------------------*/
  
  /**
  * Constructor
  *
  * Calls the constructor of the base class
  *
  * @return (void)
  * @access private
  * @see FormatDate::FormatDate()
  * @since 1.000 - 2002/10/10   
  */
  function ChooseFormatDate()
    {
    parent::FormatDate();
    } // end function

  /*-------------------*/    
  /* F U N C T I O N S */
  /*-------------------*/
  
  /**
  * Creates a dropdown menu for selecting the time format
  *
  * @return (string) $output_dropdown  + <select> tag with possible time formats
  * @access public
  * @since 1.000 - 2002/10/10   
  */
  function returnDropdownSelecttime()
    {
    $output_dropdown = (string) '<select name="' . parent::getTimeSelectName() . '" id="' . parent::getTimeSelectName() . '" class="' . parent::getSelectCSSClass() . '">';
    foreach (parent::getPossibleDisplayTimes() as $list_times => $list_times_names)
      { 
      $timeselect = (string) (($list_times == parent::getTimeset()) ? ' selected="selected"' : '' );
      $output_dropdown .= (string) '<option value="' . $list_times . '"' . $timeselect . '>' . $list_times_names . '</option>';
      } // end foreach
    unset($timeselect);
    return (string) $output_dropdown . '</select>';
    } // end function
  } // end class ChooseFormatDate
?>