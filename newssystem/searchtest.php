<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html>
<head>
<title>Unbenannt</title>
<style type="text/css">
<!--

.searchhighlight {
  background: red;
}

.ad {
  background: red;
}

-->
</style>

</head>

<body>
<?php

require_once('class.SelectLanguage.inc.php'); 
require_once('class.FormatLongString.inc.php'); 


$searchterm = 'zurückgewiesenen';
$sp = (object) new FormatLongString($searchterm);
$sp->setWalkStart(200);
$sp->setCSSHighlight('searchhighlight');

$string = 'Zur zurückgewiesenen Berufung der Softwarefirma Symicron gegen den Verlag Heinz Heise hat das Oberlandesgericht Köln jetzt die schriftliche Urteilsbegründung vorgelegt. Der Senat stellt darin fest, dass die Marke Explorer nicht rechtserhaltend genutzt worden ist. Damit seien nach Ablauf der gesetzlichen Benutzerschonfrist von fünf Jahren ab Eintragung der Marke Ansprüche gegen vermeintliche Verletzungshandlungen selbst dann ausgeschlossen, wenn tatsächlich eine Verletzung vorgelegen habe. Die Berufung geht auf eine im Dezember 2001 vom Landgericht (LG) Köln teils für unzulässig erklärte, teils abgewiesene Klage von Symicron gegen den Verlag zurück. Die Richter sollten dem Verlag Heinz Heise untersagen, die Kennzeichnung Explore2fs undoder HFVExplorer im geschäftlichen Verkehr für eine Software auf einer CD-ROM als Beilage zur Zeitschrift ct zu nutzen. Außerdem hatte Symicron Schadensersatz für die Nutzung des Begriffs verlangt. Die beiden Systemtools mit den beiden Namen befanden sich auf der ct-Shareware-Kollektion 22000, die der ct-Ausgabe 1400 beilag. Hatte das LG Köln sein Urteil im wesentlichen mit der fehlenden Verwechslungsgefahr begründet, so ging das OLG einen Schritt weiter: Die Klägerin kann schon deswegen nicht auf ihre Marken Explorer bzw. Explora stützen, weil es an der erforderlichen Nutzung dieser Marken fehlt, heißt es wörtlich. Symicron habe ihr Zeichen Explorer nicht selbst ernsthaft genutzt. Jegliche Versuche von Symicron, beziehungsweise deren Anwalt Günter Freiherr von Gravenreuth, die Nutzung der Marke Explorer zu belegen, schmetterte das Gericht ab. Darunter fällt zum Beispiel ein Artikel aus der Zeitschrift Chip aus dem Jahre 1991 oder ein Schriftstück, das beweisen sollte, dass Symicron im Jahre 1991 unter der Bezeichnung EXPLORER II ein Dokumentenarchivierungssystem auf den Markt gebracht habe. Dieser Vortrag belegt nicht, dass eine einzelne Software im hinreichenden Maße unter der Bezeichnung Explorer im kennzeichenrechtlichen Sinne vertrieben worden wäre, konstatierte der Senat. Auch durch Microsoft sei die Marke Explorer nicht rechtserhaltend genutzt worden, stellte das Gericht fest. Symicron-Anwalt von Gravenreuth insistierte stets darauf, dass Symicron mit Microsoft ein Lizenzabkommen zur Nutzung der Marke geschlossen habe. Das Gericht konnte dem nicht folgen. Microsoft habe in einem Verfahren mit Symicron einen Vergleich akzeptiert, um einem langwierigen Rechtsstreit aus dem Weg zu gehen. Daraus ginge kein Fremdbenutzungswille hervor. Des weiteren spreche die Einmalzahlung von 90.000 Mark durch Microsoft für die zeitlich und räumlich unbeschränkte Benutzung des Begriffs Explorer gegen ein Lizenzabkommen. In der Berufungsbegründung hatte der Anwalt von Symicron erklärt, warum seine Mandantin bis dato keine ausführlichen Nutzungsbeweise vorlegte. Symicron und der vertretende Anwalt von Gravenreuth seien einem Webmobbing insbesondere durch Drohungen in verschiedenen Foren von heise online ausgesetzt gewesen: Die Aussagen und Drohungen der treuen Leser der Beklagten sind an Aggressivität und Brutalität kaum noch zu überbieten, hieß es in der Begründung. Daher hatte man bisher keine Namen von Kunden, die Software von Symicron nutzen, preisgeben wollen. Auch dieser Argumentation erteilte das OLG Köln eine Abfuhr: Die Klägerin kann die Lücken ihres Vortrags in diesen Punkten nicht mit dem gegen sie geführten web-mobbing entschuldigen. [...] Der Senat vermisst die Angabe zu den Größenordnungen eines Vertriebs der Software Explorer, nicht die Anschriften von Kunden. Das Gericht hat die beantragte Revision zum Bundesgerichtshof (BGH) nicht zugelassen. Symicron kann aber über den Umweg einer Nichtzulassungsbeschwerde versuchen, das Verfahren dennoch vor den BGH zu bringen. (hobct)';


echo ('<br /><h2>Suchergebniss:</h2>');
echo ('<p>' . $sp->SearchResult($string,1) . '</p>');


echo ('<br /><h2>Original:</h2>');
echo ('<p>' . $sp->HighlightAds($string) . '</p>');


$temp = stristr($string, $searchterm);

$pos = strlen($string)-strlen($temp);

echo $pos;
?>


</body>
</html>

