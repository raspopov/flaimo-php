<?php
include('class.Timezone.inc.php');

$timezone = new Timezone('CET');


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html>
<head>
<title>tz</title>
</head>

<body>
<h1>Timezone</h1>
<?php

echo serialize($timezone->getLanguages());



?>

</body>
</html>

