<?php
/*
2002 (c) Michael Wimmer (flaimo@gmx.net | http://flaimo.com)
-------------------------------------------------------------------

-------------------------------------------------------------------
*/
@include_once('class.DBclass.inc.php');
@include_once('class.Language.inc.php');

class Helpers {

	/* V A R I A B L E S */

	var $tbl_news;
	var $tbl_category;
	var $tbl_author;
	var $tbl_comments;

	var $sumComments;
	var $sumNews;

	var $db;
	var $lg;

	/* C O N S T R U C T O R */

	function Helpers() {
		$this->setTableNames();
	} // end function


	/* F U N C T I O N S */

	function setTableNames() {
		$this->tbl_news = (string) 'tbl_news';
		$this->tbl_category = (string) 'tbl_category';
		$this->tbl_author = (string) 'tbl_author';
		$this->tbl_comment = (string) 'tbl_comment';
		/*
		$this->tbl_news = (string) 'tbl_news_test';
		$this->tbl_category = (string) 'tbl_category_test';
		$this->tbl_author = (string) 'tbl_author_test';
		$this->tbl_comment = (string) 'tbl_comment_test'; */
	} // end function

	function setSumComments() {
		$this->setLanguage();
		$this->setDBconnection();
		$sql  = 'SELECT COUNT(DISTINCT IDComment)';
		$sql .= ' FROM ' . $this->tbl_comment;
		$sql .= ' WHERE Language = "' . $this->lg->getLangContent() . '"';
		$this->sumComments = (int) $this->db->rs_single($sql);
	} // end function

	function setSumNews() {
		$this->setLanguage();
		$this->setDBconnection();
		$sql  = 'SELECT COUNT(DISTINCT IDNews)';
		$sql .= ' FROM ' . $this->tbl_news;
		$sql .= ' WHERE Language = "' . $this->lg->getLangContent() . '"';
		$this->sumNews = (int) $this->db->rs_single($sql);
	} // end function

	function setLanguage() {
		if (!isset($this->lg)) {
			$this->checkClass('Language');
			$this->lg = (object) new Language();
		} // end if
	} // end function

	function setDBconnection() {
		if (!isset($this->db)) {
			$this->checkClass('DBClass');
			$this->db = (object) new DBclass();
		} // end if
	} // end function

	function getSumComments() {
		if (!isset($this->sumComments)) {
			$this->setSumComments();
		} // end if
		return (int) $this->sumComments;
	} // end function

	function getSumNews() {
		if (!isset($this->sumNews)) {
			$this->setSumNews();
		} // end if
		return (int) $this->sumNews;
	} // end function

	function getTableName($table) {
		return (string) $this->$table;
	}

	/**
	* Checks if a class is available
	*
	* @return (object) $user  User
	* @access private
	* @since 1.001 - 2002/11/30
	*/
	function checkClass($classname = '') {
		if (strlen(trim($classname)) > 0) {
			if (!class_exists($classname)) {
				echo 'Class "' . get_class($this) . '": Class "' . $classname . '" not found!';
				die();
			} // end if
		} // end if
	} // end function
} // End Class FormatDate
?>
