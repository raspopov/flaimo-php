<?php echo '<?xml version="1.0" encoding="iso-8859-1"?>'; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
       "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="de" lang="de">
  <head>
    <title>Title here!</title>
  </head>
  <body>
  <?php

$info = fopen('lang_main.tlf');

class parse {
	var info_array;

	function startElement($parser, $name, $attrs) {
		global $currentTag;
		$currentTag=$name;
	}

	function endElement($parser, $tag) {
		global $currentTag;
		$currentTag="";
	}

	function getInfo($parser, $data) {
		global $currentTag;
		echo $currentTag . '<br>';
		$this->info_array[$currentTag]=$data;
	}

	function parseXML($data) {
		// make the parser to get the XML
		$xml_parser = xml_parser_create();
		xml_set_object($xml_parser, &$this);
		xml_set_element_handler($xml_parser, "startElement", "endElement");
		xml_set_character_data_handler($xml_parser, "getInfo");
		xml_parse($xml_parser, $data);
		xml_parser_free($xml_parser);

		print_r($this->info_array);
	}
}

$parse = new parse();
$parse->parseXML($info);
/*
foreach ($parse->info_array as $key => $value) {
	echo $key . ': ' . $value . '<br>';
}
*/
?>


  </body>
</html>
