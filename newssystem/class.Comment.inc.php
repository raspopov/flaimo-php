<?php
/*
2002 (c) Michael Wimmer (flaimo@gmx.net | http://flaimo.com)
-------------------------------------------------------------------
-------------------------------------------------------------------
*/

@include_once('class.DBclass.inc.php');
@include_once('class.Language.inc.php');
@include_once('class.Helpers.inc.php');

class Comment {

	/* V A R I A B L E S */

	var $db;
	var $lg;
	var $helpers;
	var $isValidComment;
	var $dataLoaded;

	var $id;
	var $news_id;
	var $language;
	var $text;
	var $author_name;
	var $author_mail;
	var $comment_date;
	var $ip;

	/* C O N S T R U C T O R */

	function Comment($id) {
		$this->checkClass('DBclass');
		$this->checkClass('Helpers');
		$this->id = (int) $id;
		$this->validateID($this->id);
		$this->db = (object) new DBclass();
		$this->helpers = (object) new Helpers();
	} // end function


	/* F U N C T I O N S */


	function validateID($id) {
		$this->isValidComment = (boolean) true;
		if (!is_int($id) || $id == 0) {
			$this->isValidComment = (boolean) false;
		} // end if
	}  // end function

	function emptyComment($string) {
		if (!isset($this->lg)) {
			$this->checkClass('Language');
			$this->lg = (object) new Language();
		} // end if
		$string = (string) $this->lg->Translate($string);
		$this->id = (int) 0;
		$this->news_id = (int) 0;
		$this->language = (string) '';
		$this->text = (string) $string;
		$this->author_name = (string) '';
		$this->author_mail = (string) '';
		$this->comment_date = (string) '';
		$this->ip = (string) '';
	} // end function


	function setComment() {
		if ($this->dataLoaded == false) {
			if ($this->isValidComment == false) {
				$this->emptyComment('wrong_input_for_id');
			} else {
				$sql  = 'SELECT IDComment, NewsID, Language, CommentText, CommentAuthor, CommentEmail, CommentDate, CommentIP';
				$sql .= ' FROM ' . $this->helpers->getTableName('tbl_comment');
				$sql .= ' WHERE IDComment = ' . $this->id;

				$rs_comment = $this->db->db_query($sql);
				if ($this->db->rs_num_rows($rs_comment) > 0) {
					$this->dataLoaded == true;
					while ($comment = $this->db->rs_fetch_assoc($rs_comment)) {
						$this->id = (int) $comment['IDComment'];
						$this->news_id = (int) $comment['NewsID'];
						$this->language = (string) $comment['Language'];
						$this->text = (string) $comment['CommentText'];
						$this->author_name = (string) $comment['CommentAuthor'];
						$this->author_mail = (string) $comment['CommentEmail'];
						$this->comment_date = (string) $comment['CommentDate'];
						$this->ip = (string) $comment['CommentIP'];
					} // end while
				} else {
					$this->isValidComment = false;
					$this->emptyComment('no_comment');
				} // end if
				$this->db->db_freeresult($rs_comment);
			} // end if
		} // end if
	} // end function

	function getCommentID() {
		return (int) $this->id;
	}

	function getNewsID() {
		$this->setComment();
		return (int) $this->news_id;
	}

	function getLanguage() {
		$this->setComment();
		return (string) $this->language;
	}

	function getCommentText() {
		$this->setComment();
		return (string) $this->text;
	}

	function getAuthorName() {
		$this->setComment();
		return (string) $this->author_name;
	}

	function getAuthorMail() {
		$this->setComment();
		return (string) $this->author_mail;
	}

	function getCommentDate() {
		$this->setComment();
		return (string) $this->comment_date;
	}

	function getAuthorIP() {
		$this->setComment();
		return (string) $this->ip;
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
} // end class Comment
?>
