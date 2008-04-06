<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
//+----------------------------------------------------------------------+
//| WAMP (XP-SP1/1.3.29/4.1.1/5.0.0RC1)                                    |
//+----------------------------------------------------------------------+
//| Copyright (c) 1992-2004 Michael Wimmer                               |
//+----------------------------------------------------------------------+
//| I don't have the time to read through all the licences to find out   |
//| what the exactly say. But it's simple. It's free for non commercial  |
//| projects, but as soon as you make money with it, i want my share :-) |
//+----------------------------------------------------------------------+
//| Authors: Michael Wimmer <flaimo@gmx.net>                             |
//+----------------------------------------------------------------------+
//
// $Id$
error_reporting(E_ALL);
session_start();
include_once('class.ReloadPreventer.inc.php');
$rp = (object) new ReloadPreventer;
$message = $input = '';
/* do all checks before you output the token for another form... */
if (isset($_POST['testform_button'])) { // only check if form is submitted
	// check if tokens from form and session match
	$message = (string) ($rp->isValid() == TRUE) ? 'Input OK' : 'Data sent twice';
	$input = $_POST['testform_button'];
} // end if

?>
<html>
	<head>
	<title>Sample</title>
	</head>
		<body>
		<p><b><?php echo $message; ?></b></p>
		<p><?php echo $input; ?></p>
	<form name="token" action="sample_rp.php" method="post">
	  	<?php
	  	/* automatically sets new token after old token was checked with first
	  	output of form element */
	  	echo $rp->getInputElement();
	  	?>
		<input name="teststring" type="text" value=""><br>
		<input type="submit" value="Send" name="testform_button">
		</form>
  </body>
</html>