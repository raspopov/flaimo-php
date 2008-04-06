<?php
require_once('header.inc.php');
require_once('html_header.inc.php');

$query  = 'SELECT namespace, string';
$query .= ' FROM ' . $db_table;
$query .= ' ORDER BY namespace, string ASC';
$result =  mysql_query ($query, $conn) or die ('Request not possible! SQL Statement: ' . $query . ' / ' . mysql_error());
?>
<h2>List of all translation strings</h2>
<table class="listtable">
<caption>String List</caption>
<tr><th class="headercell" width="50%">String</th><th class="headercell">Translation</th></tr>
<?php
if (mysql_num_rows ($result) > 0) {
	$current_namespace = (string) '';
	while ($row = mysql_fetch_row($result)) {
		if ($current_namespace != $row[0]) {
			echo '<tr><td class="namespace" colspan="2">' . htmlentities($row[0]) . '</td></tr>';
		} // end if
		$current_namespace = (string) $row[0];
		echo '<tr><td class="cell">' . htmlentities($row[1]) . '</td><td class="cell">&nbsp;</td></tr>';
	} // end while
} // end if
?>
</table>
<?php require_once('footer.inc.php') ?>