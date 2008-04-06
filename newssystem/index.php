<?php
ob_start('ob_gzhandler');
ini_set('arg_separator.output','&#38;');
ini_set('session.use_trans_sid','0');
putenv('TZ=CET');
header('Content-Encoding: gzip'); 
header('Cache-Control: must-revalidate');
header('Expires: ' . date("D, d M Y H:i:s", time() + (60 * 60 * 20)) . ' GMT');

/*----------------*/
/* C L A S S E S  */
/*----------------*/

require_once('Smarty.class.php');
require_once('functions.inc.php');  
require_once('class.ChooseLanguage.inc.php'); 
require_once('class.FormatLongString.inc.php'); 
require_once('class.ChooseFormatDate.inc.php'); 
require_once('class.Newslist.inc.php'); 
require_once('class.Helpers.inc.php');
require_once('class.Author.inc.php');
require_once('class.AuthorList.inc.php'); 
require_once('class.CategoryList.inc.php');  
require_once('class.NewsUser.inc.php'); 

$tpl = (object) new Smarty;
$tpl->left_delimiter = (string) '{{'; 
$tpl->right_delimiter = (string) '}}'; 
$tpl->template_dir = (string) 'tpl'; 
$tpl->compile_dir = (string) 'tpl_c'; 
$tpl->config_dir = (string) 'conf';
//$tpl->compile_check = (boolean) false; // Wenn Template fertig ist kommentar weg...
$tpl->cache_dir = (string) 'cache';
$tpl->cache_lifetime = (0);
//$tpl->caching = (boolean) true; // Nur einschalten wenn redundante Seite (also kein "dynamischer" Inhalt)$tpl->cache_dir = (string) 'cache';

/*-------------------------------------*/
/* C O O K I E S   +   S E S S I O N S */
/*-------------------------------------*/

session_start();
$user = (object) new NewsUser();
define('LASTVISIT',$user->getLastVisit()); //Cookie bzw Session um neue Beitrage als NEU zu kennzeichnen (ist aber unter Win buggy (timezone))
$user->setLastVisit();

/*-------------------------------*/
/* V A R I A B L E N (berechnen) */
/*-------------------------------*/

define('PICTUREPATH','pics/'); // Relativer Pfad zu den Newsbildern
define('SHOW_RS',10); // Anzahl der News die pro Seite angezeigt werden sollen
define('NOW',date('Y-m-d H:i:s'));
define('NOW_TIMESTAMP',time());
define('PHP_SELF',$_SERVER['PHP_SELF']);
$tpl->assign('PHP_SELF',PHP_SELF); 

if (isset($_GET['cat'])) {
    define('CAT',$_GET['cat']);
}

if (isset($_POST['author'])) {
    define('AUTHOR',$_POST['author']);
}

if (isset($_POST['cat_search'])) {
    define('CAT_SEARCH',$_POST['cat_search']);
}

if (isset($_GET['profile'])) {
    define('PROFILE',$_GET['profile']);
}

if (isset($_POST['search']) && strlen(trim($_POST['search'])) > 0) {
    $search = (string) $_POST['search'];
}
elseif (isset($_GET['search']) && strlen(trim($_GET['search'])) > 0) {
    $search = (string) urldecode($_GET['search']);
}

if (isset($search)) {
    $searchstring_temp = (string) $search;
    define('SEARCHSTRING',htmlentities($search));
} else {
    $searchstring_temp = (string) '';
    define('SEARCHSTRING','');
}

$lg = (object) new ChooseLanguage('','');
$sp = (object) new FormatLongString($searchstring_temp);
$dp = (object) new ChooseFormatDate();
$helpers = (object) new Helpers();

$sp->SetSessionURL(session_name(),session_id());
 // $language_select->lang_content
define('LANG_ISO',str_replace('_','-',$lg->getLang()));
$tpl->assign('LANG_ISO',LANG_ISO); 

define('HIGHLIGHT',$sp->SetURLencoder('search',urlencode($searchstring_temp),'&'));
define('SESSIONID',$sp->SetURLencoder(session_name(),session_id(),'&#38;'));
defined('CAT') ? define('LISTCAT',$sp->SetURLencoder('cat',CAT,'&#38;')) : define('LISTCAT','');
(defined('CAT') && !isset($search)) ? define('ONLY_CAT','?cat=' . CAT) : define('ONLY_CAT','');

if (isset($_POST['id'])) {
    $id = (int) $_POST['id'];
}
elseif (isset($_GET['id'])) {
    $id = (int) $_GET['id'];
}

define('ONLY_ID',((isset($id)) ? '?id=' . $id : ''));

/* Startpunkt für Auflistung */

if (isset($_POST['startlisting'])) {
    $startlisting = (int) $_POST['startlisting'];
}
elseif (isset($_GET['startlisting'])) {
    $startlisting = (int) $_GET['startlisting'];
}

if (!isset($startlisting) || $startlisting < 0) { // Falls kein Startwert für Weiter/Zurück Links gegeben bei 0 anfangen
    define('STARTLISTING',0);
}
elseif ($startlisting > SUM_NEWS) { // Falls Startwert größer als die Anzahl and Datensätzen
    define('STARTLISTING',(SUM_NEWS - SHOW_RS));
} else {
    define('STARTLISTING',$startlisting);
}

if (isset($startlisting)) {
    unset($startlisting);
} 

/*---------------------------------------*/
/* D B   A B F R A G E   D E R   N E W S */
/*---------------------------------------*/

if (isset($id) && is_int($id) && $id != 0) { // Auswahl der Abfrage zur Darstellung der News gesamt, Kategorie oder ID Nummer
    $newslist = new NewsList('onenews', STARTLISTING, SHOW_RS, $id, '', '', '');
}
elseif (defined('CAT')) {
    $newslist = new NewsList('category', STARTLISTING, SHOW_RS, CAT, '', '', '');
}
elseif (isset($search)) {
    $newslist = new NewsList('search', 0, 0, 0, AUTHOR, $search, CAT_SEARCH);
} else {
    $newslist = new NewsList('all', STARTLISTING, SHOW_RS, $id, '', '', '');
} // end if


define('SUM_NEWS',count($newslist->getNewsListCountAll()));

$tpl->assign_by_ref('PAGE_TITLE',$lg->__('newssystem_title')); 
$tpl->assign('META_EXPIRES',gmdate("D, d M Y H:i:s", time() + (60 * 60 * 20))); 
$tpl->assign_by_ref('LANG_MENU_TITLE',$lg->__('menu')); 
$tpl->assign('PICTURE_PATH',PICTUREPATH);

/*---------*/
/* M E N Ü */
/*---------*/

$categorylist = (object) new CategoryList($lg->getLangContent());
$tpl->assign('COUNT_CATEGORIES',count($categorylist->getCategoryList()));

foreach ($categorylist->getCategoryList() as $name => $value) {
    $values = (array) explode('_', $value);
    if (defined('CAT') && CAT == $values[0]) {
        $cat_name = (string) $name;
    }
    if (defined('CAT_SEARCH') && CAT_SEARCH == $values[0]) {
        $cat_name = (string) $name;
    } 
    $CategoryName = (string) $sp->FormatShortText($name);
    $menu_cat_nr[] = $values[0];
    $menu_cat_name[] = $sp->FormatShortText($name);
    $menu_cat_count[] = $values[1];
} // end foreach
  
if (isset($sum_cat_news)) {
    unset($sum_cat_news);
} 
$tpl->assign('MENU_CAT_NR',$menu_cat_nr);
$tpl->assign('MENU_CAT_NAME',$menu_cat_name);
$tpl->assign('MENU_CAT_COUNT',$menu_cat_count);
$tpl->assign_by_ref('FORM_SEARCH',$lg->__('search'));
$tpl->assign_by_ref('FORM_KEYWORD',$lg->__('form_keyword'));
$tpl->assign('FORM_SEARCHSTRING',SEARCHSTRING);
$tpl->assign('FORM_ADV_SEARCH',((isset($_GET['advsearch'])) ? 1 : 0));             
$tpl->assign_by_ref('FORM_CATEGORY',$lg->__('form_category'));             
$tpl->assign_by_ref('FORM_ALL',$lg->__('form_all')); 
$tpl->assign('FORM_CAT_SEARCH',CAT_SEARCH);
$tpl->assign_by_ref('FORM_AUTHOR',$lg->__('form_author'));
    
$authorlist = (object) new AuthorList();
$author_keys = (array) array_keys($authorlist->getAuthorList());
foreach ($author_keys as $authorID) {
    $author =& $authorlist->getAuthor($authorID);
    if ($author->getCountArticles() > 0) {
        $menu_author_nr[] = $author->getAuthorID();
        $menu_author_name[] = $sp->FormatShortText($author->getAuthorName());
        $menu_author_count[] = $author->getCountArticles();
    } // end if
  } // end foreach
if (isset($author)) {
    unset($author);
} 
if (isset($author_keys)) {
    unset($author_keys);
} 
$tpl->assign('MENU_AUTHOR_NR',$menu_author_nr);
$tpl->assign('MENU_AUTHOR_NAME',$menu_author_name);
$tpl->assign('MENU_AUTHOR_COUNT',$menu_author_count);

$tpl->assign('STARTLISTING',STARTLISTING);
$tpl->assign('SESSION_NAME',session_name());
$tpl->assign('SESSION_ID',session_id());
$tpl->assign_by_ref('FORM_SEARCH_BUTTON',$lg->__('form_search'));
$tpl->assign_by_ref('ADV_SEARCH_LINK',$lg->__('advanced_search'));
$tpl->assign_by_ref('FORM_LANGUAGE',ucfirst($lg->__('language')));
$tpl->assign_by_ref('FORM_OSM',ucfirst($lg->__('OSM')));
$tpl->assign('FORM_DROPDOWN_LANG',$lg->returnDropdownLang()); 
$tpl->assign_by_ref('FORM_LANGUAGE_CONTENT',ucfirst($lg->__('Content')));
$tpl->assign('FORM_DROPDOWN_LANG_CONTENT',$lg->returnDropdownLangContent());  
$tpl->assign_by_ref('FORM_TIME',ucfirst($lg->__('time')));
$tpl->assign('FORM_DROPDOWN_TIME',$dp->returnDropdownSelecttime()); 
$tpl->assign_by_ref('FORM_CHANGE',$lg->__('Change')); 

$tpl->assign('SUM_ALL_NEWS',$helpers->getSumNews());
$tpl->assign_by_ref('LANG_NEWS',$lg->__('news')); 
$tpl->assign('SUM_ALL_COMMENTS',$helpers->getSumComments());
$tpl->assign_by_ref('LANG_COMMENTS',$lg->__('comments'));
$tpl->assign('USER_ONLINE',user_online24());
$tpl->assign_by_ref('LANG_USER_ONLINE',$lg->__('user_online'));
$tpl->assign_by_ref('LANG_TIMEZONE_IS',$lg->__('timezone_is'));  
$tpl->assign_by_ref('LANG_SWATCHTIME',$lg->__('swatch_time')); 
if (isset($helpers)) {
    unset($helpers);
} 
if ($dp->GetTimeset() == 1) { // Falls swatch Zeit
    $tpl->assign('TIMEZONE_LINK','http://www.swatch.com/alu_beat/fs_itime.html');
    $tpl->assign('TIMEZONE','BMT');
    $tpl->assign_by_ref('LANG_TIMEZONE',$lg->__('swatch_time'));
    $tpl->assign('TIMEZONE_DATE',$dp->swatchdate());
    $tpl->assign('TIMEZONE_TIME',$dp->swatchtime());
}
elseif ($dp->GetTimeset() == 2) {
    $tpl->assign('TIMEZONE_LINK','http://www.stacken.kth.se/~kvickers/timezone.html');
    $tpl->assign('TIMEZONE',getenv('TZ'));
    $tpl->assign_by_ref('LANG_TIMEZONE',$lg->__('ISO_time'));
    $tpl->assign('TIMEZONE_DATE',$dp->ShortDate());
    $tpl->assign('TIMEZONE_TIME',$dp->TimeString());
} else {
    $tpl->assign('TIMEZONE_LINK','http://www.stacken.kth.se/~kvickers/timezone.html');
    $tpl->assign('TIMEZONE',getenv('TZ'));
    $tpl->assign_by_ref('LANG_TIMEZONE',$lg->__('time'));
    $tpl->assign('TIMEZONE_DATE',$dp->ShortDate());
    $tpl->assign('TIMEZONE_TIME',$dp->TimeString());
} // end if
  
$tpl->assign('RSS_LINK',$lg->getLangContent() . LISTCAT);  

  
/*---------------------------------*/
/* A U S G A B E   D E R   N E W S */
/*---------------------------------*/

$tpl->assign('COUNT_NEWSLIST',count($newslist->getNewsList())); 
$tpl->assign('PROFILE_DEFINED',((defined('PROFILE')) ? 1 : 0)); 
$tpl->assign('CAT_DEFINED',((defined('CAT')) ? 1 : 0)); 
$tpl->assign('CATSEARCH_DEFINED',((defined('CAT_SEARCH')) ? 1 : 0));
$tpl->assign('CATSEARCH_NOT_ALL',((CAT_SEARCH != 'all') ? 1 : 0)); 

if (!defined('PROFILE')) { // Schleife zur Auflistung der News
    if (defined('CAT') || (defined('CAT_SEARCH') && CAT_SEARCH != 'all')) {
        $tpl->assign('CAT',CAT);
        $tpl->assign('DISPLAY_CAT_HEADER',$sp->FormatShortText($cat_name)); 
    } else {
        $tpl->assign('CAT','');
        $tpl->assign('DISPLAY_CAT_HEADER',''); 
    } // end if
  
    $tpl->assign('SEARCHTERM_DEFINED',((isset($search)) ? 1 : 0));
    $tpl->assign('NEWSID_DEFINED',((isset($id)) ? 1 : 0));
    $tpl->assign_by_ref('LANG_SEARCHRESULT_FOR',$lg->__('search_result_for'));
    $tpl->assign('SEARCHTERM',$sp->FormatShortText($search));
    $tpl->assign('SUM_NEWS',SUM_NEWS);
    $tpl->assign('LANG_NEWS_FOUND',$lg->__('news_found'));
    $tpl->assign_by_ref('LANG_NEWS_POSTED_BY',ucfirst($lg->__('posted_by')));
    $tpl->assign_by_ref('LANG_LINKS',ucfirst($lg->__('links')));
    $tpl->assign_by_ref('LANG_SOURCE',$lg->__('source'));  
    $news_keys = (array) array_keys($newslist->getNewsList());
    foreach ($news_keys as $newsID) {
        $news =& $newslist->getNews($newsID);
        $css_color[] = $news->getCategoryID(); 
        $news_headline[] = $sp->FormatShortText($news->getHeadline());
        $author = (object) $news->getAuthor();
        $author_nick[] = $sp->FormatShortText($author->getAuthorName());
        $newsimage_alttext[] = $sp->FormatShortText($news->getImageAlttext());
        $no_comments[] = $news->getNoComments();
        $nocomments = (boolean) $news->getNoComments();             
        $news_image[] = $news->getImage();
        $news_date[] = $dp->LongDate($dp->ISOdatetimeToUnixtimestamp($news->getNewsdate()));
        $author_id[] = $author->getAuthorID();    
        $news_id[] = $news->getNewsID();
    
        if ((!defined('CAT') && !defined('CAT_SEARCH')) || (defined('CAT_SEARCH') && CAT_SEARCH == 'all')) {
            $category_name[] = $sp->FormatShortText($news->getCategoryName());
        } else {
            $category_name[] = '';
        } // end if
    
        if ((LASTVISIT != '') && (LASTVISIT < $news->getNewsdate())) {
            $news_new[] = '(' . strtoupper($lg->__('new')) . ')';
        } else {
            $news_new[] = '';
        } // end if

        if (isset($search)) {
            $news_text[] = $sp->SearchResult($news->getNewstext(), $news->getNewsID());
        }
        elseif (!isset($id)) { // Ausgabe Newstext
            $news_text[] = (($news->getShowFullText() != true) ? $sp->AbstractText($news->getNewstext(), $news->getNewsID()) : $sp->FormatLongText($news->getNewstext()));
        } else {
            $news_text[] = $sp->FormatLongText($news->getNewstext());
        } // end if

        $news_links_link_temp = array();
        $news_links_text_temp = array();

        if (count($news->getLinks()) > 0) { // Gibt Linkliste aus falls String vorhanden
            $show_links[] = 1;
            foreach ($news->getLinks() as $link => $linktext) {
                $news_links_link_temp[] = $link;
                $news_links_text_temp[] = $linktext;
            } // end foreach
        } else {
            $show_links[] = 0;
        } // end if
        $news_links_link[] = $news_links_link_temp;
        $news_links_text[] = $news_links_text_temp;
        $news_source_available[] = strlen(trim($news->getSource()));
        $news_source_link[] = $news->getSourceLink();
        $news_source[] = $news->getSource();
      
        if ((!isset($id)) && ($nocomments == false)) { // Anzeige der Anzahl von Kommentaren
            $commentlist =& $news->getComments();
            $count_comments[] = count($commentlist->getCommentList());
      
            if ($count_comments > 0) { // Gibt Namen der Kommentarautoren im title Tag aus.
                foreach ($commentlist->getAuthors() as $author) {
                    $comment_link_title .= (string) $sp->FormatShortText($author) . ', ';
                } // foreach
                $news_comments_by[] = $lg->__('comments_by') . ' ' . substr($comment_link_title, 0, (strlen($comment_link_title)-2)); // Letztes Kosmma entfernen
            } else {
                $news_comments_by[] = '';
            }// end if
            $news_lang_comments[] = ((count($commentlist->getCommentList()) != 1) ? $lg->__('comments') : $lg->__('comment'));
        } // end if
	} // end if [count($newslist->getNewsList()) > 0 && !defined('PROFILE')]
    
    $tpl->assign('NEWS_SHOW_LINKS',$show_links);  
    $tpl->assign('NEWS_CSS_COLOR',$css_color);
    $tpl->assign('NEWS_AUTHOR_NAME',$author_nick); 
    $tpl->assign('NEWS_DATE',$news_date);  
    $tpl->assign('NEWS_CAT_HEADER',$category_name);  
    $tpl->assign('NEWS_AUTHOR_ID',$author_id);      
    $tpl->assign('NEWS_HEADLINE',$news_headline);  
    $tpl->assign('NEWS_LANG_NEW',$news_new);      
    $tpl->assign('NEWS_IMAGE_ALTTEXT',$newsimage_alttext); 
    $tpl->assign('NEWS_IMAGE',$news_image); 
    $tpl->assign('NEWS_TEXT',$news_text); 
    $tpl->assign('NEWS_SOURCE_AVAILABLE',$news_source_available); 
    $tpl->assign('NEWS_LINKS_LINK',$news_links_link); 
    $tpl->assign('NEWS_LINKS_TEXT',$news_links_text); 
    $tpl->assign('NEWS_SOURCE_LINK',$news_source_link); 
    $tpl->assign('NEWS_SOURCE',$news_source); 
    $tpl->assign('NEWS_NO_COMMENTS',$no_comments); 
    $tpl->assign('NEWS_ID',$news_id); 
    $tpl->assign('HIGHLIGHT',HIGHLIGHT); 
    $tpl->assign('NEWS_COMMENTS_COUNT',$count_comments); 
    $tpl->assign('NEWS_COMMENTS_BY',$news_comments_by);
    $tpl->assign('NEWS_LANG_COMMENTS',$news_lang_comments);    
  
    if (isset($show_links)) { unset($show_links); }
    if (isset($css_color)) { unset($css_color); }
    if (isset($author_nick)) { unset($author_nick); }
    if (isset($news_date)) { unset($news_date); }
    if (isset($category_name)) { unset($category_name); }
    if (isset($author_id)) { unset($author_id); }
    if (isset($news_headline)) { unset($news_headline); }
    if (isset($news_new)) { unset($news_new); }
    if (isset($newsimage_alttext)) { unset($newsimage_alttext); }
    if (isset($news_image)) { unset($news_image); }
    if (isset($news_text)) { unset($news_text); }
    if (isset($news_source_available)) { unset($news_source_available); }
    if (isset($news_links_link)) { unset($news_links_link); }
    if (isset($news_links_text)) { unset($news_links_text); }
    if (isset($news_source_link)) { unset($news_source_link); }
    if (isset($news_source)) { unset($news_source); }
    if (isset($no_comments)) { unset($no_comments); }
    if (isset($news_id)) { unset($news_id); }
    if (isset($count_comments)) { unset($count_comments); }
    if (isset($news_comments_by)) { unset($news_comments_by); }
    if (isset($news_lang_comments)) { unset($news_lang_comments); } 
   
    /*---------------------------------------------------*/
    /* A U S G A B E   V O N   Z U R Ü C K / W E I T E R */
    /*---------------------------------------------------*/
    
    $prevpage = (string) (($newslist->getPrevPage() == true) ? '&laquo;&laquo; <a href="' . PHP_SELF . $sp->SetURLencoder('startlisting',(STARTLISTING - SHOW_RS)) . LISTCAT . SESSIONID . '">' . $lg->__('back') . '</a> &laquo;&laquo;' : '&nbsp;');
    $nextpage = (string) (($newslist->getNextPage() == true) ? '&raquo;&raquo; <a href="' . PHP_SELF . $sp->SetURLencoder('startlisting',(STARTLISTING + SHOW_RS)) . LISTCAT . SESSIONID . '">' . $lg->__('next') . '</a> &raquo;&raquo;' : '&nbsp;');

    if (count($newslist->getPages()) > 0) {
        $listpages = (string) $lg->__('page') . ': ';
        foreach ($newslist->getPages() as $page => $startlisting) {
            $listpages .= (string) (((($page-1) * SHOW_RS) != STARTLISTING) ? '<a href="' . PHP_SELF . '?startlisting=' . $startlisting . LISTCAT . SESSIONID . '">' . $page . '</a> ' : $page . ' ');
        } // end foreach
        if (isset($page)) { unset($page); }
        if (isset($startlisting)) { unset($startlisting); }
    } else {
        $listpages = (string) '&nbsp;';
    } // end if

    $tpl->assign('PREV_PAGE',$prevpage); 
    $tpl->assign('NEXT_PAGE',$nextpage);
    $tpl->assign('LIST_PAGES',$listpages);  
    
    if (isset($prevpage)) { unset($prevpage); }
    if (isset($listpages)) { unset($listpages); }
    if (isset($nextpage)) { unset($nextpage); }
    
    $tpl->assign('SINGLE_NEWS',((isset($id) && is_int($id) && $id != 0) ? 1 : 0));
    
    /*-------------------------------------------*/
    /* N E X T / P R E V I O U S   A R T I C L E */
    /*-------------------------------------------*/
  
    if (isset($id) && is_int($id) && $id != 0) {    
        $prev_news = $news->getPreviousNews();
        if ($prev_news != false) {
            $tpl->assign('PREV_ARTICLE','&#171; <a href="' . PHP_SELF . '?id=' . $prev_news->getNewsID() . SESSIONID . '" title="' . $prev_news->getHeadline() . '">' . $lg->__('previous_newsarticle') . '</a>');
        } else {
            $tpl->assign('PREV_ARTICLE','&nbsp;');
        } // end if
        
        $next_news = $news->getNextNews();
        if ($next_news != false) {
            $tpl->assign('NEXT_ARTICLE','<a href="' . PHP_SELF . '?id=' . $next_news->getNewsID() . SESSIONID . '" title="' . $next_news->getHeadline() . '">' . $lg->__('next_newsarticle') . '</a> &#187;');
        } else {
            $tpl->assign('NEXT_ARTICLE','&nbsp;');
        } // end if
        if (isset($prev_news)) { unset($prev_news); } 
        if (isset($next_news)) { unset($next_news); } 
      
      
        /*---------------------*/
        /* K O M M E N T A R E */
        /*---------------------*/
        
        /* Eintragen von Kommentaren */

        if (isset($_POST['commentsend']) && $user->isCommentWritten($id) == false) { // Überprüfung des Formulares/Cookies und Eintragen eines Kommentars
            $tpl->assign_by_ref('ERROR_COMMENT',$lg->__('error_comment'));
            if ($news->insertComment($_POST['comment'], $_POST['name'], $_POST['email']) == false) {
                $tpl->assign_by_ref('ERROR_COMMENT',$lg->__('error_comment'));
            } else {
                $tpl->assign('ERROR_COMMENT','');
            }// end if
        } else {
            $tpl->assign('ERROR_COMMENT','');
        } // end if
    
        /* Markieren des Newseintrages, dass Kommentar geschrieben wurde */
        if (isset($_POST['commentsend'])) { // Setzt nach dem Absenden des Formulares ein Cookie für ein paar Minuten, dass ein erneutes Kommentar verhindert (Spam Schutz)
            $user->setCommentWritten($_POST['commentsend']);
            $user->setNickname($_POST['name']);
            $user->setMail($_POST['email']);
        }
    
        $news->updateNewsRead();
        $day_old = (int) 0;
        $commentlist =& $news->getComments();
        $comments_keys = (array) array_keys($commentlist->getCommentList());
        foreach ($comments_keys as $commentID) {
            $comment =& $commentlist->getComment($commentID);
            $CommentText[] = (string) $sp->FormatLongText($comment->getCommentText());
            $CommentAuthor = (string) $sp->FormatShortText($comment->getAuthorName());
            $CommentEmail = (string) $sp->CheckMailHomepage($comment->getAuthorMail());
            $timestring_comment = (int) $dp->ISOdatetimeToUnixtimestamp($comment->getCommentDate());
            $timestring[] = $timestring_comment;
            $date_comment[] = (string) $dp->DateString($timestring_comment);
            $time_comment[] = (string) $dp->TimeString($timestring_comment);        
            $CommentEmailFormat[] = (string) (($CommentEmail != '') ? '<a href="' . $CommentEmail . '" target="_blank">' . $CommentAuthor . '</a>' : $CommentAuthor);        

            if (date('d',$timestring_comment) != $day_old) {
                $display_day[] = 1; 
            } else {
                $display_day[] = 0; 
            } // end if
            $day_old = (int) date('d',$timestring_comment);
        } // end foreach
        
        $tpl->assign_by_ref('LANG_COMMENTS',$lg->__('Comments'));  
        $tpl->assign('COMMENT_DISPLAY_DAY',$display_day);   
        $tpl->assign('COMMENT_TEXT',$CommentText);      
        $tpl->assign('COMMENT_AUTHOR_AND_MAIL',$CommentEmailFormat);
        $tpl->assign('COMMENT_DATE',$date_comment); 
        $tpl->assign('COMMENT_TIME',$time_comment); 
        $tpl->assign('COMMENT_DAY',$timestring);    
        $tpl->assign('COMMENT_DAY_OLD',$timestring);       
        $tpl->assign_by_ref('LANG_NO_COMMENTS',$lg->__('no_comments'));       
        
        if (isset($comments_keys)) { unset($comments_keys); } 
        if (isset($comment)) { unset($comment); } 
        if (isset($commentlist)) { unset($commentlist); } 
        if (isset($news)) { unset($news); }  
        if (isset($day_old)) { unset($day_old); }
        if (isset($CommentText)) { unset($CommentText); }
        if (isset($CommentAuthor)) { unset($CommentAuthor); }
        if (isset($CommentEmail)) { unset($CommentEmail); }
        if (isset($timestring_comment)) { unset($timestring_comment); }
        if (isset($timestring)) { unset($timestring); }
        if (isset($date_comment)) { unset($date_comment); }
        if (isset($time_comment)) { unset($time_comment); }
        if (isset($CommentEmailFormat)) { unset($CommentEmailFormat); }
        if (isset($display_day)) { unset($display_day); }
    
        /*-----------------*/
        /* F O R M U L A R */
        /*-----------------*/
      
        if ($user->isCommentWritten($id) == false && !isset($commentsend) && $nocomments == false) { // Formular für Kommentar
            $tpl->assign('SHOW_COMMENT_FORM',1); 
        } else {
            $tpl->assign('SHOW_COMMENT_FORM',0); 
        } // end if
      
        $tpl->assign_by_ref('FORM_WRITE_COMMENT',$lg->__('form_write_answer'));
        $tpl->assign_by_ref('FORM_NAME',$lg->__('form_name'));      
        $tpl->assign_by_ref('FORM_EMAIL',$lg->__('form_email'));        
        $tpl->assign_by_ref('FORM_OR',$lg->__('or'));     
        $tpl->assign_by_ref('FORM_HOMEPAGE',$lg->__('form_homepage')); 
        $tpl->assign_by_ref('FORM_COMMENT',$lg->__('form_comment')); 
        $tpl->assign('FORM_SESSION_NAME',$user->getNickname());      
        $tpl->assign('FORM_SESSION_EMAIL',$user->getMail());  
        
        $tpl->assign('SINGLE_NEWS_ID',$id);
        $tpl->assign_by_ref('FORM_BUTTON_SEND',$lg->__('form_send'));      
        
        if (isset($user)) { unset($user); }
        if (isset($sess_name)) { unset($sess_name); }
        if (isset($sess_email)) { unset($sess_email); }
        if (isset($id)) { unset($id); }
        if (isset($nocomments)) { unset($nocomments); }
        if (isset($commentsend)) { unset($commentsend); }
    } // end if [isset($id) && is_int($id) && $id != 0]
} else { 
    /*---------------*/
    /* P R O F I L E */
    /*---------------*/
  
    if (!defined('PROFILE') && strlen(trim($newslist->getErrorMessage())) > 0) {
        $tpl->assign_by_ref('ERROR_MESSAGE',$lg->__($newslist->getErrorMessage()));
    } else {
        $author = new Author(PROFILE);
        $authorname = (string) $sp->FormatShortText($author->getAuthorName());
        $newslist_author = (object) $author->getLastArticles();
        $authornews_keys = (array) array_keys($newslist_author->getNewsList());
        foreach ($authornews_keys as $newsID) {
            $news =& $newslist_author->getNews($newsID);
            $date_news[] = $dp->ShortDate($dp->ISOdatetimeToUnixtimestamp($news->getNewsdate()));
            $cat_id[] = $news->getCategoryID();
            $news_id[] = $news->getNewsID();
            $cat_name[] = $news->getCategoryName();
            $news_headline[] = $sp->FormatShortText($news->getHeadline());
        } // end foreach
    
        $tpl->assign('ERROR_MESSAGE','');
        $tpl->assign('LANG_PROFILE',ucfirst($lg->__('profile')));
        $tpl->assign('AUTHOR_NAME',$authorname);
        $tpl->assign('AUTHOR_PICTURE',$author->getAuthorPicture());
        $tpl->assign('AUTHOR_ABSTRACT',$sp->FormatLongText($author->getAuthorAbstract()));
        $tpl->assign('LANG_LATEST_NEWS_BY',ucfirst($lg->__('latest_news_by')));
        $tpl->assign('AUTHOR_LN_DATE',$date_news);  
        $tpl->assign('AUTHOR_LN_NEWSID',$news_id); 
        $tpl->assign('AUTHOR_LN_CATID',$cat_id);  
        $tpl->assign('AUTHOR_LN_CATNAME',$cat_name); 
        $tpl->assign('AUTHOR_LN_HEADLINE',$news_headline); 
        
        if (isset($authornews_keys)) { unset($authornews_keys); }
        if (isset($news)) { unset($news); }
        if (isset($newslist_author)) { unset($newslist_author); }
        if (isset($authorname)) { unset($authorname); }
        if (isset($author)) { unset($author); }
        if (isset($date_news)) { unset($date_news); }
        if (isset($news_id)) { unset($news_id); }
        if (isset($cat_id)) { unset($cat_id); }
        if (isset($cat_name)) { unset($cat_name); }
        if (isset($news_headline)) { unset($news_headline); }
    } // end if
} // end if
if (isset($dp)) { unset($dp); } 
if (isset($sp)) { unset($sp); } 
if (isset($lg)) { unset($lg); } 
$tpl->display('index.tpl.php');
ob_end_flush();
ob_end_clean();
?>