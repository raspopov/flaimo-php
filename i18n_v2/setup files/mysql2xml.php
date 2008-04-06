<?php
error_reporting(E_ALL);
$lang = 'ru';
$link = mysql_connect("127.0.0.1", "root", "");
mysql_select_db("translator_testdb");
$result = mysql_query ('set character set utf8', $link);
$result = mysql_query ('select namespace, string, `' . $lang . '` from flp_translator order by namespace',$link);

while ($row = mysql_fetch_row ($result)) {
	$strings[$row[0]][$row[1]] = $row[2];
} // end while

foreach ($strings as $namespace => $data) {

	$str  = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' . "\n";
	$str .= '<translator>' . "\n";
	$str .= '	<locale id="' . $lang . '">' . "\n";
	$str .= '		<namespace id="' . $namespace . '">' . "\n";
	$str .= '			<meta>' . "\n";
	$str .= '				<author mail="flaimo@gmx.net">Flaimo</author>' . "\n";
	$str .= '				<created>' . date('Y-m-d H:i:s') . '</created>' . "\n";
	$str .= '				<lastchange>' . date('Y-m-d H:i:s') . '</lastchange>' . "\n";
	$str .= '			</meta>' . "\n";
	$str .= '			<translations>' . "\n";

	foreach ($data as $string => $translation)  {

	$str .= '				<translation string="' . $string . '">' . $translation . '</translation>' . "\n";

	} // end foreach

	$str .= '			</translations>' . "\n";
	$str .= '		</namespace>' . "\n";
	$str .= '	</locale>' . "\n";
	$str .= '</translator>' . "\n";


	file_put_contents ($namespace . '.xml', $str);

} // end foreach


?>