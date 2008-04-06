<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html>
<head>
<title>Unbenannt</title>
</head>

<body>
<?php
include_once('class.CategoryList.inc.php');
$list = (object) new CategoryList('en');

foreach ($list->getCategoryList() as $name => $value)
  {
  echo $name . ' (' . $value . ')<br />';
  }

?>


</body>
</html>

