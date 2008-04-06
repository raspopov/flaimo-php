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

error_reporting(E_ALL & ~E_NOTICE);
$message = '';
if (isset($_POST['file']) && $_POST['method'] == 'inc2po') {
    $inc_file = $_POST['file'];
    $temp_file = explode('.',$inc_file);
    $po_filename = $temp_file[0] . '.po';

    if ($languagefile = (array) file($inc_file)) {
        $translations = (array) array();
        foreach ($languagefile as $key => $value) {
            list($lang_array_key,$lang_array_value) = split(' = ', $value);
            if ((strlen(trim($lang_array_key)) > 0) && (strlen(trim($lang_array_value)) > 0)) {
                $translations[trim($lang_array_key)] = trim($lang_array_value);
            } // end if
        } // end foreach
        unset($languagefile);
    } else {
        die('Problem with reading inc file!');
    } // end if

    $string = (string) '';
    $string .= 'msgid ""' . "\r\n";
    $string .= 'msgstr ""' . "\r\n";
    $string .= '"Project-Id-Version: \n"' . "\r\n";
    $string .= '"POT-Creation-Date: \n"' . "\r\n";
    $string .= '"PO-Revision-Date: 2003-01-03 21:25+0100\n"' . "\r\n";
    $string .= '"Last-Translator: Flaimo.com Translation Script <http://www.flaimo.com>\n"' . "\r\n";
    $string .= '"Language-Team:  <>\n"' . "\r\n";
    $string .= '"MIME-Version: 1.0\n"' . "\r\n";
    $string .= '"Content-Type: text/plain; charset=iso-8859-1\n"' . "\r\n";
    $string .= '"Content-Transfer-Encoding: 8bit\n"' . "\r\n";
    $string .= "\r\n";

    foreach ($translations as $key => $val) {
        $string .= 'msgid "' . $key . '"' . "\r\n";
        $string .= 'msgstr "' . $val . '"' . "\r\n";
        $string .= "\r\n";
    } // end foreach

    $handle = fopen($po_filename, 'w');
    fputs($handle, $string);
    fclose($handle);
    $message = 'File written! (<code>' . $po_filename . '</code>)';
} elseif (isset($_POST['file']) && $_POST['method'] == 'po2inc') {
    $inc_file = $_POST['file'];
    $temp_file = explode('.',$inc_file);
    $inc_filename = $temp_file[0] . '.inc';
    $string = (string) '';

    if ($gettextfile = (array) file($inc_file)) {
        $translations = (array) array();
        foreach ($gettextfile as $key => $value) {
            if (trim(substr($value, 0, 5)) == 'msgid') {
                $temp_val = (string) str_replace('"','',trim(substr($value, 6)));
                if (strlen(trim($temp_val)) > 0) {
                    $string .= $temp_val . ' = ';
                }
            } elseif (trim(substr($value, 0, 6)) == 'msgstr') {
                $temp_val = (string) str_replace('"','',trim(substr($value, 7)));
                if (strlen(trim($temp_val)) > 0) {
                    $string .= $temp_val . "\r\n";
                }
            } // end if
        } // end foreach
        unset($gettextfile);
    } else {
        die('Problem with reading po file!');
    } // end if
    $handle = fopen($inc_filename, 'w');
    fputs($handle, $string);
    fclose($handle);
    $message = 'File written! (<code>' . $inc_filename . '</code>)';
} // end if
$name = (string) ((!isset($_POST['file'])) ? '' : $_POST['file'] );
echo '<?xml version="1.0" encoding="iso-8859-1"?>' . "\r\n";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
<title>INC2PO file&#173;converter</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
</head>
<body>
<strong><?php echo $message;?></strong>
<p>This is a quick'n'dirty script to con&#173;vert between <code>.inc</code> files and <code>.po</code> files.<br />
Place this script in the direc&#173;tory with the files that should be con&#173;verted an enter the name of the <code>.inc</code> file and the choose the method.</p>
<p>Don't forget that you still have to <a href="http://www.aota.net/forums/showthread.php?threadid=10615">compile the <code>.po</code> files to <code>.mo</code> files</a>.</p>

<form name="fileconverter" id="fileconverter" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
  <label for="file">File&#173;name</label>
  <input type="text" name="file" id="file" value="<?php echo $name;?>" /><br /><br />
  <input type="radio" name="method" value="inc2po" id="inc2po" checked="checked" />&#160;<label for="inc2po">.inc &#187; .po</label><br />
  <input type="radio" name="method" value="po2inc" id="po2inc" />&#160;<label for="po2inc">.po &#187; .inc</label><br /><br />
  <input type="submit" name="Submit" id="Submit" value="Convert" />
</form>

<?php
if (isset($string)) {
?>
<hr />
<h3>File content</h3>
<code>
    <pre>
    <?php echo $string; ?>
    </pre>
</code>
<?php
}
?>
</body>
</html>