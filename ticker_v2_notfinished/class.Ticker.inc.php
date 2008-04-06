<?php

include_once 'class.TickerBase.inc.php';
include_once 'class.TickerMessage.inc.php';

class Ticker extends TickerBase {

	private $list_size;
	private $messages;
	private $messagelist;
	private $conn;
	private $db_table = 'ticker_backup';
	CONST COOKIE_NAME = 'ticker_comment';
	private $mail_conn_string;

	function __construct($list_size = 6) {
		if (parent::readINIsettings() === FALSE) {
			die ("Couldn't load ticker settings");
		} // end if
		$this->setListSize($list_size);
	} // end constructor

	private function setListSize($size = 6) {
		$this->list_size = (int) $size;
		if (isset($this->messagelist)) {
			unset($this->messagelist);
		} // end if
		if (isset($this->messages)) {
			unset($this->messages);
		} // end if
	} // end function

	protected function isValidAuthor($header = '') {
		if (strlen(trim($GLOBALS[TICKER_GLOBALNAME]['Settings']['whitelist'])) == 0) {
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
		if (!is_array($GLOBALS[TICKER_GLOBALNAME]['Settings']['whitelist'])) {
			$GLOBALS[TICKER_GLOBALNAME]['Settings']['whitelist'] = (array) explode(',', $GLOBALS[TICKER_GLOBALNAME]['Settings']['whitelist'])
		}// end if

		foreach ($GLOBALS[TICKER_GLOBALNAME]['Settings']['whitelist'] as $white) {
			if (strpos($from, $white) != FALSE) {
				return (boolean) TRUE; // return true if the e-mail was found in the whitelist
			} // end if
		} // end foreach
		unset($whitelist);
		unset($from);
		return (boolean) FALSE;
	} // end function

	protected function isValidMail(&$header, &$body) {
		$start_string =& $GLOBALS[TICKER_GLOBALNAME]['Settings']['start_string'];
		$start_string_length = (int) strlen($start_string);
		if (($start_string_length === 0) || (isset($header->subject) && $header->subject == $start_string) || (substr_count($body, $start_string) > 0)) {
			return (boolean) TRUE;
		} // end if
		unset($start_string_length);
		return (boolean) FALSE;
	} // end function

	private function createMailConnString() {
		if (!isset($this->mail_conn_string)) {
			$mailbox_setting =& $GLOBALS[TICKER_GLOBALNAME]['Mailbox'];
			$port =& $mailbox_setting['mail_server_port'];
			$mail_type =& $mailbox_setting['mail_server_type'];
			$folder =& $mailbox_setting['folder'];
			$type = (string) (($mail_type == 'POP3') ? 'pop3' : 'imap');
			$conn_string = '{' . $mailbox_setting['mail_server'];

			if ($mailbox_setting['mail_server_ssl'] === TRUE) {
				if ($mailbox_setting['mail_server_sc'] === TRUE) {
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

	protected function addTickerMessage($message = '', $author = '') {

		/* if message is empty don't add message */
		if (strlen(trim($message)) === 0 || $this->hasMessageWritten() === TRUE) {
			return (boolean) FALSE;
		} // end if

		$settings =& $GLOBALS[TICKER_GLOBALNAME]['Settings'];
		/* if name is given, add it to the message string */
		$message = (string) $settings['start_string'] . $message;
		if (strlen(trim($author)) > 0) {
			$message .= ' – ' . $author;
		} // end if

		$this->createMailConnString();
		$mbox = imap_open($this->mail_conn_string, $GLOBALS[TICKER_GLOBALNAME]['Mailbox']['email'],
				$GLOBALS[TICKER_GLOBALNAME]['Mailbox']['password']) or die ('Error: ' . imap_last_error());

		/* add message to the mailbox */
		imap_append($mbox,
					$this->mail_conn_string,
					'From: ' . $GLOBALS[TICKER_GLOBALNAME]['Mailbox']['email'] . "\r\n" .
					'To: ' . $GLOBALS[TICKER_GLOBALNAME]['Mailbox']['email'] . "\r\n" .
					'Subject: ' . $settings['start_string'] . "\r\n\r\n" .
					$message . "\r\n"
                  );
		imap_close($mbox);
		unset($message);
		/*
		mail($GLOBALS[TICKER_GLOBALNAME]['Mailbox']['email'],
			$GLOBALS[TICKER_GLOBALNAME]['Settings']['start_string'],
			$message,
			'From: ' . $GLOBALS[TICKER_GLOBALNAME]['Mailbox']['email'] . "\r\n" . 'X-Mailer: PHP/' . phpversion());
		*/

		/* set cookie and session that user has written a message (prevent flooding) */
		$_SESSION[COOKIE_NAME] = time() + $settings['spamprotection'];
		$_COOKIE[COOKIE_NAME] = time() + $settings['spamprotection'];
		setcookie(COOKIE_NAME, time(), time()+ $settings['spamprotection']);
		//$this->setMessages();
		return (boolean) TRUE;
	} // end function

	protected function hasMessageWritten() {
		if ((isset($_SESSION[COOKIE_NAME]) && time() < $_SESSION[COOKIE_NAME]) || isset($_COOKIE[COOKIE_NAME])) {
			return (boolean) TRUE;
		} // end if
		return (boolean) FALSE;
	} // end function

	protected function setMessages() {
		if (!isset($this->messages)) {
			$this->createMailConnString();
			/* create mailbox connection */
			$mbox = imap_open($this->mail_conn_string, $GLOBALS[TICKER_GLOBALNAME]['Mailbox']['email'], $GLOBALS[TICKER_GLOBALNAME]['Mailbox']['password']) or die ('Error: ' . imap_last_error());

			$settings =& $GLOBALS[TICKER_GLOBALNAME]['Settings'];
			$start_string =& $settings['start_string'];
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
						if ($settings['delete_false_mails'] == TRUE) {
							imap_delete($mbox, $i);
						} // end if
			 			continue;
					} // end if
					/* only go though the fist XX mails
					   the rest will be deleted or archived */
					if ($counter <= 0) { // do this for all mails
						if ($settings['db_backup'] == TRUE) {
							$tm_backup = new TickerMessage($i, $body, $header, imap_fetchstructure($mbox, $i));
							$this->backupMessage($tm_backup);
						} // end if

						if ($settings['delete_old_mails'] == TRUE) {
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

	private function setConnection() {
		if (!isset($this->conn)) {
			$settings =& $GLOBALS[TICKER_GLOBALNAME]['DB'];
			$this->conn = mysql_pconnect($settings['host'], $settings['user'], $settings['password']) or die ('Connection not possible! => ' . mysql_error());
			mysql_select_db($settings['database']) or die ('Couldn\'t connect to "' . $this->database . '" => ' . mysql_error());
			$this->db_table =& $settings['translation_table'];
		} // end if
	} // end function

	private function backupMessage(&$tm) {
		$this->setConnection();
		$sql  = 'INSERT INTO ' . $this->db_table . '(date, message)';
		$sql .= ' VALUES (' . $tm->getTimestamp() . ',"' . addslashes($tm->getText()) . '");';
		$result =  mysql_query($sql, $this->conn) or die ('Request not possible! SQL Statement: ' . $sql . ' / ' . mysql_error());
	} // end function

	protected function setMessageList() {
		if (!isset($this->messagelist)) {
			$this->setMessages();
			$this->messagelist = (array) array_keys($this->messages);
		} // end if
	} // end function

	public function getMessages() {
		$this->setMessages();
		if (isset($this->messages)) {
			return (array) $this->messages;
		} else {
			return (boolean) FALSE;
		} // end if
	} // end if

	public function getMessage($id = -1) {
		$this->setMessages();
		if (array_key_exists($id, $this->messages)) {
			return (object) $this->messages[$id];
		} else {
			return (boolean) FALSE;
		} // end if
	} // end if

	public function getMessageList() {
		$this->setMessageList();
		return (array) $this->messagelist;
	} // end function

	public function getListSize() {
		return (int) $this->list_size;
	} // end function

	public function getStartString() {
		return (string) $GLOBALS[TICKER_GLOBALNAME]['Settings']['start_string'];
	} // end function

	public function getEndString() {
		return (string) $GLOBALS[TICKER_GLOBALNAME]['Settings']['end_string'];
	} // end function

	public function getEmail() {
		return (string) $GLOBALS[TICKER_GLOBALNAME]['Mailbox']['email'];
	} // end function

	public function getMaxMessageSize() {
		return (int) $GLOBALS[TICKER_GLOBALNAME]['Settings']['max_length'];
	} // end function

} // end class Ticker
?>