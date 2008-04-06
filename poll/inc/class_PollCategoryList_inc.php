<?php
require_once 'class_PollBase_inc.php';

class PollCategoryList extends PollBase {
	protected $father;
	protected $children;

	function __construct(&$father = FALSE, $database = '') {
		$this->setFather($father);
		parent::__construct($database);
	} // end function

	protected function setFather(&$father = FALSE) {
		if ($father instanceOf PollCategory) {
			$this->father =& $father;
		} elseif ($father = 'all') {
			$this->father = 'all';
		} // end if
	} // end function

	public function &getFather() {
		return $this->father;
	} // end function

	public function fetchData() {
		if (!isset($this->father)) {
			return FALSE;
		} // end if

		$sql  = 'SELECT id';
		$sql .= ' FROM ' . $this->tables['categories'];
		if ($this->father != 'all') {
			$sql .= ' WHERE parent = ' . parent::prepareSQLdata($this->father->getID());
			$sql .= ' ORDER BY position DESC, name ASC';
		} else {
			$sql .= ' ORDER BY name ASC, id DESC';
		} // end if

		$result = sqlite_unbuffered_query(parent::getConn(), $sql);
		$this->children = array();
		while ($id = sqlite_fetch_single($result)) {
			$this->children[$id] = new PollCategory($id);
		} // end while
	} // end function

	public function &getChildren() {
		return parent::getVar('children');
	} // end function

	public function &addSubCategory($name = '', $position = 0, $template = 'default') {
		if (parent::isFilledString($name) == FALSE) {
			return FALSE;
		} // end if

		if (parent::isFilledString($template) == FALSE) {
			$template = 'default';
		} // end if

		$position = (int) $position;
		if ($position < 0) {
			$position = 0;
		} elseif ($position > 99) {
			$position = 99;
		} // end if

		$sql  = 'INSERT INTO ' . $this->tables['categories'];
		$sql .= ' (parent, name, position, template) VALUES';
		$sql .= ' (' . parent::prepareSQLdata($this->father->getID()) . ", '" . parent::prepareSQLdata($name) . "', " . parent::prepareSQLdata($position) . ", '" . parent::prepareSQLdata($template) . "')";
		$result = sqlite_query(parent::getConn(), $sql);

		if (!$result) {
			return FALSE;
		} // end if
		$id = sqlite_last_insert_rowid(parent::getConn());
		$this->fetchData();
		return $this->children[$id];
	} // end function


	public function deleteCategory(PollCategory &$category = FALSE) {
		if (!($category instanceOf PollCategory)) {
			return FALSE;
		} // end if

		sqlite_exec(parent::getConn(), 'BEGIN TRANSACTION delete_category');
		$sql  = 'UPDATE ' . $this->tables['polls'];
		$sql .= ' SET category = 0';
		$sql .= ' WHERE category = ' . parent::prepareSQLdata($category->getID());
		sqlite_exec(parent::getConn(), $sql);
		$sql  = 'UPDATE ' . $this->tables['categories'];
		$sql .= ' SET parent = 0';
		$sql .= ' WHERE parent = ' . parent::prepareSQLdata($category->getID());
		sqlite_exec(parent::getConn(), $sql);
		$sql  = ' DELETE FROM ' . $this->tables['categories'];
		$sql .= ' WHERE id = ' . parent::prepareSQLdata($category->getID());
		sqlite_exec(parent::getConn(), $sql);
		sqlite_exec(parent::getConn(), 'END TRANSACTION delete_category');

		if (isset($this->children)) {
			unset($this->children);
			$this->fetchData();
		} // end if
		parent::vacuumDB();
		return TRUE;
	} // end if

	public function moveCategory(PollCategory &$category_to_move = FALSE, PollCategory &$target_category = FALSE) {
		$category_to_move->fetchData();
		$category_to_move->setParent($target_category);
		return $category_to_move->updateData();
	} // end function
} // end class
?>