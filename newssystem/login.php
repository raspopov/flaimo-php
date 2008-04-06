<?php
ini_set('arg_separator.output','&#38;');
ini_set('session.use_trans_sid','0');
putenv('TZ=CET');
echo "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n";
include('classes.inc.php'); 
$db = new db_class();
define('PHP_SELF',$_SERVER['PHP_SELF']);
define('TBL_NEWS','tbl_news');
define('TBL_AUTHOR','tbl_author');
define('TBL_CATEGORY','tbl_category');
define('TBL_COMMENT','tbl_comment');
@session_start();

if (isset($_GET['logout']) && $_GET['logout'] == 1)
  {
  unset($_SESSION['IDAuthor']);
  unset($_SESSION['Language']);
  unset($_SESSION['EditNews']);
  unset($_SESSION['EditCat']);
  unset($_SESSION['AllowedCats']);
  session_unset('login','IDAuthor','LangAuthor','EditNews','EditCat','AllowedCats');
  session_unregister('login','IDAuthor','LangAuthor','EditNews','EditCat','AllowedCats');
  }

if (isset($_POST['formsend']) && $_POST['formsend'] == 1 && isset($_POST["password"]) && isset($_POST["username"]))
  {
  $rs_checkuser = $db->db_query("SELECT IDAuthor,EditNews,EditCat,AllowedCats FROM " . TBL_AUTHOR . " WHERE AuthorNick = '" . $_POST["username"] . "' AND AuthorPassword = '" . $_POST["password"] . "' LIMIT 1");
  
if ($db->rs_num_rows($rs_checkuser) > 0)
    {
    $_SESSION['login'] = $GLOBALS['login'] = 1;

    while ($rs = $db->rs_fetch_assoc($rs_checkuser))
      {
      $_SESSION['IDAuthor'] = $GLOBALS['IDAuthor'] = $rs['IDAuthor'];
      $_SESSION['EditNews'] = $GLOBALS['EditNews'] = $rs['EditNews'];
      $_SESSION['EditCat'] = $GLOBALS['EditCat'] = $rs['EditCat'];
      $_SESSION['AllowedCats'] = $GLOBALS['AllowedCats'] = $rs['AllowedCats'];
      } 
    
    session_register('login','IDAuthor','Language','EditNews','EditCat','AllowedCats');
    }
  }


 ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Login</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
</head>

<body>
<h1>Admin</h1>
<h2>Login Menu</h2>
<?php


if (isset($_SESSION['login']) && $_SESSION['login'] == 1)
  {
  
  if (isset($_SESSION['IDAuthor']) && $_SESSION['IDAuthor'] == 1)
    {
    echo '<a href="admin_author.php?'.session_name().'='.session_id().'">Autor</a><br />';
    }
  
  if (isset($_SESSION['EditNews']) && $_SESSION['EditNews'] == 'y')
    {
    echo '<a href="admin_news.php?'.session_name().'='.session_id().'">News</a><br />';
    }
  
  if (isset($_SESSION['EditCat']) && $_SESSION['EditCat'] == 'y')
    {
    echo '<a href="admin_cat.php?'.session_name().'='.session_id().'">Kategorie</a><br />';
    }       
    
  echo '<br /><strong><a href="'.PHP_SELF.'?logout=1">Logout/Neu Anmelden</a></strong><br />';
  }
else
  {

?>

<form name="login_choose" id="login_choose" method="post" action="<?php echo PHP_SELF; ?>">
 <label for="username">Username: </label>

 <input type="text" name="username" id="username" /><br />
 <label for="password">Password:</label>

  <input type="password" name="password" id="password" />
  <br />
<input name="formsend" type="hidden" id="formsend" value="1" />
<?php echo '<input type="hidden" name="' . session_name() . '" value="' . session_id() . '" />'; ?>
 <input type="submit" value="Login" />
</form>
<?php
  }
 ?>
<p>&nbsp; </p>
</body>
</html>
<?php $db->db_close(); ?>
