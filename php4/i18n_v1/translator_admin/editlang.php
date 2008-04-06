<?php
require_once('header.inc.php');
require_once('html_header.inc.php');

function shortenString($string) {
	$cutat = (int) 200;
	$strlength = (int) strlen(trim($string));
	return (string) (($strlength <= $cutat) ? $string : substr($string, 0, $cutat) . '&#8230;');
}

$lang = (string) trim($_GET['col']);
if (isset($_GET['orderby']) && $_GET['orderby'] == 'translation') {
	$order_by = (string) $lang . ', string';
} else {
	$order_by = (string) 'string, ' . $lang;
} // end if

$query = (string) 'SELECT namespace, string, ' . $lang . ' FROM ' . $db_table . ' WHERE';
$query_null = (string) $query . ' ISNULL(' . $lang . ') ORDER BY ' . $order_by . ' ASC';
$query_not_null = (string) $query . ' ' . $lang . ' IS NOT NULL ORDER BY ' . $order_by . ' DESC';

$result_null =  mysql_query ($query_null, $conn) or die ('Request not possible! SQL Statement: ' . $query_null . ' / ' . mysql_error());
$result_not_null =  mysql_query ($query_not_null, $conn) or die ('Request not possible! SQL Statement: ' . $query_not_null . ' / ' . mysql_error());
?>
<h2>Step 3: Edit Language &#8220;<?php echo getCountryName($lang);?>&#8221;</h2>
<?php
if (isset($_GET['orderby']) && $_GET['orderby'] == 'translation') {
	echo '<p><strong><a href="' . $_SERVER['PHP_SELF'] . '?col=' . $lang . '&amp;orderby=string&amp;' . session_name() . '=' . session_id() . '">Order by String</a></strong></p>';
} else {
	echo '<p><strong><a href="' . $_SERVER['PHP_SELF'] . '?col=' . $lang . '&amp;orderby=translation&amp;' . session_name() . '=' . session_id() . '">Order by Translation</a></strong></p>';
} // end if

if (mysql_num_rows ($result_null) > 0) {
	echo '<table border="1"><caption>Untranslated Strings</caption><tr><th>Namespace</th><th>String</th><th colspan="2">Options</th></tr>';
	while ($row = mysql_fetch_row ($result_null)) {
		echo '<tr><td>' . htmlentities($row[0]) . '</td><td>' . htmlentities($row[1]) . '</td><td><a href="edittranslation.php?string=' . urlencode($row[1]) . '&amp;col=' . $lang . '&amp;' . session_name() . '=' . session_id() . '">Edit</a></td><td><a href="deletestring.php?string=' . urlencode($row[1]) . '&amp;col=' . $lang . '&amp;' . session_name() . '=' . session_id() . '">Delete String</a> (all languages)</td></tr>';
	} // end while
	echo '</table><br />';
} // end if

if (mysql_num_rows ($result_not_null) > 0) {
	echo '<table border="1"><caption>Translated Strings</caption><tr><th>Namespace</th><th>String</th><th>Translation</th><th colspan="2">Options</th></tr>';
	while ($row = mysql_fetch_row($result_not_null)) {
		echo '<tr><td>' . htmlentities($row[0]) . '</td><td>' . htmlentities($row[1]) . '</td><td>' . shortenString($row[2]) . '</td><td><a href="edittranslation.php?string=' . urlencode($row[1]) . '&amp;col=' . $lang . '&amp;' . session_name() . '=' . session_id() . '">Edit</a></td><td><a href="deletestring.php?string=' . urlencode($row[1]) . '&amp;col=' . $lang . '&amp;' . session_name() . '=' . session_id() . '">Delete String</a> (all languages)</td></tr>';
	} // end while
	echo '</table>';
} // end if
?>
<p><a href="print_lang.php?col=<?php echo $lang; ?>&amp;<?php echo session_name(); ?>=<?php echo session_id(); ?>">Printable List</a></p>
<p><a href="index.php?<?php echo session_name(); ?>=<?php echo session_id(); ?>">Back to language selection</a></p>
<?php require_once('footer.inc.php'); ?>