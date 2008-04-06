<?php
if (isset($_SERVER['PHP_AUTH_USER'])) {
	if (($_SERVER['PHP_AUTH_USER'] == 'flaimo') && ($_SERVER['PHP_AUTH_PW'] == 'alyssa04pc')) {
		header('WWW-Authenticate: Basic realm="Flaimo.com Administratorbereich"');
		header('HTTP/1.0 401 Unauthorized');
		echo 'Authorization Required!';
		exit;
	} else {
		echo "<p>Hello {$_SERVER['PHP_AUTH_USER']}.</p>";
		echo "<p>You entered {$_SERVER['PHP_AUTH_PW']} as your password.</p>";
	}
}
?>