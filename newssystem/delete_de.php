<?php
isset($_POST["step"]) ? $step = $_POST["step"] : $step = "";
if($step == 4) // Letzter Schritt: Redirect
    {
    header("Location: index.php");
    exit;
    }
@session_start();

include("connection_start.inc.php"); 

if($step == 2) // Setzt nach dem Absenden des Formulares ein Cookie für ein paar Minuten, dass ein erneutes Kommentar verhindert (Spam Schutz)
  {
  if($_POST["password"] and $_POST["user"]) // Passwortabfrage
    {
    $rs_checkuser = mysql_query("SELECT COUNT(*) FROM tbl_author WHERE AuthorNick = '" . $_POST["user"] . "' AND AuthorPassword = '" . $_POST["password"] . "'" , $conn);
    $checkuser = mysql_result($rs_checkuser, 0, 0);
    mysql_free_result($rs_checkuser); 
      
    if ($checkuser > 0) 
      {
      session_register("sess_name"); // Merkt sich Name,EMail damit man es nicht bei jedem Kommentar neu eintragen muss...
      session_register("sess_pw");
      $sess_name = $_POST["user"];
      $sess_pw = $_POST["password"];
      }
    }
  }
echo "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
       "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="de" lang="de">
<head>
<title>L&ouml;schen</title>
<meta http-equiv="cache-control" content="no-cache" />
<meta http-equiv="pragma" content="no-cache" />
<link rel="stylesheet" media="screen" href="style.css" type="text/css" title="Bildschirm" />
</head>
<body>
<?php
$now = date("Y-m-d H:i:s");

echo "<form method=\"post\" action=\"" . $_SERVER["PHP_SELF"] . "\">\n"; 

if($step == 3) // Eintragen der Daten
    {
 
    echo "<h2>Schritt 3</h2>\n"; 
 
    if($_POST["add_rs"] == 1)
        {
        if(!isset($_POST["delete_news"]))
            {
            echo "<p><em>Kein Newseintrag ausgewählt!</em></p>\n";
            }
        else
            {
            $rs_deletenews = mysql_query("DELETE FROM tbl_news WHERE IDNews = ".$_POST["delete_news"], $conn); // News wird in DB eingetragen...
            $rs_deletecomments = mysql_query("DELETE FROM tbl_comment WHERE NewsID = ".$_POST["delete_news"], $conn); // News wird in DB eingetragen...

            echo "<p><strong>News gel&ouml;scht</strong></p>\n";
            }
        echo "<input type=\"hidden\" name=\"id\" value=\"".($_POST["delete_news"]-1)."\" />\n";
        }
    elseif($_POST["add_rs"] == 2)
        {
        if(!isset($_POST["delete_user"]))
            {
            echo "<p><em>Kein User ausgew&auml;hlt!</em></p>\n";
            }
        else
            {
            $rs_deleteuser = mysql_query("DELETE FROM tbl_author WHERE IDAuthor = ".$_POST["delete_user"], $conn); // News wird in DB eingetragen...
            
            echo "<p><strong>User gel&ouml;scht</strong></p>\n";
            }
        }
    elseif($_POST["add_rs"] == 3)
        {
        if(!isset($_POST["delete_cat"]))
            {
            echo "<p><em>Keine Kategorie ausgew&auml;hlt!</em></p>\n";
            }
        else
            {
            $rs_deletecat = mysql_query("DELETE FROM tbl_category WHERE IDCategory = ".$_POST["delete_cat"], $conn); // News wird in DB eingetragen...
            
            echo "<p><strong>Kategorie gel&ouml;scht</strong></p>\n";
            }
        }
    echo "<br /><input type=\"hidden\" name=\"step\" value=\"4\" />\n";
    echo "<input type=\"submit\" value=\"Fertig\" />\n";
    }
elseif ($step == 2)
    {
    
    echo "<h2>Schritt 2</h2>\n"; 
    
    if(isset($_POST["password"]) and isset($_POST["user"])) // Passwortabfrage
        {
        $rs_checkuser = mysql_query("SELECT COUNT(*) FROM tbl_author WHERE AuthorNick = '" . $_POST["user"] . "' AND AuthorPassword = '" . $_POST["password"] . "'" , $conn);
        $checkuser = mysql_result($rs_checkuser, 0, 0);
        mysql_free_result($rs_checkuser); 
        $rs_user_id = mysql_query("SELECT IDAuthor,AuthorLevel FROM tbl_author WHERE AuthorNick = '" . $_POST["user"] . "' AND AuthorPassword = '" . $_POST["password"] . "'" , $conn);
        $user_id = mysql_result($rs_user_id, 0, "tbl_author.IDAuthor");
        $user_level = mysql_result($rs_user_id, 0, "tbl_author.AuthorLevel");
        mysql_free_result($rs_user_id);         
        
        if ($checkuser > 0) 
            {
            echo "<fieldset class=\"abstand\">\n";
            
            if($_POST["add_rs"] == 1)
                {
                if($user_level <= 1)
                    {
                    echo "<legend>News ausw&auml;hlen</legend>\n";

                    $pointer_news = 0;
                    $rs_news = mysql_query("SELECT IDNews,NewsHeadline FROM tbl_news WHERE Language = 'de' ORDER BY NewsDate DESC", $conn); // Auflistung des linken Auswahlmenüs
                    if (mysql_num_rows($rs_news) > 0)
                        {
                        echo "<p>\n";
                        while (mysql_fetch_row($rs_news))
                            {
                            $IDNews = mysql_result($rs_news, $pointer_news, "tbl_news.IDNews");
                            $NewsHeadline = mysql_result($rs_news, $pointer_news, "tbl_news.NewsHeadline");

                            echo "<input type=\"radio\" name=\"delete_news\" value=\"" . $IDNews . "\" id=\"delete_user" . $IDNews . "\" /><label for=\"delete_user" . $IDNews . "\">" . htmlentities($NewsHeadline) . "</label><br />\n";
                            $pointer_news++;
                            }
                        }
                    mysql_free_result($rs_news);
                    }
                else
                    {
                    echo "<p><em>Keine Berechtigung!</em></p>\n"; 
                    }
                }
            elseif($_POST["add_rs"] == 2)
                {
                if($user_level <= 1)
                    {
                    echo "<legend>Autor ausw&auml;hlen</legend>\n";

                    $pointer_user = 0;
                    $rs_users = mysql_query("SELECT IDAuthor,AuthorNick FROM tbl_author WHERE Language = 'de' ORDER BY AuthorNick ASC", $conn); // Auflistung des linken Auswahlmenüs
                    if (mysql_num_rows($rs_users) > 0)
                        {
                        echo "<p>\n";
                        while (mysql_fetch_row($rs_users))
                            {
                            $IDAuthor = mysql_result($rs_users, $pointer_user, "tbl_author.IDAuthor");
                            $AuthorNick = mysql_result($rs_users, $pointer_user, "tbl_author.AuthorNick");

                		    if($AuthorNick <> "admin")
                                {
                                echo "<input type=\"radio\" name=\"delete_user\" value=\"" . $IDAuthor . "\" id=\"delete_user" . $IDAuthor . "\" /><label for=\"delete_user" . $IDAuthor . "\">" . htmlentities($AuthorNick) . "</label><br />\n";
                                }
                            $pointer_user++;
                            }
                        }
                    mysql_free_result($rs_users);
                    }
                else
                    {
                    echo "<p><em>Keine Berechtigung!</em></p>\n"; 
                    }
                }
            elseif($_POST["add_rs"] == 3)
                {
                if($user_level <= 1)
                    {
                    echo "<legend>Kategorie ausw&auml;hlen</legend>\n";

                    $pointer_cat = 0;
                    $rs_category = mysql_query("SELECT DISTINCT IDCategory, CategoryName FROM tbl_category WHERE Language = 'de' ORDER BY CategoryPosition DESC", $conn); // Auflistung des linken Auswahlmenüs
                    if (mysql_num_rows($rs_category) > 0)
                        {
                        echo "<p>\n";
                        while (mysql_fetch_row($rs_category))
                            {
                            $IDCategory = mysql_result($rs_category, $pointer_cat, "tbl_category.IDCategory");
                            $CategoryName = mysql_result($rs_category, $pointer_cat, "tbl_category.CategoryName");

                		    echo "<input type=\"radio\" name=\"delete_cat\" value=\"" . $IDCategory . "\" id=\"delete_cat" . $IDCategory . "\" /><label for=\"delete_cat" . $IDCategory . "\">" . htmlentities($CategoryName) . "</label><br />\n";
                            $pointer_cat++;
                            }
                        }
                    mysql_free_result($rs_category);
                    }
                else
                    {
                    echo "<p><em>Keine Berechtigung!</em></p>\n"; 
                    }
                }
            echo "<input type=\"hidden\" name=\"add_rs\" value=\"".$_POST["add_rs"]."\" />\n";
            echo "<input type=\"hidden\" name=\"step\" value=\"3\" />\n";
            echo "<input type=\"submit\" value=\"Weiter zu Schritt 3\" />\n";
            echo "</fieldset>\n";
            }
        else
            {
            echo "<p><em>Falsches Passwort und/oder Username!</em></p>\n";
            }
        }
    else    
        {
        echo "<p><em>Username und/oder Password nicht eingegeben!</em></p>";
        }
    
    }
else // Erster Schritt der Eingabe Kategorie/Autor/News
    {
    
    !isset($_SESSION["sess_name"]) ? $sess_name = "" : $sess_name = $_SESSION["sess_name"];
    !isset($_SESSION["sess_pw"]) ? $sess_pw = "" : $sess_pw = $_SESSION["sess_pw"];
    
    echo "<h2>Schritt 1</h2>\n";

    echo "<fieldset class=\"abstand\">\n";
    echo "<legend>Was soll gel&ouml;scht werden?</legend>\n";
    
    echo "<p><label for=\"user\"><strong>Benutzer:</strong></label><br>\n";    
    echo "<input type=\"text\" id=\"user\" name=\"user\" size=\"60\" value=\"".$sess_name."\" /></p>\n";
    
    echo "<p><label for=\"password\"><strong>Passwort:</strong></label><br />\n";    
    echo "<input type=\"password\" id=\"password\" name=\"password\" size=\"60\" value=\"".$sess_pw."\" /></p>\n";

    echo "<p><label for=\"add_rs\"><strong>Art des zu löschenden Elements:</strong></label><br />\n";
    echo "<select name=\"add_rs\" id=\"add_rs\">\n";
	echo "  <option value=\"1\">News</option>\n";
	echo "  <option value=\"2\">Autor</option>\n";
	echo "  <option value=\"3\">News-Kategorie</option>\n";
    echo "</select></p>\n";
    
    echo "<input type=\"hidden\" name=\"step\" value=\"2\" />\n";
    
    echo "<input type=\"submit\" value=\"Weiter zu Schritt 2\" />\n";
    echo "</fieldset>\n";
    }

echo "</form>\n";    
include("connection_end.inc.php"); 

?>
</body></html>