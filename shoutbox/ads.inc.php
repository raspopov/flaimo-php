<?php
/*
2002 (c) Michael Wimmer (flaimo@gmx.net | http://flaimo.com)
-------------------------------------------------------------------------
You can change the bad words/ special words for every language seperatly.

badwordlist: Words you would like to see blurred. 

specialwords: 3 kinds of special <html> tags are available - abbr, dfn, acronym
1st dimension is the language, 2nd is the html tag, 3rd is the word that should 
be marked with the html tag from the 2nd dimension. the value is an array: 1st 
dimension here is the description. 2nd the language of the described word.
/*

/* G E R M A N */

$this->adlist['de']['Flughafen'] = 'http://www.flughafen.com/'; 
$this->adlist['de']['Telecomunternehmen'] = 'http://www.Telecomunternehmen.com/'; 
$this->adlist['de']['Markt'] = 'http://www.Dollar.com/'; 
$this->adlist['de']['Peking'] = 'http://www.Peking.com/'; 


/* E N G L I S H */

$this->adlist['en']['Regierung'] = 'http://www.Regierung.com/'; 
$this->adlist['en']['Bluetooth'] = 'http://www.Bluetooth.com/'; 
$this->adlist['en']['Zahlen'] = 'http://www.Zahlen.com/'; 

?>
