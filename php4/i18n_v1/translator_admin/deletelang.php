<?php
require_once('header.inc.php');
include_once('../class.Language.inc.php');
$lg = (object) new Language();

if (isset($_GET['col'])) { // only check if form is submitted
	$lang = (string) strtolower(trim($_GET['col']));
	if ($lg->IsValidLanguageCode($lang) == TRUE) {
		$query = 'ALTER TABLE ' . $db_table . ' DROP ' . $lang;
		$result = mysql_query($query, $conn) or die ('Request not possible! SQL Statement: ' . $query . ' / ' . mysql_error());
	} else {
		die ('Error: Not an iso-code!');
	} // end if
} // end if
$path_parts = pathinfo($_SERVER['SCRIPT_NAME']);
$port = (string) (($_SERVER['SERVER_PORT'] != 80) ? ':' . $_SERVER['SERVER_PORT'] : '' );
header('Location:http://' . $_SERVER['SERVER_NAME'] . $port . $path_parts["dirname"] . '/index.php?' . session_name() . '=' . session_id());
ob_end_flush();
?>