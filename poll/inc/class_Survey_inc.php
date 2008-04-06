<?php
require_once 'class_PollBase_inc.php';

class Survey extends PollBase {
	protected $id;
	protected $name;
	protected $description;
	protected $created;
	protected $start_survey;
	protected $stop_survey;
	protected $revote;
	protected $cp;
	protected $channel;
	protected $show_ordered_list;
	protected $registered_users_only;

	protected $available;
	protected $errors = array();

	const ORDERED_LIST = 1;
	const REGISTERED_USERS_ONLY = 2;

	function __construct($id = 0, $database = '') {
		$this->setID($id);
		parent::__construct($database);
	} // end function

	public function fetchData() {
		$sql  = 'SELECT name, description, created, start_survey, stop_survey, flags, revote, cp, channel';
		$sql .= ' FROM ' . $this->tables['surveys'];
		$sql .= ' WHERE id = ' . parent::prepareSQLdata($this->id);
		$sql .= ' LIMIT 0,1';
		$result = sqlite_query(parent::getConn(), $sql);

		if (!$result || sqlite_num_rows($result) != 1) {
			$this->available = FALSE;
			return FALSE;
		} // end if

		$this->available = TRUE;
		$this->setData(sqlite_fetch_array($result, SQLITE_NUM));
	} // end function

	public function setData(&$row = FALSE) {
		if (is_array($row)) {
			$this->setName(parent::returnSQLdata($row[0]));
			$this->setDescription(parent::returnSQLdata($row[1]));
			$this->setCreated(parent::returnSQLdata($row[2]));
			$this->setStartSurvey(parent::returnSQLdata($row[3]));
			$this->setStopSurvey(parent::returnSQLdata($row[4]));
			$flags =& $row[5];
			$this->setShowOrderedList($flags & Poll::ORDERED_LIST);
			$this->setRegisteredUsersOnly($flags & Poll::REGISTERED_USERS_ONLY);
			$this->setRevote((int) $row[6]);
			$this->setCP(parent::returnSQLdata($row[7]));
			$this->setChannel(parent::returnSQLdata($row[8]));
		} // end if
	} // end function

	public function &available() {
		if (!isset($this->available)) {
			$this->fetchData();
		} // end if
		return $this->available;
	} // end function

	public function updateData() {
		$sql  = ' UPDATE ' . $this->tables['surveys'] . ' SET';
		$sql .= " name = '" . parent::prepareSQLdata($this->name) . "', ";
		$sql .= " description = '" . parent::prepareSQLdata($this->description) . "', ";
		$sql .= ' created = ' . parent::prepareSQLdata($this->created) . ', ';
		$sql .= ' start_survey = ' . parent::prepareSQLdata($this->start_survey) . ', ';
		$sql .= ' stop_survey = ' . parent::prepareSQLdata($this->stop_survey) . ', ';
		$flags = 0;
		if ($this->show_ordered_list == TRUE) { $flags += Poll::ORDERED_LIST; }
		if ($this->registered_users_only == TRUE) { $flags += Poll::REGISTERED_USERS_ONLY; }
		$sql .= ' flags = ' . parent::prepareSQLdata($flags) . ', ';
		$sql .= ' revote = ' . parent::prepareSQLdata($this->revote) . ', ';
		$sql .= " cp = '" . parent::prepareSQLdata($this->cp) . "', ";
		$sql .= ' channel = ' . parent::prepareSQLdata($this->channel);
		$sql .= ' WHERE id = ' . parent::prepareSQLdata($this->id);
		$result = sqlite_unbuffered_query(parent::getConn(), $sql);
		return (!$result) ? FALSE : TRUE;
	} // end function

	protected function setID($id = 0) {
		if ($id >= 0) {
			$this->id = (int) $id;
		} // end if
	} // end function

	public function setName($string = '') {
		if (parent::isFilledString($string)) {
			$this->name = (string) $string;
		} // end if
	} // end function

	public function setDescription($string = '') {
		$this->description = (string) $string;
	} // end function

	protected function setTimestamp($var = '', $int = 0) {
		if (parent::isFilledString($var) == TRUE && $int >= 0 && $int <= 2147483647) {
			$this->$var = (int) $int;
		} // end if
	} // end function

	protected function setCreated($int = 0) {
		$this->setTimestamp('created', $int);
	} // end function

	public function setStartSurvey($int = 0) {
		$this->setTimestamp('start_survey', $int);
	} // end function

	public function setStopSurvey($int = 0) {
		$this->setTimestamp('stop_survey', $int);
	} // end function

	public function setShowOrderedList($boolean = FALSE) {
		$this->show_ordered_list = (boolean) $boolean;
	} // end function

	public function setRegisteredUsersOnly($boolean = FALSE) {
		$this->registered_users_only = (boolean) $boolean;
	} // end function

	public function setRevote($int = 0) {
		$this->setTimestamp('revote', $int);
	} // end function

	public function setCP($string = '') {
		if (parent::isFilledString($string) == TRUE) {
			$this->cp = (string) $string;
		} // end if
	} // end function

	public function setChannel($int = 0) {
		if (parent::isFilledString($int) == TRUE) {
			$this->channel = (int) $int;
		} // end if
	} // end function

	public function &getID() {
		return $this->id;
	} // end if

	public function &getName() {
		return parent::getVar('name');
	} // end if

	public function &getDescription() {
		return parent::getVar('description');
	} // end if

	public function &getCreated() {
		return parent::getVar('created');
	} // end if

	public function &getStartSurvey() {
		return parent::getVar('start_survey');
	} // end if

	public function &getStopSurvey() {
		return parent::getVar('stop_survey');
	} // end if

	public function &getShowOrderedList() {
		return parent::getVar('show_ordered_list');
	} // end if

	public function &getRegisteredUsersOnly() {
		return parent::getVar('registered_users_only');
	} // end if

	public function &getRevote() {
		return parent::getVar('revote');
	} // end if

	public function &getCP() {
		return parent::getVar('cp');
	} // end if

	public function &getChannel() {
		return parent::getVar('channel');
	} // end if

	public function &getErrors() {
		return $this->errors;
	} // end function
} // end class
?>