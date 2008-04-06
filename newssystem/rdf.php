<?php header ("content-type: text/xml");
echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" standalone=\"yes\"?>\n\n";

include('classes.inc.php'); 

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

$db = new db_class(); 
$list = $db->db_query($sql);  

if ($db->rs_num_rows($list) > 0) 
    {
    $seq = $items = '';
    while ($rs = $db->rs_fetch_assoc($list))
	    {
      $header = $rs['CategoryName']; 
      $seq .= "<rdf:li rdf:resource=\"http://www.flaimo.com/newssystem/index.php?id=" . $rs['IDNews'] . "\" />\n";
      
      $items .= "<item rdf:about=\"http://www.flaimo.com/newssystem/index.php?id=" . $rs['IDNews'] . "\">\n
                <title>" . $rs['NewsHeadline'] . "</title>\n
		            <link>http://www.flaimo.com/newssystem/index.php?id=" . $rs['IDNews'] . "</link>\n
                </item>\n";
      }
    }
    
$db->db_freeresult($list);
$db->db_close();

(isset($cat) and ($cat != '') and ($cat != ' ')) ? $header = $header : $header = 'Flaimo.com';
            
?><rdf:RDF xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#" xmlns="http://purl.org/rss/1.0/">
  <channel rdf:about="rss.php">
		<title><?php echo $header; ?></title>
    <copyright>Flaimo.com</copyright>
		<description>Die letzten News von Flaimo.com</description>
		<link>http://www.flaimo.com/</link>
		<items>
			<rdf:Seq>
        <?php echo $seq; ?>
		  </rdf:Seq>
		</items>
	</channel>
  <?php echo $items; ?>
</rdf:RDF>