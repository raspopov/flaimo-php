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

  
  if ($_POST['name'] == '' or $_POST['name'] == ' ')
    {
    $message = 'Fehler bei der Eingabe';
    }
  else
    {
    $rs_insertautor = $db->db_query("INSERT INTO " . TBL_CATEGORY . " (Language,CategoryName) VALUES ('".$_POST["Language"]."','".$_POST["name"]."')");
    $message = 'Kategorie eingetragen';
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
<h2>Autor</h2>
<?php
if (isset($_POST['formsend']) and $_POST['formsend'] == 1)
  {
  echo '<p>'.$message.'</p>';
  }


if (isset($_SESSION['login']) and $_SESSION['login'] == 1 and isset($_SESSION['EditCat']) and $_SESSION['EditCat'] == 'y')
  {

?>

<form name="cat" id="cat" method="post" action="<?php echo PHP_SELF; ?>">
  <label for="language">Language</label>
 <?php 
 
$rs_languages = $db->db_query("SELECT DISTINCT Language FROM " . TBL_CATEGORY); 
    
    
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
  <label for="name">Name</label>
 <input type="text" name="name" id="name" /><br />
   
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
