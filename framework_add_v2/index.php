<?php
error_reporting(E_ALL & ~E_NOTICE);
$string = 'Der Gef�ngnisaufseher Stanley Young kann seinen Anspruch auf Entsch�digung f�r angebliche Verleumdungen im Internet nicht weiter juristisch verfolgen. Der Supreme Court, das oberste Gericht der USA, hat eine Berufung gegen ein Urteil vom vergangenen Jahr abgewiesen. Seinerzeit hatte ein Gericht in Virginia entschieden, Young k�nne nicht gegen Zeitungsberichte des Hartford Courant und New Haven Advocate aus Connecticut klagen. Young, Aufseher eines Gef�ngnisses in Big Stone Gap in Virginia, hatte den Zeitungen vorgeworfen, sie h�tten ihn als Rassisten denunziert. Dabei geht es um Berichte �ber die Verlegung von H�ftlingen, die Ende 1999 wegen �berbelegung von Connecticut nach Virginia �berf�hrt worden waren. In dem von Young beaufsichtigten Gef�ngnis gebe es menschenunw�rdige Haftbedingungen, vor allem f�r nicht-wei�e H�ftlinge. Die Zeitungen hatten argumentiert, die Artikel waren f�r Leser in Connecticut gedacht, w�hrend Young meinte, da die Berichte Ereignisse behandelten, die in Virginia passiert sind und diese �ber das Internet in dem Bundesstaat nachlesbar gewesen seien, k�nnten seine Vorw�rfe gegen die Zeitungen auch dort verhandelt werden. Ende vergangenen Jahres wollte ein Gericht in Virginia dieser Argumentation nicht folgen und wies die Klage ab.';

include('class.StringFunctions.inc.php');
$sf = new StringFunctions();

echo $sf->searchResultAbstract($string, 'iuopu');

?>
