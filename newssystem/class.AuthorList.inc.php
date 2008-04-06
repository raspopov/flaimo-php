<?php
/*
2002 (c) Michael Wimmer (flaimo@gmx.net | http://flaimo.com)
-------------------------------------------------------------------

-------------------------------------------------------------------
*/

@include_once('class.DBclass.inc.php');
@include_once('class.Author.inc.php');
@include_once('class.Helpers.inc.php');

class AuthorList {

	/* V A R I A B L E S */

	var $authorList;


	/* C O N S T R U C T O R */

	function AuthorList() {
		$this->checkClass('DBclass');
		$this->checkClass('Helpers');
		$this->db = (object) new DBclass();
		$this->helpers = (object) new Helpers();
		$this->setAuthorList();
	} // end function

	/* F U N C T I O N S */

	function setAuthorList() {
		$this->checkClass('Author');
		$this->authorList = (array) array();
		$sql  = 'SELECT DISTINCT IDAuthor';
		$sql .= ' FROM ' . $this->helpers->getTableName('tbl_author');
		$sql .= ' WHERE IDAuthor <> 1';
		$sql .= ' ORDER BY AuthorNick ASC';
		$rs_authors = $this->db->db_query($sql);

		if ($this->db->rs_num_rows($rs_authors) > 0) {
			while ($author_r = $this->db->rs_fetch_row($rs_authors)) {
				$authorID = (int) $author_r[0];
				$author = new Author($authorID);
				$this->authorList[$authorID] = $author;
			} // end while
			unset($authorID);
			unset($author);
			unset($author_r);
		} // end if
		$this->db->db_freeresult($rs_authors);
	} // end function


	function &getAuthorList() {
		return (array) $this->authorList;
	}


	function &getAuthor($id = false) {
		if ($id != false && is_int($id) && array_key_exists($id, $this->authorList)) {
			return (object) $this->authorList[$id];
		} else {
			return (string) 'no_author';
		} // end if
	} // end function

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
} // end class CommentList
?>
