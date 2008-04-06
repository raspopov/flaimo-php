<?php
error_reporting(E_ALL & ~E_NOTICE);
$string = 'Der Gefängnisaufseher Stanley Young kann seinen Anspruch auf Entschädigung für angebliche Verleumdungen im Internet nicht weiter juristisch verfolgen. Der Supreme Court, das oberste Gericht der USA, hat eine Berufung gegen ein Urteil vom vergangenen Jahr abgewiesen. Seinerzeit hatte ein Gericht in Virginia entschieden, Young könne nicht gegen Zeitungsberichte des Hartford Courant und New Haven Advocate aus Connecticut klagen. Young, Aufseher eines Gefängnisses in Big Stone Gap in Virginia, hatte den Zeitungen vorgeworfen, sie hätten ihn als Rassisten denunziert. Dabei geht es um Berichte über die Verlegung von Häftlingen, die Ende 1999 wegen Überbelegung von Connecticut nach Virginia überführt worden waren. In dem von Young beaufsichtigten Gefängnis gebe es menschenunwürdige Haftbedingungen, vor allem für nicht-weiße Häftlinge. Die Zeitungen hatten argumentiert, die Artikel waren für Leser in Connecticut gedacht, während Young meinte, da die Berichte Ereignisse behandelten, die in Virginia passiert sind und diese über das Internet in dem Bundesstaat nachlesbar gewesen seien, könnten seine Vorwürfe gegen die Zeitungen auch dort verhandelt werden. Ende vergangenen Jahres wollte ein Gericht in Virginia dieser Argumentation nicht folgen und wies die Klage ab.';

include('class.StringFunctions.inc.php');
$sf = new StringFunctions();

echo $sf->searchResultAbstract($string, 'iuopu');

?>
