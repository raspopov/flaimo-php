<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
//+----------------------------------------------------------------------+
//| WAMP (XP-SP2/2.2/5.2/5.3.0)                                          |
//+----------------------------------------------------------------------+
//| Copyright(c) 2001-2009 Michael Wimmer                                |
//+----------------------------------------------------------------------+
//| Licence: GNU General Public License v3                               |
//+----------------------------------------------------------------------+
//| Authors: Michael Wimmer <flaimo@gmail.com>                           |
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