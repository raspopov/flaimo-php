<?php echo "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n"; ?>
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
	width: 19%;
  text-align: center;
	vertical-align: bottom;
}

span.nsalileft, div.nsalileft {
  width: 33%;
  float: left;
  text-align: left;
}
span.nsaliright, div.nsaliright {
  width: 33%;
  float: right;
  text-align: right;
} 
span.nsalicenter, div.nsalicenter {
  width: 33%;
  float: left;
  text-align: center;
} 




-->
</style>
</head>

<body>
<?php
$root = 'bilder/';
$sub = ((isset($_GET['showdir']) && $_GET['showdir'] != '') ? urldecode($_GET['showdir']) : '');
$subdir = ((isset($_GET['showdir']) && $_GET['showdir'] != '') ? urldecode($_GET['showdir']) . '/' : '');
$activedir = $root . $subdir;

function show_dir($dir) {
    $root = 'bilder/';
    echo '<strong>' . stripslashes($root . $dir) . '</strong><ul>';
    $handle = @opendir($root);
    while ($file = @readdir ($handle)) {
        if(is_dir($root . $file)) {
            if (eregi("^\.{1,2}$",$file)) { continue; }
            echo '<li><a href="index.php?showdir=' . urlencode($file) . '">' . $file . '</a>';
            //echo $file .'=='. $dir;
            if ($file == $dir) {
                echo '<ul>';
                $subhandle = @opendir($root . $dir . '/');
                while ($subfile = @readdir ($subhandle)) {
                    if(is_dir($root . $dir . $subfile)) {
                        if (eregi("^\.{1,2}$",$subfile)) { continue; }
                        echo '<li><i><a href="index.php?showdir=' . urlencode($dir . $subfile . '/') . '">' . $subfile . '</a></i></li>';
                    } // end if
                } // end while
                @closedir($subhandle);
                echo '</ul>';
            } // end if
            echo '</li>';
        } // end if
    } // end while
    @closedir($handle);
    echo '</ul>';
  } // end function

function output_filesize($pic) {
    $size = (double) (filesize($pic) / 1024);
    $size_text = (string) ' kb';
    if ($size > 1024) {
        $size = (double) $size / 1024;
        $size_text = (string) ' mb';
    } // end if
    return number_format($size,2,',','.') . $size_text;
} // end function
  
  
function pic_name($pic) {
    $find_last_dash = (int) strrpos ($pic,'/');
    if ($find_last_dash != 0) { $pic = (string) substr($pic,$find_last_dash+1,strlen($pic)); }
    return str_replace('_',' ',substr($pic,0,(strlen($pic)-4)));
} // end function
  

  /*
  
function show_files($dir)
  {
  $handle = opendir($dir);
  while ($file = readdir ($handle))
    {
    if (!eregi("^\.{1,2}$",$file) and !is_dir($dir.$file) and eregi("\.(jpg|gif|png)",$file))
      {
      echo ('<div class="thumbnail"><a href="show.php?pic=' . $dir . urlencode($file) . '"><img src="thumbnail.php?pic=' . $dir . urlencode($file) . '" /></a><br /><strong>' . pic_name($file) . '</strong><br /><em>Gr&ouml;&szlig;e:</em> ' . output_filesize($dir.$file) . '</div>');
      }
    }
    closedir($handle);
  }
*/
//--------------------------------------------------------

echo '<div style="float:left; width:15%;background-color:white;padding:8px;">';
show_dir($subdir);
echo '</div><div style="float:right; width:83%;">';
$dir = $activedir;
$handle = opendir($dir);
while ($file = readdir ($handle)) {
    if (!eregi("^\.{1,2}$",$file) && !is_dir($dir.$file) && eregi("\.(jpg|gif|png)",$file)) {
        $list[filemtime($dir.$file)] = $file;
    } // end if
} // end while
closedir($handle);
echo '<br /><br />';
define('SHOW_RS',10);
define('PHP_SELF',$_SERVER['PHP_SELF']);
$startlisting = ((isset($_GET['startlisting'])) ? $_GET['startlisting'] : 0 );
krsort($list);
$sum_pics = count($list);
$counter = 1;

foreach (array_slice($list,$startlisting,SHOW_RS) as $key => $value) {
    echo ('<div class="thumbnail"><a href="show.php?pic=' . urlencode($activedir . $value) . '"><img src="thumbnail.php?pic=' . urlencode($activedir . $value) . '" /></a><br /><strong>' . pic_name($value) . '</strong><br /><em>Gr&ouml;&szlig;e:</em> ' . output_filesize($activedir . $value) . '<br /><small>' . date("d.m.Y",filemtime($dir.$value)) . '</small></div>');
    if ($counter++ > 4) {
        echo '<br style="clear:both" />';
        $counter = 0;
    } // end if
} // end foreach
echo '<hr style="clear:both" />';
if (($startlisting - SHOW_RS) >= 0) {
    echo '<div class="nsalileft">&laquo;&laquo; <a href="' . PHP_SELF . '?startlisting=' . ($startlisting - SHOW_RS) . '&#38;showdir=' . urlencode($sub) . '">Zur&uuml;ck</a> &laquo;&laquo;</div>';
} // end if
if ($sum_pics > SHOW_RS) { // Seitenzahlen
    for ($i = 0,$listpages = '<div class="nsalicenter">Seite: ',$sum_pages = ceil($sum_pics/SHOW_RS); $i<$sum_pages; $i++) {
        $i * SHOW_RS != $startlisting ? $listpages .= '<a href="' . PHP_SELF . '?startlisting=' . ($i * SHOW_RS) . '&#38;showdir=' . urlencode($sub) . '">' . ($i+1) . '</a> ' : $listpages .= ($i+1).' ';
    } // end for
    echo $listpages . '</div>';
} // end if
if (($startlisting + SHOW_RS) <= $sum_pics) {
    echo '<div class="nsaliright">&raquo;&raquo; <a href="' . PHP_SELF . '?startlisting=' . ($startlisting + SHOW_RS) . '&#38;showdir=' . urlencode($sub) . '">Weiter</a> &raquo;&raquo;</div>';
} // end if
?>
</div>
</body>
</html>
