<?php

header('Content-type: html/text');
/*
header('Expires: ' . date("D, d M Y H:i:s", time() + (60 * 60 * 400)) . ' GMT');
header('Cache-Control: public');
header('Cache-Control: max-age=' . (60 * 60 * 400));
*/

include_once('class.Cache.inc.php');
$cache = (object) new Cache('cached');
$pic = urldecode($_GET['pic']);
$filename = explode('/',$pic);
if ($cache->isPictureCached($pic, 20, 'png') == true) {
    $cache->returnPictureCache($pic, 'png', false);
} else { // if picture is not cached
    $root = (string) 'bilder/';
    $type = (array) getimagesize($pic);
    $icons = imagecreatefrompng('icons.png');
    switch ($type[2]) {
        case 1:
            $img = imagecreatefromgif($pic);
            break;
        case 2:
            $img = imagecreatefromjpeg($pic);
            break;
        case 3:
            $img = imagecreatefrompng($pic);
            break;
    } // end switch

    $img_width = (int) imagesx($img);
    $img_height = (int) imagesy($img);
    $max_height = (int) 120;
    $max_width = (int) 100;
    if (($img_height > $max_height) or ($img_width < $max_width)) {
        $sizefactor = (double) ( ($img_height > $img_width) ? ($max_height / $img_height) : ($max_width / $img_width) );
    } else {
        $sizefactor = (int) 1;
    } // end if
  
    $new_width = (int) ($img_width * $sizefactor);
    $new_height = (int) ($img_height * $sizefactor);
    $img_output = imagecreatetruecolor($new_width,$new_height);
    //imagecopyresized($img_output,$img,0,0,0,0,$new_width,$new_height,$img_width,$img_height); // faster
    imagecopyresampled($img_output,$img,0,0,0,0,$new_width,$new_height,$img_width,$img_height); // better quality
    imagealphablending($img_output, true);
    switch ($type[2]) {
        case 1:
            imagecopy($img_output, $icons, 1,1,0,0,15,8);
            break;
        case 2:
            imagecopy($img_output, $icons, 1,1,0,7,15,8);
            break;
        case 3:
            imagecopy($img_output, $icons, 1,1,0,0,15,8);
            break;
    } // end switch
    $cache->writePictureCache($pic, $img_output, 'png');
    imagepng($img_output);
    imagedestroy($img_output);
    imagedestroy($img);
    imagedestroy($icons);
  }  // end if
?>
