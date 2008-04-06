<?php
/*
2002 (c) Michael Wimmer (flaimo@gmx.net | http://flaimo.com)
-------------------------------------------------------------------
IMPORTANT FUNCTIONS IN THIS CLASS:

>> setTokenSession:
Creates a token based on md5 value of the current time in msec,
creates a session with that token.

>> getToken:
You have to place the returned value as a hidden field in all of
your forms.

>> isValid:
Compares the session with the transmitted token from the form.
If they don't match, a boolean "false" is returned.

You have to be careful to call the setToken method AFTER you
have valitated all of your important code fragments, otherwise
the session token and the form token will never match up.
-------------------------------------------------------------------
*/

@include_once('class.DBclass.inc.php');
@include_once('class.Helpers.inc.php');
@include_once('class.Language.inc.php');
@include_once('class.Newslist.inc.php');

class Author {

	/* V A R I A B L E S */

	var $isValidAuthor;
	var $db;
	var $helpers;
	var $dataLoaded;

	var $tbl_news;
	var $tbl_category;
	var $tbl_author;

	var $id;
	var $name;
	var $picture;
	var $abstract;
	var $newslist;
	var $countArticles;

	/* C O N S T R U C T O R */

	function Author($id = 1) {
		$this->checkClass('DBclass');
		$this->checkClass('Helpers');
		$this->id = (int) $id;
		$this->validateID($this->id);
		$this->db = (object) new DBclass();
		$this->helpers = (object) new Helpers();
	} // end function


	/* F U N C T I O N S */

	function validateID($id) {
		$this->isValidAuthor = (boolean) true;
		if (!is_int($id) || $id == 0) {
			$this->isValidAuthor = (boolean) false;
		} // end if
	}  // end function

	function emptyAuthor($string) {
		if (!isset($this->lg)) {
			$this->checkClass('Language');
			$this->lg = (object) new Language();
		} // end if
		$string = (string) $this->lg->Translate($string);
		$this->id = (int) 1;
		$this->name = (string) '';
		$this->picture = (string) '';
		$this->abstract = (string) $string;
	} // end function

	function setAuthor() {
		if ($this->dataLoaded == false) {
			if ($this->isValidAuthor == false) {
				$this->emptyNews('wrong_input_for_id');
			} else {
				if (!class_exists('Language')) {
					echo 'Class "' . get_class($this) . '": Class "Language" not found!';
					die();
				} // end if
				elseif (!isset($this->lg)) {
					$this->checkClass('Language');
					$this->lg = (object) new Language();
				} // end if
				$sql  = (string) 'SELECT IDAuthor, AuthorNick, Picture, Abstract_' . $this->lg->getLangContent() . ' as Abstract';
				$sql .= (string) ' FROM ' . $this->helpers->getTableName('tbl_author');
				$sql .= (string) ' WHERE IDAuthor = ' . $this->id;
				$rs_author = $this->db->db_query($sql);

				if ($this->db->rs_num_rows($rs_author) > 0) {
					while ($author = $this->db->rs_fetch_assoc($rs_author)) {
						$this->id = (int) $author['IDAuthor'];
						$this->name = (string) $author['AuthorNick'];
						$this->picture = (string) $author['Picture'];
						$this->abstract = (string) $author['Abstract'];
					} // end while
				} else {
					$this->isValidAuthor = false;
					$this->emptyNews('no_author');
				} // end if
				$this->db->db_freeresult($rs_author);
			} // end if
		} // end if
	} // end function

	function setLastArticles() {
		$this->checkClass('NewsList');
		$this->newslist = (object) new NewsList('profile', 0, 5, $this->id, '', '', '');
	} // end function

	function &getLastArticles() {
		if (!isset($this->newslist)) {
			$this->setLastArticles();
		} // end if
		return (object) $this->newslist;
	} // end function

	function setCountArticles() {
		$sql  = 'SELECT COUNT(*) as sum_author_news';
		$sql .= ' FROM ' . $this->helpers->getTableName('tbl_news');
		$sql .= ' WHERE NewsAuthor = ' . $this->id;
		$this->countArticles = (int) $this->db->rs_single($sql);
	} // end function

	function getCountArticles() {
		if (!isset($this->countArticles)) {
			$this->setCountArticles();
		} // end if
		return (int) $this->countArticles;
	} // end function

	function getAuthorID() {
		return (int) $this->id;
	}

	function getAuthorName() {
		$this->setAuthor();
		return (string) $this->name;
	}

	function getAuthorPicture() {
		$this->setAuthor();
		return (string) $this->picture;
	}

	function getAuthorAbstract() {
		$this->setAuthor();
		return (string) $this->abstract;
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
} // end class Author
?>
