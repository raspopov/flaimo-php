<?php
/*
You can change the bad words/ special words for every language seperatly.

badwordlist: Words you would like to see blurred. 

specialwords: 3 kinds of special <html> tags are available - abbr, dfn, acronym
1st dimension is the language, 2nd is the html tag, 3rd is the word that should 
be marked with the html tag from the 2nd dimension. the value is an array: 1st 
dimension here is the description. 2nd the language of the described word.
/*

/* G E R M A N */

$this->badwordlist['de'] = array('ficken','f i c k','FICK','F I C K','Ficken','Ficker','ficker','fick','Fick','Arsch lecken','arsch lecke','Muschi','Schwanz blasen','schwanz blasen','schwanz lutschen','Schwanz lutschen','Microsoft','Windows');

/* Text mit Bedeutung "dies ist eine Abk�rzung" (z.B. "z.B.") */
$this->specialwords['de']['acronym']['z.B.'] = array('zum Beispiel','de'); // Sprach-|L�nderk�rzel f�r Sprache des Acronyms
$this->specialwords['de']['acronym']['idg.'] = array('indogermanisch','de');
$this->specialwords['de']['acronym']['A.T.'] = array('Altes Testament','de');
$this->specialwords['de']['acronym']['N.T.'] = array('Neues Testament','de');
$this->specialwords['de']['acronym']['Ggs.'] = array('oder �hnliches','de');
$this->specialwords['de']['acronym']['o.�.'] = array('zum Beispiel','de');
$this->specialwords['de']['acronym']['ppa.'] = array('per procura','de');

/* Text mit der Bedeutung "dies ist eine Definition" */ 
$this->specialwords['de']['dfn']['Buffy'] = array('Sendung auf dem amerikanischen Sender The WB','de');
$this->specialwords['de']['dfn']['Fanfiction'] = array('Fiktive Geschichten von Fans f�r Fans','en');

/* Text mit Bedeutung "dies ist eine abgek�rzte Schreibweise" (z.B. "WWW") */
$this->specialwords['de']['abbr']['WWW'] = array('World Wide Web ("Weltweites Netz")','en');
$this->specialwords['de']['abbr']['FAQ'] = array('Frequently Asked Questions (Oft gestellte Fragen)','en');
$this->specialwords['de']['abbr']['FIAT'] = array('Societ� Anonima Fabbrica Italiana di Automobili Torino','it');
$this->specialwords['de']['abbr']['TGV'] = array('Train � Grande Vitesse (Hochgeschwindigkeitszug)','fr');
$this->specialwords['de']['abbr']['ETA'] = array('Euskadi Ta Askatasuna (Freiheit f�r die baskische Heimat)','eu');
$this->specialwords['de']['abbr']['DFB'] = array('Deutscher Fu�ball-Bund','de');
$this->specialwords['de']['abbr']['AIA'] = array('Asociaci�n Internacional del Autom�vil (Internationaler Automobilclub)','es');

/* A U S T R I A N */

$this->badwordlist['de_at'] = array('ficken','f i c k','FICK','F I C K','Ficken','Ficker','ficker','fick','Fick','Arsch lecken','arsch lecke','Muschi','Schwanz blasen','schwanz blasen','schwanz lutschen','Schwanz lutschen','Microsoft','Windows');

/* Text mit Bedeutung "dies ist eine Abk�rzung" (z.B. "z.B.") */
$this->specialwords['de_at']['acronym']['z.B.'] = array('zum Beispiel','de'); // Sprach-|L�nderk�rzel f�r Sprache des Acronyms
$this->specialwords['de_at']['acronym']['idg.'] = array('indogermanisch','de');
$this->specialwords['de_at']['acronym']['A.T.'] = array('Altes Testament','de');
$this->specialwords['de_at']['acronym']['N.T.'] = array('Neues Testament','de');
$this->specialwords['de_at']['acronym']['Ggs.'] = array('oder �hnliches','de');
$this->specialwords['de_at']['acronym']['o.�.'] = array('zum Beispiel','de');
$this->specialwords['de_at']['acronym']['ppa.'] = array('per procura','de');

/* Text mit der Bedeutung "dies ist eine Definition" */ 
$this->specialwords['de_at']['dfn']['Buffy'] = array('Sendung auf dem amerikanischen Sender The WB','de');
$this->specialwords['de_at']['dfn']['Fanfiction'] = array('Fiktive Geschichten von Fans f�r Fans','en');

/* Text mit Bedeutung "dies ist eine abgek�rzte Schreibweise" (z.B. "WWW") */
$this->specialwords['de_at']['abbr']['WWW'] = array('World Wide Web ("Weltweites Netz")','en');
$this->specialwords['de_at']['abbr']['FAQ'] = array('Frequently Asked Questions (Oft gestellte Fragen)','en');
$this->specialwords['de_at']['abbr']['FIAT'] = array('Societ� Anonima Fabbrica Italiana di Automobili Torino','it');
$this->specialwords['de_at']['abbr']['TGV'] = array('Train � Grande Vitesse (Hochgeschwindigkeitszug)','fr');
$this->specialwords['de_at']['abbr']['ETA'] = array('Euskadi Ta Askatasuna (Freiheit f�r die baskische Heimat)','eu');
$this->specialwords['de_at']['abbr']['DFB'] = array('Deutscher Fu�ball-Bund','de');
$this->specialwords['de_at']['abbr']['AIA'] = array('Asociaci�n Internacional del Autom�vil (Internationaler Automobilclub)','es');


/* E N G L I S H */

$this->badwordlist['en'] = array('fuck','your words here');

/* Text with the meaning "this is an abbreviation" */
$this->specialwords['en']['acronym']['c.a.d.'] = array('cash against documents','en'); // Sprach-|L�nderk�rzel f�r Sprache des Acronyms
$this->specialwords['en']['acronym']['d/s'] = array('days after sight','en');
$this->specialwords['en']['acronym']['f.a.a.'] = array('free of all average','en');
$this->specialwords['en']['acronym']['n.d.'] = array('no date','en');

/* TText with the meaning "this is a definition" */ 
$this->specialwords['en']['dfn']['Buffy'] = array('Series on the WB','en');
$this->specialwords['en']['dfn']['Fanfiction'] = array('Stories from fans for fans','en');

/* Text with meaning "this is a shortened way of writing"  */
$this->specialwords['en']['abbr']['WWW'] = array('World Wide Web','en');
$this->specialwords['en']['abbr']['FAQ'] = array('Frequently Asked Questions','en');
$this->specialwords['en']['abbr']['FIAT'] = array('Societ� Anonima Fabbrica Italiana di Automobili Torino','it');
$this->specialwords['en']['abbr']['TGV'] = array('Train � Grande Vitesse (High-speed train)','fr');
$this->specialwords['en']['abbr']['ETA'] = array('Euskadi Ta Askatasuna (Basque Homeland and Freedom)','eu');
$this->specialwords['en']['abbr']['DFB'] = array('Deutscher Fu�ball-Bund (German soccer federation)','de');
$this->specialwords['en']['abbr']['AIA'] = array('Asociaci�n Internacional del Autom�vil (International automobile club)','es');


/* S P A N I S H (beta) */

$this->badwordlist['es'] = array('cogida','your words here');

/* El texto con el significado "esto es una abreviatura"  */
$this->specialwords['es']['acronym']['a/f'] = array('a favor','es'); // Sprach-|L�nderk�rzel f�r Sprache des Acronyms
$this->specialwords['es']['acronym']['afmo.'] = array('afect�simo','es');
$this->specialwords['es']['acronym']['c.te'] = array('cuenta corriente','es');
$this->specialwords['es']['acronym']['dto.'] = array('descuento','es');
$this->specialwords['es']['acronym']['s.e.u.o.'] = array('salvo error u omisi�n','es');

/* El texto con el significado "esto es una definici�n"  */ 
$this->specialwords['es']['dfn']['Buffy'] = array('Una serie de la televisi�n','en');
$this->specialwords['es']['dfn']['Fanfiction'] = array('Historias de los ventiladores para los ventiladores','en');

/* El texto con el significado "esto es una manera de la escritura acortada"  */
$this->specialwords['es']['abbr']['WWW'] = array('World Wide Web','en');
$this->specialwords['es']['abbr']['FAQ'] = array('Frequently Asked Questions','en');
$this->specialwords['es']['abbr']['FIAT'] = array('Societ� Anonima Fabbrica Italiana di Automobili Torino','it');
$this->specialwords['es']['abbr']['TGV'] = array('Train � Grande Vitesse (Tren alto de la velocidad)','fr');
$this->specialwords['es']['abbr']['ETA'] = array('Euskadi Ta Askatasuna','eu');
$this->specialwords['es']['abbr']['DFB'] = array('Deutscher Fu�ball-Bund (Federaci�n alemana del f�tbol)','de');
$this->specialwords['es']['abbr']['AIA'] = array('Asociaci�n Internacional del Autom�vil','es');


/* F R E N C H (beta) */

$this->badwordlist['fr'] = array('baise','your words here');

/* formate un texte et signifie "ceci est une abr�viation". Et ce texte aura cette apparence chez vous. */
$this->specialwords['fr']['acronym']['a/s'] = array('aux soins','fr'); // Sprach-|L�nderk�rzel f�r Sprache des Acronyms
$this->specialwords['fr']['acronym']['c.�.d.'] = array('c�est-�-dire','fr');
$this->specialwords['fr']['acronym']['f.a.b.'] = array('franco � bord','fr');
$this->specialwords['fr']['acronym']['p.p.'] = array('par procuration','fr');

/* formate un texte et signifie "ceci est la d�finition d'un concept". Et ce texte aura cette apparence chez vous. */ 
$this->specialwords['fr']['dfn']['Buffy'] = array('Une s�rie de t�l�vision','en');
$this->specialwords['fr']['dfn']['Fanfiction'] = array('Histoires des ventilateurs pour des ventilateurs','en');

/* Le texte avec la signification "ceci est une mani�re de l'�criture raccourcie"  */
$this->specialwords['fr']['abbr']['WWW'] = array('World Wide Web','en');
$this->specialwords['fr']['abbr']['FAQ'] = array('Frequently Asked Questions (Questions Fr�quemment Pos�es)','en');
$this->specialwords['fr']['abbr']['FIAT'] = array('Societ� Anonima Fabbrica Italiana di Automobili Torino','it');
$this->specialwords['fr']['abbr']['TGV'] = array('Train � Grande Vitesse','fr');
$this->specialwords['fr']['abbr']['ETA'] = array('Euskadi Ta Askatasuna','eu');
$this->specialwords['fr']['abbr']['DFB'] = array('Deutscher Fu�ball-Bund (F�d�ration allemande du football)','de');
$this->specialwords['it']['abbr']['AIA'] = array('Asociaci�n Internacional del Autom�vil (Club international d�automobile)','es');


/* I T A L I A N (beta) */

$this->badwordlist['it'] = array('scopata','your words here');

/* Il testo con il significato "questo � un'abbreviazione"  */
$this->specialwords['it']['acronym']['ecc.'] = array('ecc�tera','it'); // Sprach-|L�nderk�rzel f�r Sprache des Acronyms
$this->specialwords['it']['acronym']['s.l.m.'] = array('sul livello del mare','it');

/* Il testo con il significato "questo � una definizione"  */ 
$this->specialwords['it']['dfn']['Buffy'] = array('Una serie della televisione','en');
$this->specialwords['it']['dfn']['Fanfiction'] = array('Storia dai ventilatori per i ventilatori','en');

/* Il testo con il significato "questo � un senso di scrittura ridotto"  */
$this->specialwords['it']['abbr']['WWW'] = array('World Wide Web','en');
$this->specialwords['it']['abbr']['FAQ'] = array('Frequently Asked Questions (Domande Frequentemente Fatte)','en');
$this->specialwords['it']['abbr']['FIAT'] = array('Societ� Anonima Fabbrica Italiana di Automobili Torino','it');
$this->specialwords['it']['abbr']['TGV'] = array('Train � Grande Vitesse (Alto treno di velocit�)','fr');
$this->specialwords['it']['abbr']['ETA'] = array('Euskadi Ta Askatasuna','eu');
$this->specialwords['it']['abbr']['DFB'] = array('Deutscher Fu�ball-Bund (Federazione tedesca di soccer)','de');
$this->specialwords['it']['abbr']['AIA'] = array('Asociaci�n Internacional del Autom�vil (Randello internazionale dell�automobile)','es');
?>
