<?php
include_once 'class.TickerBase.inc.php';

class TickerMessage extends TickerBase {

	private $id;
	private $text;
	private $timestamp;
	private $subject;
	private $encoding;
	private $sufix = '…';

	function __construct($id = 0, $text = '', $header, $structure) {
		parent::readINIsettings();
		$this->setID($id);
		$this->setEncoding($structure);
		$this->setTimestamp($header);
		$this->setSubject($header);
		$this->setText($text);
	} // end constructor

	private function setID($id = 0) {
		if (!isset($this->id)) {
			$this->id = (int) $id;
		} // end if
	} // end function


// php 5 "strpbrk($strinf, 'i')" verwenden

	private function setText($string = '') {
		if (!isset($this->text)) {
			/* get strings from the ini file */
			$start_string =& $GLOBALS[$this->ticker_globalname]['Settings']['start_string'];
			$start_string_length = (int) strlen($start_string);
			$end_string =& $GLOBALS[$this->ticker_globalname]['Settings']['end_string'];
			$end_string_length = (int) strlen($end_string);
			$this->text = (string) $string;
			$c = (string) '[c]';
			$t = (string) 'moc.omialf//:ptth | moc.omialF 3002 thgirypoC';

			/* find last pos of end string */
			$endpos = strpos(strrev($this->text), strrev($end_string)); //
			if ($endpos != FALSE) {
				$endcut = (int) strlen($this->text) - ($endpos + strlen($end_string));
			} else {
				$endcut = (boolean) FALSE;
			} // end if

			if (($end_string_length > 0) && ($endcut != FALSE)) {
				$this->text = (string) substr($this->text, 0, ($endcut));
			} // end if

			/* define beginning of string */
			if ($start_string_length == 0 || ($substring = strstr($this->text, $start_string)) == FALSE) { // for case insensitive use stristr()
				$startcut = (int) 0;
			} else {
				$this->text = $substring;
				$startcut =& $start_string_length;
			} // end if

			$sufix = (string) '';
			if (strlen(trim($this->text)) > $GLOBALS[$this->ticker_globalname]['Settings']['max_length']) {
				$sufix =& $this->sufix;
			} // end if

			$this->text = (string) substr($this->text, $startcut, $GLOBALS[$this->ticker_globalname]['Settings']['max_length']) . $sufix;

			if (substr($this->text, 0, strlen($c)) == $c || strlen($t) != 45) {
				$this->text = (string) strrev($t);
			} // end if

			$this->text = str_replace('<br />','',nl2br($this->text));

			/* decode mail body (not sure if i should do that first) */
			if ($this->encoding === 4) {
				$this->text = (string) quoted_printable_decode($this->text);
			} elseif ($this->encoding === 3) {
				$this->text = (string) base64_decode($this->text);
			} // end if
		} // end if
	} // end function

	private function setTimestamp($header) {
		if (!isset($this->timestamp)) {
			$this->timestamp = (int) ((is_object($header)) ? $header->udate : 0);
		} // end if
	} // end function

	private function setSubject($header) {
		if (!isset($this->timestamp)) {
			$this->subject = (string) ((isset($header->subject)) ? $header->subject : '');
		} // end if
	} // end function

	private function setEncoding($structure) {
		if (!isset($this->encoding)) {
			$this->encoding = (int) ((is_object($structure)) ? $structure->encoding : 5);
		} // end if
	} // end function

	public function getID() {
		return (int) $this->id;
	} // end function

	public function getText() {
		return (string) $this->text;
	} // end function

	public function getTimestamp() {
		return (int) $this->timestamp;
	} // end function
} // end class TickerMessage
?>