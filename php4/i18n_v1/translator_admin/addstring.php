<?php
require_once('header.inc.php');
include_once('class.ReloadPreventer.inc.php');
include_once('../class.Language.inc.php');
$rp = (object) new ReloadPreventer();

if (isset($_POST['SubmitString']) && isset($_POST['newstring'])) { // only check if form is submitted
	if ($rp->isValid() == true) { // check if tokens match up
		$newstring = (string) trim($_POST['newstring']);

		$query = (string) 'SELECT SQL_SMALL_RESULT COUNT(*) FROM ' .  $db_table . ' WHERE string = "' . $newstring . '"';
		$result = mysql_query($query, $conn) or die ('Request not possible! SQL Statement: ' . $query . ' / ' . mysql_error());
		if (mysql_result($result, 0, 0) > 0) {
			die ('Error: String already exists in Database');
		} // end if

		if (strlen(trim($_POST['newnamespace'])) > 0) {
			$namespace = trim($_POST['newnamespace']);
		} else {
			$namespace = trim($_POST['namespace']);
		} // end if

		$query = (string) 'INSERT INTO ' . $db_table . ' (namespace, string) VALUES ("' . $namespace . '", "' . $newstring . '")';
		$result = mysql_query($query, $conn) or die ('Request not possible! SQL Statement: ' . $query . ' / ' . mysql_error());
		$query = (string) "ALTER TABLE `" . $db_table . "` ORDER BY `namespace` ASC, `string` ASC";
		$altertable = mysql_query($query, $conn) or die ('Request not possible! SQL Statement: ' . $query . ' / ' . mysql_error());

		$rp->setTokenSession();

		$path_parts = pathinfo($_SERVER['SCRIPT_NAME']);
		$port = (string) (($_SERVER['SERVER_PORT'] != 80) ? ':' . $_SERVER['SERVER_PORT'] : '' );
		header('Location:http://' . $_SERVER['SERVER_NAME'] . $port . $path_parts["dirname"] . '/index.php?suc=1&' . session_name() . '=' . session_id());
	} else {
		die ('Error: Data sent twice!');
	} // end if
} // end if
ob_end_flush();
?>