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
    $rs_checkuser = mysql_query("SELECT COUNT(*) FROM tbl_author_en WHERE AuthorNick = '" . $_POST["user"] . "' AND AuthorPassword = '" . $_POST["password"] . "'" , $conn);
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
 
    echo "<h2>Step 3</h2>\n"; 
 
    if($_POST["add_rs"] == 1)
        {
        if(!$_POST["header"] or !$_POST["newstext"] or !$_POST["category"])
            {
            echo "<p><em>Empty fields!</em></p>\n";
            }
        else
            {
            $rs_insertnews = mysql_query("INSERT INTO tbl_news_en (NewsHeadline,NewsText,NewsSource,NewsSourceLink,NewsDate,NewsAuthor,NewsCategory,NewsImage,NewsLinks,NoComments) VALUES ('".$_POST["header"]."','".$_POST["newstext"]."','".$_POST["source"]."','".$_POST["sourcelink"]."','".$now."','".$_POST["user_id"]."','".$_POST["category"]."','".$_POST["picture"]."','".$_POST["links"]."','".$_POST["nocomments"]."')", $conn); // News wird in DB eingetragen...
            
            $rs_shownews = mysql_query("SELECT * FROM tbl_news_en INNER JOIN tbl_author_en ON (tbl_news_en.NewsAuthor = tbl_author_en.IDAuthor) INNER JOIN tbl_category_en ON (tbl_news_en.NewsCategory = tbl_category_en.IDCategory) ORDER BY tbl_news_en.IDNews DESC LIMIT 1", $conn);

            if (mysql_num_rows($rs_shownews) > 0) // News werden nochmal ausgegeben
		        {
                $IDNews = mysql_result($rs_shownews, 0, "tbl_news_en.IDNews");
                $NewsHeadline = mysql_result($rs_shownews, 0, "tbl_news_en.NewsHeadline");
                $NewsText = mysql_result($rs_shownews, 0, "tbl_news_en.NewsText");
                $NewsSource = mysql_result($rs_shownews, 0, "tbl_news_en.NewsSource");
                $NewsSourceLink = mysql_result($rs_shownews, 0, "tbl_news_en.NewsSourceLink");
                
                list($date_post,$time_post) = split(' ',mysql_result($rs_shownews, 0, "tbl_news_en.NewsDate"));
		            list($Year_post,$Month_post,$Day_post) = split('-',$date_post);
		            list($Hour_post,$Minute_post,$Second_post) = split(':',$time_post);
            
                $NewsChangedBy = mysql_result($rs_shownews, 0, "tbl_news_en.NewsChangedBy");
                $NewsChangedDate = mysql_result($rs_shownews, 0, "tbl_news_en.NewsChangedDate");
                $NewsRead = mysql_result($rs_shownews, 0, "tbl_news_en.NewsRead");
                $NewsImage = mysql_result($rs_shownews, 0, "tbl_news_en.NewsImage");
                $NewsLinks = mysql_result($rs_shownews, 0, "tbl_news_en.NewsLinks");
                $expireDate = mysql_result($rs_shownews, 0, "tbl_news_en.expireDate");
                $releaseDate = mysql_result($rs_shownews, 0, "tbl_news_en.releaseDate");
    		    $CategoryName = mysql_result($rs_shownews, 0, "tbl_category_en.CategoryName");
                $AuthorNick = mysql_result($rs_shownews, 0, "tbl_author_en.AuthorNick");
                    
			    echo "<h2>" . htmlentities($NewsHeadline) . "</h2>\n";
                echo "<p>\n<strong>" . htmlentities($AuthorNick) . "</strong> - <em>" . $Day_post . "." . $Month_post . "." . $Year_post . ", " . $Hour_post . ":" . $Minute_post . " </em><br />\n";
            
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
                    echo "<br /><strong>Source:</strong> " . $NewsSource;
                    }
                
                if($NewsSourceLink != "")
                    {
                    echo "<br /><strong>Source (Link):</strong> <a href=\"" . $NewsSourceLink . "\">" . $NewsSourceLink . "</a>";
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
            echo "<p><em>Empty fields!</em></p>\n";
            }
        else
            {
            $rs_insertcat = mysql_query("INSERT INTO tbl_author_en (AuthorNick,AuthorRealName,AuthorPassword,AuthorLevel,AuthorEmail,AuthorHomepage) VALUES ('".$_POST["new_user"]."','".$_POST["new_user_real"]."','".$_POST["new_user_password"]."','".$_POST["new_user_level"]."','".$_POST["new_user_mail"]."','".$_POST["new_user_home"]."','".$_POST["new_user_abstract"]."')", $conn); // News wird in DB eingetragen...
                                                                    
            echo "<p><strong>Author added!</strong></p>\n";
            }
        }
    elseif($_POST["add_rs"] == 3)
        {
        if(!$_POST["new_cat"])
            {
            echo "<p><em>Empty fields!</em></p>\n";
            }
        else
            {
            $rs_insertcat = mysql_query("INSERT INTO tbl_category_en (CategoryName,CategoryPosition) VALUES ('".$_POST["new_cat"]."','".$_POST["new_cat_pos"]."')", $conn); // News wird in DB eingetragen...
            
            echo "<p><strong>New category created!</strong></p>\n";
            }
        }
    echo "<br /><input type=\"hidden\" name=\"step\" value=\"4\" />\n";
    echo "<input type=\"submit\" value=\"Done\" />\n";
    }
elseif ($step == 2) 
    {
    
    echo "<h2>Step 2</h2>\n"; 
    
    if($_POST["password"] and $_POST["user"]) // Passwortabfrage
        {
        $rs_checkuser = mysql_query("SELECT COUNT(*) FROM tbl_author_en WHERE AuthorNick = '" . $_POST["user"] . "' AND AuthorPassword = '" . $_POST["password"] . "'" , $conn);
        $checkuser = mysql_result($rs_checkuser, 0, 0);
        mysql_free_result($rs_checkuser); 
        $rs_user_id = mysql_query("SELECT IDAuthor,AuthorLevel FROM tbl_author_en WHERE AuthorNick = '" . $_POST["user"] . "' AND AuthorPassword = '" . $_POST["password"] . "'" , $conn);
        $user_id = mysql_result($rs_user_id, 0, "tbl_author_en.IDAuthor");
        $user_level = mysql_result($rs_user_id, 0, "tbl_author_en.AuthorLevel");
        mysql_free_result($rs_user_id);         
        
        if ($checkuser > 0) 
            {
            echo "<fieldset class=\"abstand\">\n";
            
            if($_POST["add_rs"] == 1)
                {
                echo "<legend>New News</legend>\n";
    
                echo "<p><label for=\"header\"><strong>Header:</strong></label><br />\n";    
                echo "<input type=\"text\" id=\"header\" name=\"header\" size=\"60\" /></p>\n";

                echo "<p><label for=\"newstext\"><strong>Newstext:</strong></label><br>\n";    
                echo "<textarea cols=\"45\" rows=\"4\" id=\"newstext\" name=\"newstext\"></textarea></p>\n";
    
                echo "<p><label for=\"category\"><strong>Newscategory:</strong></label><br />\n";
                echo "<select name=\"category\" id=\"category\">\n";
	            
                $pointer_cat = 0;
                $rs_category = mysql_query("SELECT DISTINCT IDCategory, CategoryName FROM tbl_category_en ORDER BY CategoryPosition DESC", $conn); // Auflistung des linken Auswahlmenüs
                if (mysql_num_rows($rs_category) > 0)
                    {
                    while (mysql_fetch_row($rs_category))
                        {
                        $IDCategory = mysql_result($rs_category, $pointer_cat, "tbl_category_en.IDCategory");
                        $CategoryName = mysql_result($rs_category, $pointer_cat, "tbl_category_en.CategoryName");

                        echo "  <option value=\"". $IDCategory ."\">".$CategoryName."</option>\n";
                        
                        $pointer_cat++;
                        }
                    }
                mysql_free_result($rs_category);

                echo "</select></p>\n";

                echo "<p><label for=\"picture\"><strong>Picture:</strong></label><br />\n";    
                echo "<input type=\"text\" id=\"picture\" name=\"picture\" size=\"60\" /></p>\n";
    
                echo "<p><label for=\"source\"><strong>Source (Name):</strong></label><br />\n";    
                echo "<input type=\"text\" id=\"source\" name=\"source\" size=\"60\" /></p>\n";

                echo "<p><label for=\"sourcelink\"><strong>Source (Link):</strong></label><br />\n";    
                echo "<input type=\"text\" id=\"sourcelink\" name=\"sourcelink\" size=\"60\" /></p>\n";
                
                echo "<p><label for=\"links\"><strong>Links (Description 1, URL 1, Description 2, URL 2,...):</strong></label><br />\n";    
                echo "<input type=\"text\" id=\"links\" name=\"links\" size=\"60\" /></p>\n";
                
                echo "<p><label for=\"nocomments\"><strong><u>De</u>activate comments for this news?:</strong></label><br />\n";    
                
                echo "<select id=\"nocomments\" name=\"nocomments\" size=\"1\"><option value=\"0\" selected=\"selected\">No</option>\n
	                    <option value=\"1\">Yes</option></select></p>\n";
                
                }
            elseif($_POST["add_rs"] == 2)
                {
                if($user_level <= 1)
                    {
                    echo "<legend>New Author</legend>\n";
                    
                    echo "<p><label for=\"new_user\"><strong>Username:</strong></label><br />\n";    
                    echo "<input type=\"text\" id=\"new_user\" name=\"new_user\" size=\"60\" /></p>\n";
    
                    echo "<p><label for=\"new_user_password\"><strong>Password:</strong></label><br />\n";    
                    echo "<input type=\"password\" id=\"new_user_password\" name=\"new_user_password\" size=\"60\" /></p>\n";              
                
                    echo "<p><label for=\"new_user_password_rep\"><strong>Repeat password:</strong></label><br />\n";    
                    echo "<input type=\"password\" id=\"new_user_password_rep\" name=\"new_user_password_rep\" size=\"60\" /></p>\n";  

                    echo "<p><label for=\"new_user_real\"><strong>Real Name:</strong></label><br />\n";    
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
                    echo "<legend>New Category</legend>\n
                          <p><label for=\"new_cat\"><strong>New Category:</strong></label><br />\n 
                          <input type=\"text\" id=\"new_cat\" name=\"new_cat\" size=\"60\" /></p>\n";

                    
                    $rs_maxcat = mysql_query("SELECT MAX(CategoryPosition) FROM tbl_category_en", $conn);
                    $maxcat = mysql_result($rs_maxcat, 0, 0);
                    mysql_free_result($rs_maxcat); 
                    
                    echo "<p><label for=\"new_cat_pos\"><strong>Position:</strong></label><br />\n   
                          <input type=\"text\" id=\"new_cat_pos\" name=\"new_cat_pos\" size=\"1\" value=\"".($maxcat+1)."\" /></p>\n";
                    }
                else
                    {
                    echo "<p><em>Userlevel not high enough!</em></p>\n"; 
                    }
                }
            echo "<input type=\"hidden\" name=\"step\" value=\"3\" />\n
                  <input type=\"hidden\" name=\"add_rs\" value=\"".$_POST["add_rs"]."\" />\n
                  <input type=\"hidden\" name=\"user_id\" value=\"".$user_id."\" />\n
                  <input type=\"submit\" value=\"Step 3\" />\n
                  </fieldset>\n";
            }
        else
            {
            echo "<p><em>Wrong password and/or username!</em></p>\n";
            }
        }
    else    
        {
        echo "<p><em>Username and/or password missing!</em></p>";
        }
    
    }
else // Erster Schritt der Eingabe Kategorie/Autor/News
    {
    
    !isset($_SESSION["sess_name"]) ? $sess_name = "" : $sess_name = $_SESSION["sess_name"];
    !isset($_SESSION["sess_pw"]) ? $sess_pw = "" : $sess_pw = $_SESSION["sess_pw"];
    
    echo "<h2>Step 1</h2>\n";

    echo "<fieldset class=\"abstand\">\n";
    echo "<legend>What kind of Recordset?</legend>\n";
    
    echo "<p><label for=\"user\"><strong>User:</strong></label><br>\n";    
    echo "<input type=\"text\" id=\"user\" name=\"user\" size=\"60\" value=\"".$sess_name."\" /></p>\n";
    
    echo "<p><label for=\"password\"><strong>Password:</strong></label><br />\n";    
    echo "<input type=\"password\" id=\"password\" name=\"password\" size=\"60\" value=\"".$sess_pw."\" /></p>\n";

    echo "<p><label for=\"add_rs\"><strong>What kind of Recordset?</strong></label><br />\n";
    echo "<select name=\"add_rs\" id=\"add_rs\">\n";
	echo "  <option value=\"1\">News</option>\n";
	echo "  <option value=\"2\">Author</option>\n";
	echo "  <option value=\"3\">News-Category</option>\n";
    echo "</select></p>\n";
    
    echo "<input type=\"hidden\" name=\"step\" value=\"2\" />\n";
    
    echo "<input type=\"submit\" value=\"Step 2\" />\n";
    echo "</fieldset>\n";
    }

echo "</form>\n";    
include("connection_end.inc.php"); 
?>
</body></html>