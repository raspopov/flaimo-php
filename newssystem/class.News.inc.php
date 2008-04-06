<?php
/*
2002 (c) Michael Wimmer (flaimo@gmx.net | http://flaimo.com)
-------------------------------------------------------------------
*/

@include_once('class.DBclass.inc.php');
@include_once('class.Language.inc.php');
@include_once('class.Helpers.inc.php');
@include_once('class.CommentList.inc.php');
@include_once('class.Author.inc.php');

class News {

	/* V A R I A B L E S */

	var $isValidNews;
	var $db;
	var $lg;
	var $helpers;
	var $dataLoaded;

	var $tbl_news;
	var $tbl_category;
	var $tbl_author;

	var $id;
	var $headline;
	var $newstext;
	var $source;
	var $source_link;
	var $news_date;
	var $category_id;
	var $news_changed_date;
	var $image;
	var $image_alttext;
	var $links;
	var $no_comments;
	var $show_full_text;
	var $category_name;
	var $author;
	var $comments;

	var $next_article;
	var $previous_article;

	/* C O N S T R U C T O R */

	function News($id) {
		$this->checkClass('DBClass');
		$this->checkClass('Helpers');
		$this->id = (int) $id;
		$this->validateID($this->id);
		$this->db = (object) new DBclass();
		$this->helpers = (object) new Helpers();
	} // end function


	/* F U N C T I O N S */


	function validateID($id) {
		$this->isValidNews = (boolean) true;
		if (!is_int($id) || $id == 0) {
			$this->isValidNews = (boolean) false;
		} // end if
	}  // end function

	function emptyNews($string) {
		$this->checkClass('Author');
		if (!isset($this->lg)) {
			$this->checkClass('Language');
			$this->lg = (object) new Language();
		}
		$string = (string) $this->lg->Translate($string);
		$this->id = (int) 0;
		$this->headline = (string) $string;
		$this->newstext = (string) $string;
		$this->source = (string) '';
		$this->source_link = (string) '';
		$this->news_date = (string) date('Y-m-d H:i:s',time());
		$this->category_id = (int) 0;
		$this->news_changed_date = (string) date('Y-m-d H:i:s',time());
		$this->image = (string) '';
		$this->image_alttext = (string) '';
		$this->links = (array) array();
		$this->no_comments = (boolean) true;
		$this->show_full_text = (boolean) false;
		$this->category_name = (string) $string;
		$this->author_id = (int) 0;
		$this->author = (object) new Author(1);
	} // end function


	function setNews() {
		if ($this->dataLoaded == false) {
			if ($this->isValidNews == false) {
				$this->emptyNews('wrong_input_for_id');
			} else {
				$sql  = (string) 'SELECT ' . $this->helpers->getTableName('tbl_news') . '.IDNews,' . $this->helpers->getTableName('tbl_news') . '.NewsHeadline,' . $this->helpers->getTableName('tbl_news') . '.NewsText,' . $this->helpers->getTableName('tbl_news') . '.NewsSource,' . $this->helpers->getTableName('tbl_news') . '.NewsSourceLink,' . $this->helpers->getTableName('tbl_news') . '.NewsDate,' . $this->helpers->getTableName('tbl_news') . '.NewsCategory,' . $this->helpers->getTableName('tbl_news') . '.NewsChangedDate,' . $this->helpers->getTableName('tbl_news') . '.NewsImage,' . $this->helpers->getTableName('tbl_news') . '.NewsImageAlttext,' . $this->helpers->getTableName('tbl_news') . '.NewsLinks,' . $this->helpers->getTableName('tbl_news') . '.NoComments,' . $this->helpers->getTableName('tbl_news') . '.ShowFullText,' . $this->helpers->getTableName('tbl_category') . '.CategoryName,' . $this->helpers->getTableName('tbl_author') . '.IDAuthor,' . $this->helpers->getTableName('tbl_author') . '.AuthorNick';
				$sql .= (string) ' FROM ' . $this->helpers->getTableName('tbl_news');
				$sql .= (string) ' INNER JOIN ' . $this->helpers->getTableName('tbl_author') . ' ON (' . $this->helpers->getTableName('tbl_news') . '.NewsAuthor = ' . $this->helpers->getTableName('tbl_author') . '.IDAuthor)';
				$sql .= (string) ' INNER JOIN ' . $this->helpers->getTableName('tbl_category') . ' ON (' . $this->helpers->getTableName('tbl_news') . '.NewsCategory = ' . $this->helpers->getTableName('tbl_category') . '.IDCategory)';
				$sql .= (string) ' WHERE ' . $this->helpers->getTableName('tbl_news') . '.IDNews = ' . $this->id;

				$rs_news = $this->db->db_query($sql);
				if ($this->db->rs_num_rows($rs_news) > 0) {
					$this->dataLoaded == true;
					while ($news = $this->db->rs_fetch_assoc($rs_news)) {
						$this->id = (int) $news['IDNews'];
						$this->headline = (string) $news['NewsHeadline'];
						$this->newstext = (string) $news['NewsText'];
						$this->source = (string) $news['NewsSource'];
						$this->source_link = (string) $news['NewsSourceLink'];
						$this->news_date = (string) $news['NewsDate'];
						$this->category_id = (int) $news['NewsCategory'];
						$this->news_changed_date = (string) $news['NewsChangedDate'];
						$this->image = (string) $news['NewsImage'];
						$this->image_alttext = (string) $news['NewsImageAlttext'];
						$this->links = (array) array();
						if (strlen(trim($news['NewsLinks'])) > 0) {
							$link_list = explode(',',$news['NewsLinks']);
							for ($i = (int) 0, $max = (int) sizeof($link_list); $i <= $max; $i++) {
								if ($i < $max) {
									$this->links[$link_list[$i+1]] = $link_list[$i];
								} // end if
								$i++;
							} // end for
							unset($i);
							unset($link_list);
							unset($max);
						} // end if
						$this->no_comments = (boolean) $news['NoComments'];
						$this->show_full_text = (boolean) $news['ShowFullText'];
						$this->category_name = (string) $news['CategoryName'];
						$author_id = (int) $news['IDAuthor'];
						$this->author = new Author($author_id);
					} // end while
				} else {
					$this->isValidNews = false;
					$this->emptyNews('no_news');
				} // end if
				$this->db->db_freeresult($rs_news);
			} // end if
		} // end if
	} // end function

	function setComments() {
		$this->checkClass('CommentList');
		if (!isset($this->comments)) {
			$this->comments = (object) new CommentList($this->id);
		} // end if
	} // end function

	function &getComments() {
		if (!isset($this->comments)) {
			$this->setComments();
		} // end if
		return (object) $this->comments;
	} // end function


	function updateNewsRead() {
		$sql  = 'UPDATE ' . $this->helpers->getTableName('tbl_news');
		$sql .= ' SET NewsRead = (NewsRead+1)';
		$sql .= ' WHERE IDNews = ' . $this->id;
		$rs_viewsupdate = $this->db->db_query($sql);
	} // end function

	function getPreviousNews() {
		if (!isset($this->previous_article)) {
			$sql  = "SELECT IDNews";
			$sql .= " FROM " . $this->helpers->getTableName('tbl_news');
			$sql .= " WHERE NewsCategory = " . $this->category_id;
			$sql .= " AND IDNews > " . $this->id;
			$sql .= " ORDER BY IDnews ASC LIMIT 1";

			$prev_news_rs = $this->db->db_query($sql);
			if ($this->db->rs_num_rows($prev_news_rs) > 0) {
				$this->checkClass('News');
				$prev_news_r = $this->db->rs_fetch_row($prev_news_rs);
				$prev_news = (int) $prev_news_r[0];
				$this->previous_article = (object) new News($prev_news);
				unset($prev_news_r);
				unset($prev_news);
			} else {
				$this->previous_article = false;
			}  // end if
			$this->db->db_freeresult($prev_news_rs);
		} // end if
		return $this->previous_article;
	} // end function


	function getNextNews() {
		if (!isset($this->next_article)) {
			$sql  = 'SELECT IDNews';
			$sql .= ' FROM ' . $this->helpers->getTableName('tbl_news');
			$sql .= ' WHERE NewsCategory = ' . $this->category_id;
			$sql .= ' AND IDNews < ' . $this->id;
			$sql .= ' ORDER BY IDnews DESC LIMIT 1';

			$next_news_rs = $this->db->db_query($sql);
			if ($this->db->rs_num_rows($next_news_rs) > 0) {
				$this->checkClass('News');
				$next_news_r = $this->db->rs_fetch_row($next_news_rs);
				$next_news = (int) $next_news_r[0];
				$this->next_article = (object) new News($next_news);
				unset($next_news_r);
				unset($next_news);
			} else {
				$this->next_article = false;
			}  // end if
			$this->db->db_freeresult($next_news_rs);
		} // end if
		return $this->next_article;
	} // end function


	function insertComment($comment, $name, $mail) {
		if (!isset($this->lg)) {
			$this->checkClass('Language');
			$this->lg = (object) new Language();
		} // end if
		if (!isset($name) || strlen(trim($name)) < 1 || !isset($comment) || strlen(trim($comment)) < 1) {
			return (boolean) false;
		} else {
			$sql  = (string) 'INSERT INTO ' . $this->helpers->getTableName('tbl_comment') . ' (NewsID, Language, CommentText, CommentAuthor, CommentEmail, CommentDate, CommentIP)';
			$sql .= (string) ' VALUES (' . $this->id . ',"' . $this->lg->getLangContent() . '","' . trim($comment) . '","' . trim($name) . '","' . trim($mail) . '","'. date('Y-m-d H:i:s') . '","' . $_SERVER['REMOTE_ADDR'] . '")';
			$rs_insertcomment = $this->db->db_query($sql);
			return (boolean) true;
		} // end if
	} // end function

	function getNewsID() {
		return (int) $this->id;
	}

	function getHeadline() {
		$this->setNews();
		return (string) $this->headline;
	}

	function getNewstext() {
		$this->setNews();
		return (string) $this->newstext;
	}

	function getSource() {
		$this->setNews();
		return (string) $this->source;
	}

	function getSourceLink() {
		$this->setNews();
		return (string) $this->source_link;
	}

	function getNewsdate() {
		$this->setNews();
		return (string) $this->news_date;
	}

	function getCategoryID() {
		$this->setNews();
		return (int) $this->category_id;
	}
	function getNewsChangedDate() {
		$this->setNews();
		return (string) $this->news_changed_date;
	}

	function getImage() {
		$this->setNews();
		return (string) $this->image;
	}

	function getImageAlttext() {
		$this->setNews();
		return (string) $this->image_alttext;
	}

	function getLinks() {
		$this->setNews();
		return (array) $this->links;
	}

	function getNoComments() {
		$this->setNews();
		return (boolean) $this->no_comments;
	}

	function getShowFullText() {
		$this->setNews();
		return (boolean) $this->show_full_text;
	}

	function getCategoryName() {
		$this->setNews();
		return (string) $this->category_name;
	}

	function getAuthor() {
		$this->setNews();
		return (object) $this->author;
	}

} // End Class News
?>
