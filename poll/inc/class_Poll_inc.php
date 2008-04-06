<?php
require_once 'class_PollBase_inc.php';

class Poll extends PollBase {
	protected $id;
	protected $category;
	protected $title;
	protected $description;
	protected $created;
	protected $start_poll;
	protected $stop_poll;
	protected $multiple_choise;
	protected $show_ordered_list;
	protected $show_popular_results;
	protected $use_only_active_options;
	protected $display_only_active_options;
	protected $registered_users_only;
	protected $show_votes_percent;
	protected $show_votes_absolute;
	protected $revote;
	protected $template;
	protected $button_label;
	protected $cp;
	protected $cp_type;
	protected $channel;
	protected $l1_cat;
	protected $l2_cat;

	protected $available;
	protected $errors = array();

	const MULTIBLE_CHOISE = 1;
	const ORDERED_LIST = 2;
	const POPULAR_RESULT = 4;
	const USE_ONLY_ACTIVE_OPTIONS = 8;
	const DISPLAY_ONLY_ACTIVE_OPTIONS = 16;
	const REGISTERED_USERS_ONLY = 32;
	const SHOW_VOTES_PERCENT = 64;
	const SHOW_VOTES_ABSOLUTE = 128;

	function __construct($id = 0, $database = '') {
		$this->setID($id);
		parent::__construct($database);
	} // end function

	public function fetchData() {
		$sql  = 'SELECT category, title, description, created, start_poll, stop_poll, flags, revote, template, button_label, cp, cp_type, channel, L1, L2';
		$sql .= ' FROM ' . $this->tables['polls'];
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
			$this->setCategory(new PollCategory($row[0]));
			$this->setTitle(parent::returnSQLdata($row[1]));
			$this->setDescription(parent::returnSQLdata($row[2]));
			$this->setCreated(parent::returnSQLdata($row[3]));
			$this->setStartPoll(parent::returnSQLdata($row[4]));
			$this->setStopPoll(parent::returnSQLdata($row[5]));
			$flags =& $row[6];
			$this->setMultipleChoise($flags & Poll::MULTIBLE_CHOISE);
			$this->setShowOrderedList($flags & Poll::ORDERED_LIST);
			$this->setShowPopularResult($flags & Poll::POPULAR_RESULT);
			$this->setUseOnlyActiveOptions($flags & Poll::USE_ONLY_ACTIVE_OPTIONS);
			$this->setDisplayOnlyActiveOptions($flags & Poll::DISPLAY_ONLY_ACTIVE_OPTIONS);
			$this->setRegisteredUsersOnly($flags & Poll::REGISTERED_USERS_ONLY);
			$this->setShowVotesPercent($flags & Poll::SHOW_VOTES_PERCENT);
			$this->setShowVotesAbsolute($flags & Poll::SHOW_VOTES_ABSOLUTE);
			$this->setRevote((int) $row[7]);
			$this->setTemplate(parent::returnSQLdata($row[8]));
			$this->setButtonLabel(parent::returnSQLdata($row[9]));
			$this->setCP(parent::returnSQLdata($row[10]));
			$this->setCPType(parent::returnSQLdata($row[11]));
			$this->setChannel(parent::returnSQLdata($row[12]));
			$this->setL1Cat(new PollCategory($row[13]));
			$this->setL2Cat(new PollCategory($row[14]));
		} // end if
	} // end function

	public function &available() {
		if (!isset($this->available)) {
			$this->fetchData();
		} // end if
		return $this->available;
	} // end function

	public function updateData() {
		$sql  = ' UPDATE ' . $this->tables['polls'] . ' SET';
		$sql .= ' category = ' . parent::prepareSQLdata($this->category->getID()) . ', ';
		$sql .= " title = '" . parent::prepareSQLdata($this->title) . "', ";
		$sql .= " description = '" . parent::prepareSQLdata($this->description) . "', ";
		$sql .= ' created = ' . parent::prepareSQLdata($this->created) . ', ';
		$sql .= ' start_poll = ' . parent::prepareSQLdata($this->start_poll) . ', ';
		$sql .= ' stop_poll = ' . parent::prepareSQLdata($this->stop_poll) . ', ';
		$flags = 0;
		if ($this->multiple_choise == TRUE) { $flags += Poll::MULTIBLE_CHOISE; }
		if ($this->show_ordered_list == TRUE) { $flags += Poll::ORDERED_LIST; }
		if ($this->show_popular_results == TRUE) { $flags += Poll::POPULAR_RESULT; }
		if ($this->use_only_active_options == TRUE) { $flags += Poll::USE_ONLY_ACTIVE_OPTIONS; }
		if ($this->display_only_active_options == TRUE) { $flags += Poll::DISPLAY_ONLY_ACTIVE_OPTIONS; }
		if ($this->registered_users_only == TRUE) { $flags += Poll::REGISTERED_USERS_ONLY; }
		if ($this->show_votes_percent == TRUE) { $flags += Poll::SHOW_VOTES_PERCENT; }
		if ($this->show_votes_absolute == TRUE) { $flags += Poll::SHOW_VOTES_ABSOLUTE; }
		$sql .= ' flags = ' . parent::prepareSQLdata($flags) . ', ';
		$sql .= ' revote = ' . parent::prepareSQLdata($this->revote) . ', ';
		$sql .= " template = '" . parent::prepareSQLdata($this->template) . "', ";
		$sql .= " button_label = '" . parent::prepareSQLdata($this->button_label) . "', ";
		$sql .= " cp = '" . parent::prepareSQLdata($this->cp) . "', ";
		$sql .= " cp_type = '" . parent::prepareSQLdata($this->cp_type) . "', ";
		$sql .= ' channel = ' . parent::prepareSQLdata($this->channel) . ', ';

		//hier neue l1 und l2 berechnen
		if ($this->category->getID() == 1) {
			$this->setL1Cat($this->category);
			$this->setL2Cat($this->category);
		} elseif ($this->category->getParent()->getID() == 1) {
			$this->setL1Cat($this->category);
			$this->setL2Cat($this->category->getParent());
		} elseif ($this->category->getParent()->getParent()->getID() == 1) {
			$this->setL1Cat($this->category->getParent());
			$this->setL2Cat($this->category);
		} else {
			$found = FALSE;
			$temp_cat = $this->category;
			while ($temp_cat->getID() != 1) {
				if ($temp_cat->getParent()->getParent()->getID() == 1) {
					$this->setL1Cat($temp_cat->getParent());
					$this->setL2Cat($temp_cat);
					$found = TRUE;
					break;
				} // end if
				$temp_cat = $temp_cat->getParent();
			} // end while

			if ($found == FALSE) {
				$this->setL1Cat(new PollCategory(1));
				$this->setL2Cat(new PollCategory(1));
			} // end if
		} // end if

		$sql .= ' L1 = ' . parent::prepareSQLdata($this->l1_cat->getID()) . ', ';
		$sql .= ' L2 = ' . parent::prepareSQLdata($this->l2_cat->getID());
		$sql .= ' WHERE id = ' . parent::prepareSQLdata($this->id);
		$result = sqlite_unbuffered_query(parent::getConn(), $sql);
		return (!$result) ? FALSE : TRUE;
	} // end function

	public function resetVotes() {
		$sql  = 'UPDATE ' . $this->tables['polloptions'];
		$sql .= ' SET votes= 0, lastvote = 0';
		$sql .= ' WHERE poll = ' . parent::prepareSQLdata($this->id);
		return sqlite_unbuffered_query(parent::getConn(), $sql);
	} // end function

	public function addVote($mixed = '') {
		$user_last_vote = parent::getUser()->getLastVote($this);

		if ($this->isPollOnline() == FALSE) {
			$this->errors[] = 'Abstimmung nicht mehr möglich: Die Umfrage ist nicht mehr online';
			return FALSE;
		} elseif ($this->userAllowedToVote() == FALSE) {
			$this->errors[] = 'Abstimmung nicht mehr möglich: Sie können erst wieder in ' . round(parent::getUser()->getNextVote($this) / 60, 0) . ' Minuten abstimmen';
			return FALSE;
		} // end if

		if ($this->getMultipleChoise() == TRUE && is_array($mixed)) {
			foreach ($mixed as $polloption_id) {
				$tmp_polloption = new PollOption($polloption_id);
				if ($tmp_polloption->getIsActive() == TRUE) {
					$tmp_polloption->countVote();
				} else {
					$this->errors['f'] = 'Abstimmung nur für aktive Antworten möglich';
				} // end if
			} // end foreach
		} elseif ($this->getMultipleChoise() == FALSE && is_array($mixed)) {
			$tmp_polloption = new PollOption((int) $mixed[0]);
			if ($tmp_polloption->getIsActive() == TRUE) {
				$tmp_polloption->countVote();
			} else {
				$this->errors[] = 'Abstimmung nur für aktive Antworten möglich';
			} // end if
		} else {
			$tmp_polloption = new PollOption((int) $mixed);
			if ($tmp_polloption->getIsActive() == TRUE) {
				$tmp_polloption->countVote();
			} else {
				$this->errors[] = 'Abstimmung nur für aktive Antworten möglich';
			} // end if
		} // end if
		unset($tmp_polloption);
		parent::getUser()->setLastVote($this);
	} // end function

	public function getSumVotes() {
		$sql  = 'SELECT SUM(votes) FROM ' . $this->tables['polloptions'];
		$sql .= ' WHERE poll = ' . parent::prepareSQLdata($this->id);
		return sqlite_single_query(parent::getConn(), $sql, TRUE);
	} // end function

	public function getSumActiveVotes() {
		$sql  = 'SELECT SUM(votes) FROM ' . $this->tables['polloptions'];
		$sql .= ' WHERE poll = ' . parent::prepareSQLdata($this->id) . ' AND flags & ' . parent::prepareSQLdata(PollOption::ACTIVE);
		return sqlite_single_query(parent::getConn(), $sql, TRUE);
	} // end function

	public function createImageFolder() {
		$folder = $this->images_path . $this->id;
		if (is_dir($folder) == TRUE) {
			return TRUE;
		} // end if
		return mkdir($folder, 0777);
	} // end function

	protected function setID($id = 0) {
		if ($id >= 0) {
			$this->id = (int) $id;
		} // end if
	} // end function

	public function setCategory(PollCategory $category = FALSE) {
		if ($category instanceOf PollCategory) {
			$this->category = $category;
		} // end if
	} // end function

	public function setTitle($string = '') {
		if (parent::isFilledString($string)) {
			$this->title = (string) $string;
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

	public function setStartPoll($int = 0) {
		$this->setTimestamp('start_poll', $int);
	} // end function

	public function setStopPoll($int = 0) {
		$this->setTimestamp('stop_poll', $int);
	} // end function

	public function setMultipleChoise($boolean = FALSE) {
		$this->multiple_choise = (boolean) $boolean;
	} // end function

	public function setShowOrderedList($boolean = FALSE) {
		$this->show_ordered_list = (boolean) $boolean;
	} // end function

	public function setShowPopularResult($boolean = FALSE) {
		$this->show_popular_results = (boolean) $boolean;
	} // end function

	public function setUseOnlyActiveOptions($boolean = FALSE) {
		$this->use_only_active_options = (boolean) $boolean;
	} // end function

	public function setDisplayOnlyActiveOptions($boolean = FALSE) {
		$this->display_only_active_options = (boolean) $boolean;
	} // end function

	public function setRegisteredUsersOnly($boolean = FALSE) {
		$this->registered_users_only = (boolean) $boolean;
	} // end function

	public function setShowVotesPercent($boolean = FALSE) {
		$this->show_votes_percent = (boolean) $boolean;
	} // end function

	public function setShowVotesAbsolute($boolean = FALSE) {
		$this->show_votes_absolute = (boolean) $boolean;
	} // end function

	public function setRevote($int = 0) {
		$this->setTimestamp('revote', $int);
	} // end function

	public function setTemplate($string = '') {
		$this->template = (string) $string;
	} // end function

	public function setButtonLabel($string = 'Abstimmen') {
		if (parent::isFilledString($string) == TRUE) {
			$this->button_label = (string) $string;
		} // end if
	} // end function

	public function setCP($string = '') {
		if (parent::isFilledString($string) == TRUE) {
			$this->cp = (string) $string;
		} // end if
	} // end function

	public static final function correctCPType($char = '') {
		$allowed_cp_types = array('r' => 'Redaktioneller Content', 'i' => 'Infotainment', 'c' => 'Community', 's' => 'Service', 'u' => 'Unterhaltung und Games', 'e' => 'E-Commerce', 'd' => 'Diverses');
		return array_key_exists($char, $allowed_cp_types);
	} // end function

	public function setCPType($char = 'u') {
		if (parent::isFilledString($char) == TRUE && $this->correctCPType($char) == TRUE) {
			$this->cp_type = (string) $char;
		} // end if
	} // end function

	public function setChannel($int = 0) {
		if (parent::isFilledString($int) == TRUE) {
			$this->channel = (int) $int;
		} // end if
	} // end function

	public function setL1Cat(PollCategory $category = FALSE) {
		if ($category instanceOf PollCategory) {
			$this->l1_cat = $category;
		} // end if
	} // end function

	public function setL2Cat(PollCategory $category = FALSE) {
		if ($category instanceOf PollCategory) {
			$this->l2_cat = $category;
		} // end if
	} // end function

	public function &getID() {
		return $this->id;
	} // end if

	public function &getCategory() {
		return parent::getVar('category');
	} // end if

	public function &getTitle() {
		return parent::getVar('title');
	} // end if

	public function &getDescription() {
		return parent::getVar('description');
	} // end if

	public function &getCreated() {
		return parent::getVar('created');
	} // end if

	public function &getStartPoll() {
		return parent::getVar('start_poll');
	} // end if

	public function &getStopPoll() {
		return parent::getVar('stop_poll');
	} // end if

	public function isPollOnline() {
		$time = time();
		return ($this->getStartPoll() < $time && $this->getStopPoll() > $time) ? TRUE : FALSE;
	} // end function

	public function userAllowedToVote() {
		if ($this->getRevote() < 1) {
			return TRUE;
		} // end if

		$user_last_vote = parent::getUser()->getLastVote($this);
		if ($user_last_vote == FALSE ||
			  ($this->getRevote() < (time() - $user_last_vote))) {
			return TRUE;
		} // end if

		return FALSE;
	} // end function

	public function &getMultipleChoise() {
		return parent::getVar('multiple_choise');
	} // end if

	public function &getShowOrderedList() {
		return parent::getVar('show_ordered_list');
	} // end if

	public function &getShowPopularResult() {
		return parent::getVar('show_popular_results');
	} // end if

	public function &getUseOnlyActiveOptions() {
		return parent::getVar('use_only_active_options');
	} // end if

	public function &getDisplayOnlyActiveOptions() {
		return parent::getVar('display_only_active_options');
	} // end if

	public function &getRegisteredUsersOnly() {
		return parent::getVar('registered_users_only');
	} // end if

	public function &getShowVotesPercent() {
		return parent::getVar('show_votes_percent');
	} // end if

	public function &getShowVotesAbsolute() {
		return parent::getVar('show_votes_absolute');
	} // end if

	public function &getRevote() {
		return parent::getVar('revote');
	} // end if

	public function &getTemplate() {
		return parent::getVar('template');
		/*
		$template = parent::getVar('template');
		if (parent::isFilledString($template) == FALSE) {
			$template = $this->getCategory()->getTemplate();
		} // end if

		return (parent::isFilledString($template) == FALSE) ? 'default' : $template;
		*/
	} // end function

	public function &getButtonLabel() {
		return parent::getVar('button_label');
	} // end if

	public function &getCP() {
		return parent::getVar('cp');
	} // end if

	public function &getCPType() {
		return parent::getVar('cp_type');
	} // end if

	public function &getChannel() {
		return parent::getVar('channel');
	} // end if

	public function &getL1Cat() {
		return parent::getVar('l1_cat');
	} // end if

	public function &getL2Cat() {
		return parent::getVar('l2_cat');
	} // end if

	public function &getErrors() {
		return $this->errors;
	} // end function
} // end class
?>