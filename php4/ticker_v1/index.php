<?php
error_reporting(E_ALL & E_NOTICE);
ob_start();
session_start();
include_once 'class.Ticker.inc.php';
$t = new Ticker(20);
$list = $t->getMessageList();
?>
<h2>Last <?php echo $t->getListSize(); ?> Messages</h2>
<?php

$singlestring = '';
foreach ($list as $id) {
	$m = $t->getMessage($id);
	echo '<p style="font-size:0.6em;border-bottom: 1px solid #333;"><b>ID:</b> ' , $m->getID() , '<br />';
	echo '<b>Date:</b> ' , date('Y-m-d H:i:s',$m->getTimestamp()) , '<br />';
	echo '<b>Message:</b> ' , htmlentities(strip_tags($m->getText())) , '</p>';
} // end foreach

ob_end_flush();
?>