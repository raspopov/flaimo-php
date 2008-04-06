<?php
ob_start();
header("content-type: text/html;charset=UTF-8 \r\n");
define('PHPSELF', $_SERVER['PHP_SELF']);
define('MAX_ANSWERS', 6);
define('SCRIPT_PATH_PUBLIC', '/php/poll/root/');
define('SCRIPT_PATH_ADMIN', '/php/poll/root/admin/');
define('SCRIPT_PATH_CLASSES', '/php/poll/');
define('IMAGES_PATH', '/php/poll/root/images/');
define('MAX_LENGTH_CATEGORY_NAME', 50); // in db max 50 chars
define('MAX_LENGTH_POLL_TITLE', 150); // in db max 150 chars
define('MAX_LENGTH_POLL_DESCRIPTION', 500); // in db unbegrenzt
define('MAX_LENGTH_POLL_BUTTON', 50); // in db max 80 chars
define('MAX_LENGTH_POLL_CP', 80); // in db max 100 chars
define('MAX_LENGTH_POLLOPTION_TITLE', 80); // in db max 120 chars
define('MAX_LENGTH_POLLOPTION_DESCRIPTION', 255); // in db unbegrenzt
define('INACTIVE_SUFIX', 'inactive');

$errors = $notices = array();
$allowed_cp_types = array('r' => 'Redaktioneller Content', 'i' => 'Infotainment', 'c' => 'Community', 's' => 'Service', 'u' => 'Unterhaltung und Games', 'e' => 'E-Commerce', 'd' => 'Diverses');

if (isset($_POST['category'])) {
	$cat_id =& $_POST['category'];
} elseif (isset($_GET['category'])) {
	$cat_id =& $_GET['category'];
} else {
	$cat_id = 1;
} // end if

require_once SCRIPT_PATH_CLASSES . 'inc/class_PollBase_inc.php';
require_once SCRIPT_PATH_CLASSES . 'smarty/class_PollSmarty_inc.php';
$base_category = new PollCategory($cat_id);
$smarty = new PollSmarty;
$bc_str = '';

if ($base_category->available() == TRUE) {
	$bc =& $base_category->getFamilyTree();
	$list = new PollList($base_category);
	if (isset($_POST['delete'])) {
		if (isset($_POST['del_check_' . $_POST['poll']]) &&
			$_POST['del_check_' . $_POST['poll']] == 1) {
			$tmp_poll = new Poll($_POST['poll']);
			$title = $tmp_poll->getTitle();
			if ($list->deletePoll($tmp_poll) == TRUE) {
				$notices[] = 'Umfrage „' . htmlspecialchars($title) . '“ erfolgreich gelöscht';
			} else {
				$errors['delete_poll'] = 'Fehler beim löschen von Umfrage „' . htmlspecialchars($title) . '“';
			} // end if
			unset($tmp_poll);
		} else {
			$errors['del_check'] = 'Zum Löschen einer Umfrage muß die linke Kontroll-Checkbox aktiviert werden';
		} // end if
	} elseif (isset($_POST['move']) && isset($_POST['category_to_use']) && isset($_POST['poll'])) {
		$selected_poll = new Poll((int) $_POST['poll']);
		$cat_to_move = new PollCategory((int) $_POST['category_to_use']);
		$selected_poll->fetchData();
		$selected_poll->setCategory($cat_to_move);
		if ($selected_poll->updateData() == TRUE) {
			$notices[] = 'Umfrage „' . htmlspecialchars($selected_poll->getTitle())  . '“ erfolgreich in Kategorie „' . htmlspecialchars($cat_to_move->getName()) . '“ verschoben';
		} else {
			$errors['move_poll'] = 'Fehler beim Verschieben von Umfrage „' . htmlspecialchars($selected_poll->getTitle())  . '“';
		} // end if
	} elseif (isset($_POST['copy']) && isset($_POST['category_to_use']) && isset($_POST['poll'])) {
		$poll_to_copy = new Poll((int) $_POST['poll']);
		$category_to_copy_to = new PollCategory((int) $_POST['category_to_use']);

		if ($list->copyPoll($poll_to_copy, $category_to_copy_to) == TRUE) {
			$notices[] = 'Umfrage „' . $poll_to_copy->getTitle()  . '“ erfolgreich in Kategorie „' . htmlspecialchars($category_to_copy_to->getName()) . '“ kopiert';
		} else {
			$errors['copy_poll'] = 'Fehler beim Verschieben von Umfrage „' . htmlspecialchars($poll_to_copy->getTitle())  . '“';
		} // end if
	} // end if

	foreach ($bc as &$cat) {
		$bc_str .= ' <a href="index.php?category=' . $cat->getID() . '">' . htmlspecialchars($cat->getName()) . '</a> →';
	} // end foreach
	$bc_str = mb_substr($bc_str, 0, mb_strlen($bc_str) - 1);
	$base_category_list = new PollCategoryList($base_category);

	if (isset($_POST['create_new_category']) && isset($_POST['new_category'])) {
		if (strlen(trim($_POST['new_category'])) > 0) {
			if ($base_category_list->addSubCategory($_POST['new_category'], 0, $_POST['new_category_template']) == TRUE) {
				$notices[] = 'Kategorie „' . htmlspecialchars($_POST['new_category']) . '“ erstellt';
			} else {
				$errors['add_category'] = 'Fehler beim erstellen der Kategorie';
			} // end if
		} else {
			$errors[] = 'Es muß ein Name für die neue Kategorie angegeben werden';
		} // end if
	} // end if

	$subcategories =& $base_category_list->getChildren();
} // end if

require_once 'functions_inc.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="de" xml:lang="de">
	<head>
		<title>Umfragen – Admin: <?php echo $pagetitle; ?></title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<style type="text/css">
			<!--
			@import url("poll_admin.css");
			-->
		</style>
		<script type="text/javascript">
			<!--
			function disableButton(submit_button, label) {
				submit_button.disabled = true;
				submit_button.value = label;
				submit_button.form.submit();
				return true;
			} // end function

			function toggleHelp() {
				var button 		= document.getElementById("togglehelp");
				var divs 		= document.getElementsByTagName("div");
				var divlength 	= divs.length;

				for (var i = 0; i < divlength; i++) {
					if (divs[i].className != "help") {
						continue;
					} // end if

					if (divs[i].style.display != "block") {
						divs[i].style.display = "block";
						button.innerHTML = "Hilfe <b>aus</b>schalten";
					} else {
						divs[i].style.display = "none";
						button.innerHTML = "Hilfe <b>ein</b>schalten";
					} // end if
				} // end for
				return true;
			} // end function
			//-->
		</script>
	</head>
<body>
	<div id="header">
		<h1>P<sub>2</sub></h1>
		<p id="breadcrumb"><?php echo $bc_str; ?></p>
		<h2><?php echo $pagetitle; ?></h2>
	</div>
	<div id="navigation">
		<?php
		if ($base_category->available() == TRUE) {
			echo '<h3>' , htmlspecialchars($base_category->getName()) , '</h3>';
			if (count($subcategories) > 0) {
				echo '<ul id="subcategories">';
				foreach ($subcategories as &$subcat) {
					echo '<li><a href="index.php?category=' . $subcat->getID() . '">' , htmlspecialchars($subcat->getName()) , '</a></li>';
				} // end if
				echo '</ul>';
			} // end if
		} // end if
		?>
	</div>
	<div id="content">