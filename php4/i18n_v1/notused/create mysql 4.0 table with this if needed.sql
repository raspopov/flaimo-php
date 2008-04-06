# phpMyAdmin MySQL-Dump
# version 2.4.0-rc1
# http://www.phpmyadmin.net/ (download page)
#
# Host: 127.0.0.1
# Erstellungszeit: 25. Februar 2003 um 22:09
# Server Version: 4.0.4
# PHP-Version: 4.3.0
# Datenbank: `translator_testdb`
# --------------------------------------------------------

#
# Tabellenstruktur für Tabelle `flp_translator`
#

CREATE TABLE flp_translator (
  string varchar(255) NOT NULL default '',
  en text,
  de text,
  de_at text,
  fr text,
  es text,
  it text,
  ru text,
  PRIMARY KEY  (string),
  KEY string (string)
) TYPE=MyISAM;

#
# Daten für Tabelle `flp_translator`
#

INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('Yes', 'Yes', 'Ja', 'Ja', 'Oui', 'Sí', 'Sí', '&#1044;&#1072;');
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('No', 'No', 'Nein', 'Nein', 'Non', 'No', 'No', '&#1053;&#1077;&#1090;');
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('hour', 'hours', 'Uhr', 'Uhr', 'heure', 'horas', 'ora', '&#1095;&#1072;&#1089;');
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('timezone_is', 'All times:', 'Zeit­zone:', 'Zeit­zone:', 'Toutes les fois:', 'Todas las veces:', 'Tutte le volte:', '&#1074;&#1088;&#1077;&#1084;&#1103;&#1079;&#1086;&#1085;&#1072;');
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('time', 'time', 'Zeit', 'Zeit', 'temps', 'hora', 'tempo', '&#1074;&#1088;&#1077;&#1084;&#1103;');
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('switch_to', 'switch to', 'Wechsle zu', 'Wechsle zu', 'commutez à', 'cambie a', 'commuti a', '&#1087;&#1077;&#1088;&#1077;&#1082;&#1083;&#1102;&#1095;&#1080;&#1090;&#1077; &#1082;');
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('de_at', 'Austrian', 'Öster­reichisch', 'Öster­reichisch', 'Autrichien', 'Austríaco', 'Austriaco', '&#1072;&#1074;&#1089;&#1090;&#1088;&#1080;&#1081;&#1089;&#1082;&#1086;');
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('de', 'Ger­man', 'Deutsch', 'Deutsch', 'Allemand', 'Alemán', 'Tedesco', '&#1085;&#1077;&#1084;&#1077;&#1094;&#1082;&#1086;');
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('en', 'English', 'Englisch', 'Englisch', 'Anglais', 'Inglés', 'Inglese', '&#1072;&#1085;&#1075;&#1083;&#1080;&#1081;&#1089;&#1082;&#1086;');
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('es', 'Spanish', 'Spanisch', 'Spanisch', 'Espagnol', 'Español', 'Spagnolo', '&#1080;&#1089;&#1087;&#1072;&#1085;&#1089;&#1082;&#1086;');
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('fr', 'French', 'Französisch', 'Französisch', 'Français', 'Francés', 'Francese', '&#1092;&#1088;&#1072;&#1085;&#1094;&#1091;&#1079;&#1089;&#1082;&#1086;');
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('it', 'Italian', 'Italienisch', 'Italienisch', 'Italien', 'Italiano', 'Italiano', '&#1080;&#1090;&#1072;&#1083;&#1100;&#1103;&#1085;&#1089;&#1082;&#1086;');
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('no_records_found', 'No recordset found!', 'Kein Datensatz gefunden!', 'Kein Datensatz gefunden!', NULL, NULL, NULL, '&#1086;&#1090;&#1089;&#1091;&#1090;&#1089;&#1090;&#1074;&#1080;&#1077; &#1085;&#1072;&#1081;&#1076;&#1077;&#1085;&#1085;&#1099;&#1093; &#1087;&#1086;&#1082;&#1072;&#1079;&#1072;&#1090;&#1077;&#1083;&#1077;&#1081;');
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('standard_time', 'standard time', 'Standard­zeit', 'Standard­zeit', 'temps standard', 'hora estándar', 'tempo standard', '&#1074;&#1088;&#1077;&#1084;&#1077;&#1085;&#1103;');
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('swatch_time', 'swatch time', 'swatch Zeit', 'swatch Zeit', 'temps swatch', 'swatch tiempo', 'tempo swatch', '&#1074;&#1088;&#1077;&#1084;&#1103; swatch');
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('ISO_time', 'ISO time', 'ISO Zeit', 'ISO Zeit', 'temps ISO', 'ISO tiempo', 'tempo ISO', '&#1074;&#1088;&#1077;&#1084;&#1103; ISO');
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('sunday', 'Sun­day', 'Sonn­tag', 'Sonn­tag', 'Di­manche', 'Domingo', 'Domenica', '&#1074;&#1086;&#1089;&#1082;&#1088;&#1077;&#1089;&#1077;&#1085;&#1100;&#1077;');
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('monday', 'Mon­day', 'Mon­tag', 'Mon­tag', 'Lundi', 'Lunes', 'Lunedì', '&#1087;&#1086;&#1085;&#1077;&#1076;&#1077;&#1083;&#1100;&#1085;&#1080;&#1082;');
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('tuesday', 'Tues­day', 'Diens­tag', 'Diens­tag', 'Mardi', 'Martes', 'Martedì', '&#1074;&#1090;&#1086;&#1088;&#1085;&#1080;&#1082;');
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('wednesday', 'Wednes­day', 'Mittwoch', 'Mittwoch', 'Mer­credi', 'Miércoles', 'Mercoledì', '&#1089;&#1088;&#1077;&#1076;&#1072;');
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('thursday', 'Thurs­day', 'Donners­tag', 'Donners­tag', 'Jeudi', 'Jueves', 'Giovedì', '&#1095;&#1077;&#1090;&#1074;&#1077;&#1088;&#1075;');
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('friday', 'Fri­day', 'Frei­tag', 'Frei­tag', 'Vendredi', 'Viernes', 'Venerdì', '&#1087;&#1103;&#1090;&#1085;&#1080;&#1094;&#1072;');
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('saturday', 'Satur­day', 'Sams­tag', 'Sams­tag', 'Samedi', 'Sábado', 'Sabato', '&#1089;&#1091;&#1073;&#1073;&#1086;&#1090;&#1072;');
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('january', 'January', 'Ja­nuar', 'Jän­ner', 'Jan­vier', 'Enero', 'Gennaio', '&#1103;&#1085;&#1074;&#1072;&#1088;&#1100;');
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('february', 'February', 'Fe­bruar', 'Fe­bruar', 'Febrier', 'Febrero', 'Feb­braio', '&#1092;&#1077;&#1074;&#1088;&#1072;&#1083;&#1100;');
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('march', 'March', 'März', 'März', 'Mars', 'Marzo', 'Marzo', '&#1084;&#1072;&#1088;&#1096;');
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('april', 'April', 'April', 'April', 'Avril', 'Abril', 'Aprile', '&#1072;&#1087;&#1088;&#1077;&#1083;&#1100;');
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('may', 'May', 'Mai', 'Mai', 'Mai', 'Mayo', 'Maggio', '&#1089;&#1084;&#1086;&#1075;&#1080;&#1090;&#1077;');
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('june', 'June', 'Ju­ni', 'Ju­ni', 'Juin', 'Junio', 'Giugno', '&#1080;&#1102;&#1085;&#1100;');
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('july', 'July', 'Ju­li', 'Ju­li', 'Juillet', 'Julio', 'Luglio', '&#1080;&#1102;&#1083;&#1100;');
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('august', 'Au­gust', 'Au­gust', 'Au­gust', 'Août', 'Agosto', 'Agosto', '&#1072;&#1074;&#1075;&#1091;&#1089;&#1090;');
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('september', 'Sep­tem­ber', 'Sep­tem­ber', 'Sep­tem­ber', 'Sep­tem­bre', 'Septiem­bre', 'Set­tem­bre', '&#1089;&#1077;&#1085;&#1090;&#1103;&#1073;&#1088;&#1100;');
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('october', 'Octo­ber', 'Ok­to­ber', 'Ok­to­ber', 'Oc­to­bre', 'Octu­bre', 'Otto­bre', '&#1086;&#1082;&#1090;&#1103;&#1073;&#1088;&#1100;');
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('november', 'No­vem­ber', 'No­vem­ber', 'No­vem­ber', 'No­vem­bre', 'Noviem­bre', 'No­vem­bre', '&#1085;&#1086;&#1103;&#1073;&#1088;&#1100;');
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('december', 'De­cem­ber', 'De­zem­ber', 'De­zem­ber', 'De­cem­bre', 'Di­ciem­bre', 'Di­cem­bre', '&#1076;&#1077;&#1082;&#1072;&#1073;&#1088;&#1100;');
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('mm_short', 'mm', 'mm', 'mm', 'mm', 'mm', 'mm', NULL);
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('mm_long', 'milli­meters', 'Milli­meter', 'Milli­meter', 'milli­meters', 'milli­meters', 'milli­meters', '&#1084;&#1080;&#1083;&#1083;&#1080;&#1084;&#1077;&#1090;&#1088;&#1099;');
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('cm_short', 'cm', 'cm', 'cm', 'cm', 'cm', 'cm', NULL);
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('cm_long', 'centi­meters', 'Zenti­meter', 'Zenti­meter', 'centi­meters', 'centi­meters', 'centi­meters', '&#1089;&#1072;&#1085;&#1090;&#1080;&#1084;&#1077;&#1090;&#1088;&#1099;');
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('dm_short', 'dm', 'dm', 'dm', 'dm', 'dm', 'dm', NULL);
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('dm_long', 'deci­meters', 'Dezi­meter', 'Dezi­meter', 'deci­meters', 'deci­meters', 'deci­meters', NULL);
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('m_short', 'm', 'm', 'm', 'm', 'm', 'm', NULL);
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('m_long', 'meters', 'Meter', 'Meter', 'meters', 'meters', 'meters', '&#1084;&#1077;&#1090;&#1088;&#1099;');
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('km_short', 'km', 'km', 'km', 'km', 'km', 'km', NULL);
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('km_long', 'kilo­meters', 'Kilo­meter', 'Kilo­meter', 'kilo­meters', 'kilo­meters', 'kilo­meters', '&#1082;&#1080;&#1083;&#1086;&#1084;&#1077;&#1090;&#1088;&#1099;');
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('in_short', 'in', 'in', 'in', 'in', 'in', 'in', NULL);
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('in_long', 'inches', 'Inches', 'Inches', 'inches', 'inches', 'inches', '&#1076;&#1102;&#1081;&#1084;&#1099;');
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('ft_short', 'ft', 'ft', 'ft', 'ft', 'ft', 'ft', NULL);
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('ft_long', 'feet', 'feet', 'feet', 'feet', 'feet', 'feet', '&#1085;&#1086;&#1075;&#1080;');
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('yd_short', 'y', 'y', 'y', 'y', 'y', 'y', NULL);
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('yd_long', 'yards', 'Yards', 'Yards', 'yards', 'yards', 'yards', '&#1103;&#1088;&#1076;&#1099;');
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('fur_short', 'fur', 'fur', 'fur', 'fur', 'fur', 'fur', NULL);
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('fur_long', 'fur­longs', 'Fur­longs', 'Fur­longs', 'fur­longs', 'fur­longs', 'fur­longs', NULL);
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('mi_short', 'mi', 'mi', 'mi', 'mi', 'mi', 'mi', NULL);
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('mi_long', 'miles', 'Miles', 'Miles', 'miles', 'miles', 'miles', '&#1084;&#1080;&#1083;&#1080;');
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('mm2_short', 'mm²', 'mm²', 'mm²', 'mm²', 'mm²', 'mm²', NULL);
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('mm2_long', 'square milli­meters', 'Quatrat­milli­meter', 'Quatrat­milli­meter', 'square milli­meters', 'square milli­meters', 'square milli­meters', '&#1082;&#1074;&#1072;&#1076;&#1088;&#1072;&#1090;&#1085;&#1099;&#1077; &#1084;&#1080;&#1083;&#1083;&#1080;&#1084;&#1077;&#1090;&#1088;&#1099;');
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('cm2_short', 'cm²', 'cm²', 'cm²', 'cm²', 'cm²', 'cm²', NULL);
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('cm2_long', 'square centi­meters', 'Quatrat­zenti­meter', 'Quatrat­zenti­meter', 'square centi­meters', 'square centi­meters', 'square centi­meters', '&#1082;&#1074;&#1072;&#1076;&#1088;&#1072;&#1090;&#1085;&#1099;&#1077; &#1089;&#1072;&#1085;&#1090;&#1080;&#1084;&#1077;&#1090;&#1088;&#1099;');
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('dm2_short', 'dm²', 'dm²', 'dm²', 'dm²', 'dm²', 'dm²', NULL);
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('dm2_long', 'square deci­meters', 'Quatrat­dezi­meter', 'Quatrat­dezi­meter', 'square deci­meters', 'square deci­meters', 'square deci­meters', NULL);
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('m2_short', 'm²', 'm²', 'm²', 'm²', 'm²', 'm²', NULL);
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('m2_long', 'square meters', 'Quatrat­meter', 'Quatrat­meter', 'square meters', 'square meters', 'square meters', '&#1082;&#1074;&#1072;&#1076;&#1088;&#1072;&#1090;&#1085;&#1099;&#1077; &#1084;&#1077;&#1090;&#1088;&#1099;');
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('a_short', '?Are?', '?Are?', '?Are?', '?Are?', '?Are?', '?Are?', NULL);
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('a_long', 'a', 'a', 'a', 'a', 'a', 'a', NULL);
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('ha_short', 'ha', 'ha', 'ha', 'ha', 'ha', 'ha', NULL);
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('ha_long', 'hectares', 'Hektar', 'Hektar', 'hectares', 'hectares', 'hectares', '&#1075;&#1077;&#1082;&#1090;&#1072;&#1088;&#1099;');
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('km2_short', 'km²', 'km²', 'km²', 'km²', 'km²', 'km²', NULL);
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('km2_long', 'square kilo­meters', 'Quatrat­kilo­meter', 'Quatrat­kilo­meter', 'square kilo­meters', 'square kilo­meters', 'square kilo­meters', '&#1082;&#1074;&#1072;&#1076;&#1088;&#1072;&#1090;&#1085;&#1099;&#1077; &#1082;&#1080;&#1083;&#1086;&#1084;&#1077;&#1090;&#1088;&#1099;');
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('sqin_short', 'sq in', 'sq in', 'sq in', 'sq in', 'sq in', 'sq in', NULL);
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('sqin_long', 'square inches', 'Square inches', 'Square inches', 'square inches', 'square inches', 'square inches', '&#1082;&#1074;&#1072;&#1076;&#1088;&#1072;&#1090;&#1085;&#1099;&#1077; &#1076;&#1102;&#1081;&#1084;&#1099;');
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('sqft_short', 'sq ft', 'sq ft', 'sq ft', 'sq ft', 'sq ft', 'sq ft', NULL);
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('sqft_long', 'square feet', 'Square feet', 'Square feet', 'square feet', 'square feet', 'square feet', '&#1082;&#1074;&#1072;&#1076;&#1088;&#1072;&#1090;&#1085;&#1099;&#1077; &#1085;&#1086;&#1075;&#1080;');
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('sqyd_short', 'sq yd', 'sq yd', 'sq yd', 'sq yd', 'sq yd', 'sq yd', NULL);
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('sqyd_long', 'square yards', 'Square yards', 'Square yards', 'square yards', 'square yards', 'square yards', '&#1082;&#1074;&#1072;&#1076;&#1088;&#1072;&#1090;&#1085;&#1099;&#1077; &#1103;&#1088;&#1076;&#1099;');
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('sqrd_short', 'sq rd', 'sq rd', 'sq rd', 'sq rd', 'sq rd', 'sq rd', NULL);
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('sqrd_long', 'square rod', 'Square rod', 'Square rod', 'square rod', 'square rod', 'square rod', '&#1082;&#1074;&#1072;&#1076;&#1088;&#1072;&#1090;&#1085;&#1099;&#1077; rod');
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('acre_short', 'Acre', 'Acre', 'Acre', 'Acre', 'Acre', 'Acre', '&#1040;&#1082;&#1088;');
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('acre_long', 'Acre', 'Acre', 'Acre', 'Acre', 'Acre', 'Acre', '&#1040;&#1082;&#1088;');
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('sqmi_short', 'sq mi', 'sq mi', 'sq mi', 'sq mi', 'sq mi', 'sq mi', NULL);
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('sqmi_long', 'square miles', 'Square miles', 'Square miles', 'square miles', 'square miles', 'square miles', '&#1082;&#1074;&#1072;&#1076;&#1088;&#1072;&#1090;&#1085;&#1099;&#1077; &#1084;&#1080;&#1083;&#1080;');
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('mm3_short', 'mm³', 'mm³', 'mm³', 'mm³', 'mm³', 'mm³', NULL);
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('mm3_long', 'cubic milli­meters', 'Kubik­milli­meter', 'Kubik­milli­meter', 'cubic milli­meters', 'cubic milli­meters', 'cubic milli­meters', NULL);
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('cm3_short', 'cm³', 'cm³', 'cm³', 'cm³', 'cm³', 'cm³', NULL);
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('cm3_long', 'cubic centi­meters', 'Kubik­zenti­meter', 'Kubik­zenti­meter', 'cubic centi­meters', 'cubic centi­meters', 'cubic centi­meters', NULL);
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('dm3_short', 'dm³', 'dm³', 'dm³', 'dm³', 'dm³', 'dm³', NULL);
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('dm3_long', 'cubic dezi­meters', 'Kubik­dezi­meter', 'Kubik­dezi­meter', 'cubic dezi­meters', 'cubic dezi­meters', 'cubic dezi­meters', NULL);
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('m3_short', 'm³', 'm³', 'm³', 'm³', 'm³', 'm³', NULL);
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('m3_long', 'cubic meters', 'Kubik­meter', 'Kubik­meter', 'cubic meters', 'cubic meters', 'cubic meters', NULL);
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('km3_short', 'km³', 'km³', 'km³', 'km³', 'km³', 'km³', NULL);
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('km3_long', 'cubic kilo­meters', 'Kubik­kilo­meter', 'Kubik­kilo­meter', 'cubic kilo­meters', 'cubic kilo­meters', 'cubic kilo­meters', NULL);
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('cuin_short', 'cu in', 'cu in', 'cu in', 'cu in', 'cu in', 'cu in', NULL);
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('cuin_long', 'cubic inches', 'Cubic inches', 'Cubic inches', 'cubic inches', 'cubic inches', 'cubic inches', NULL);
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('cuft_short', 'cu ft', 'cu ft', 'cu ft', 'cu ft', 'cu ft', 'cu ft', NULL);
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('cuft_long', 'cubic feet', 'Cubic feet', 'Cubic feet', 'cubic feet', 'cubic feet', 'cubic feet', NULL);
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('cuyd_short', 'cu yd', 'cu yd', 'cu yd', 'cu yd', 'cu yd', 'cu yd', NULL);
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('cuyd_long', 'cubic yards', 'Cubic yards', 'Cubic yards', 'cubic yards', 'cubic yards', 'cubic yards', NULL);
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('acrefd_short', 'acre ft', 'Acre ft', 'Acre ft', 'acre ft', 'acre ft', 'acre ft', NULL);
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('acrefd_long', 'acre feet', 'Acre feet', 'Acre feet', 'acre feet', 'acre feet', 'acre feet', NULL);
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('cumi_short', 'cu mi', 'cu mi', 'cu mi', 'cu mi', 'cu mi', 'cu mi', NULL);
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('cumi_long', 'cubic miles', 'Cubic miles', 'Cubic miles', 'cubic miles', 'cubic miles', 'cubic miles', NULL);
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('tsp_is_short', 'tsp', 'tsp', 'tsp', 'tsp', 'tsp', 'tsp', NULL);
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('tsp_is_long', 'tea­spoon', 'Tee­löffel', 'Tee­löffel', 'tea­spoon', 'tea­spoon', 'tea­spoon', NULL);
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('tbs_is_short', 'tbs', 'tbs', 'tbs', 'tbs', 'tbs', 'tbs', NULL);
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('tbs_is_long', 'table­spoon', 'Ess­löffel', 'Ess­löffel', 'table­spoon', 'table­spoon', 'table­spoon', NULL);
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('tsp_uscs_short', 'tsp', 'tsp', 'tsp', 'tsp', 'tsp', 'tsp', NULL);
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('tsp_uscs_long', 'tea­spoon', 'Tee­löffel', 'Tee­löffel', 'tea­spoon', 'tea­spoon', 'tea­spoon', NULL);
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('tbs_uscs_short', 'tbs', 'tbs', 'tbs', 'tbs', 'tbs', 'tbs', NULL);
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('tbs_uscs_long', 'table­spoon', 'Ess­löffel', 'Ess­löffel', 'table­spoon', 'table­spoon', 'table­spoon', NULL);
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('cup_short', 'cup', 'cup', 'cup', 'cup', 'cup', 'cup', NULL);
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('cup_long', 'cup', 'cup', 'cup', 'cup', 'cup', 'cup', NULL);
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('mg_short', 'mg', 'mg', 'mg', 'mg', 'mg', 'mg', NULL);
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('mg_long', 'milli­gram', 'Milli­gramm', 'Milli­gramm', 'milli­gram', 'milli­gram', 'milli­gram', NULL);
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('cg_short', 'cg', 'cg', 'cg', 'cg', 'cg', 'cg', NULL);
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('cg_long', 'centi­gram', 'Zenti­gramm', 'Zenti­gramm', 'centi­gram', 'centi­gram', 'centi­gram', NULL);
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('dg_short', 'dg', 'dg', 'dg', 'dg', 'dg', 'dg', NULL);
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('dg_long', 'dezi­gram', 'Dezi­gramm', 'Dezi­gramm', 'dezi­gram', 'dezi­gram', 'dezi­gram', NULL);
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('g_short', 'g', 'g', 'g', 'g', 'g', 'g', NULL);
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('g_long', 'gram', 'Gramm', 'Gramm', 'gram', 'gram', 'gram', NULL);
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('dag_short', 'dag', 'dag', 'dag', 'dag', 'dag', 'dag', NULL);
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('dag_long', 'deka­gram', 'Deka­gramm', 'Deka­gramm', 'deka­gram', 'deka­gram', 'deka­gram', NULL);
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('kg_short', 'kg', 'kg', 'kg', 'kg', 'kg', 'kg', NULL);
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('kg_long', 'kilo­gram', 'Kilo­gramm', 'Kilo­gramm', 'kilo­gram', 'kilo­gram', 'kilo­gram', NULL);
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('ton_is_short', 't', 't', 't', 't', 't', 't', NULL);
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('ton_is_long', 'tons', 'Tonnen', 'Tonnen', 'tons', 'tons', 'tons', NULL);
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('grain_short', 'grain', 'Grain', 'Grain', 'grain', 'grain', 'grain', NULL);
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('grain_long', 'grain', 'Grain', 'Grain', 'grain', 'grain', 'grain', NULL);
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('dr_short', 'dr', 'dr', 'dr', 'dr', 'dr', 'dr', NULL);
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('dr_long', 'dram', 'Dram', 'Dram', 'dram', 'dram', 'dram', NULL);
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('oz_short', 'oz', 'oz', 'oz', 'oz', 'oz', 'oz', NULL);
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('oz_long', 'ounces', 'Ounces', 'Ounces', 'ounces', 'ounces', 'ounces', NULL);
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('lb_short', 'lb', 'lb', 'lb', 'lb', 'lb', 'lb', NULL);
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('lb_long', 'pounds', 'Pounds', 'Pounds', 'pounds', 'pounds', 'pounds', NULL);
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('stone_short', 'stone', 'Stone', 'Stone', 'stone', 'stone', 'stone', NULL);
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('stone_long', 'stone', 'Stone', 'Stone', 'stone', 'stone', 'stone', NULL);
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('cwt_us_short', 'cwt', 'cwt', 'cwt', 'cwt', 'cwt', 'cwt', NULL);
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('cwt_us_long', 'hundred­weight', 'hundred­weight', 'hundred­weight', 'hundred­weight', 'hundred­weight', 'hundred­weight', NULL);
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('ton_us_short', 'tons', 'tons', 'tons', 'tons', 'tons', 'tons', NULL);
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('ton_us_long', 'tons', 'tons', 'tons', 'tons', 'tons', 'tons', NULL);
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('ml_short', 'ml', 'ml', 'ml', 'ml', 'ml', 'ml', NULL);
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('ml_long', 'milli­liter', 'Milli­liter', 'Milli­liter', 'milli­liter', 'milli­liter', 'milli­liter', NULL);
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('cl_short', 'cl', 'cl', 'cl', 'cl', 'cl', 'cl', NULL);
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('cl_long', 'centi­liter', 'Zenti­liter', 'Zenti­liter', 'centi­liter', 'centi­liter', 'centi­liter', NULL);
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('dl_short', 'dl', 'dl', 'dl', 'dl', 'dl', 'dl', NULL);
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('dl_long', 'dezi­liter', 'Dezi­liter', 'Dezi­liter', 'dezi­liter', 'dezi­liter', 'dezi­liter', NULL);
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('l_short', 'l', 'l', 'l', 'l', 'l', 'l', NULL);
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('l_long', 'liter', 'Liter', 'Liter', 'liter', 'liter', 'liter', NULL);
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('dal_short', 'dal', 'dal', 'dal', 'dal', 'dal', 'dal', NULL);
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('dal_long', 'deka­liter', 'Deka­liter', 'Deka­liter', 'deka­liter', 'deka­liter', 'deka­liter', NULL);
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('hl_short', 'hl', 'hl', 'hl', 'hl', 'hl', 'hl', NULL);
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('hl_long', 'hekto­liter', 'Hekto­liter', 'Hekto­liter', 'hekto­liter', 'hekto­liter', 'hekto­liter', NULL);
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('min_short', 'min', 'min', 'min', 'min', 'min', 'min', NULL);
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('min_long', 'minim', 'Minim', 'Minim', 'minim', 'minim', 'minim', NULL);
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('fldr_short', 'fl dr', 'fl dr', 'fl dr', 'fl dr', 'fl dr', 'fl dr', NULL);
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('fldr_long', 'fluid dram', 'Fluid dram', 'Fluid dram', 'fluid dram', 'fluid dram', 'fluid dram', NULL);
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('floz_short', 'fl oz', 'fl oz', 'fl oz', 'fl oz', 'fl oz', 'fl oz', NULL);
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('floz_long', 'fluid Ounces', 'Fluid Ounces', 'Fluid Ounces', 'fluid Ounces', 'fluid Ounces', 'fluid Ounces', NULL);
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('gi_short', 'gi', 'gi', 'gi', 'gi', 'gi', 'gi', NULL);
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('gi_long', 'gill', 'Gill', 'Gill', 'gill', 'gill', 'gill', NULL);
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('pt_short', 'pt', 'pt', 'pt', 'pt', 'pt', 'pt', NULL);
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('pt_long', 'pint', 'Pint', 'Pint', 'pint', 'pint', 'pint', NULL);
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('qt_short', 'qt', 'qt', 'qt', 'qt', 'qt', 'qt', NULL);
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('qt_long', 'quart', 'Quart', 'Quart', 'quart', 'quart', 'quart', NULL);
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('gal_short', 'gal', 'gal', 'gal', 'gal', 'gal', 'gal', NULL);
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('gal_long', 'gallons', 'Gallons', 'Gallons', 'gallons', 'gallons', 'gallons', NULL);
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('barrel_short', 'barrels', 'Barrels', 'Barrels', 'barrels', 'barrels', 'barrels', NULL);
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('barrel_long', 'barrels', 'Barrels', 'Barrels', 'barrels', 'barrels', 'barrels', NULL);
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('C_short', '°C', '°C', '°C', '°C', '°C', '°C', NULL);
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('C_long', 'degrees celsius', 'Grad Celsius', 'Grad Celsius', 'degrees celsius', 'degrees celsius', 'degrees celsius', NULL);
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('F_short', '°F', '°F', '°F', '°F', '°F', '°F', NULL);
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('F_long', 'degrees fahren­heit', 'Grad Fahren­heit', 'Grad Fahren­heit', 'degrees fahren­heit', 'degrees fahren­heit', 'degrees fahren­heit', NULL);
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('ru', 'Russian', 'Russisch', 'Russisch', 'Russe', 'Ruso', 'Russo', '&#1088;&#1091;&#1089;&#1089;&#1082;&#1086;');
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('at_currency_major_long', 'Euro', 'Euro', 'Euro', 'Euro', 'Euro', 'Euro', 'Euro');
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('at_currency_major_short', 'EUR', 'EUR', 'EUR', 'EUR', 'EUR', 'EUR', 'EUR');
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('at_currency_minor_long', 'Cent', 'Cent', 'Cent', 'Cent', 'Centavo', 'Centesimo', '&#1062;&#1077;&#1085;&#1090;');
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('at_currency_minor_short', 'c', 'c', 'c', 'c', 'c', 'c', 'c');
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('at_currency_major_symbol', '€', '€', '€', '€', '€', '€', '€');
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('at_currency_minor_symbol', '¢', '¢', '¢', '¢', '¢', '¢', '¢');
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('de_currency_major_long', 'Euro', 'Euro', 'Euro', 'Euro', 'Euro', 'Euro', 'Euro');
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('de_currency_major_short', 'EUR', 'EUR', 'EUR', 'EUR', 'EUR', 'EUR', 'EUR');
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('de_currency_minor_long', 'Cent', 'Cent', 'Cent', 'Cent', 'Centavo', 'Centesimo', '&#1062;&#1077;&#1085;&#1090;');
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('de_currency_minor_short', 'c', 'c', 'c', 'c', 'c', 'c', 'c');
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('de_currency_major_symbol', '€', '€', '€', '€', '€', '€', '€');
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('de_currency_minor_symbol', '¢', '¢', '¢', '¢', '¢', '¢', '¢');
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('it_currency_major_long', 'Euro', 'Euro', 'Euro', 'Euro', 'Euro', 'Euro', 'Euro');
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('it_currency_major_short', 'EUR', 'EUR', 'EUR', 'EUR', 'EUR', 'EUR', 'EUR');
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('it_currency_minor_long', 'Cent', 'Cent', 'Cent', 'Cent', 'Centavo', 'Centesimo', '&#1062;&#1077;&#1085;&#1090;');
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('it_currency_minor_short', NULL, 'c', 'c', 'c', 'c', 'c', 'c');
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('it_currency_major_symbol', '€', '€', '€', '€', '€', '€', '€');
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('it_currency_minor_symbol', '¢', '¢', '¢', '¢', '¢', '¢', '¢');
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('fr_currency_major_long', 'Euro', 'Euro', 'Euro', 'Euro', 'Euro', 'Euro', 'Euro');
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('fr_currency_major_short', 'EUR', 'EUR', 'EUR', 'EUR', 'EUR', 'EUR', 'EUR');
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('fr_currency_minor_long', 'Cent', 'Cent', 'Cent', 'Cent', 'Centavo', 'Centesimo', '&#1062;&#1077;&#1085;&#1090;');
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('fr_currency_minor_short', 'c', 'c', 'c', 'c', 'c', 'c', 'c');
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('fr_currency_major_symbol', '€', '€', '€', '€', '€', '€', '€');
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('fr_currency_minor_symbol', '¢', '¢', '¢', '¢', '¢', '¢', '¢');
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('es_currency_major_long', 'Euro', 'Euro', 'Euro', 'Euro', 'Euro', 'Euro', 'Euro');
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('es_currency_major_short', 'EUR', 'EUR', 'EUR', 'EUR', 'EUR', 'EUR', 'EUR');
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('es_currency_minor_long', 'Cent', 'Cent', 'Cent', 'Cent', 'Centavo', 'Centesimo', '&#1062;&#1077;&#1085;&#1090;');
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('es_currency_minor_short', 'c', 'c', 'c', 'c', 'c', 'c', 'c');
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('es_currency_major_symbol', '€', '€', '€', '€', '€', '€', '€');
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('es_currency_minor_symbol', '¢', '¢', '¢', '¢', '¢', '¢', '¢');
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('gb_currency_major_long', 'Pound', 'Pfund', 'Pfund', 'Livre', 'Libra', 'Pound', '&#1060;&#1091;&#1085;&#1090;');
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('gb_currency_major_short', 'GBP', 'GBP', 'GBP', 'GBP', 'GBP', 'GBP', 'GBP');
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('gb_currency_minor_long', 'Pence', 'Pence', 'Pence', 'Pence', 'Pence', 'Pence', '&#1055;&#1077;&#1085;&#1085;&#1080;&#1086;');
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('gb_currency_minor_short', 'p', 'p', 'p', 'p', 'p', 'p', 'p');
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('gb_currency_major_symbol', '£', '£', '£', '£', '£', '£', '£');
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('gb_currency_minor_symbol', 'p', 'p', 'p', 'p', 'p', 'p', 'p');
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('us_currency_major_long', 'Dollar', 'Dollar', 'Dollar', 'Dollar', 'Dólar', 'Dollaro', '&#1044;&#1086;&#1083;&#1083;&#1072;&#1088;');
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('us_currency_major_short', 'USD', 'USD', 'USD', 'USD', 'USD', 'USD', 'USD');
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('us_currency_minor_long', 'Cent', 'Cent', 'Cent', 'Cent', 'Centavo', 'Centesimo', '&#1062;&#1077;&#1085;&#1090;');
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('us_currency_minor_short', 'c', 'c', 'c', 'c', 'c', 'c', 'c');
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('us_currency_major_symbol', '$', '$', '$', '$', '$', '$', '$');
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('us_currency_minor_symbol', '¢', '¢', '¢', '¢', '¢', '¢', '¢');
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('sa_currency_major_long', 'Dirham', 'Dirham', 'Dirham', 'Dirham', 'Dirham', 'Dirham', 'Dirham');
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('sa_currency_major_short', 'SAR', 'SAR', 'SAR', 'SAR', 'SAR', 'SAR', 'SAR');
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('sa_currency_minor_long', 'Fils', 'Fils', 'Fils', 'Fils', 'Fils', 'Fils', 'Fils');
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('sa_currency_major_symbol', 'SAR', 'SAR', 'SAR', 'SAR', 'SAR', 'SAR', 'SAR');
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('ru_currency_major_long', 'Ruble', 'Rubel', 'Rubel', 'Rouble', 'Rublo', 'Ruble', '&#1056;&#1091;&#1073;&#1083;&#1077;&#1074;&#1082;&#1072;');
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('ru_currency_major_short', 'RUB', 'RUB', 'RUB', 'RUB', 'RUB', 'RUB', 'RUB');
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('ru_currency_minor_long', 'Kopecks', 'Kopeken', 'Kopeken', 'Kopecks', 'Kopecks', 'Kopecks', '&#1050;&#1086;&#1087;&#1077;&#1081;&#1082;&#1080;');
INSERT INTO flp_translator (string, en, de, de_at, fr, es, it, ru) VALUES ('ru_currency_major_symbol', 'RUB', 'RUB', 'RUB', 'RUB', 'RUB', 'RUB', 'RUB');

