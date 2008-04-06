<?php
ob_start('ob_gzhandler');
ini_set('arg_separator.output','&#38;');
ini_set('session.use_trans_sid','0');
putenv('TZ=CET');
header('Content-Encoding: gzip'); 
header('Cache-Control: must-revalidate');
header('Expires: ' . date("D, d M Y H:i:s", time() + (60 * 60 * 20)) . ' GMT');

/*-------------------------------------*/
/* C O O K I E S   +   S E S S I O N S */
/*-------------------------------------*/

@session_start();

//Cookie bzw Session um neue Beitrage als NEU zu kennzeichnen (ist aber unter Win buggy (timezone))
if (isset($_SESSION['lastvisit'])) 
  {
  define('LASTVISIT',$_SESSION['lastvisit']);
  }
elseif (isset($_COOKIE['lastvisit']))
  {
  define('LASTVISIT',$_COOKIE['lastvisit']);
  $_SESSION['lastvisit'] = $GLOBALS['lastvisit'] = $_COOKIE['lastvisit'];
  session_register('lastvisit');
  }
else
  {
  define('LASTVISIT','');
  }
  
if (isset($_POST['commentsend'])) // Setzt nach dem Absenden des Formulares ein Cookie für ein paar Minuten, dass ein erneutes Kommentar verhindert (Spam Schutz)
  {
  setcookie('commentwritten_' . $_POST['commentsend'],1,time()+360);
  
  if(isset($_POST['name']))
    {
    $_SESSION['sess_name'] = $GLOBALS['sess_name'] = $_POST['name'];
    setcookie('cook_name',$_POST['name'],time()+31536000);
    }
  if(isset($_POST['email']))
    {
    $_SESSION['sess_email'] = $GLOBALS['sess_email'] = $_POST['email'];
    setcookie('cook_email',$_POST['email'],time()+31536000);
    }
  
  session_register('sess_name','sess_email'); // Merkt sich Name,EMail damit man es nicht bei jedem Kommentar neu eintragen muss...
  }

setcookie('lastvisit','',time()-3600);
setcookie('lastvisit',date("Y-m-d H:i:s"),time()+31536000);

/*-----------------------------------------------------*/
/* F U N K T I O N E N   +   D B   C O N N E C T I O N */
/*-----------------------------------------------------*/

require_once('functions.inc.php');  
require_once('class.ChooseLanguage.inc.php'); 
require_once('class.FormatLongString.inc.php'); 
require_once('class.ChooseFormatDate.inc.php'); 
require_once('class.Newslist.inc.php'); 
require_once('class.Helpers.inc.php');
require_once('class.Author.inc.php');
require_once('class.AuthorList.inc.php'); 
require_once('class.CategoryList.inc.php');  
/*-------------------------------*/
/* V A R I A B L E N (berechnen) */
/*-------------------------------*/

define('PICTUREPATH','pics/'); // Relativer Pfad zu den Newsbildern
define('SHOW_RS',10); // Anzahl der News die pro Seite angezeigt werden sollen
define('NOW',date('Y-m-d H:i:s'));
define('NOW_TIMESTAMP',time());
define('PHP_SELF',$_SERVER['PHP_SELF']);

if (isset($_GET['cat'])) { define('CAT',$_GET['cat']); }
if (isset($_POST['author'])) { define('AUTHOR',$_POST['author']); }
if (isset($_POST['cat_search'])) { define('CAT_SEARCH',$_POST['cat_search']); }

if (isset($_GET['profile'])) { define('PROFILE',$_GET['profile']); }

if (isset($_POST['search']) && strlen(trim($_POST['search'])) > 0)
  {
  $search = (string) $_POST['search'];
  }
elseif (isset($_GET['search']) && strlen(trim($_GET['search'])) > 0)
  {
  $search = (string) urldecode($_GET['search']);
  }

if (isset($search))
  {
  $searchstring_temp = (string) $search;
  define('SEARCHSTRING',htmlentities($search));
  }
else
  {
  $searchstring_temp = (string) '';
  define('SEARCHSTRING','');
  }

$lg = (object) new ChooseLanguage();
$sp = (object) new FormatLongString($searchstring_temp);
$dp = (object) new ChooseFormatDate();
$helpers = (object) new Helpers();

$sp->SetSessionURL(session_name(),session_id());
 // $language_select->lang_content
define('LANG_ISO',str_replace('_','-',$lg->getLang()));


define('HIGHLIGHT',$sp->SetURLencoder('search',urlencode($searchstring_temp),'&'));
define('SESSIONID',$sp->SetURLencoder(session_name(),session_id(),'&#38;'));
defined('CAT') ? define('LISTCAT',$sp->SetURLencoder('cat',CAT,'&#38;')) : define('LISTCAT','');
(defined('CAT') && !isset($search)) ? define('ONLY_CAT','?cat=' . CAT) : define('ONLY_CAT','');

if (isset($_POST['comment'])) { $comment = (string) $_POST['comment']; }
if (isset($_POST['name'])) { $name = (string) $_POST['name']; }
if (isset($_POST['email'])) { $email = (string) $_POST['email']; }
if (isset($_POST['commentsend'])) { $commentsend = (int) $_POST['commentsend']; }

if (isset($_COOKIE['cook_name'])) // Überprüfung für das Vorausfüllen des Formulares
  {
  $sess_name = (string) $_COOKIE['cook_name'];
  }
elseif(isset($_SESSION['sess_name']))
  {
  $sess_name = (string) $_SESSION['sess_name'];
  }
else
  {
  $sess_name = (string) '';
  }
  
if (isset($_COOKIE['cook_email'])) // Überprüfung für das Vorausfüllen des Formulares
  {
  $sess_email = (string) $_COOKIE['cook_email'];
  }
elseif(isset($_SESSION['sess_email']))
  {
  $sess_email = (string) $_SESSION['sess_email'];
  }
else
  {
  $sess_email = (string) '';
  }

if (isset($_POST['id']))
  {
  $id = (int) $_POST['id'];
  }
elseif (isset($_GET['id']))
  {
  $id = (int) $_GET['id'];
  }

if (isset($id))
  {
  define('ONLY_ID','?id=' . $id);
  $commentcheck = (string) 'commentwritten_' . $id; // Variable für dynamische Variable
  }
else
  {
  define('ONLY_ID','');
  }


/* Startpunkt für Auflistung */

if (isset($_POST['startlisting']))
  {
  $startlisting = (int) $_POST['startlisting'];
  }
elseif (isset($_GET['startlisting']))
  {
  $startlisting = (int) $_GET['startlisting'];
  }

if (!isset($startlisting) || $startlisting < 0) // Falls kein Startwert für Weiter/Zurück Links gegeben bei 0 anfangen
  {
  define('STARTLISTING',0);
  }
elseif ($startlisting > SUM_NEWS) // Falls Startwert größer als die Anzahl and Datensätzen
  {
  define('STARTLISTING',(SUM_NEWS - SHOW_RS));
  }
else
  {
  define('STARTLISTING',$startlisting);
  }

if (isset($startlisting)) { unset($startlisting); } 

/*---------------------------------------*/
/* D B   A B F R A G E   D E R   N E W S */
/*---------------------------------------*/

if (isset($id) && is_int($id) && $id != 0) // Auswahl der Abfrage zur Darstellung der News gesamt, Kategorie oder ID Nummer
  {
  $newslist = new NewsList('onenews', STARTLISTING, SHOW_RS, $id, '', '', '');
  }
elseif (defined('CAT'))
  {
  $newslist = new NewsList('category', STARTLISTING, SHOW_RS, CAT, '', '', '');
  }
elseif (isset($search))
  {
  $newslist = new NewsList('search', 0, 0, 0, AUTHOR, $search, CAT_SEARCH);
  }
else
  {
  $newslist = new NewsList('all', STARTLISTING, SHOW_RS, $id, '', '', '');
  } // end if


define('SUM_NEWS',count($newslist->getNewsListCountAll()));

echo '<?xml version="1.0" encoding="iso-8859-1"?>';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
       "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php echo 'xml:lang="' . LANG_ISO . '" lang="' . LANG_ISO . '"'; ?>>
  <head>
    <title><?php echo $lg->TranslateEncode('newssystem_title'); ?></title>
      <meta http-equiv="MSThemeCompatible" content="Yes" />
      <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
      <meta http-equiv="Content-Style-Type" content="text/css" />
      <?php echo '<meta http-equiv="expires" content="'.gmdate("D, d M Y H:i:s", time() + (60 * 60 * 20)).'" />'; ?>
      <meta http-equiv="pragma" content="no-cache" />
      <style type="text/css">
      <!--
      @import url("newssystem.css");
      -->
      </style>
  
  </head>
  <body>
  <div id="nsmenu">
<?php
/*---------*/
/* M E N Ü */
/*---------*/

$categorylist = (object) new CategoryList($lg->getLangContent());
if (count($categorylist->getCategoryList()) > 0)
  {
  echo '<h2 class="nsmenu"><a href="' . PHP_SELF . '" style="color:white;text-decoration:none;">' . $lg->TranslateEncode('menu') . '</a></h2>
        <div class="nsmenucontent">';
  foreach ($categorylist->getCategoryList() as $name => $value)
    {
    $values = (array) explode('_', $value);
    if (defined('CAT') && CAT == $values[0]) { $cat_name = (string) $name; }
    if (defined('CAT_SEARCH') && CAT_SEARCH == $values[0]) { $cat_name = (string) $name; } 
    $sum_cat_news = (int) (($values[1] == 1) ? ($values[1]) : $values[1] ); 
    echo '  <a href="' . PHP_SELF . '?cat=' . $values[0] . SESSIONID . '"  class="menu">' .  $sp->FormatShortText($name) . ' <small class="ns">(' . $sum_cat_news . ')</small></a><br />';
    } // end foreach
 
  if (isset($sum_cat_news)) { unset($sum_cat_news); } 
 
  echo '<form method="post" action="' . PHP_SELF . '" title="' . $lg->TranslateEncode('search') . '" style="text-align:center"> 
              <fieldset class="ns">
              <legend>' . $lg->TranslateEncode('search') . '</legend>
  
                  <br /><em><label for="search" class="ns">' . $lg->TranslateEncode('form_keyword') . ':</label></em><br />
                  <input type="text" name="search" id="search" size="9" value="' . SEARCHSTRING . '" tabindex="1" class="ns" /><br />';
                
                
                
  if (isset($_GET['advsearch']))
    {              
    echo '        <em><label for="cat_search" class="ns">' . $lg->TranslateEncode('form_category') . ':</label></em><br />
                  <select name="cat_search" id="cat_search" size="1" tabindex="2" class="ns">
                  <option value="all" selected="selected">' . $lg->TranslateEncode('form_all') . '</option>';
  

    foreach ($categorylist->getCategoryList() as $name => $value)
      {
      $values = (array) explode('_', $value);
      $CategoryName = (string) $sp->FormatShortText($name);
      $catselect = (string) (($values[0] == CAT_SEARCH) ? ' selected="selected"' : '');
      echo '<option value="' . $values[0] . '"' . $catselect . '>' . $name . '</option>';
      } // end foreach

    if (isset($CategoryName)) { unset($CategoryName); } 
    if (isset($catselect)) { unset($catselect); }             
    if (isset($name)) { unset($name); } 
    if (isset($value)) { unset($value); }
    if (isset($values)) { unset($values); }
    
    echo '        </select><br />
                  <em><label for="author" class="ns">' . $lg->TranslateEncode('form_author') . ':</label></em><br />
                  <select name="author" id="author" size="1" tabindex="3" class="ns">
                   <option value="all" selected="selected">' . $lg->TranslateEncode('form_all') . '</option>'; // Suchformular

    $authorlist = (object) new AuthorList();
    if (count($authorlist->getAuthorList()) > 0)
      {
      foreach ($authorlist->getAuthorList() as $author)
        {
        if ($author->getCountArticles() > 0)
          {
          $AuthorNick = $sp->FormatShortText($author->getAuthorName());
          $authorselect = (($author->getAuthorID() == AUTHOR) ? ' selected="selected"' : '');
          echo '<option value="' . $author->getAuthorID() . '"' . $authorselect . '>' . $AuthorNick . ' (' . $author->getCountArticles() . ')</option>';          
          } // end if
        } // end foreach
      
      if (isset($AuthorNick)) { unset($AuthorNick); }
      if (isset($authorselect)) { unset($authorselect); }
      } // end if
    if (isset($author)) { unset($author); }
    if (isset($authorlist)) { unset($authorlist); }    
    echo '          </select><br /><br />';        
    }
  else
    {
    echo '        <br />
                  <input type="hidden" name="cat_search" value="all" />
                  <input type="hidden" name="author" value="all" />';
    } // end if
  
  echo '          <input type="hidden" name="startlisting" value="' . STARTLISTING . '" />
                  <input type="hidden" name="' . session_name() . '" value="' . session_id() . '" />
                  <input type="submit" value="' . $lg->TranslateEncode('form_search') . '" tabindex="4" class="ns" /><br /><br />';
  
  if (!isset($_GET['advsearch'])) { echo '<small class="ns"><a href="' . PHP_SELF . '?advsearch=1&#38;' . session_name() . '=' . session_id() . '">' . $lg->TranslateEncode('advanced_search') . '</a></small>'; }

  echo ' </fieldset>
          </form>
          <form action="' . PHP_SELF . '" method="post" title="' . $lg->TranslateEncode('language') . '" style="text-align:center">
            <fieldset class="ns">
              <legend>' . ucfirst($lg->TranslateEncode('language')) . '</legend><br />
              <label for="lang" class="ns">' . $lg->TranslateEncode('OSM') . '</label>
              <br />' . $lg->returnDropdownLang() . '<br /><br />
              
              <label for="lang_content" class="ns">' . ucfirst($lg->TranslateEncode('Content')) . '</label>
              <br />' . $lg->returnDropdownLangContent() . '<br /><br />
              
              <label for="timeset" class="ns">' . ucfirst($lg->TranslateEncode('time')) . '</label>
              <br />' . $dp->returnDropdownSelecttime() . '<br />
              <input type="hidden" name="startlisting" value="' . STARTLISTING . '" class="ns" />
              <input type="hidden" name="' . session_name() . '" value="' . session_id() . '" class="ns" />
              <br /><input type="submit" value="' . $lg->TranslateEncode('Change') . '" tabindex="7" class="ns" />
            </fieldset>
          </form>';


  echo '<small class="ns">'
       . $helpers->getSumNews() . ' ' . $lg->TranslateEncode('news') . '<br />'
       . $helpers->getSumComments() . ' ' . $lg->TranslateEncode('comments') . '<br />'
       . user_online24() . ' ' . $lg->TranslateEncode('user_online') . '</small>     
       <br /><br /><small class="ns">';


  if ($dp->GetTimeset() == 1) // Falls swatch Zeit
    {
    echo '<a href="http://www.swatch.com/alu_beat/fs_itime.html" target="_blank">' . $lg->TranslateEncode('timezone_is') . ' BMT</a><br />';
    echo '<strong>' . $lg->TranslateEncode('swatch_time') . ':<br />' . $dp->swatchdate() . ' &#8211; ' . $dp->swatchtime() . '</strong>';    
    }
  elseif ($dp->GetTimeset() == 2)
    {
    echo '<a href="http://www.stacken.kth.se/~kvickers/timezone.html" target="_blank">' . $lg->TranslateEncode('timezone_is') . ' ' . getenv('TZ') . '</a><br />';
    echo '<strong>' . $lg->TranslateEncode('ISO_time') . ':<br />' . $dp->ShortDate() . ' &#8211; ' . $dp->TimeString() . '</strong>';
    }
  else
    {
    echo '<a href="http://www.stacken.kth.se/~kvickers/timezone.html" target="_blank">' . $lg->TranslateEncode('timezone_is') . ' ' . getenv('TZ') . '</a><br />';
    echo '<strong>' . $lg->TranslateEncode('time') . ':<br />' . $dp->ShortDate() . ' &#8211; ' . $dp->TimeString() . '</strong>';
    } // end if
  
  echo '</small><br />';
  
  echo '<br /><div style="text-align:center;"><a href="rss.php?lang=' . $lg->getLangContent() . LISTCAT . '"><img src="xml.gif" alt="RSS Content Syndication" width="36" height="14" border="0" /></a></div>';


  echo '</div>
        </div>
        <div id="nscontent">';
  } // end if [$db->rs_num_rows($rs_category) > 0]

if (isset($categorylist)) { unset($categorylist); }
  
/*---------------------------------*/
/* A U S G A B E   D E R   N E W S */
/*---------------------------------*/

if (count($newslist->getNewsList()) > 0 && !defined('PROFILE')) // Schleife zur Auflistung der News
  {
  if (defined('CAT') || (defined('CAT_SEARCH') && CAT_SEARCH != 'all'))
    {
    echo '<h3 class="nsnewsheader' . CAT . '">' . $sp->FormatShortText($cat_name) . '</h3><br />';
    } // end if

  if ((isset($search) || defined('CAT_SEARCH')) && !isset($id))
    {
    echo '<h2 class="nsneutralheader">' . $lg->TranslateEncode('search_result_for') . ' &#8222;' . $sp->FormatShortText($search) . '&#8220;</h2><h4 class="ns">' . SUM_NEWS . ' ' . $lg->TranslateEncode('news_found') . '</h4>';
    } // end if

  foreach ($newslist->getNewsList() as $news)
	  {
    $css_color = (string) $news->getCategoryID(); 
    $NewsHeadline = (string) $sp->FormatShortText($news->getHeadline());
    $author = (object) $news->getAuthor();
    $AuthorNick = (string) $sp->FormatShortText($author->getAuthorName());
    $CategoryName = (string) $sp->FormatShortText($news->getCategoryName());
    $newsimage_alttext = (string) $sp->FormatShortText($news->getImageAlttext());
    $nocomments = (boolean) $news->getNoComments();
                 
    if ((!defined('CAT') && !defined('CAT_SEARCH')) || (defined('CAT_SEARCH') && CAT_SEARCH == 'all'))
      {
      echo '<h3 class="nsnewsheader' . $css_color . '">' . $CategoryName . '</h3>';
      } // end if Falls Gesamtübersicht werden Kategorien angezeigt

    echo '<p class="nsnewssubheader' . $css_color . '">
            <big class="nspostdate">' . $dp->LongDate($dp->ISOdatetimeToUnixtimestamp($news->getNewsdate())) . '</big>
            <big class="nspostauthor">' . ucfirst($lg->TranslateEncode('posted_by')) . ' <a href="' . PHP_SELF . '?profile=' . $author->getAuthorID() . '">' . $AuthorNick . '</a></big>
          </p>
          <h4 class="nsheader' . $css_color . '">' . $NewsHeadline;
    if ((LASTVISIT != '') && (LASTVISIT < $news->getNewsdate())) { echo ' <span class="nshighlight">(' . strtoupper($lg->TranslateEncode('new')) . ')</span>'; }
    echo '</h4>         
          <p class="nsnewstext' . $css_color . '">';
    if (strlen(trim($news->getImage())) > 0) { echo '<img src="' . PICTUREPATH . $news->getImage() . '" alt="' . $newsimage_alttext . '" class="nsnewspic" />'; }

    if (isset($search))
      {
      echo $sp->SearchResult($news->getNewstext(), $news->getNewsID());
      }
    elseif (!isset($id)) // Ausgabe Newstext
      {
      echo (($news->getShowFullText() != true) ? $sp->AbstractText($news->getNewstext(), $news->getNewsID()) : $sp->FormatLongText($news->getNewstext()));
      }
    else
      {
      echo $sp->FormatLongText($news->getNewstext());
      } // end if

    
    if (count($news->getLinks()) > 0) // Gibt Linkliste aus falls String vorhanden
      {
      echo '<br /><strong>' . $lg->TranslateEncode('links') . ':</strong><br />';
      foreach ($news->getLinks() as $link => $linktext)
        {
        echo '&#8226; <a href="' . $link . '" target="_blank" class="nsnewslink' . $css_color . '">' . $sp->FormatShortText($linktext) . '<br /></a>';
        } // end foreach
      } // end if
    
    echo '</p>';
    
    if (strlen(trim($news->getSource())) > 0 || $nocomments == false)
      {
      echo '<p class="nsnewssubheader' . $css_color . '">
              <big class="nssource">';
      
      if (strlen(trim($news->getSource())) > 0)
        {
        echo '<strong>' . $lg->TranslateEncode('source') . ':</strong> ';
        echo ((strlen(trim($news->getSourceLink())) > 0) ? '<a href="' . $news->getSourceLink() . '">' . $news->getSource() . '</a>' : $news->getSource());
        } // Gibt Quelle aus falls vorhanden
          
      echo '  </big>
              <big class="nscomments">';

      
      if ((!isset($id)) && ($nocomments == false)) // Anzeige der Anzahl von Kommentaren
        {
        $commentlist = $news->getComments();
        $count_comments = count($commentlist->getCommentList());
        $comment_link_title = (string) '';
        
        if ($count_comments > 0) // Gibt Namen der Kommentarautoren im title Tag aus.
          {
          $comment_link_title .= (string) $lg->TranslateEncode('comments_by') . ' ';
          $commentlist->getAuthors();

          foreach ($commentlist->getAuthors() as $author)
            {
            $comment_link_title .= (string) $sp->FormatShortText($author) . ', ';
            } // foreach

          $comment_link_title = (string) substr($comment_link_title, 0, (strlen($comment_link_title)-2)); // Letztes Kosmma entfernen
          } // end if
       
        echo '<a href="' . PHP_SELF . '?id=' . $news->getNewsID() . HIGHLIGHT . SESSIONID . '" title="' . $comment_link_title . '">' . $count_comments . ' '; 
        echo (($count_comments != 1) ? $lg->TranslateEncode('comments') : $lg->TranslateEncode('comment'));
        echo '</a>';
        
        } // end if   
      echo '  </big>
            </p>';
      }
    echo '<br />';      
		} // end if [count($newslist->getNewsList()) > 0 && !defined('PROFILE')]
    
    if (isset($cat_name)) { unset($cat_name); }
    if (isset($max)) { unset($max); }
    if (isset($link_list)) { unset($link_list); }
    if (isset($count_comments)) { unset($count_comments); }
    if (isset($NewsHeadline)) { unset($NewsHeadline); }
    if (isset($AuthorNick)) { unset($AuthorNick); }
    if (isset($CategoryName)) { unset($CategoryName); }
    if (isset($newsimage_alttext)) { unset($newsimage_alttext); }
    if (isset($comment_link_title)) { unset($comment_link_title); }
    if (isset($author)) { unset($author); }
    
    if (isset($id) && is_int($id) && $id != 0) // Nächter/Vorheriger Artikel Ausgabe
      { 
      echo '<div class="nsquicknav"><span class="nsalileft"><small class="ns">';      
      $prev_news = $news->getPreviousNews();
      
      if ($prev_news != false)
        {
        echo '&#171; <a href="' . PHP_SELF . '?id=' . $prev_news->getNewsID() . SESSIONID . '" title="' . $prev_news->getHeadline() . '">' . $lg->TranslateEncode('previous_newsarticle') . '</a>';
        }
      else
        {
        echo '&nbsp;';
        } // end if
      
      if (isset($prev_news)) { unset($prev_news); }
      echo '</span><span class="nsalicenter">&nbsp;</span><span class="nsaliright">';
      $next_news = $news->getNextNews();
      
      if ($next_news != false)
        {
        echo '<a href="' . PHP_SELF . '?id=' . $next_news->getNewsID() . SESSIONID . '" title="' . $next_news->getHeadline() . '">' . $lg->TranslateEncode('next_newsarticle') . '</a> &#187;';
        } 
      else
        {
        echo '&nbsp;';
        } // end if
      
      if (isset($next_news)) { unset($next_news); }
      echo '</span></small></div>';
      } // end if
  /*-----------------------------------------------*/
  /* A U S G A B E   V O N   K O M M E N T A R E N */
  /*-----------------------------------------------*/
        
  if (isset($id) && is_int($id) && $id != 0) // Zähler rauf + Ausgabe der Kommentare bei Einzelnewsansicht
    {
    if ($nocomments == false) { echo '<h3 class="nsnewsheader' . $css_color . '">' . $lg->TranslateEncode('Comments') . '</h3>'; }
    
    
    /*---------------------------------------------------*/
    /* E I N T R A G E N   V O N   K O M M E N T A R E N */
    /*---------------------------------------------------*/
     
    if (isset($commentsend) && !isset($_COOKIE[$commentcheck])) // Überprüfung des Formulares/Cookies und Eintragen eines Kommentars
      {
      if ($news->insertComment($comment, $name, $email) == false)
        {
        echo '  <div><em>' . $lg->TranslateEncode('error_comment') . '</em></div>';
        } // end if
      } // end if
    
    if (isset($name)) { unset($name); }
    if (isset($comment)) { unset($comment); }
    if (isset($email)) { unset($email); }
    if (isset($commentsend)) { unset($commentsend); }
    
    $news->updateNewsRead();
    $day_old = (int) 0;
    $commentlist = $news->getComments();
    if (count($commentlist->getCommentList()) > 0)
      {
      foreach ($commentlist->getCommentList() as $comment)
        {
        $CommentText = (string) $sp->FormatLongText($comment->getCommentText());
        $CommentAuthor = (string) $sp->FormatShortText($comment->getAuthorName());
        $CommentEmail = (string) $sp->CheckMailHomepage($comment->getAuthorMail());
        $timestring_comment = (int) $dp->ISOdatetimeToUnixtimestamp($comment->getCommentDate());
        $date_comment = (string) $dp->DateString($timestring_comment);
        $time_comment = (string) $dp->TimeString($timestring_comment);        
        
        $CommentEmailFormat = (string) (($CommentEmail != '') ? '<a href="' . $CommentEmail . '" target="_blank">' . $CommentAuthor . '</a>' : $CommentAuthor);        

        if (date('d',$timestring_comment) != $day_old) { echo '<h4 class="nscommentdate">' . $date_comment . '</h4>'; }
        
        echo '<p class="nsnewssubheader' . $css_color . '">
                <big class="nspostdate">' . $CommentEmailFormat . '</big> 
                <big class="nspostauthor">' . $time_comment . '</big>
              </p>
             <p class="nsnewstext' . $css_color . '" style="border-bottom: 1px solid #000000;">' . $CommentText . '</p><br />';
        
        $day_old = (int) date('d',$timestring_comment);
        }
      
      if (isset($day_old)) { unset($day_old); }
      if (isset($CommentText)) { unset($CommentText); }
      if (isset($CommentAuthor)) { unset($CommentAuthor); }
      if (isset($CommentEmail)) { unset($CommentEmail); }
      if (isset($date_comment)) { unset($date_comment); }
      if (isset($time_comment)) { unset($time_comment); }
      if (isset($CommentEmailFormat)) { unset($CommentEmailFormat); }
      }
    else
      {
      if ($nocomments == false) { echo '<p class="nsnewstext' . $css_color . '" style="border-bottom: 1px solid #000000;"><strong>' . $lg->TranslateEncode('no_comments') . '</strong></p>'; }
      }
    
    if (isset($news)) { unset($news); }
    if (isset($css_color)) { unset($css_color); }       
    if (isset($comment)) { unset($comment); } 
    if (isset($commentlist)) { unset($commentlist); } 
    
    /*-----------------*/
    /* F O R M U L A R */
    /*-----------------*/
    
    if (!isset($_COOKIE[$commentcheck]) && !isset($commentsend) && $nocomments == false) // Formular für Kommentar
      {
      echo '<br /><form method="post" action="' . PHP_SELF . '" title="' . $lg->TranslateEncode('form_write_answer') . '">
              <fieldset class="abstand" class="ns">
                  <legend class="ns">' . $lg->TranslateEncode('form_write_answer') . '</legend><br />
                  <label for="name" class="ns">' . $lg->TranslateEncode('form_name') . ':</label><br />
                  <input type="text" id="name" name="name" size="50" value="' . $sess_name . '" accesskey="n" tabindex="8" class="ns" /><br />
                  <label for="email" class="ns">' . $lg->TranslateEncode('form_email') . ' ' . $lg->TranslateEncode('or') . ' ' . $lg->TranslateEncode('form_homepage') . ':</label><br />
                  <input type="text" id="email" name="email" size="50" value="' . $sess_email . '" accesskey="e" tabindex="9" class="ns" /><br />
                  <label for="comment" class="ns">' . $lg->TranslateEncode('form_comment') . ':</label><br />
                  <textarea cols="40" rows="3" id="comment" name="comment" accesskey="k" tabindex="10" class="ns"></textarea><br />
                  <input type="hidden" name="commentsend" value="' . $id . '" />
                  <input type="hidden" name="id" value="' . $id . '" />
                  <input type="hidden" name="startlisting" value="' . STARTLISTING . '" />
                  <input type="hidden" name="' . session_name() . '" value="' . session_id() . '" />
                  <br /><input type="submit" name="submitcomment" id="submitcomment" value="' . $lg->TranslateEncode('form_send') . '" accesskey="s" tabindex="11" class="ns" />
              </fieldset>
            </form>';
      }
    }
  if (isset($commentcheck)) { unset($commentcheck); }
  if (isset($sess_email)) { unset($sess_email); }
  if (isset($sess_name)) { unset($sess_name); }
  if (isset($nocomments)) { unset($nocomments); }
  if (isset($commentsend)) { unset($commentsend); }
  if (isset($id)) { unset($id); }
  }
else
  {
  
    /*---------------*/
    /* P R O F I L E */
    /*---------------*/
  
  if (!defined('PROFILE') && strlen(trim($newslist->getErrorMessage())) > 0)
    {
    echo '<strong><em>' . $lg->TranslateEncode($newslist->getErrorMessage()) . '</em></strong>';
    }
  else
    {
    $author = new Author(PROFILE);
    $authorname = (string) $sp->FormatShortText($author->getAuthorName());
        
    echo '<h2 class="nsneutralheader">' . ucfirst($lg->TranslateEncode('profile')) . '</h2>
          <h4 class="ns">'. $authorname .'</h2>
          <div class="nsprofiletext">';
    if (strlen(trim($author->getAuthorPicture())) > 0) { echo '<img src="' . PICTUREPATH . $author->getAuthorPicture() . '" alt="Icon" align="left" class="nsnewspic" />'; }
    echo  $sp->FormatLongText($author->getAuthorAbstract()) . '</div>
          <br /><h4 class="ns">' . ucfirst($lg->TranslateEncode('latest_news_by')) . ' ' . $authorname . '</h4>';
        
    if (isset($authorname)) { unset($authorname); }
        
    $newslist_author = (object) $author->getLastArticles();
        
    if (count($newslist_author->getNewsList()) > 0)
      {
      echo '<div class="nsprofiletext">';
      foreach ($newslist_author->getNewsList() as $news)
	      {
        $date_news = (string) $dp->ShortDate($dp->ISOdatetimeToUnixtimestamp($news->getNewsdate()));        
        echo '<span class="nscolorbox' . $news->getCategoryID() . '" title="' . $sp->FormatShortText($news->getCategoryName()) . '">&nbsp;&#187;&nbsp;</span> <a href="' . PHP_SELF . '?id=' . $news->getNewsID() . '">' . $sp->FormatShortText($news->getHeadline()) . '</a> <small class="ns">(' . $date_news . ')</small><br />';
        } // end foreach
      echo '</div>';
      } // end if

    if (isset($news)) { unset($news); }
    if (isset($newslist_author)) { unset($newslist_author); }
    if (isset($search)) { unset($search); }
    if (isset($date_news)) { unset($date_news); }
    } // end if
  } // end if

if (isset($dp)) { unset($dp); }
if (isset($author)) { unset($author); }

/*---------------------------------------------------*/
/* A U S G A B E   V O N   Z U R Ü C K / W E I T E R */
/*---------------------------------------------------*/

if ($newslist->getPrevPage() == true)
  {
  $prevpage = (string) '&laquo;&laquo; <a href="' . PHP_SELF . $sp->SetURLencoder('startlisting',(STARTLISTING - SHOW_RS)) . LISTCAT . SESSIONID . '">' . $lg->TranslateEncode('back') . '</a> &laquo;&laquo;';
  }
else
  {
  $prevpage = (string) '&nbsp;';
  } // end if

if ($newslist->getNextPage() == true)
  {
  $nextpage = (string) '&raquo;&raquo; <a href="' . PHP_SELF . $sp->SetURLencoder('startlisting',(STARTLISTING + SHOW_RS)) . LISTCAT . SESSIONID . '">' . $lg->TranslateEncode('next') . '</a> &raquo;&raquo;';
  }
else
  {
  $nextpage = (string) '&nbsp;';
  } // end if

if (count($newslist->getPages()) > 0) 
  {
  $listpages = (string) $lg->TranslateEncode('page') . ': ';
  foreach ($newslist->getPages() as $page => $startlisting)
    {
    $listpages .= (string) ((($page * SHOW_RS) != STARTLISTING) ? '<a href="' . PHP_SELF . '?startlisting=' . $startlisting . LISTCAT . SESSIONID . '">' . $page . '</a> ' : $page . ' ');
    } // end foreach
  }
else
  {
  $listpages = (string) '&nbsp;';
  } // end if

if (isset($lg)) { unset($lg); }
if (isset($sp)) { unset($sp); }  
if (isset($newslist)) { unset($newslist); }

echo '        <br style="clear:both" />
              <span class="nsalileft">' . $prevpage . '</span>
              <span class="nsalicenter">' . $listpages . '</span>
              <span class="nsaliright">' . $nextpage . '</span>
              <br style="clear:both" />
              </div><h6 class="ns">Newsscript and all other Content &#169; 2002 Flaimo.com</h6></body></html>';


if (isset($prevpage)) { unset($prevpage); }
if (isset($listpages)) { unset($listpages); }
if (isset($nextpage)) { unset($nextpage); }
ob_end_flush();
ob_end_clean();
?>