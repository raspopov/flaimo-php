<?php
require_once 'class_PollBase_inc.php';

class PollCategory extends PollBase {
	protected $id;
	protected $parent;
	protected $name;
	protected $position;
	protected $template;

	protected $available;
	protected $family_tree;

	function __construct($id = 0, $database = '') {
		$this->setID($id);
		parent::__construct($database);
	} // end function

	public function fetchData() {
		$sql  = 'SELECT parent, name, position, template';
		$sql .= ' FROM ' . $this->tables['categories'];
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
			$this->setParent(new PollCategory($row[0]));
			$this->setName(parent::returnSQLdata($row[1]));
			$this->setPosition(parent::returnSQLdata($row[2]));
			$this->setTemplate(parent::returnSQLdata($row[3]));
		} // end if
	} // end function

	public function &available() {
		if (!isset($this->available)) {
			$this->fetchData();
		} // end if
		return $this->available;
	} // end function

	public function updateData() {
		$sql  = ' UPDATE ' . $this->tables['categories'] . ' SET';
		$sql .= ' parent = ' . parent::prepareSQLdata($this->parent->getID()) . ', ';
		$sql .= " name = '" . parent::prepareSQLdata($this->name) . "', ";
		$sql .= ' position = ' . parent::prepareSQLdata($this->position) . ', ';
		$sql .= " template = '" . parent::prepareSQLdata($this->template) . "'";
		$sql .= ' WHERE id = ' . parent::prepareSQLdata($this->id);
		$result = sqlite_unbuffered_query(parent::getConn(), $sql);
		return (!$result) ? FALSE : TRUE;
	} // end function

	protected function setID($id = 0) {
		if ($id >= 0) {
			$this->id = (int) $id;
		} // end if
	} // end function

	public function setParent(PollCategory $category = FALSE) {
		if ($category instanceOf PollCategory) {
			$this->parent = $category;
		} // end if
	} // end function

	public function setName($string = '') {
		if (parent::isFilledString($string)) {
			$this->name = (string) $string;
		} // end if
	} // end function

	public function setPosition($int = '') {
		if ($int > 0 && $int < 100) {
			$this->position = (int) $int;
		} // end if
	} // end function

	public function setTemplate($string = '') {
		if (parent::isFilledString($string)) {
			$this->template = (string) $string;
		} // end if
	} // end function

	public function &getID() {
		return $this->id;
	} // end if

	public function &getParent() {
		return parent::getVar('parent');
	} // end if

	public function &getName() {
		return parent::getVar('name');
	} // end if

	public function &getPosition() {
		return parent::getVar('position');
	} // end if

	public function &getTemplate() {
		return parent::getVar('template');
	} // end if

	public function &getFamilyTree() {
		if (isset($this->family_tree)) {
			return $this->family_tree;
		} // end if

		$this->family_tree = array();
		$this->family_tree[] = $tmp = $this;

		while ($tmp->getParent()->getID() != 0) {
			$tmp = $tmp->getParent();
			$this->family_tree[] = $tmp;
		} // end if
		$this->family_tree = array_reverse($this->family_tree);
		return $this->family_tree;
	} // end function

} // end class
?>