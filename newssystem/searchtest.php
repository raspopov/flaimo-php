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


$searchterm = 'zur�ckgewiesenen';
$sp = (object) new FormatLongString($searchterm);
$sp->setWalkStart(200);
$sp->setCSSHighlight('searchhighlight');

$string = 'Zur zur�ckgewiesenen Berufung der Softwarefirma Symicron gegen den Verlag Heinz Heise hat das Oberlandesgericht K�ln jetzt die schriftliche Urteilsbegr�ndung vorgelegt. Der Senat stellt darin fest, dass die Marke Explorer nicht rechtserhaltend genutzt worden ist. Damit seien nach Ablauf der gesetzlichen Benutzerschonfrist von f�nf Jahren ab Eintragung der Marke Anspr�che gegen vermeintliche Verletzungshandlungen selbst dann ausgeschlossen, wenn tats�chlich eine Verletzung vorgelegen habe. Die Berufung geht auf eine im Dezember 2001 vom Landgericht (LG) K�ln teils f�r unzul�ssig erkl�rte, teils abgewiesene Klage von Symicron gegen den Verlag zur�ck. Die Richter sollten dem Verlag Heinz Heise untersagen, die Kennzeichnung Explore2fs undoder HFVExplorer im gesch�ftlichen Verkehr f�r eine Software auf einer CD-ROM als Beilage zur Zeitschrift ct zu nutzen. Au�erdem hatte Symicron Schadensersatz f�r die Nutzung des Begriffs verlangt. Die beiden Systemtools mit den beiden Namen befanden sich auf der ct-Shareware-Kollektion 22000, die der ct-Ausgabe 1400 beilag. Hatte das LG K�ln sein Urteil im wesentlichen mit der fehlenden Verwechslungsgefahr begr�ndet, so ging das OLG einen Schritt weiter: Die Kl�gerin kann schon deswegen nicht auf ihre Marken Explorer bzw. Explora st�tzen, weil es an der erforderlichen Nutzung dieser Marken fehlt, hei�t es w�rtlich. Symicron habe ihr Zeichen Explorer nicht selbst ernsthaft genutzt. Jegliche Versuche von Symicron, beziehungsweise deren Anwalt G�nter Freiherr von Gravenreuth, die Nutzung der Marke Explorer zu belegen, schmetterte das Gericht ab. Darunter f�llt zum Beispiel ein Artikel aus der Zeitschrift Chip aus dem Jahre 1991 oder ein Schriftst�ck, das beweisen sollte, dass Symicron im Jahre 1991 unter der Bezeichnung EXPLORER II ein Dokumentenarchivierungssystem auf den Markt gebracht habe. Dieser Vortrag belegt nicht, dass eine einzelne Software im hinreichenden Ma�e unter der Bezeichnung Explorer im kennzeichenrechtlichen Sinne vertrieben worden w�re, konstatierte der Senat. Auch durch Microsoft sei die Marke Explorer nicht rechtserhaltend genutzt worden, stellte das Gericht fest. Symicron-Anwalt von Gravenreuth insistierte stets darauf, dass Symicron mit Microsoft ein Lizenzabkommen zur Nutzung der Marke geschlossen habe. Das Gericht konnte dem nicht folgen. Microsoft habe in einem Verfahren mit Symicron einen Vergleich akzeptiert, um einem langwierigen Rechtsstreit aus dem Weg zu gehen. Daraus ginge kein Fremdbenutzungswille hervor. Des weiteren spreche die Einmalzahlung von 90.000 Mark durch Microsoft f�r die zeitlich und r�umlich unbeschr�nkte Benutzung des Begriffs Explorer gegen ein Lizenzabkommen. In der Berufungsbegr�ndung hatte der Anwalt von Symicron erkl�rt, warum seine Mandantin bis dato keine ausf�hrlichen Nutzungsbeweise vorlegte. Symicron und der vertretende Anwalt von Gravenreuth seien einem Webmobbing insbesondere durch Drohungen in verschiedenen Foren von heise online ausgesetzt gewesen: Die Aussagen und Drohungen der treuen Leser der Beklagten sind an Aggressivit�t und Brutalit�t kaum noch zu �berbieten, hie� es in der Begr�ndung. Daher hatte man bisher keine Namen von Kunden, die Software von Symicron nutzen, preisgeben wollen. Auch dieser Argumentation erteilte das OLG K�ln eine Abfuhr: Die Kl�gerin kann die L�cken ihres Vortrags in diesen Punkten nicht mit dem gegen sie gef�hrten web-mobbing entschuldigen. [...] Der Senat vermisst die Angabe zu den Gr��enordnungen eines Vertriebs der Software Explorer, nicht die Anschriften von Kunden. Das Gericht hat die beantragte Revision zum Bundesgerichtshof (BGH) nicht zugelassen. Symicron kann aber �ber den Umweg einer Nichtzulassungsbeschwerde versuchen, das Verfahren dennoch vor den BGH zu bringen. (hobct)';


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

