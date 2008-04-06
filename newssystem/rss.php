<?php header ("content-type: text/xml");
echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" standalone=\"yes\"?>\n\n";

require_once('functions.inc.php');  
require_once('class.DBclass.inc.php'); 
require_once('class.Language.inc.php'); 
require_once('class.FormatLongString.inc.php'); 
require_once('class.FormatDate.inc.php'); 

(isset($_GET['cat'])) ? $cat = $_GET['cat'] : $cat = '';
(isset($_GET['lang'])) ? $lang = $_GET['lang'] : $lang = 'en';
(isset($_GET['limit'])) ? $limit = $_GET['limit'] : $limit = 5;
define('TBL_NEWS','tbl_news');
define('TBL_CATEGORY','tbl_category');

$sql = "SELECT " . TBL_NEWS . ".IDNews," . TBL_NEWS . ".NewsHeadline," . TBL_CATEGORY . ".CategoryName FROM " . TBL_NEWS . " LEFT JOIN " . TBL_CATEGORY . " ON (" . TBL_NEWS . ".NewsCategory = " . TBL_CATEGORY . ".IDCategory)";
if (isset($cat) and $cat != '' and $cat != ' ')
  {
  $sql .= " WHERE " . TBL_NEWS . ".NewsCategory = " . $cat;
  }
else
  {
  $sql .= " WHERE " . TBL_NEWS . ".Language = '" . $lang . "'";
  }

$sql .= " ORDER BY " . TBL_NEWS . ".NewsDate DESC LIMIT " . $limit;

$db = new DBclass(); 
$list = $db->db_query($sql);  

if ($db->rs_num_rows($list) > 0) 
    {
    $seq = $items = '';
    while ($rs = $db->rs_fetch_assoc($list))
	    {
      $header = htmlspecialchars($rs['CategoryName']); 
      $seq .= "<rdf:li rdf:resource=\"http://www.flaimo.com/newssystem/index.php?id=" . $rs['IDNews'] . "\" />\n";
      
      $items .= "<item rdf:about=\"http://www.flaimo.com/newssystem/index.php?id=" . $rs['IDNews'] . "\">\n
                <title>" . htmlspecialchars($rs['NewsHeadline']) . "</title>\n
		            <link>http://www.flaimo.com/newssystem/index.php?id=" . $rs['IDNews'] . "</link>\n
                </item>\n";
      }
    }
    
$db->db_freeresult($list);
$db->db_close();

(isset($cat) and ($cat != '') and ($cat != ' ')) ? $header = $header : $header = 'Flaimo.com';
            
?><rdf:RDF xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#" xmlns="http://purl.org/rss/1.0/">
  <channel rdf:about="http://www.flaimo.com/newssystem/rss.php">
		<title><?php echo $header; ?></title>
    <link>http://www.flaimo.com/</link>
    <copyright>Flaimo.com</copyright>
		<description>Die letzten News von Flaimo.com</description>
		<image rdf:resource="http://xml.com/universal/images/xml_tiny.gif" />
    
		<items>
			<rdf:Seq>
        <?php echo $seq; ?>
		  </rdf:Seq>
		</items>
	</channel>
  
  <image rdf:about="http://www.flaimo.com/logo.gif">
    <title>Flaimo.com</title>
    <link>http://www.flaimo.com</link>
    <url>http://www.flaimo.com/logo.gif</url>
  </image>
  
  <?php echo $items; ?>
</rdf:RDF>