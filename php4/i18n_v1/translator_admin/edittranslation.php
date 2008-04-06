<?php
require_once('header.inc.php');
require_once('html_header.inc.php');

$lang = (string) trim($_GET['col']);
$string = (string) trim(urldecode($_GET['string']));
$query = (string) 'SELECT SQL_SMALL_RESULT ' . $lang . ' FROM ' . $db_table . ' WHERE string = "' . $string . '"';
$result =  mysql_query ($query, $conn) or die ('Request not possible! SQL Statement: ' . $query . ' / ' . mysql_error());
$rp = (object) new ReloadPreventer();
$rp->setTokenSession();
?>
<h2>Step 4: Edit String</h2>
<?php
if (mysql_num_rows ($result) < 1) {
	die ('Error: String not found in Database');
} else {
	echo '<p><strong>String</strong><br />' . $string . '</p>';
	echo '<p><strong>Language</strong><br />' . getCountryName($lang) . '</p>';
	$row = mysql_fetch_row($result);
	$prestring = (string) mysql_result($result, 0, 0);
?>
	<p><strong><label for="textarea">Translation</label></strong></p>
<form name="edittranslation" id="edittranslation" method="post" action="updatetranslation.php">
	<textarea name="updatetranslation" id="updatetranslation" cols="70" rows="12"><?php echo trim(mysql_result($result, 0, 0)); ?></textarea><br /><br />
	<input type="hidden" name="<?php echo $rp->getFormName(); ?>" value="<?php echo $rp->getToken(); ?>">
	<input type="hidden" name="<?php echo session_name(); ?>" value="<?php echo session_id(); ?>" />
	<input type="hidden" name="col" value="<?php echo $lang; ?>" />
	<input type="hidden" name="string" value="<?php echo $string; ?>" />
	<input type="submit" name="Submit_return" value="Save Translation &amp; return to string list" id="Submit_return" />
<?php
	$query = (string) 'SELECT SQL_SMALL_RESULT COUNT(*) FROM ' . $db_table . ' WHERE ISNULL(' . $lang . ')';
	$result = mysql_query($query, $conn) or die ('Request not possible! SQL Statement: ' . $query . ' / ' . mysql_error());
	if (mysql_result($result, 0, 0) > 0) {
?>
	<br /><strong>or</strong><br />
	<input type="submit" name="Submit_next" value="Save Translation &amp; edit next untranslated string" id="Submit_next" />
<?php } ?>
</form>
<?php
} // end if
?>
<p><a href="editlang.php?col=<?php echo $lang; ?>&amp;<?php echo session_name(); ?>=<?php echo session_id(); ?>">Back to string selection</a></p>
<?php require_once('footer.inc.php'); ?>