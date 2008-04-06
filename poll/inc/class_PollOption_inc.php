<?php
require_once 'class_PollBase_inc.php';

class PollOption extends PollBase {
	protected $id;
	protected $poll;
	protected $title;
	protected $description;
	protected $votes;
	protected $last_vote;
	protected $position;
	protected $active;

	protected $available;

	const ACTIVE = 1;

	function __construct($id = 0, $database = '') {
		$this->setID($id);
		parent::__construct($database);
	} // end function

	public function fetchData() {
		$sql  = 'SELECT poll, title, description, votes, lastvote, position, flags';
		$sql .= ' FROM ' . $this->tables['polloptions'];
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
			$this->setPoll(new Poll($row[0]));
			$this->setTitle(parent::returnSQLdata($row[1]));
			$this->setDescription(parent::returnSQLdata($row[2]));
			$this->setVotes(parent::returnSQLdata($row[3]));
			$this->setLastVote(parent::returnSQLdata($row[4]));
			$this->setPosition(parent::returnSQLdata($row[5]));
			$flags =& $row[6];
			$this->setIsActive($flags & PollOption::ACTIVE);
		} // end if
	} // end function

	public function countVote() {
		if ($this->getIsActive() == FALSE) {
			return FALSE;
		} // end if
		$sql  = 'UPDATE ' . $this->tables['polloptions'];
		$sql .= ' SET votes= votes + 1, lastvote = ' . time();
		$sql .= ' WHERE id = ' . parent::prepareSQLdata($this->id);
		return sqlite_unbuffered_query(parent::getConn(), $sql);
	} // end function

	public function &available() {
		if (!isset($this->available)) {
			$this->fetchData();
		} // end if
		return $this->available;
	} // end function

	public function updateData() {
		$sql  = 'UPDATE ' . $this->tables['polloptions'] . ' SET';
		$sql .= ' poll = ' . parent::prepareSQLdata($this->poll->getID()) . ', ';
		$sql .= " title = '" . parent::prepareSQLdata($this->title) . "', ";
		$sql .= " description = '" . parent::prepareSQLdata($this->description) . "', ";
		$sql .= ' votes = ' . parent::prepareSQLdata($this->votes) . ', ';
		$sql .= ' lastvote = ' . parent::prepareSQLdata($this->last_vote) . ', ';
		$sql .= ' position = ' . parent::prepareSQLdata($this->position) . ', ';
		$flags = 0;
		if ($this->active == TRUE) { $flags += PollOption::ACTIVE; }
		$sql .= ' flags = ' . parent::prepareSQLdata($flags) . ' ';
		$sql .= ' WHERE id = ' . parent::prepareSQLdata($this->id);
		$result = sqlite_unbuffered_query(parent::getConn(), $sql);
		return (!$result) ? FALSE : TRUE;
	} // end function

	protected function setID($id = 0) {
		if ($id >= 0) {
			$this->id = (int) $id;
		} // end if
	} // end function

	public function setPoll(Poll $poll = FALSE) {
		if ($poll instanceOf Poll) {
			$this->poll = $poll;
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

	public function setVotes($int = 0) {
		if ($int >= 0) {
			$this->votes = (int) $int;
		} // end if
	} // end function

	protected function setLastVote($int = 0) {
		if ($int >= 0 && $int <= 2147483647) {
			$this->last_vote = (int) $int;
		} // end if
	} // end function

	public function setPosition($int = 0) {
		if ($int >= 0 && $int <= 99) {
			$this->position = (int) $int;
		} // end if
	} // end function

	public function setIsActive($boolean = FALSE) {
		$this->active = (boolean) $boolean;
	} // end function

	public function &getID() {
		return $this->id;
	} // end function

	public function &getPoll() {
		return parent::getVar('poll');
	} // end function

	public function &getTitle() {
		return parent::getVar('title');
	} // end function

	public function &getDescription() {
		return parent::getVar('description');
	} // end function

	public function &getVotes() {
		return parent::getVar('votes');
	} // end function

	public function &getLastVote() {
		return parent::getVar('last_vote');
	} // end function

	public function &getPosition() {
		return parent::getVar('position');
	} // end function

	public function &getIsActive() {
		return parent::getVar('active');
	} // end function

	public function getImagePath($status = '') {
		$status = (parent::isFilledString($status) == TRUE) ? '_' . $status : '';
		return $this->images_path . $this->getPoll()->getID() . '/' . $this->id . $status . '.jpg';
	} // end function

	public function addImage(&$file = '', $status = '') {
		if (parent::isImageUpload($file) == FALSE ||
			$this->getPoll()->createImageFolder() == FALSE) {
			return FALSE;
		} // end if
		if ($this->removeImage($status) == FALSE) {
			return FALSE;
		} // end if
		return move_uploaded_file($file['tmp_name'], $this->getImagePath($status));
	} // end function

	public function removeImage($status = '') {
		if (!is_file($this->getImagePath($status))) {
			return TRUE;
		} // end if
		return unlink($this->getImagePath($status));
	} // end function
} // end class
?>