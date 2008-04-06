<?php
error_reporting(E_ALL & ~E_NOTICE);
echo '<?xml version="1.0" encoding="iso-8859-1"?>'; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
       "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="de" lang="de">
  <head>
    <title>Title here!</title>
  </head>
  <body>
  <?php
//echo $_SERVER['SERVER_SOFTWARE'];

$format = 'hallo dayname_short is ein guter tag';

if (!preg_match('(monthname|dayname|hour)', $format)) {
 echo 'nix gefunden';
} // end if



/*
include('class.FormatDate.inc.php');
$fd = new FormatDate('de');
echo date('Y-m-d h:i:s A',$fd->ISOdatetimeToUnixtimestamp('2003-05-19 00:01:00'));




echo memSegSpot('deaW');
exit;

	echo  serialize(parse_ini_file('locale/de/words.ini', TRUE));

	function memSegSpot($string) {
		$string = str_pad(str_replace('_', '', $string), 4, 'Z'); // Z = 5a
		$out = '';
		for ($a = 0; $a < 4; $a++) {
	 		$out .= dechex(ord(substr($string, $a , 1))); // ord returns dec, we need hex for shared memory segments
		} // end for
		return substr(('0x' . substr($out, 2)), 0, 8); // prepend it with 0x
	}//end function




function reloadSharedMemory() {
	if (extension_loaded('shmop')) {
		$key = 0xff3;
		$shm_id = shmop_open($key, 'c', 0644, 1000);
		@shmop_delete($shm_id);
		shmop_close($shm_id);
		$this->getSharedMemory();
	} // end if
} // end function



if (extension_loaded('shmop')) {

	$sm_content = trim(shmop_read($shm_id, 0, 1000));

	if(isset($sm_content) && strlen($sm_content) > 0) {
		echo "The data inside shared memory was: ". $sm_content ."\n";
	} else {
		$inifile = (string) serialize(parse_ini_file('i18n_settings.ini', TRUE)); // hier vorher in global reinschreiben
		$shm_bytes_written = shmop_write($shm_id, $inifile, 0);
  		echo 'Daten geschrieben';
	}
	shmop_close($shm_id);
} else {
	# ini file lesen
} // end if


// Create 5000 byte shared memory block with system id if 0xff3

if(!$shm_id) {
   echo "Couldn't create shared memory segment\n";
}

// Get shared memory block's size
$shm_size = shmop_size($shm_id);
echo "SHM Block Size: ".$shm_size. " has been created.\n";

// Lets write a test string into shared memory
$shm_bytes_written = shmop_write($shm_id, $serial_ini, 0);

if($shm_bytes_written != strlen($serial_ini)) {
   echo "Couldn't write the entire length of data\n";
}

// Now lets read the string back
$my_string = shmop_read($shm_id, 0, $shm_size);
if(!$my_string) {
   echo "Couldn't read from shared memory block\n";
}
echo "The data inside shared memory was: ".$my_string."\n";

//Now lets delete the block and close the shared memory segment
if(!shmop_delete($shm_id)) {
   echo "Couldn't mark shared memory block for deletion.";
}

shmop_close($shm_id);
*/
/*
$s = array();
$s[] = 'a';
$s[] = 'b';
$s[] = 'c';

echo count($s) . '<br><br>';

foreach ($s as $k => $v) {
	echo $k . ': ' . $v . '<br>';
	unset($s[$k]);
}

sort($s);
foreach ($s as $k => $v) {
	echo $k . ': ' . $v . '<br>';
}

echo count($s) . '<br><br>';

include('class.DBconnector.inc.php');
$db = new DBconnector();

$query  = (string) 'SELECT string, de FROM ' . $db->db_table . ' WHERE namespace="lang_main"';
echo $query;


$result = $db->DBQuery($query);

echo $db->RSNumRows($result);

while($row = $db->RSFetchRow($result)) {
	echo $row[0] . '|' . $row[1] . '<br>';
}

*/  /*
echo 999;

include('class.FormatNumber.inc.php');
$fn = new FormatNumber('de');

echo $fn->Currency(0.56, 'full', 'de', FALSE);


include('class.Translator.inc.php');
$lg = new Translator('','lang_main,lang_classFormatDate');
echo $lg->getLocale() . '<br>';
echo 'Yes:' . $lg->_('Yes') . '<br />';
echo 'June:' . $lg->_('june','lang_classFormatDate');



include('class.Measure.inc.php');
$fs = new Measure('uscs', 'uscs');
echo $fs->Liquid(30000, 0, 1) . ' ' . $fs->Unit(3);

*/
/*
include('class.FormatDate.inc.php');
$fd = new FormatDate('de');
echo $fd->LongDate($fd->ISOdatetimeToUnixtimestamp('2002-12-25 00:30:00'));



include('class.ChooseLanguage.inc.php');
$lg = new ChooseLanguage('it');
echo $lg->__('timezone_is') . '<br>';
$lg->changeLang('de');
echo $lg->__('timezone_is');

echo $lg->returnDropdownLang();
    */




/*
// Set the language as 'it'
$language = 'de';
putenv('LANG=' . $language);
//setlocale(LC_ALL, $language);

// Set the text domain as 'mydomain'
$domain = 'index';
bindtextdomain($domain, 'locale/');
textdomain($domain);

// The .mo file searched is:
// ./locale/it/LC_MESSAGES/mydomain.mo

echo _('hello');
*/


/*
include('class.Translator.inc.php');
$lg = new Translator('de','lang_classMeasure');
echo $lg->__('cm2_long') . '<br>';
$lg->changeLang('en');
echo $lg->__('mm2_short') . '<br>';



include('class.Translator.inc.php');
$lg = new Translator('it');
echo 'wwwwwwwwwwwwwwwwwwww' . $lg->__('timezone_is') . 'wwwwwwwwwwwwwwwwwwwwww<br>';
$lg->changeLang('de');
echo 'wwwwwwwwwwwwwwwwwwww' . $lg->__('timezone_is') . 'wwwwwwwwwwwwwwwwwwwwww';


include('class.FormatDate.inc.php');
$fd = new FormatDate('de');
echo '<br>';
echo $fd->LongDate($fd->ISOdatetimeToUnixtimestamp('2002-12-31 13:45:00'))


include('class.ChooseLanguage.inc.php');
$lg = new ChooseLanguage('it');
echo $lg->returnDropdownLang();

$lg->

function alternateRowColor() {
    static $i = 0;
    return ' bgcolor="' . ((++$i % 2) ? 'blue"' : 'red"');
}

echo '<table width="50%" border=1>';
for ($i = 0; $i < 20; $i++) {
    echo '<tr' . alternateRowColor() . '><td>x</td><td>x</td><td>x</td><td>x</td></tr>' . "\r\n";
}
echo '<table>';
*/


?>
  </body>
</html>
