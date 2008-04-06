<?php
require_once('header.inc.php');

if (isset($_GET['string'])) { // only check if form is submitted
	$query = 'DELETE FROM ' . $db_table . ' WHERE string = "' . urldecode($_GET['string']) . '"';
	$result = mysql_query($query, $conn) or die ('Request not possible! SQL Statement: ' . $query . ' / ' . mysql_error());
} // end if
$path_parts = pathinfo($_SERVER['SCRIPT_NAME']);
$port = (string) (($_SERVER['SERVER_PORT'] != 80) ? ':' . $_SERVER['SERVER_PORT'] : '' );
header('Location:http://' . $_SERVER['SERVER_NAME'] . $port . $path_parts["dirname"] . '/editlang.php?col=' . $_GET['col'] . '&' . session_name() . '=' . session_id());
ob_end_flush();
?>