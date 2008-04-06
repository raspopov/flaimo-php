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
//| Authors: Michael Wimmer <flaimo 'at' gmx 'dot' net>                  |
//+----------------------------------------------------------------------+
//
// $Id$
/**
* @package ReloadPreventer
*/
error_reporting(E_ALL & E_NOTICE);
session_start();
include_once('class.ReloadPreventer.inc.php');
$rp = (object) new ReloadPreventer;
$message = $input = '';
if (isset($_POST['testform_button'])) { // only check if form is submitted
	if ($rp->isValid() == true) { // check if tokens match up
		$message = (string) 'Input OK';
	} else {
		$message = (string) 'Data sent twice';
	} // end if
	$input = $_POST['testform_button'];
} // end if
$rp->setTokenSession(); // Set new token after old token was checked
?>
<html>
	<head>
	<title>Sample</title>
	</head>
		<body>
		<p><b><?php echo $message; ?></b></p>
		<p><?php echo $input; ?></p>
	<form name="token" action="sample_rp.php" method="post">
		<input name="teststring" type="text" value=""><br>
		<input type="submit" value="Send" name="testform_button">
	  <input name="<?php echo $rp->getFormName(); ?>" type="hidden" value="<?php echo $rp->getToken(); ?>">
		</form>
  </body>
</html>