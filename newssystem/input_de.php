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
    $rs_checkuser = mysql_query("SELECT COUNT(*) FROM tbl_author WHERE Language = 'de' AuthorNick = '" . $_POST["user"] . "' AND AuthorPassword = '" . $_POST["password"] . "'" , $conn);
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
<title>Eingabe</title>
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
        if(!$_POST["header"] or !$_POST["newstext"] or !$_POST["category"])
            {
            echo "<p><em>Nicht alle Felder ausgefüllt!</em></p>\n";
            }
        else
            {
            $rs_insertnews = mysql_query("INSERT INTO tbl_news (Language,NewsHeadline,NewsText,NewsSource,NewsSourceLink,NewsDate,NewsAuthor,NewsCategory,NewsImage,NewsLinks,NoComments) VALUES ('de','".$_POST["header"]."','".$_POST["newstext"]."','".$_POST["source"]."','".$_POST["sourcelink"]."','".$now."','".$_POST["user_id"]."','".$_POST["category"]."','".$_POST["picture"]."','".$_POST["links"]."','".$_POST["nocomments"]."')", $conn); // News wird in DB eingetragen...
            
            $rs_shownews = mysql_query("SELECT * FROM tbl_news INNER JOIN tbl_author_de ON (tbl_news_de.NewsAuthor = tbl_author_de.IDAuthor) INNER JOIN tbl_category_de ON (tbl_news_de.NewsCategory = tbl_category_de.IDCategory) ORDER BY tbl_news_de.IDNews DESC LIMIT 1", $conn);

            if (mysql_num_rows($rs_shownews) > 0) // News werden nochmal ausgegeben
		        {
                $IDNews = mysql_result($rs_shownews, 0, "tbl_news.IDNews");
                $NewsHeadline = mysql_result($rs_shownews, 0, "tbl_news.NewsHeadline");
                $NewsText = mysql_result($rs_shownews, 0, "tbl_news.NewsText");
                $NewsSource = mysql_result($rs_shownews, 0, "tbl_news.NewsSource");
                $NewsSourceLink = mysql_result($rs_shownews, 0, "tbl_news.NewsSourceLink");
                
                list($date_post,$time_post) = split(' ',mysql_result($rs_shownews, 0, "tbl_news.NewsDate"));
		            list($Year_post,$Month_post,$Day_post) = split('-',$date_post);
		            list($Hour_post,$Minute_post,$Second_post) = split(':',$time_post);
            
                $NewsChangedBy = mysql_result($rs_shownews, 0, "tbl_news.NewsChangedBy");
                $NewsChangedDate = mysql_result($rs_shownews, 0, "tbl_news.NewsChangedDate");
                $NewsRead = mysql_result($rs_shownews, 0, "tbl_news.NewsRead");
                $NewsImage = mysql_result($rs_shownews, 0, "tbl_news.NewsImage");
                $NewsLinks = mysql_result($rs_shownews, 0, "tbl_news.NewsLinks");
                $expireDate = mysql_result($rs_shownews, 0, "tbl_news.expireDate");
                $releaseDate = mysql_result($rs_shownews, 0, "tbl_news.releaseDate");
    		    $CategoryName = mysql_result($rs_shownews, 0, "tbl_category.CategoryName");
                $AuthorNick = mysql_result($rs_shownews, 0, "tbl_author.AuthorNick");
                    
			    echo "<h2>" . htmlentities($NewsHeadline) . "</h2>\n";
                echo "<p>\n<strong>" . htmlentities($AuthorNick) . "</strong> - <em>" . $Day_post . "." . $Month_post . "." . $Year_post . ", " . $Hour_post . ":" . $Minute_post . " Uhr</em><br />\n";
            
                if($NewsImage != "")
                    {
                    echo "<img src=\"" . $picturepath . $NewsImage . "\" alt=\"\" align=\"left\" class=\"newspic\" />";
                    }
            
                echo nl2br(htmlentities($NewsText))."\n"; // Ausgane Newstext
            
            
                if($NewsLinks != "")
                    {
                    echo "<br /><strong>Links:</strong><br />";
                
                    $link_list = explode(",",$NewsLinks);
                
                    foreach($link_list as $link_value)
                        {
                        echo "<a href=\"" . $link_value . "\">";
                        echo $link_value . "<br />";
                        echo "</a>";
                        }
                
                    }
            
                if($NewsSource != "")
                    {
                    echo "<br /><strong>Quelle:</strong> " . $NewsSource;
                    }
                
                if($NewsSourceLink != "")
                    {
                    echo "<br /><strong>Quelle (Link):</strong> <a href=\"" . $NewsSourceLink . "\">" . $NewsSourceLink . "</a>";
                    }
                
                }
            mysql_free_result($rs_shownews); 
            
            }

        echo "<input type=\"hidden\" name=\"id\" value=\"".$IDNews."\" />\n";
        }
    elseif($_POST["add_rs"] == 2)
        {
        if(!$_POST["new_user"] or !$_POST["new_user_password"] or !$_POST["new_user_password_rep"] or !$_POST["new_user_mail"] or !$_POST["new_user_level"] or ($_POST["new_user_password"]<>$_POST["new_user_password_rep"]))
            {
            echo "<p><em>Nicht alle Felder ausgefüllt oder Password falsch eingegeben!</em></p>\n";
            }
        else
            {
            $rs_insertcat = mysql_query("INSERT INTO tbl_author (Language,AuthorNick,AuthorRealName,AuthorPassword,AuthorLevel,AuthorEmail,AuthorHomepage,Abstract) VALUES ('de','".$_POST["new_user"]."','".$_POST["new_user_real"]."','".$_POST["new_user_password"]."','".$_POST["new_user_level"]."','".$_POST["new_user_mail"]."','".$_POST["new_user_home"]."','".$_POST["new_user_abstract"]."')", $conn); // News wird in DB eingetragen...
                                                                    
            echo "<p><strong>Neuer Autor eingetragen</strong></p>\n";
            }
        }
    elseif($_POST["add_rs"] == 3)
        {
        if(!$_POST["new_cat"])
            {
            echo "<p><em>Nicht alle Felder ausgefüllt!</em></p>\n";
            }
        else
            {
            $rs_insertcat = mysql_query("INSERT INTO tbl_category (Language,CategoryName,CategoryPosition) VALUES ('de','".$_POST["new_cat"]."','".$_POST["new_cat_pos"]."')", $conn); // News wird in DB eingetragen...
            
            echo "<p><strong>Neue Kategorie eingetragen</strong></p>\n";
            }
        }
    echo "<br /><input type=\"hidden\" name=\"step\" value=\"4\" />\n";
    echo "<input type=\"submit\" value=\"Fertig\" />\n";
    }
elseif ($step == 2) 
    {
    
    echo "<h2>Schritt 2</h2>\n"; 
    
    if($_POST["password"] and $_POST["user"]) // Passwortabfrage
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
                echo "<legend>Neuer Newseintrag</legend>\n";
    
                echo "<p><label for=\"header\"><strong>&Uuml;berschrift:</strong></label><br />\n";    
                echo "<input type=\"text\" id=\"header\" name=\"header\" size=\"60\" /></p>\n";

                echo "<p><label for=\"newstext\"><strong>Newstext:</strong></label><br>\n";    
                echo "<textarea cols=\"45\" rows=\"4\" id=\"newstext\" name=\"newstext\"></textarea></p>\n";
    
                echo "<p><label for=\"category\"><strong>Newskategorie:</strong></label><br />\n";
                echo "<select name=\"category\" id=\"category\">\n";
	            
                $pointer_cat = 0;
                $rs_category = mysql_query("SELECT DISTINCT IDCategory, CategoryName FROM tbl_category WHERE Language = 'de' ORDER BY CategoryPosition DESC", $conn); // Auflistung des linken Auswahlmenüs
                if (mysql_num_rows($rs_category) > 0)
                    {
                    while (mysql_fetch_row($rs_category))
                        {
                        $IDCategory = mysql_result($rs_category, $pointer_cat, "tbl_category.IDCategory");
                        $CategoryName = mysql_result($rs_category, $pointer_cat, "tbl_category.CategoryName");

                        echo "  <option value=\"". $IDCategory ."\">".$CategoryName."</option>\n";
                        
                        $pointer_cat++;
                        }
                    }
                mysql_free_result($rs_category);

                echo "</select></p>\n";

                echo "<p><label for=\"picture\"><strong>Bild:</strong></label><br />\n";    
                echo "<input type=\"text\" id=\"picture\" name=\"picture\" size=\"60\" /></p>\n";
    
                echo "<p><label for=\"source\"><strong>Quelle (Name):</strong></label><br />\n";    
                echo "<input type=\"text\" id=\"source\" name=\"source\" size=\"60\" /></p>\n";

                echo "<p><label for=\"sourcelink\"><strong>Quelle (Link):</strong></label><br />\n";    
                echo "<input type=\"text\" id=\"sourcelink\" name=\"sourcelink\" size=\"60\" /></p>\n";
                
                echo "<p><label for=\"links\"><strong>Links (Beschreibun 1, URL 1, Beschreibung 2, URL 2,...):</strong></label><br />\n";    
                echo "<input type=\"text\" id=\"links\" name=\"links\" size=\"60\" /></p>\n";
                
                echo "<p><label for=\"nocomments\"><strong>Kommentare f&#252;r diesen Eintrag deaktivieren?:</strong></label><br />\n";    
                
                echo "<select id=\"nocomments\" name=\"nocomments\" size=\"1\"><option value=\"0\" selected=\"selected\">Nein</option>\n
	                    <option value=\"1\">Ja</option></select></p>\n";
                
                }
            elseif($_POST["add_rs"] == 2)
                {
                if($user_level <= 1)
                    {
                    echo "<legend>Neuer Autor</legend>\n";
                    
                    echo "<p><label for=\"new_user\"><strong>Username:</strong></label><br />\n";    
                    echo "<input type=\"text\" id=\"new_user\" name=\"new_user\" size=\"60\" /></p>\n";
    
                    echo "<p><label for=\"new_user_password\"><strong>Passwort:</strong></label><br />\n";    
                    echo "<input type=\"password\" id=\"new_user_password\" name=\"new_user_password\" size=\"60\" /></p>\n";              
                
                    echo "<p><label for=\"new_user_password_rep\"><strong>Passwort wiederholen:</strong></label><br />\n";    
                    echo "<input type=\"password\" id=\"new_user_password_rep\" name=\"new_user_password_rep\" size=\"60\" /></p>\n";  

                    echo "<p><label for=\"new_user_real\"><strong>Echter Name:</strong></label><br />\n";    
                    echo "<input type=\"text\" id=\"new_user_real\" name=\"new_user_real\" size=\"60\" /></p>\n";

                    echo "<p><label for=\"new_user_mail\"><strong>E-Mail Adresse:</strong></label><br />\n";    
                    echo "<input type=\"text\" id=\"new_user_mail\" name=\"new_user_mail\" size=\"60\" /></p>\n";

                    echo "<p><label for=\"new_user_home\"><strong>Homepage:</strong></label><br />\n";    
                    echo "<input type=\"text\" id=\"new_user_home\" name=\"new_user_home\" size=\"60\" /></p>\n";

                    echo "<p><label for=\"new_user_abstract\"><strong>Info-Text:</strong></label><br />\n";    
                    echo "<textarea cols=\"40\" rows=\"6\" id=\"new_user_abstract\" name=\"new_user_abstract\"></textarea></p>\n";
                    
                    echo "<p><label for=\"new_user_level\"><strong>Level:</strong></label><br />\n";    
                    echo "<input type=\"text\" id=\"new_user_level\" name=\"new_user_level\" size=\"1\" value=\"".$user_level."\" /></p>\n";
                    
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
                    echo "<legend>Neue Kategorie</legend>\n
                          <p><label for=\"new_cat\"><strong>Neue Kategorie:</strong></label><br />\n 
                          <input type=\"text\" id=\"new_cat\" name=\"new_cat\" size=\"60\" /></p>\n";

                    
                    $rs_maxcat = mysql_query("SELECT MAX(CategoryPosition) FROM tbl_category WHERE Language = 'de'", $conn);
                    $maxcat = mysql_result($rs_maxcat, 0, 0);
                    mysql_free_result($rs_maxcat); 
                    
                    echo "<p><label for=\"new_cat_pos\"><strong>Position:</strong></label><br />\n   
                          <input type=\"text\" id=\"new_cat_pos\" name=\"new_cat_pos\" size=\"1\" value=\"".($maxcat+1)."\" /></p>\n";
                    }
                else
                    {
                    echo "<p><em>Keine Berechtigung!</em></p>\n"; 
                    }
                }
            echo "<input type=\"hidden\" name=\"step\" value=\"3\" />\n
                  <input type=\"hidden\" name=\"add_rs\" value=\"".$_POST["add_rs"]."\" />\n
                  <input type=\"hidden\" name=\"user_id\" value=\"".$user_id."\" />\n
                  <input type=\"submit\" value=\"Weiter zu Schritt 3\" />\n
                  </fieldset>\n";
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
    echo "<legend>Was soll eingegeben werden?</legend>\n";
    
    echo "<p><label for=\"user\"><strong>Benutzer:</strong></label><br>\n";    
    echo "<input type=\"text\" id=\"user\" name=\"user\" size=\"60\" value=\"".$sess_name."\" /></p>\n";
    
    echo "<p><label for=\"password\"><strong>Passwort:</strong></label><br />\n";    
    echo "<input type=\"password\" id=\"password\" name=\"password\" size=\"60\" value=\"".$sess_pw."\" /></p>\n";

    echo "<p><label for=\"add_rs\"><strong>Art des neuen Eintrages:</strong></label><br />\n";
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