<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
//+----------------------------------------------------------------------+
//| WAMP (XP-SP1/1.3.24/4.0.12/4.3.0)                                    |
//+----------------------------------------------------------------------+
//| Copyright (c) 1992-2003 Michael Wimmer                               |
//+----------------------------------------------------------------------+
//| I don't have the time to read through all the licences to find out   |
//| what the exactly say. But it's simple. It's free for non commercial  |
//| projects, but as soon as you make money with it, i want my share :-) |
//+----------------------------------------------------------------------+
//| Authors: Michael Wimmer <flaimo@gmx.net>                             |
//+----------------------------------------------------------------------+
//
// $Id$
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
<title>File2Database Converter</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<style type="text/css">
<!--
.red {
	color: red;
}
.green {
	color: green;
}

-->
</style>
</head>
<body>
<?php
$notice = (string) '';
$name = (string) ((!isset($_POST['file'])) ? '' : $_POST['file'] );

$host = 			(string) 'localhost';
$user = 			(string) 'root';
$password = 		(string) '';
$database = 		(string) 'translator_testdb';
$db_table = 		(string) 'flp_translator';

$conn = mysql_pconnect($host, $user, $password) or die ('Connection not possible! => ' . mysql_error());
mysql_select_db($database) or die ('Couldn\'t connect to "' . $database . '" => ' . mysql_error());

$fields = mysql_list_fields($database, $db_table, $conn);
$columns = (int) mysql_num_fields(&$fields);
for ($i = 1; $i < $columns; $i++) {
	$languages[] = trim(mysql_field_name(&$fields, $i));
} // end for

if (isset($_POST['lang']) && isset($_POST['file']) && $_POST['method'] == 'inc2db') {
    $inc_file = $_POST['file'];
	if ($languagefile = (array) file($inc_file)) {
	    foreach ($languagefile as $key => $value) {
	        if (strlen(trim($value)) > 0) {
	        	list($lang_array_key,$lang_array_value) = split(' = ', $value);
	        } else {
				$lang_array_key = '';
				$lang_array_value = '';
	        } // end if

	        if ((strlen(trim($lang_array_key)) > 0) && (strlen(trim($lang_array_value)) > 0)) {
				$lang_array_key = (string) trim($lang_array_key);
				$lang_array_value = (string) trim($lang_array_value);

				$query = (string) 'SELECT SQL_SMALL_RESULT COUNT(*) FROM ' .  $db_table . ' WHERE string = "' . $lang_array_key . '"';
				$result = mysql_query($query, $conn) or die ('Request not possible! SQL Statement: ' . $query . ' / ' . mysql_error());
				if (mysql_result($result, 0, 0) > 0) {
					$notice .= (string) '<span class="red">"' . $lang_array_key . '": String not written to database because it already exists.</span><br />';
				} else {
					if ((strlen($lang_array_key) > 0) && (strlen($lang_array_value) > 0)) {
						$query = (string) 'INSERT INTO ' . $db_table . ' (string, ' . $_POST['lang'] . ') VALUES ("' . $lang_array_key . '", "' . $lang_array_value . '")';
						$notice .= (string) '<span class="green">"' . $lang_array_key . '" written to database</span><br />';
						$result = mysql_query($query, $conn) or die ('Request not possible! SQL Statement: ' . $query . ' / ' . mysql_error());
					} // end if
				} // end if
	        } // end if
	    } // end foreach
	    unset($languagefile);
	} else {
	    die('Problem with reading inc file!');
	} // end if
} elseif (isset($_POST['lang']) && isset($_POST['file']) && $_POST['method'] == 'po2db') {
    $inc_file = $_POST['file'];
	$namespace = $_POST['namespace'];
	$lang_array_key = '';
	$lang_array_value = '';
	if ($gettextfile = (array) file($inc_file)) {
        foreach ($gettextfile as $key => $value) {
            if (trim(substr($value, 0, 5)) == 'msgid') {
                $temp_val = (string) str_replace('"','',trim(substr($value, 6)));
                if (strlen(trim($temp_val)) > 0) {
                    $lang_array_key = trim($temp_val);
                }
            } elseif (trim(substr($value, 0, 6)) == 'msgstr') {
                $temp_val = (string) str_replace('"','',trim(substr($value, 7)));
                if (strlen(trim($temp_val)) > 0) {
                    $lang_array_value = trim($temp_val);
                }
            } // end if
			if ((strlen($lang_array_key) > 0) && (strlen($lang_array_value) > 0)) {
				$query = (string) 'SELECT SQL_SMALL_RESULT COUNT(*) FROM ' .  $db_table . ' WHERE string = "' . $lang_array_key . '"';
				$result = mysql_query($query, $conn) or die ('Request not possible! SQL Statement: ' . $query . ' / ' . mysql_error());
				if (mysql_result($result, 0, 0) > 0) {
					$notice .= (string) '<span class="red">"' . $lang_array_key . '": String not written to database because it already exists.</span><br />';
				} else {
					if ((strlen($lang_array_key) > 0) && (strlen($lang_array_value) > 0)) {
						$query = (string) 'INSERT INTO ' . $db_table . ' (namespace, string, ' . $_POST['lang'] . ') VALUES ("' . $namespace . '","' . $lang_array_key . '", "' . $lang_array_value . '")';
						$notice .= (string) '<span class="green">"' . $lang_array_key . '" written to database</span><br />';
						$result = mysql_query($query, $conn) or die ('Request not possible! SQL Statement: ' . $query . ' / ' . mysql_error());
					} // end if
				} // end if
				$lang_array_key = '';
				$lang_array_value = '';
			} // end if
        } // end foreach
        unset($gettextfile);
    } else {
        die('Problem with reading po file!');
    } // end if
} // end if

?>
<h1>File2Database Converter</h1>
<pre>
<?php echo $notice; ?>
</pre>
<br />
<form name="fileconverter" id="fileconverter" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
  <label for="file">File&#173;name</label>
  <input type="text" name="file" id="file" value="<?php echo $name;?>" /><br />
  <label for="lang">Language (only for MySQL)</label>
  <select name="lang" id="lang">
		<?php
		foreach ($languages as $language) {
			echo '<option value="' . $language . '">' . $language . '</option>';
		} // end foreach
		?>
  </select><br />
  <label for="namespace">Namespace</label> <input type="text" name="namespace" id="namespace" value="" />
  <br /><br />
  <input type="radio" name="method" value="inc2db" id="inc2db" checked="checked" />&#160;<label for="inc2po">.inc &#187; DB</label><br />
  <input type="radio" name="method" value="po2db" id="po2db" />&#160;<label for="po2inc">.po &#187; DB</label><br /><br />
  <input type="submit" name="Submit" id="Submit" value="Convert" />

</form>


</body>
</html>