<?php
ini_set('arg_separator.output','&#38;');
ini_set('session.use_trans_sid','0');
putenv('TZ=CET');
echo "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n";
include('classes.inc.php'); 
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

  
  if ($_POST['nickname'] == '' or $_POST['nickname'] == ' ' or $_POST['password'] == '' or $_POST['password'] == ' ' or $_POST['password_repeat'] == '' or $_POST['password_repeat'] == ' ' or $_POST['email'] == '' or $_POST['email'] == ' ' or ($_POST['password'] != $_POST['password_repeat']))
    {
    $message = 'Fehler bei der Eingabe';
    }
  else
    {
      $path = "pics/";
      $acceptable_file_types = "image/gif|image/jpeg|image/pjpeg";
      $upload_file_name = "picture";
      $default_extension = "";
      $mode = 1;
  /*
      $my_uploader = new uploader;
      $my_uploader->max_filesize(30000);
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
      */
      if (isset($_POST['allowedcats']) and is_array($_POST['allowedcats']))
        {
        $test = $_POST['allowedcats'];
        asort($test);
        reset($test);
        $splitcats = implode(',', $test);
        }
      else
        {
        $splitcats = ' ';
        }  
   
   $picture = '';
   $rs_insertautor = $db->db_query("INSERT INTO " . TBL_AUTHOR . " (Language,AuthorNick,AuthorRealName,AuthorPassword,AuthorEmail,Abstract_de,Abstract_en,EditNews,EditCat,Picture,AllowedCats) VALUES ('".$_POST["language"]."','".$_POST["nickname"]."','".$_POST["realname"]."','".$_POST["password"]."','".$_POST["email"]."','".$_POST["abstract_de"]."','".$_POST["abstract_en"]."','".$_POST["EditNews"]."','".$_POST["EditCat"]."','".$picture."','" . $splitcats . "')");

    $message = 'Autor eingetragen';
    }

  }

 ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Autor</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
</head>

<body>
<h1>Admin</h1>
<h2>Autor</h2>
<?php
if (isset($_POST['formsend']) and $_POST['formsend'] == 1)
  {
  echo '<p>'.$message.'</p>';
  }


if (isset($_SESSION['login']) and $_SESSION['login'] == 1 and isset($_SESSION['IDAuthor']) and $_SESSION['IDAuthor'] == 1)
  {

?>

<form name="author" id="author" method="post" action="<?php echo PHP_SELF; ?>">
  <label for="nickname">Nickname</label>
 <input type="text" name="nickname" id="nickname" /><br />
 <label for="realname">Real Name</label>
 <input type="text" name="realname" id="realname" /><br />
 <label for="language">Language</label>
 <?php 
 
$rs_languages = $db->db_query("SELECT DISTINCT Language FROM " . TBL_AUTHOR); 
    
    
    if ($db->rs_num_rows($rs_languages) > 0)
      {
      $output_dropdown = '<select name="language" id="language">';
      
      while ($rs = $db->rs_fetch_assoc($rs_languages))
        {
        $output_dropdown .= '<option value="' . $rs['Language'] . '">' . $rs['Language'] . '</option>';        
        }
      
      echo $output_dropdown . '</select>';
      }
    $db->db_freeresult($rs_languages);
 
 ?>
 <br />
 <label for="password">Password</label>
 <input type="password" name="password" id="password" /><br />
 <label for="password_repeat">Repeat Password</label>
 <input type="password" name="password_repeat" id="password_repeat" /><br />
 <label for="email">E-Mail</label>
 <input type="text" name="email" id="email" /><br />
 <label for="abstract_de">Abstract (DE)</label>
 <textarea name="abstract_de" id="abstract_de" rows="10"></textarea><br />
 <label for="abstract_en">Abstract (EN)</label>
 <textarea name="abstract_en" id="abstract_en" rows="10"></textarea><br />
<label for="picture">Picture</label>
 <input type="file" name="picture" id="picture" />
 <br />
  <input type="checkbox" name="EditNews" value="y" id="EditNews" checked="checked" />
 <label for="EditNews">News editieren</label>
<br />
  <input type="checkbox" name="EditCat" value="y" id="EditCat" checked="checked" />
  <label for="EditCat">Kategorien editieren</label><br /><br />
  <strong> Erlaubte Kategorien</strong><br />
   <?php 
 
$rs_languages = $db->db_query("SELECT IDCategory,Language,CategoryName FROM " . TBL_CATEGORY . " ORDER BY Language ASC, CategoryName ASC"); 
    
    
    if ($db->rs_num_rows($rs_languages) > 0)
      {
      $output = '';
      while ($rs = $db->rs_fetch_assoc($rs_languages))
        {
        $output .= '<input type="checkbox" name="allowedcats[' . $rs['IDCategory'] . ']" id="allowedcats' . $rs['IDCategory'] . '" value="' . $rs['IDCategory'] . '" checked="checked" /> <label for="allowedcats' . $rs['IDCategory'] . '">' . $rs['CategoryName'] . ' <small>(' . $rs['Language'] . ')</small></label><br />';        
        }
      
      echo $output;
      }
    $db->db_freeresult($rs_languages);
 
 ?><br />
  
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
