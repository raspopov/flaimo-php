<?php
$time = microtime();
$time = explode(' ', $time);
$time = $time[1] + $time[0];
$begintime = $time;


include_once '../inc/class.SSession.inc.php';
$session = new SSession(TRUE);
$session->useEncryption(FALSE);

/*
$_SESSION['a'] = 'Test';
$_SESSION['b'] = '123Polizei';
*/

foreach ($_SESSION as $key => $val) {
	echo $key, ': ' , $val , '<br>';
}

echo '<br><br><a href="sample.php">Self</a>';

 $time = microtime();
$time = explode(" ", $time);
$time = $time[1] + $time[0];
$endtime = $time;
$totaltime = ($endtime - $begintime);
echo '<br><br>PHP parsed this page in ' .$totaltime. ' seconds.';
?>
