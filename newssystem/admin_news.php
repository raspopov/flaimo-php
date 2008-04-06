<?php
ini_set('arg_separator.output','&#38;');
ini_set('session.use_trans_sid','0');
putenv('TZ=CET');
echo "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n";
include('classes.inc.php');
include('fileupload_class.php'); 
$db = new db_class();
$language_select = new SelectLanguage();
define('PHP_SELF',$_SERVER['PHP_SELF']);
define('TBL_NEWS','tbl_news');
define('TBL_AUTHOR','tbl_author');
define('TBL_CATEGORY','tbl_category');
define('TBL_COMMENT','tbl_comment');
@session_start();

if (isset($_POST['formsend']) and $_POST['formsend'] == 1)
  {
  if ($_POST['header'] == '' or $_POST['header'] == ' ' or $_POST['newstext'] == '' or $_POST['newstext'] == ' ')
    {
    $message = 'Fehler bei der Eingabe';
    }
  else
    {

      $path = 'pics/';
      $acceptable_file_types = 'image/gif|image/jpeg|image/pjpeg';
      $upload_file_name = 'picture';
      $default_extension = '';
      $mode = 2;
  
      $my_uploader = new uploader;
      $my_uploader->max_filesize(50000);
  		$my_uploader->max_image_size(100, 100); // max_image_size($width, $height)
  
  
      if ($my_uploader->upload($upload_file_name, $acceptable_file_types, $default_extension))
        {
        $success = $my_uploader->save_file($path, $mode);
        }
      
      if (!isset($success))
        {
        if($my_uploader->errors)
          {
          while(list($key, $var) = each($my_uploader->errors))
            {
            $message = $var . "<br>";
            }
          }
        $picture = '';
        }
      $picture = $my_uploader->file['name'];

    
    $now = date("Y-m-d H:i:s");
    $nocomments = ((isset($_POST['nocomments'])) ? $_POST['nocomments'] : '0');
    $showfulltext = ((isset($_POST['showfulltext'])) ? $_POST['showfulltext'] : '0');
    
    $sourcename = ((isset($_POST['sourcename'])) ? $_POST['sourcename'] : '');
    $sourcelink = ((isset($_POST['sourcelink'])) ? $_POST['sourcelink'] : '');
    $morelinks = ((isset($_POST['morelinks'])) ? $_POST['morelinks'] : '');
    $splitcatvalue = explode(',',$_POST['cat']);
    $cat = $splitcatvalue[0];
    $language = $splitcatvalue[1];
    
    $picture_alttext = ((isset($_POST['picture_alttext'])) ? $_POST['picture_alttext'] : '');
    
    $rs_insertautor = $db->db_query("INSERT INTO " . TBL_NEWS . " (Language, NewsHeadline, NewsText, NewsSource, NewsSourceLink, NewsDate, NewsAuthor, NewsCategory, NewsImage, NewsImageAlttext, NewsLinks, NoComments, ShowFullText) VALUES ('".$language."','".$_POST['header']."','".$_POST['newstext']."','".$sourcename."','".$sourcelink."','".$now."','".$_SESSION['IDAuthor']."','".$cat."','".$picture."','".$picture_alttext."','".$morelinks."','".$nocomments."','".$showfulltext."')");
    $message = 'News eingetragen';
    
    }

  }

 ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Kategorien</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
</head>

<body>
<h1>Admin</h1>
<h2>News</h2>
<?php
if (isset($_POST['formsend']) and $_POST['formsend'] == 1)
  {
  echo '<p>'.$message.'</p>';
  }


if (isset($_SESSION['login']) and $_SESSION['login'] == 1 and isset($_SESSION['EditNews']) and $_SESSION['EditNews'] == 'y')
  {

?>

<form enctype="multipart/form-data" name="cat" id="cat" method="post" action="<?php echo PHP_SELF; ?>">
 <label for="header">Header</label>
 <input type="text" name="header" id="header" /><br />
 <label for="newstext">Newstext</label>
 <textarea name="newstext" rows="10" id="newstext"></textarea>
 <br />
 <label for="cat">Category</label>
 <select name="cat" id="cat">
    <?php 
    $splitcats = explode(',',$_SESSION['AllowedCats']);
    
    
    $sqlpart = " WHERE "; 
    foreach ($splitcats as $splitcats_choise => $splitcats_choise_value)
      { 
      $sqlpart .= "IDCategory = " . $splitcats_choise_value . " OR ";
      }
    $sqlpart = substr($sqlpart,0,(strlen($sqlpart)-3)); 
   

$rs_category = $db->db_query("SELECT IDCategory,Language,CategoryName FROM " . TBL_CATEGORY . $sqlpart . "ORDER BY Language ASC, CategoryName ASC"); // Auflistung des linken Auswahlmenüs
  
if ($db->rs_num_rows($rs_category) > 0)
  {
  while ($rs = $db->rs_fetch_assoc($rs_category))
    {
    echo '<option value="'.$rs['IDCategory'].','.$rs['Language'].'">'.$rs['CategoryName'].' (' . $rs['Language'] . ')</option>';
    }
  }

 ?> 
 </select><br />
 <label for="sourcename">Source Name</label>
 <input type="text" name="sourcename" id="sourcename" /><br />
 <label for="sourcelink">Source Link</label>
 <input type="text" name="sourcelink" id="sourcelink" /><br />
 <label for="morelinks">MoreLinks</label>
 <input type="text" name="morelinks" id="morelinks" /> (Beschreibung1,Link1,Beschreibung2,Link2,...)<br />
<label for="picture">Picture</label>
 <input type="file" name="picture" id="picture" />
 <br />
  <label for="picture_alttext"><code>ALT</code> Text</label>
 <input type="text" name="picture_alttext" id="picture_alttext" /><br />
 <input type="checkbox" name="nocomments" value="1" id="nocomments" />
 <label for="nocomments"><strong>De</strong>activate Comments?</label><br />
 <input type="checkbox" name="showfulltext" value="1" id="showfulltext" />
 <label for="showfulltext">Always show full text</label><br />
     
<input name="formsend" type="hidden" id="formsend" value="1" />
<?php echo '<input type="hidden" name="' . session_name() . '" value="' . session_id() . '" />'; ?>
 <input type="submit" value="Eintragen" />
</form>
<?php
  }
  echo '<br /><strong><a href="login.php?logout=1">Logout/Neu Anmelden</a></strong><br />';
  echo '<br /><strong><a href="login.php?'.session_name().'='.session_id().'">Menü</a></strong><br />';
 ?>
<p>&nbsp; </p>
</body>
</html>
<?php $db->db_close(); ?>
