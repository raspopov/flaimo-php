<?php require_once('header.inc.php'); ?>
<?php require_once('html_header.inc.php'); ?>

Login Überprüfung. falls ja kommt das:
<?php
if (isset($_GET['suc'])) {
	echo '<p><strong>String successfully written to database!</strong></p>';
}

?>
<h2>Step 2</h2>
<h3>Select Language</h3>
<table border="1">
<caption>Language List</caption>
<tr><th>Language</th><th colspan="2">Options</th></tr>
<?php
$rp = (object) new ReloadPreventer();
$rp->setTokenSession();

$fields = mysql_list_fields($database, $db_table, $conn);
$columns = mysql_num_fields($fields);
for ($i = 3; $i < $columns; $i++) {
	$name = (string) trim( mysql_field_name($fields, $i));
	echo '<tr><td>' . getCountryName($name) . '</td>';
	echo '<td><a href="editlang.php?col=' . $name . '&amp;' . session_name() . '=' . session_id() . '">Edit</a></td>';
	echo '<td><a href="deletelang.php?col=' . $name . '&amp;' . session_name() . '=' . session_id() . '">Delete</a></td></tr>';
} // end for
?>
</table>
<hr />
<h3>Add Language</h3>
<form name="addlang" id="addlang" method="post" action="addlang.php">
	<input type="hidden" name="<?php echo $rp->getFormName(); ?>" value="<?php echo $rp->getToken(); ?>" />
	<input type="hidden" name="<?php echo session_name(); ?>" value="<?php echo session_id(); ?>" />
	<label for="newlang"></label>
	<input type="text" name="newlang" id="newlang" size="5" maxlength="5" />
	<input type="submit" name="SubmitLang" value="Add Language" id="SubmitLang" />
	(Only <a href="http://nl.ijs.si/gnusl/cee/std/ISO_639.html" target="_blank">iso-named</a> languages allowed)
</form>
<hr />
<h3>Add Translation string</h3>
<form name="addstring" id="addstring" method="post" action="addstring.php">
	<input type="hidden" name="<?php echo $rp->getFormName(); ?>" value="<?php echo $rp->getToken(); ?>" />
	<input type="hidden" name="<?php echo session_name(); ?>" value="<?php echo session_id(); ?>" />
	<label for="newstring"></label>
	<input type="text" name="newstring" id="newstring" size="90" maxlength="254" /><br />
	<label for="namespace">Namespace:</label> <select id="namespace" name="namespace">
<?php
$sql = 'SELECT DISTINCT namespace FROM ' . $db_table . ' ORDER BY namespace ASC';
$result =  mysql_query(&$sql, $conn) or die ('Request not possible! SQL Statement: ' . $slq . ' / ' . mysql_error());
while ($row = mysql_fetch_array(&$result, MYSQL_NUM)) {
	echo '<option value="' . $row[0] . '">' . $row[0] . '</option>';
} // end while
?>
	</select> or enter <label for="newnamespace">new namespace:</label> <input type="text" name="newnamespace" id="newnamespace" size="25" maxlength="254" /><br />
	<input type="submit" name="SubmitString" value="Add String" id="SubmitString" />
</form>
<hr />
<h3>Print string list</h3>
<a href="print_string_list.php?<?php echo session_name() . '=' . session_id(); ?>">Print string list</a>
<?php require_once('footer.inc.php'); ?>