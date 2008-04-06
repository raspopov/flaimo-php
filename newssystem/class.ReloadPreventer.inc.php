<?php
/**
* Preventing data being parsed twice
*
* You have to be careful to call the setToken method AFTER you
* have valitated all of your important code fragments, otherwise
* the session token and the form token will never match up.
* Last change: 2002-09-25
*
* @access public
* @author Michael Wimmer <flaimo@gmx.net>
* @copyright Michael Wimmer
* @link http://www.flaimo.com/  flaimo.com
* @package FLP
* @version 1.000
*/
class ReloadPreventer
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
  var $token;
  
  /**
  * No Information available
  *
  * @var string
  * @access private
  */
  var $sessionName;
  
  /**
  * No Information available
  *
  * @var string
  * @access private
  */
  var $formName;
  
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
  function ReloadPreventer()
    {
    $this->setSessionName('token_session');
    $this->setFormName('token');
    } // end function
  
  
  /*-------------------*/    
  /* F U N C T I O N S */
  /*-------------------*/
  
  /**
  * Sets token class variable
  *
  * @param (string) $value
  * @return (void)
  * @access private
  * @since 1.000 - 2002/10/10   
  */
  function setToken($value)
    {
    $this->token = (string) $value;
    } // end function
  
  /**
  * Returns token
  *
  * @return (string) $token
  * @access public
  * @since 1.000 - 2002/10/10   
  */
  function getToken()
    {
    return (string) $this->token;
    } // end function

  /**
  * Sets the name for the session variable
  *
  * @param (string) $string
  * @return (void)
  * @access private
  * @since 1.000 - 2002/10/10   
  */
  function setSessionName($string)
    {
    $this->sessionName = (string) $string;
    } // end function
  
  /**
  * Returns session name
  *
  * @return (string) $sessionName
  * @access public
  * @since 1.000 - 2002/10/10   
  */  
  function getSessionName()
    {
    return (string) $this->sessionName;
    } // end function

  /**
  * Sets the name for the form name (POST)
  *
  * @param (string) $string
  * @return (void)
  * @access private
  * @since 1.000 - 2002/10/10   
  */
  function setFormName($string)
    {
    $this->formName = (string) $string;
    } // end function
  
  /**
  * Returns form name
  *
  * @return (string) $formName
  * @access public
  * @since 1.000 - 2002/10/10   
  */
  function getFormName()
    {
    return (string) $this->formName;
    } // end function
  
//------------------------------------------------ 

  /**
  * Creates a new token
  *
  * Creates a token based on md5 value of the current time in msec,
  * creates a session with that token.
  *
  * @return (void)
  * @access public
  * @see setTokenSession()
  * @since 1.000 - 2002/10/10   
  */  
  function setTokenSession() // PHP5: public
    {
    session_register($this->sessionName);
    $this->setToken(md5(microtime()));
    $_SESSION[$this->sessionName] = (string) $this->token;
    } // end function
  
  /**
  * Checks if token is valid
  *
  * Compares the session with the transmitted token from the form.
  * If they don't match, a boolean "false" is returned.
  *
  * @return (boolean)
  * @access public
  * @see setTokenSession()
  * @since 1.000 - 2002/10/10   
  */  
  function isValid()
    {
    if (!isset($_POST[$this->formName]) || !isset($_SESSION[$this->sessionName]))
      {
      return (boolean) false;
      }
    else
      {
      return (boolean) (($_POST[$this->formName] != $_SESSION[$this->sessionName]) ? false : true);
      } // end if
    } // end function
  } // end class ReloadPreventer
?>