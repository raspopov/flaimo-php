<?php
/*
2002 (c) Michael Wimmer (flaimo@gmx.net | http://flaimo.com)
-------------------------------------------------------------------

-------------------------------------------------------------------
*/

@include_once('class.DBclass.inc.php');
@include_once('class.Helpers.inc.php');

class CategoryList {

	/* V A R I A B L E S */

	var $categoryList;
	var $lang;


	/* C O N S T R U C T O R */

	function CategoryList($lang = 'all') {
		$this->checkClass('DBclass');
		$this->checkClass('Helpers');
		$this->db = (object) new DBclass();
		$this->lang = $lang;
		$this->helpers = (object) new Helpers();
		$this->setCategoryList();
	} // end function

	/* F U N C T I O N S */

	function setCategoryList() {
		$this->categoryList = (array) array();
		$sql  = (string) 'SELECT DISTINCT ' . $this->helpers->getTableName('tbl_news') . '.NewsCategory, ' . $this->helpers->getTableName('tbl_category') . '.CategoryName, COUNT(' . $this->helpers->getTableName('tbl_news') . '.IDNews) as sum_cat_news';
		$sql .= (string) ' FROM ' . $this->helpers->getTableName('tbl_news');
		$sql .= (string) ' LEFT JOIN ' . $this->helpers->getTableName('tbl_category') . ' ON (' . $this->helpers->getTableName('tbl_news') . '.NewsCategory = ' . $this->helpers->getTableName('tbl_category') . '.IDCategory)';
		if ($this->lang != 'all') {
			$sql .= (string) " WHERE " . $this->helpers->getTableName('tbl_news') . ".Language = '" . $this->lang . "'";
		} // end if
		$sql .= (string) " GROUP BY " . $this->helpers->getTableName('tbl_news') . ".NewsCategory";
		$sql .= (string) " HAVING sum_cat_news > 0";

		$rs_category = $this->db->db_query($sql);
		if ($this->db->rs_num_rows($rs_category) > 0) {
			while ($cat_r = $this->db->rs_fetch_assoc($rs_category)) {
				$catID = (int) $cat_r['NewsCategory'];
				$name = (string) $cat_r['CategoryName'];
				$sumNews = (int) $cat_r['sum_cat_news'];
				$this->categoryList[$name] = $catID . '_' . $sumNews;
			} // end while
			unset($sumNews);
			unset($name);
			unset($catID);
			unset($author);
			unset($author_r);
		} // end if
		$this->db->db_freeresult($rs_category);
	} // end function


	function getCategoryList() {
		return (array) $this->categoryList;
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
} // end class CategoryList
?>
