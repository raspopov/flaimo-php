<?php
/*
2002 (c) Michael Wimmer (flaimo@gmx.net | http://flaimo.com)
-------------------------------------------------------------------

-------------------------------------------------------------------
*/

@include_once('class.DBclass.inc.php');
@include_once('class.Comment.inc.php');
@include_once('class.Helpers.inc.php');

class CommentList {

	/* V A R I A B L E S */

	var $commentList;
	var $isValidID;
	var $db;
	var $lg;
	var $errorMessage;

	var $id;
	var $authors;


	/* C O N S T R U C T O R */

	function CommentList($id) {
		$this->checkClass('DBclass');
		$this->checkClass('Helpers');
		$this->id = (int) $id;
		$this->validateID($this->id);
		$this->db = (object) new DBclass();
		$this->helpers = (object) new Helpers();
		$this->setCommentList();
	} // end function

	/* F U N C T I O N S */

	function validateID($id) {
		$this->isValidID = (boolean) true;
		if (!is_int($id) || $id == 0) {
			$this->errorMessage = 'wrong_input_for_id';
			$this->isValidID = (boolean) false;
		} // end if
	}  // end function

	function setAuthorList() {
		$this->authors = (array) array();
		if ($this->isValidID != false) {
			$sql  = (string) 'SELECT DISTINCT(CommentAuthor)';
			$sql .= (string) ' FROM ' . $this->helpers->getTableName('tbl_comment');
			$sql .= (string) ' WHERE NewsID = ' . $this->id;
			$sql .= (string) ' ORDER BY CommentDate ASC';

			$rs_authors = $this->db->db_query($sql);
			if ($this->db->rs_num_rows($rs_authors) > 0) {
				while ($author_r = $this->db->rs_fetch_row($rs_authors)) {
					$author = (int) $author_r[0];
					$this->authors[] = $comment;
				} // end while
			} // end if
			$this->db->db_freeresult($rs_authors);
		} // end if
	} // end function

	function &getAuthors() {
		if (!isset($this->authors)) {
			$this->setAuthorList();
		} // end if
		return (array) $this->authors;
	}

	function setCommentList() {
		$this->commentList = (array) array();
		if ($this->isValidID != false) {
			$this->checkClass('Comment');
			$sql  = (string) 'SELECT IDComment';
			$sql .= (string) ' FROM ' . $this->helpers->getTableName('tbl_comment');
			$sql .= (string) ' WHERE NewsID = ' . $this->id;
			$sql .= (string) ' ORDER BY CommentDate ASC';

			$rs_comments = $this->db->db_query($sql);
			if ($this->db->rs_num_rows($rs_comments) < 1) {
				$this->errorMessage = (string) 'no_comments_found';
			} // end if
			while ($comment_r = $this->db->rs_fetch_row($rs_comments)) {
				$commentID = (int) $comment_r[0];
				$comment = new Comment($commentID);
				$this->commentList[$commentID] = $comment;
			} // end while
			unset($commentID);
			$this->db->db_freeresult($rs_comments);
		} // end if
	} // end function


	function &getComment($id = false) {
		if ($id != false && is_int($id) && array_key_exists($id, $this->commentList)) {
			return (object) $this->commentList[$id];
		} else {
			return (string) 'no_searchresult';
		} // end if
	} // end function

	function &getCommentList() {
		return (array) $this->commentList;
	} // end function


	function getErrorMessage() {
		return (string) $this->errorMessage;
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
} // end class CommentList
?>
