<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
//+----------------------------------------------------------------------+
//| WAMP (XP-SP1/1.3.24/4.0.12/4.3.0)                                    |
//+----------------------------------------------------------------------+
//| Copyright (c) 1992-2003 Michael Wimmer                               |
//+----------------------------------------------------------------------+
//| I don't have the time to read through all the licences to find out   |
//| what the exactly say. But it's simple. It's free for non commercial  |
//| projects, but as soon as you make money with it, i want my share :-) |
//| (License : Free for non-commercial use)                              |
//+----------------------------------------------------------------------+
//| Authors: Michael Wimmer <flaimo@gmx.net>                             |
//+----------------------------------------------------------------------+
//
// $Id$

/**
* @package Ticker
* @category FLP
*/

/**
* Including the abstract class
*/
include_once 'class.TickerBase.inc.php';
/**
* Including the child class
*/
include_once 'class.TickerMessage.inc.php';

/**
* Class for creating a (email) ticker
*
* based on the idea from Markus Fraikin <mailto:markus@fraikin.net>
*
* Tested with WAMP (XP-SP1/1.3.24/4.0.12/4.3.0)
* Last change: 2003-05-24
*
* @desc Class for creating a (email) ticker
* @access public
* @author Michael Wimmer <flaimo 'at' gmx 'dot' net>
* @copyright Michael Wimmer
* @link http://www.flaimo.com/
* @example ticker_example_script.php Sample script
* @package Ticker
* @category	FLP
* @version 1.003
*/
class Ticker extends TickerBase {

	/*-------------------*/
	/* V A R I A B L E S */
	/*-------------------*/


	/**#@+
	* @access private
	*/
	/**
	* number of ticker messages to be displayed (the rest will be deleted from the mailbox)
	*
	* @desc number of ticker messages to be displayed (the rest will be deleted from the mailbox)
	* @var int
	*/
	var $list_size;

	/**
	* array with all the ticker messages
	*
	* @desc array with all the ticker messages
	* @var 	array
	*/
	var $messages;

	/**
	* array with all the ticker-id's
	*
	* @desc array with all the ticker-id's
	* @var 	array
	*/
	var $messagelist;

	/**
	* Name of the MySQL connection
	*
	* @desc Name of the MySQL connection
	* @var 	string
	*/
	var $conn;

	/**
	* Name of the MySQL Table with the translation table
	*
	* @desc Name of the MySQL Table with the translation table
	* @var 	string
	*/
	var $db_table = 'ticker_backup';

	/**
	* cookie/session name
	*
	* @desc cookie/session name
	* @var 	string
	*/
	var $cookie_name = 'ticker_comment';

	/**
	* conn string for connectiong to mailbox
	*
	* @desc conn string for connectiong to mailbox
	* @var 	string
	*/
	var $mail_conn_string;


    /*-----------------------*/
	/* C O N S T R U C T O R */
	/*-----------------------*/

	/**
	* Constructor
	*
	* @desc Constructor
	* @param int $list_size number of ticker messages to be displayed
	* @return void
	* @uses TickerBase::readINIsettings()
	* @uses setListSize()
	* @uses setMessages()
	*/
	function Ticker($list_size = 6) {
		parent::readINIsettings();
		$this->setListSize($list_size);
	} // end constructor
	/**#@-*/

	/*-------------------*/
	/* F U N C T I O N S */
	/*-------------------*/

	/**
	* sets the number of messages to be read
	*
	* @desc sets the number of messages to be read
	* @param int $size number of ticker messages to be displayed
	* @return void
	* @access public
	* @uses setMessages()
	*/
	function setListSize($size = 6) {
		$this->list_size = (int) $size;
		if (isset($this->messagelist)) {
			unset($this->messagelist);
		} // end if
		if (isset($this->messages)) {
			unset($this->messages);
		} // end if
	} // end function

	/**
	* checks if one of the words in the whitelist is part of an e-mail address from a given mail header
	*
	* @desc checks if one of the words in the whitelist is part of an e-mail address from a given mail header
	* @param object $header e-mail header
	* @return boolean
	* @access public
	*/
	function isValidAuthor($header = '') {
		if (strlen(trim($GLOBALS[$this->ticker_globalname]['Settings']['whitelist'])) == 0) {
			return (boolean) TRUE; // return true if whitelist is empty
		} else {
			@$headerstring = (string) $header;
			if (strlen(trim($headerstring)) === 0) {
				return (boolean) FALSE;
			} // end if
			unset($headerstring);
		}// end if
		/* get e-mail address from mail header and compare it with */
 		$from = $header->fromaddress . ' ' . $header->from[0]->mailbox . '@' . $header->from[0]->host;

		/* convert whitelist to an array */
		if (!is_array($GLOBALS[$this->ticker_globalname]['Settings']['whitelist'])) {
			$GLOBALS[$this->ticker_globalname]['Settings']['whitelist'] = (array) explode(',', $GLOBALS[$this->ticker_globalname]['Settings']['whitelist']);
		}// end if

		foreach ($GLOBALS[$this->ticker_globalname]['Settings']['whitelist'] as $white) {
			if (strpos($from, $white) != FALSE) {
				return (boolean) TRUE; // return true if the e-mail was found in the whitelist
			} // end if
		} // end foreach
		unset($whitelist);
		unset($from);
		return (boolean) FALSE;
	} // end function

	/**
	* checks if a mail fits the display rules
	*
	* @desc checks if a mail fits the display rules
	* @param object $header e-mail header
	* @param string $body e-mail body
	* @return boolean
	* @access public
	*/
	function isValidMail(&$header, &$body) {
		$start_string =& $GLOBALS[$this->ticker_globalname]['Settings']['start_string'];
		$start_string_length = (int) strlen($start_string);
		if (($start_string_length === 0) || (isset($header->subject) && $header->subject == $start_string) || (substr_count($body, $start_string) > 0)) {
			return (boolean) TRUE;
		} // end if
		unset($start_string_length);
		return (boolean) FALSE;
	} // end function


	/**
	* creates the conn string for connection to the mailbox
	*
	* @desc creates the conn string for connection to the mailbox
	* @return void
	* @access private
	*/
	function createMailConnString() {
		if (!isset($this->mail_conn_string)) {
			$port =& $GLOBALS[$this->ticker_globalname]['Mailbox']['mail_server_port'];
			$mail_type =& $GLOBALS[$this->ticker_globalname]['Mailbox']['mail_server_type'];
			$folder =& $GLOBALS[$this->ticker_globalname]['Mailbox']['folder'];
			$type = (string) (($mail_type == 'POP3') ? 'pop3' : 'imap');
			$conn_string = '{' . $GLOBALS[$this->ticker_globalname]['Mailbox']['mail_server'];

			if ($GLOBALS[$this->ticker_globalname]['Mailbox']['mail_server_ssl'] === TRUE) {
				if ($GLOBALS[$this->ticker_globalname]['Mailbox']['mail_server_sc'] === TRUE) {
					$conn_string .= ':' . $port . '/' . $type . '/ssl/novalidate-cert}';
				} else {
					$conn_string .= ':' . $port . '/' . $type . '/ssl}' . $folder;
				}// end if
			} else {
				$conn_string .= ':' . $port . '}' . $folder;
			}// end if
			$this->mail_conn_string = (string) $conn_string;
		} // end if
	} // end function

	/**
	* adds a single TickerMessage to the mailbox
	*
	* for example though a html form, example:
	* <code>
	* if ($t->hasMessageWritten() === FALSE && isset($_POST['Submit'])) {
	*     $t->addTickerMessage($_POST['message'],$_POST['name']);
	* } // end if
	* </code>
	*
	* @desc adds a single TickerMessage to the mailbox
	* @param string $message
	* @param string $author optional
	* @return boolean successful or not
	* @access public
	*/
	function addTickerMessage($message = '', $author = '') {

		/* if message is empty don't add message */
		if (strlen(trim($message)) === 0 || $this->hasMessageWritten() === TRUE) {
			return (boolean) FALSE;
		} // end if

		/* if name is given, add it to the message string */
		$message = (string) $GLOBALS[$this->ticker_globalname]['Settings']['start_string'] . $message;
		if (strlen(trim($author)) > 0) {
			$message .= ' – ' . $author;
		} // end if

		$this->createMailConnString();
		$mbox = imap_open($this->mail_conn_string, $GLOBALS[$this->ticker_globalname]['Mailbox']['email'],
				$GLOBALS[$this->ticker_globalname]['Mailbox']['password']) or die ('Error: ' . imap_last_error());

		/* add message to the mailbox */
		imap_append($mbox,
					$this->mail_conn_string,
					'From: ' . $GLOBALS[$this->ticker_globalname]['Mailbox']['email'] . "\r\n" .
					'To: ' . $GLOBALS[$this->ticker_globalname]['Mailbox']['email'] . "\r\n" .
					'Subject: ' . $GLOBALS[$this->ticker_globalname]['Settings']['start_string'] . "\r\n\r\n" .
					$message . "\r\n"
                  );
		imap_close($mbox);
		unset($message);
		/*
		mail($GLOBALS[$this->ticker_globalname]['Mailbox']['email'],
			$GLOBALS[$this->ticker_globalname]['Settings']['start_string'],
			$message,
			'From: ' . $GLOBALS[$this->ticker_globalname]['Mailbox']['email'] . "\r\n" . 'X-Mailer: PHP/' . phpversion());
		*/

		/* set cookie and session that user has written a message (prevent flooding) */
		$_SESSION[$this->cookie_name] = time() + $GLOBALS[$this->ticker_globalname]['Settings']['spamprotection'];
		$_COOKIE[$this->cookie_name] = time() + $GLOBALS[$this->ticker_globalname]['Settings']['spamprotection'];
		setcookie($this->cookie_name, time(), time()+ $GLOBALS[$this->ticker_globalname]['Settings']['spamprotection']);
		//$this->setMessages();
		return (boolean) TRUE;
	} // end function

	/**
	* checks if a user has postet a message within the spamtime set in the ini file
	*
	* @desc checks if a user has postet a message within the spamtime set in the ini file
	* @return boolean
	* @access public
	*/
	function hasMessageWritten() {
		if ((isset($_SESSION[$this->cookie_name]) && time() < $_SESSION[$this->cookie_name]) || isset($_COOKIE[$this->cookie_name])) {
			return (boolean) TRUE;
		} // end if
		return (boolean) FALSE;
	} // end function

	/**#@+
	* @return void
	* @access private
	*/
	/**
	* sets the array with TickerMessage objects
	*
	* @desc sets the array with TickerMessage objects
	* @uses TickerMessage
	*/
	function setMessages() {
		if (!isset($this->messages)) {
			$this->createMailConnString();
			/* create mailbox connection */
			$mbox = imap_open($this->mail_conn_string, $GLOBALS[$this->ticker_globalname]['Mailbox']['email'], $GLOBALS[$this->ticker_globalname]['Mailbox']['password']) or die ('Error: ' . imap_last_error());

			$start_string =& $GLOBALS[$this->ticker_globalname]['Settings']['start_string'];
			$start_string_length = (int) strlen($start_string);
			$headers = imap_headers($mbox);
			$count_mails = (int) count($headers);
			unset($headers);
			$counter = (int) $this->list_size;
			$this->messages = (array) array();

			if ($count_mails > 0) { // if mailbox is not empty
				for ($i = $count_mails; $i > 0; $i--) {
					$header = imap_header($mbox, $i);
					$body = imap_body($mbox, $i);

			 		/* if mail does not fit the rules continue with next
			 			(and maybe delete it) */
			 		if ($this->isValidAuthor($header) === FALSE || $this->isValidMail(&$header, &$body) === FALSE) {
						if ($GLOBALS[$this->ticker_globalname]['Settings']['delete_false_mails'] == TRUE) {
							imap_delete($mbox, $i);
						} // end if
			 			continue;
					} // end if
					/* only go though the fist XX mails
					   the rest will be deleted or archived */
					if ($counter <= 0) { // do this for all mails
						if ($GLOBALS[$this->ticker_globalname]['Settings']['db_backup'] == TRUE) {
							$tm_backup = new TickerMessage($i, $body, $header, imap_fetchstructure($mbox, $i));
							$this->backupMessage($tm_backup);
						} // end if

						if ($GLOBALS[$this->ticker_globalname]['Settings']['delete_old_mails'] == TRUE) {
							imap_delete($mbox, $i);
						} // end if
			 			continue;
					} // end if

					/* create now message object and stick it into an array */
					$tm = new TickerMessage($i, $body, $header, imap_fetchstructure($mbox, $i));
					$this->messages[$i] = $tm;
					$counter--;
				} // end for
				unset($header);
				unset($body);
				unset($tm);
			} // end if
			imap_expunge($mbox);
			imap_close($mbox);
		} // end if
	} // end function

	/**
	* sets the database connection
	*
	* @desc sets the database connection
	*/
	function setConnection() {
		if (!isset($this->conn)) {
			parent::readINIsettings(); // get DB settings from the ini file
			if (isset($GLOBALS[$this->ticker_globalname])) {
				$this->conn = mysql_pconnect($GLOBALS[$this->ticker_globalname]['DB']['host'], $GLOBALS[$this->ticker_globalname]['DB']['user'], $GLOBALS[$this->ticker_globalname]['DB']['password']) or die ('Connection not possible! => ' . mysql_error());
				mysql_select_db($GLOBALS[$this->ticker_globalname]['DB']['database']) or die ('Couldn\'t connect to "' . $this->database . '" => ' . mysql_error());
				$this->db_table =& $GLOBALS[$this->ticker_globalname]['DB']['translation_table'];
			} else {
				die('cant read ini settings');
			} // end if
		} // end if
	} // end function

	/**
	* writes a single TickerMessage to the database
	*
	* @desc writes a single TickerMessage to the database
	* @param object $tm TickerMessage object
	*/
	function backupMessage(&$tm) {
		$this->setConnection();
		$sql  = 'INSERT INTO ' . $this->db_table . '(date, message)';
		$sql .= ' VALUES (' . $tm->getTimestamp() . ',"' . addslashes($tm->getText()) . '");';
		$result =  mysql_query($sql, $this->conn) or die ('Request not possible! SQL Statement: ' . $sql . ' / ' . mysql_error());
	} // end function

	/**
	* creates an array with id's from all the TickerMessage objects
	*
	* @desc creates an array with id's from all the TickerMessage objects
	*/
	function setMessageList() {
		if (!isset($this->messagelist)) {
			$this->setMessages();
			$this->messagelist = (array) array_keys($this->messages);
		} // end if
	} // end function
	/**#@-*/

	/**#@+
	* @access public
	*/
	/**
	* returns an array wthi all TickerMessage objects
	*
	* @desc returns an array wthi all TickerMessage objects
	* @return mixed array with TickerMessage objects if successfull, if not returns FALSE
	*/
	function getMessages() {
		$this->setMessages();
		if (isset($this->messages)) {
			return (array) $this->messages;
		} else {
			return (boolean) FALSE;
		} // end if
	} // end if

	/**
	* returns a single TickerMessage object
	*
	* @desc returns a single TickerMessage object
	* @param int $id id number of the TickerMessage object
	* @return mixed TickerMessage object if successfull, if not returns FALSE
	*/
	function getMessage($id = -1) {
		$this->setMessages();
		if (array_key_exists($id, $this->messages)) {
			return (object) $this->messages[$id];
		} else {
			return (boolean) FALSE;
		} // end if
	} // end if

	/**
	* returns an array with id's from all the TickerMessage objects
	*
	* @desc returns an array with id's from all the TickerMessage objects
	* @return array $messagelist
	* @uses setMessageList()
	*/
	function getMessageList() {
		$this->setMessageList();
		return (array) $this->messagelist;
	} // end function

	/**
	* returns the number of TickerMessage objects to be displayed
	*
	* @desc returns the number of TickerMessage objects to be displayed
	* @return int $list_size
	*/
	function getListSize() {
		return (int) $this->list_size;
	} // end function

	/**
	* returns the start string
	*
	* @desc returns the start string
	* @return string
	*/
	function getStartString() {
		return (string) $GLOBALS[$this->ticker_globalname]['Settings']['start_string'];
	} // end function

	/**
	* returns the end string
	*
	* @desc returns the end string
	* @return string
	*/
	function getEndString() {
		return (string) $GLOBALS[$this->ticker_globalname]['Settings']['end_string'];
	} // end function

	/**
	* returns the used tiecker email address
	*
	* @desc returns the used tiecker email address
	* @return string
	*/
	function getEmail() {
		return (string) $GLOBALS[$this->ticker_globalname]['Mailbox']['email'];
	} // end function

	/**
	* returns the maximum of characters that are displayed
	*
	* @desc returns the maximum of characters that are displayed
	* @return int
	*/
	function getMaxMessageSize() {
		return (int) $GLOBALS[$this->ticker_globalname]['Settings']['max_length'];
	} // end function
	/**#@-*/
} // end class Ticker
?>