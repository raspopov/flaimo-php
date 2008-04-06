<?php
require_once('header.inc.php');
require_once('html_header.inc.php');

function shortenString($string) {
	$cutat = (int) 200;
	$strlength = (int) strlen(trim($string));
	return (string) (($strlength <= $cutat) ? $string : substr($string, 0, $cutat) . '&#8230;');
} // end function

$lang = (string) trim($_GET['col']);
$query = (string) 'SELECT namespace,string, ' . $lang . ' FROM ' . $db_table . ' WHERE';
$query_null = (string) $query . ' ISNULL(' . $lang . ') ORDER BY string ASC';
$query_not_null = (string) $query . ' ' . $lang . ' IS NOT NULL ORDER BY namespace, string, ' . $lang . ' DESC';

$result_null =  mysql_query ($query_null, $conn) or die ('Request not possible! SQL Statement: ' . $query_null . ' / ' . mysql_error());
$result_not_null =  mysql_query ($query_not_null, $conn) or die ('Request not possible! SQL Statement: ' . $query_not_null . ' / ' . mysql_error());
?>
<h2>Translation list for language &#8220;<?php echo getCountryName($lang);?>&#8221;</h2>
<?php
if (mysql_num_rows ($result_null) > 0) {
	$current_namespace = (string) '';
	echo '<table class="listtable"><caption>Untranslated Strings</caption><tr><th class="headercell">String</th><th class="headercell">Translation</th></tr>';
	while ($row = mysql_fetch_row ($result_null)) {
		if ($current_namespace != $row[0]) {
			echo '<tr><td class="namespace" colspan="2">' . htmlentities($row[0]) . '</td></tr>';
			$current_namespace = (string) $row[0];
		} // end if
		echo '<tr><td class="cell">' . htmlentities($row[1]) . '</td><td class="cell">&nbsp;</td></tr>';
	}
	echo '</table><br />';
} // end if

if (mysql_num_rows ($result_not_null) > 0) {
	$current_namespace = (string) '';
	echo '<table class="listtable"><caption>Translated Strings</caption><tr><th class="headercell">String</th><th class="headercell">Translation</th></tr>';
	while ($row = mysql_fetch_row($result_not_null)) {
		if ($current_namespace != $row[0]) {
			echo '<tr><td class="namespace" colspan="2">' . htmlentities($row[0]) . '</td></tr>';
			$current_namespace = (string) $row[0];
		} // end if
		echo '<tr><td class="cell">' . htmlentities($row[1]) . '</td><td class="cell">' . $row[2] . '</td></tr>';
	}
	echo '</table>';
} // end if

require_once('footer.inc.php'); ?>