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
@include_once('class.Language.inc.php');
@include_once('class.News.inc.php');
@include_once('class.Helpers.inc.php');

class NewsList {

	/* V A R I A B L E S */

	var $newsList;
	var $isValidID;
	var $isValidSearchterm;

	var $id;
	var $limiter;
	var $rs_start;
	var $rs_listsize;
	var $author;
	var $searchterm;
	var $searchcat;

	var $db;
	var $lg;
	var $helpers;
	var $errorMesage;
	var $newsListCountAll;

	var $prevpage;
	var $nextpage;
	var $pages;


	/* C O N S T R U C T O R */

	function NewsList($limiter = 'all', $start = 1, $listsize = 10, $id = 0, $author = 'all', $searchterm = '', $searchcat = 'all') { // all, onenews, category, search | start, end | $author, $searchterm
		$this->id = (int) $id;
		$this->validateID($this->id);
		$this->rs_start = (int) $start;
		$this->rs_listsize = (int) $listsize;
		$this->author = $author;
		$this->searchterm = (string) $searchterm;
		$this->searchcat = $searchcat;
		$this->validSearchterm($this->searchterm);
		$this->setLimiter($limiter);
		$this->checkClass('DBclass');
		$this->checkClass('Language');
		$this->checkClass('Helpers');
		$this->db = (object) new DBclass();
		$this->lg = (object) new Language();
		$this->helpers = (object) new Helpers();
		$this->setNewsList();
	} // end function

	/* F U N C T I O N S */

	function validateID($id) {
		$this->isValidID = (boolean) true;
		if (!is_int($id) || $id == 0) {
			$this->errorMessage = 'wrong_input_for_id';
			$this->isValidID = (boolean) false;
		} // end if
	}  // end function


	function validSearchterm($string) {
		$this->isValidSearchterm = (boolean) true;
		if (strlen(trim($string)) < 1) {
			$this->errorMessage = 'no_searchterm';
			$this->isValidSearchterm = (boolean) false;
		} // end if
		elseif (strlen(trim($string)) < 3) {
			$this->errorMessage = 'searchterm_too_short';
			$this->isValidSearchterm = (boolean) false;
		} // end if
	}  // end function


	function setLimiter($limiter) {
		if($limiter == 'all') {
			$this->limiter = (int) 5;
		}
		elseif($limiter == 'profile') {
			$this->limiter = (int) 4;
		}
		elseif ($limiter == 'search') {
			$this->limiter = (int) 3;
		}
		elseif ($limiter == 'category') {
			$this->limiter = (int) 2;
		}
		elseif ($limiter == 'onenews') {
			$this->limiter = (int) 1;
		} else {
			$this->limiter = (int) 0;
		} // end if
	} // end function

	function setNewsList() {
		$this->checkClass('News');
		$this->newsList = (array) array();
		if ($this->limiter == 0 || ($this->limiter == 3 && $this->isValidSearchterm == false) || ($this->limiter == 1 && $this->isValidID == false) || ($this->limiter == 2 && $this->isValidID == false)) {
			$this->newsListCountAll = (int) 0;
		} else {
			$sql  = (string) 'SELECT ' . $this->helpers->getTableName('tbl_news') . '.IDNews';
			$sql .= (string) " FROM " . $this->helpers->getTableName('tbl_news');
			$sql_count  = (string) 'SELECT COUNT(DISTINCT ' . $this->helpers->getTableName('tbl_news') . '.IDNews)';
			$sql_count .= (string) ' FROM ' . $this->helpers->getTableName('tbl_news');
			if ($this->limiter == 1 && $this->isValidID != false) { // news
				$sql .= (string) ' WHERE ' . $this->helpers->getTableName('tbl_news') . '.IDNews = ' . $this->id;
				$sql_count .= (string) ' WHERE ' . $this->helpers->getTableName('tbl_news') . '.IDNews = ' . $this->id;
			}
			elseif($this->limiter == 2 && $this->isValidID != false) { // category
				$sql .= (string) ' WHERE ' . $this->helpers->getTableName('tbl_news') . '.NewsCategory = ' . $this->id;
				$sql .= (string) ' ORDER BY NewsDate DESC';
				$sql .= (string) ' LIMIT ' . $this->rs_start . "," . $this->rs_listsize;
				$sql_count .= (string) ' WHERE ' . $this->helpers->getTableName('tbl_news') . '.NewsCategory = ' . $this->id;
			}
			elseif($this->limiter == 3 && $this->isValidSearchterm == true) { // search
				$sql .= (string) ' WHERE (NewsHeadline LIKE "%' . $this->searchterm . '%"';
				$sql .= (string) ' OR NewsText LIKE "%' . $this->searchterm . '%"';
				$sql .= (string) ' OR NewsSource LIKE "%' . $this->searchterm . '%")';
				$sql_count .= (string) ' WHERE (NewsHeadline LIKE "%' . $this->searchterm . '%"';
				$sql_count .= (string) ' OR NewsText LIKE "%' . $this->searchterm . '%"';
				$sql_count .= (string) ' OR NewsSource LIKE "%' . $this->searchterm . '%")';
				if ($this->searchcat != 'all' && $this->author != 'all') {
					$sql .= (string) ' AND ' . $this->helpers->getTableName('tbl_news') . '.NewsCategory = ' . $this->searchcat;
					$sql .= (string) ' AND NewsAuthor = ' . $this->author;
					$sql_count .= (string) ' AND ' . $this->helpers->getTableName('tbl_news') . '.NewsCategory = ' . $this->searchcat;
					$sql_count .= (string) ' AND NewsAuthor = ' . $this->author;
				}
				elseif ($this->searchcat != 'all') {
					$sql .= (string) ' AND ' . $this->helpers->getTableName('tbl_news') . '.NewsCategory = ' . $this->searchcat;
					$sql_count .= (string) ' AND ' . $this->helpers->getTableName('tbl_news') . '.NewsCategory = ' . $this->searchcat;
				}
				elseif ($this->author != 'all') {
					$sql .= (string) ' AND NewsAuthor = ' . $this->author;
					$sql_count .= (string) ' AND NewsAuthor = ' . $this->author;
				} // end if
				$sql .= (string) ' ORDER BY NewsDate DESC';
			}
			elseif ($this->limiter == 4) { // profile
				$sql .= (string) ' WHERE ' . $this->helpers->getTableName('tbl_news') . '.NewsAuthor = ' . $this->id;
				$sql .= (string) ' AND ' . $this->helpers->getTableName('tbl_news') . '.Language = "' . $this->lg->getLangContent() . '"';
				$sql .= (string) ' ORDER BY NewsDate DESC';
				$sql .= (string) ' LIMIT ' . $this->rs_start . ',' . $this->rs_listsize;
			} // end if
			elseif ($this->limiter == 5) { // all
				$sql .= (string) ' WHERE ' . $this->helpers->getTableName('tbl_news') . '.Language = "' . $this->lg->getLangContent() . '"';
				$sql .= (string) ' ORDER BY NewsDate DESC';
				$sql .= (string) ' LIMIT ' . $this->rs_start . ',' . $this->rs_listsize;
				$sql_count .= (string) ' WHERE ' . $this->helpers->getTableName('tbl_news') . '.Language = "' . $this->lg->getLangContent() . '"';
			} // end if

			$rs_news = $this->db->db_query($sql);
			if ($this->db->rs_num_rows($rs_news) < 1) {
				$this->errorMessage = (string) 'no_searchresult';
			} // end if
			while ($news_r = $this->db->rs_fetch_row($rs_news)) {
				$newsID = (int) $news_r[0];
				$news = new News($newsID);
				$this->newsList[$newsID] = $news;
			} // end while
			unset($newsID);
			$this->db->db_freeresult($rs_news);
			$this->newsListCountAll = (int) $this->db->rs_single($sql_count);
		} // end if

		if ($this->limiter == 2 && $this->isValidID != false) { // Prev/Next
			$this->prevpage = (boolean) ( (($this->rs_start - $this->rs_listsize) >= 0) ? true : false );
			$this->nextpage = (boolean) ( (($this->rs_start + $this->rs_listsize) <= $this->newsListCountAll) ? true : false );
			if ($this->newsListCountAll > $this->rs_listsize) {
				$this->pages = (array) array();
				for ($i = (int) 0, $sum_pages = (int) ceil($this->newsListCountAll/$this->rs_listsize); $i < $sum_pages; $i++) {
					$this->pages[($i+1)] = ($i * $this->rs_listsize);
				} // end for
				unset($i);
				unset($sum_pages);
			} // end if
		} else {
			$this->prevpage = (boolean) false;
			$this->nextpage = (boolean) false;
			$this->pages = (array) array();
		} // end if
	} // end function


	function &getNews($id = false) {
		if ($id != false && is_int($id) && array_key_exists($id, $this->newsList)) {
			return (object) $this->newsList[$id];
		} else {
			return (string) 'no_searchresult';
		} // end if
	} // end function

	function &getNewsList() {
		return (array) $this->newsList;
	} // end function

	function getNewsListCountAll() {
		return (int) $this->newsListCountAll;
	} // end function

	function getErrorMessage() {
		return (string) $this->errorMessage;
	}

	function getPrevPage() {
		return (boolean) $this->prevpage;
	}

	function getNextPage() {
		return (boolean) $this->nextpage;
	}

	function getPages() {
		return (array) $this->pages;
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
} // End Class NewsList
?>
