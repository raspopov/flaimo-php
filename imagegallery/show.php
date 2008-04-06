<?php echo "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n";?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html>
<head>
<title>Gallery</title>
<style type="text/css">
<!--
.thumbnail {
	float: left;
	height: 200px;
	width: 140px;
  text-align: center;
	vertical-align: bottom;
}
-->
</style>
</head>

<body>
<?php

function pic_name($pic) {
    $find_last_dash = (int) strrpos ($pic,'/');
    if ($find_last_dash != 0) {
        $pic = (string) substr($pic,$find_last_dash+1,strlen($pic));
    } // end if
    return str_replace('_',' ',substr($pic,0,(strlen($pic)-4)));
} // end function

$picure = (string) $_GET['pic'];
if (file_exists($picure)) {
    $infos = (array) getimagesize($picure);
    echo '<img src="' . $picure . '" width="' . $infos[0]  . '" height="' . $infos[1] . '" alt="' . pic_name($picure) . '" />';
} else {
    echo 'Kein Bild gefunden';
} // end if
?>
</body>
</html>
