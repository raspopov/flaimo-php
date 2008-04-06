/* 
SQLyog v3.02
Host - localhost : Database - translator_testdb
**************************************************************
Server version 4.0.12-nt
*/

create database if not exists `translator_testdb`;

use `translator_testdb`;

/*
Table struture for flp_translator
*/

drop table if exists `flp_translator`;
CREATE TABLE `flp_translator` (
  `namespace` varchar(255) NOT NULL default 'lang_main',
  `string` varchar(255) NOT NULL default '',
  `lastupdate` int(10) unsigned default '0',
  `en` text,
  `de` text,
  `de_at` text,
  `fr` text,
  `es` text,
  `it` text,
  `ru` text,
  PRIMARY KEY  (`string`),
  KEY `namespace` (`namespace`)
) TYPE=MyISAM;

/*
Table data for translator_testdb.flp_translator
*/

INSERT INTO `flp_translator` VALUES ('lang_classFormatDate','april',1050763783,'April','April','April','Avril','Abril','Aprile','&#1072;&#1087;&#1088;&#1077;&#1083;&#1100;');
INSERT INTO `flp_translator` VALUES ('lang_classFormatDate','august',1050763783,'Au­gust','Au­gust','Au­gust','Août','Agosto','Agosto','&#1072;&#1074;&#1075;&#1091;&#1089;&#1090;');
INSERT INTO `flp_translator` VALUES ('lang_classFormatDate','december',1050763783,'De­cem­ber','De­zem­ber','De­zem­ber','De­cem­bre','Di­ciem­bre','Di­cem­bre','&#1076;&#1077;&#1082;&#1072;&#1073;&#1088;&#1100;');
INSERT INTO `flp_translator` VALUES ('lang_classFormatDate','february',1050763783,'February','Fe­bruar','Fe­bruar','Febrier','Febrero','Feb­braio','&#1092;&#1077;&#1074;&#1088;&#1072;&#1083;&#1100;');
INSERT INTO `flp_translator` VALUES ('lang_classFormatDate','friday',1050763783,'Fri­day','Frei­tag','Frei­tag','Vendredi','Viernes','Venerdì','&#1087;&#1103;&#1090;&#1085;&#1080;&#1094;&#1072;');
INSERT INTO `flp_translator` VALUES ('lang_classFormatDate','hour',1050763783,'hours','Uhr','Uhr','heure','horas','ora','&#1095;&#1072;&#1089;');
INSERT INTO `flp_translator` VALUES ('lang_classFormatDate','ISO_time',1050763783,'ISO time','ISO Zeit','ISO Zeit','temps ISO','ISO tiempo','tempo ISO','&#1074;&#1088;&#1077;&#1084;&#1103; ISO');
INSERT INTO `flp_translator` VALUES ('lang_classFormatDate','january',1050763783,'January','Ja­nuar','Jän­ner','Jan­vier','Enero','Gennaio','&#1103;&#1085;&#1074;&#1072;&#1088;&#1100;');
INSERT INTO `flp_translator` VALUES ('lang_classFormatDate','july',1050763783,'July','Ju­li','Ju­li','Juillet','Julio','Luglio','&#1080;&#1102;&#1083;&#1100;');
INSERT INTO `flp_translator` VALUES ('lang_classFormatDate','june',1050763783,'June','Ju­ni','Ju­ni','Juin','Junio','Giugno','&#1080;&#1102;&#1085;&#1100;');
INSERT INTO `flp_translator` VALUES ('lang_classFormatDate','march',1050763783,'March','März','März','Mars','Marzo','Marzo','&#1084;&#1072;&#1088;&#1096;');
INSERT INTO `flp_translator` VALUES ('lang_classFormatDate','may',1050763783,'May','Mai','Mai','Mai','Mayo','Maggio','&#1089;&#1084;&#1086;&#1075;&#1080;&#1090;&#1077;');
INSERT INTO `flp_translator` VALUES ('lang_classFormatDate','monday',1050763783,'Mon­day','Mon­tag','Mon­tag','Lundi','Lunes','Lunedì','&#1087;&#1086;&#1085;&#1077;&#1076;&#1077;&#1083;&#1100;&#1085;&#1080;&#1082;');
INSERT INTO `flp_translator` VALUES ('lang_classFormatDate','november',1050763783,'No­vem­ber','No­vem­ber','No­vem­ber','No­vem­bre','Noviem­bre','No­vem­bre','&#1085;&#1086;&#1103;&#1073;&#1088;&#1100;');
INSERT INTO `flp_translator` VALUES ('lang_classFormatDate','october',1050763783,'Octo­ber','Ok­to­ber','Ok­to­ber','Oc­to­bre','Octu­bre','Otto­bre','&#1086;&#1082;&#1090;&#1103;&#1073;&#1088;&#1100;');
INSERT INTO `flp_translator` VALUES ('lang_classFormatDate','saturday',1050763783,'Satur­day','Sams­tag','Sams­tag','Samedi','Sábado','Sabato','&#1089;&#1091;&#1073;&#1073;&#1086;&#1090;&#1072;');
INSERT INTO `flp_translator` VALUES ('lang_classFormatDate','september',1050763783,'Sep­tem­ber','Sep­tem­ber','Sep­tem­ber','Sep­tem­bre','Septiem­bre','Set­tem­bre','&#1089;&#1077;&#1085;&#1090;&#1103;&#1073;&#1088;&#1100;');
INSERT INTO `flp_translator` VALUES ('lang_classFormatDate','standard_time',1050763783,'standard time','Standard­zeit','Standard­zeit','temps standard','hora estándar','tempo standard','&#1074;&#1088;&#1077;&#1084;&#1077;&#1085;&#1103;');
INSERT INTO `flp_translator` VALUES ('lang_classFormatDate','sunday',1050763783,'Sun­day','Sonn­tag','Sonn­tag','Di­manche','Domingo','Domenica','&#1074;&#1086;&#1089;&#1082;&#1088;&#1077;&#1089;&#1077;&#1085;&#1100;&#1077;');
INSERT INTO `flp_translator` VALUES ('lang_classFormatDate','swatch_time',1050763783,'swatch time','swatch Zeit','swatch Zeit','temps swatch','swatch tiempo','tempo swatch','&#1074;&#1088;&#1077;&#1084;&#1103; swatch');
INSERT INTO `flp_translator` VALUES ('lang_classFormatDate','thursday',1050763783,'Thurs­day','Donners­tag','Donners­tag','Jeudi','Jueves','Giovedì','&#1095;&#1077;&#1090;&#1074;&#1077;&#1088;&#1075;');
INSERT INTO `flp_translator` VALUES ('lang_classFormatDate','tuesday',1050763783,'Tues­day','Diens­tag','Diens­tag','Mardi','Martes','Martedì','&#1074;&#1090;&#1086;&#1088;&#1085;&#1080;&#1082;');
INSERT INTO `flp_translator` VALUES ('lang_classFormatDate','wednesday',1050763783,'Wednes­day','Mittwoch','Mittwoch','Mer­credi','Miércoles','Mercoledì','&#1089;&#1088;&#1077;&#1076;&#1072;');
INSERT INTO `flp_translator` VALUES ('lang_classFormatNumber','at_currency_major_long',1050763783,'Euro','Euro','Euro','Euro','Euro','Euro','Euro');
INSERT INTO `flp_translator` VALUES ('lang_classFormatNumber','at_currency_major_short',1050763783,'EUR','EUR','EUR','EUR','EUR','EUR','EUR');
INSERT INTO `flp_translator` VALUES ('lang_classFormatNumber','at_currency_major_symbol',1050763783,'€','€','€','€','€','€','€');
INSERT INTO `flp_translator` VALUES ('lang_classFormatNumber','at_currency_minor_long',1050763783,'Cent','Cent','Cent','Cent','Centavo','Centesimo','&#1062;&#1077;&#1085;&#1090;');
INSERT INTO `flp_translator` VALUES ('lang_classFormatNumber','at_currency_minor_short',1050763783,'c','c','c','c','c','c','c');
INSERT INTO `flp_translator` VALUES ('lang_classFormatNumber','at_currency_minor_symbol',1050763783,'¢','¢','¢','¢','¢','¢','¢');
INSERT INTO `flp_translator` VALUES ('lang_classFormatNumber','de_currency_major_long',1050763783,'Euro','Euro','Euro','Euro','Euro','Euro','Euro');
INSERT INTO `flp_translator` VALUES ('lang_classFormatNumber','de_currency_major_short',1050763783,'EUR','EUR','EUR','EUR','EUR','EUR','EUR');
INSERT INTO `flp_translator` VALUES ('lang_classFormatNumber','de_currency_major_symbol',1050763783,'€','€','€','€','€','€','€');
INSERT INTO `flp_translator` VALUES ('lang_classFormatNumber','de_currency_minor_long',1050763783,'Cent','Cent','Cent','Cent','Centavo','Centesimo','&#1062;&#1077;&#1085;&#1090;');
INSERT INTO `flp_translator` VALUES ('lang_classFormatNumber','de_currency_minor_short',1050763783,'c','c','c','c','c','c','c');
INSERT INTO `flp_translator` VALUES ('lang_classFormatNumber','de_currency_minor_symbol',1050763783,'¢','¢','¢','¢','¢','¢','¢');
INSERT INTO `flp_translator` VALUES ('lang_classFormatNumber','es_currency_major_long',1050763783,'Euro','Euro','Euro','Euro','Euro','Euro','Euro');
INSERT INTO `flp_translator` VALUES ('lang_classFormatNumber','es_currency_major_short',1050763783,'EUR','EUR','EUR','EUR','EUR','EUR','EUR');
INSERT INTO `flp_translator` VALUES ('lang_classFormatNumber','es_currency_major_symbol',1050763783,'€','€','€','€','€','€','€');
INSERT INTO `flp_translator` VALUES ('lang_classFormatNumber','es_currency_minor_long',1050763783,'Cent','Cent','Cent','Cent','Centavo','Centesimo','&#1062;&#1077;&#1085;&#1090;');
INSERT INTO `flp_translator` VALUES ('lang_classFormatNumber','es_currency_minor_short',1050763783,'c','c','c','c','c','c','c');
INSERT INTO `flp_translator` VALUES ('lang_classFormatNumber','es_currency_minor_symbol',1050763783,'¢','¢','¢','¢','¢','¢','¢');
INSERT INTO `flp_translator` VALUES ('lang_classFormatNumber','fr_currency_major_long',1050763783,'Euro','Euro','Euro','Euro','Euro','Euro','Euro');
INSERT INTO `flp_translator` VALUES ('lang_classFormatNumber','fr_currency_major_short',1050763783,'EUR','EUR','EUR','EUR','EUR','EUR','EUR');
INSERT INTO `flp_translator` VALUES ('lang_classFormatNumber','fr_currency_major_symbol',1050763783,'€','€','€','€','€','€','€');
INSERT INTO `flp_translator` VALUES ('lang_classFormatNumber','fr_currency_minor_long',1050763783,'Cent','Cent','Cent','Cent','Centavo','Centesimo','&#1062;&#1077;&#1085;&#1090;');
INSERT INTO `flp_translator` VALUES ('lang_classFormatNumber','fr_currency_minor_short',1050763783,'c','c','c','c','c','c','c');
INSERT INTO `flp_translator` VALUES ('lang_classFormatNumber','fr_currency_minor_symbol',1050763783,'¢','¢','¢','¢','¢','¢','¢');
INSERT INTO `flp_translator` VALUES ('lang_classFormatNumber','gb_currency_major_long',1050763783,'Pound','Pfund','Pfund','Livre','Libra','Pound','&#1060;&#1091;&#1085;&#1090;');
INSERT INTO `flp_translator` VALUES ('lang_classFormatNumber','gb_currency_major_short',1050763783,'GBP','GBP','GBP','GBP','GBP','GBP','GBP');
INSERT INTO `flp_translator` VALUES ('lang_classFormatNumber','gb_currency_major_symbol',1050763783,'£','£','£','£','£','£','£');
INSERT INTO `flp_translator` VALUES ('lang_classFormatNumber','gb_currency_minor_long',1050763783,'Pence','Pence','Pence','Pence','Pence','Pence','&#1055;&#1077;&#1085;&#1085;&#1080;&#1086;');
INSERT INTO `flp_translator` VALUES ('lang_classFormatNumber','gb_currency_minor_short',1050763783,'p','p','p','p','p','p','p');
INSERT INTO `flp_translator` VALUES ('lang_classFormatNumber','gb_currency_minor_symbol',1050763783,'p','p','p','p','p','p','p');
INSERT INTO `flp_translator` VALUES ('lang_classFormatNumber','it_currency_major_long',1050763783,'Euro','Euro','Euro','Euro','Euro','Euro','Euro');
INSERT INTO `flp_translator` VALUES ('lang_classFormatNumber','it_currency_major_short',1050763783,'EUR','EUR','EUR','EUR','EUR','EUR','EUR');
INSERT INTO `flp_translator` VALUES ('lang_classFormatNumber','it_currency_major_symbol',1050763783,'€','€','€','€','€','€','€');
INSERT INTO `flp_translator` VALUES ('lang_classFormatNumber','it_currency_minor_long',1050763783,'Cent','Cent','Cent','Cent','Centavo','Centesimo','&#1062;&#1077;&#1085;&#1090;');
INSERT INTO `flp_translator` VALUES ('lang_classFormatNumber','it_currency_minor_short',1050763783,NULL,'c','c','c','c','c','c');
INSERT INTO `flp_translator` VALUES ('lang_classFormatNumber','it_currency_minor_symbol',1050763783,'¢','¢','¢','¢','¢','¢','¢');
INSERT INTO `flp_translator` VALUES ('lang_classFormatNumber','ru_currency_major_long',1050763783,'Ruble','Rubel','Rubel','Rouble','Rublo','Ruble','&#1056;&#1091;&#1073;&#1083;&#1077;&#1074;&#1082;&#1072;');
INSERT INTO `flp_translator` VALUES ('lang_classFormatNumber','ru_currency_major_short',1050763783,'RUB','RUB','RUB','RUB','RUB','RUB','RUB');
INSERT INTO `flp_translator` VALUES ('lang_classFormatNumber','ru_currency_major_symbol',1050763783,'RUB','RUB','RUB','RUB','RUB','RUB','RUB');
INSERT INTO `flp_translator` VALUES ('lang_classFormatNumber','ru_currency_minor_long',1050763783,'Kopecks','Kopeken','Kopeken','Kopecks','Kopecks','Kopecks','&#1050;&#1086;&#1087;&#1077;&#1081;&#1082;&#1080;');
INSERT INTO `flp_translator` VALUES ('lang_classFormatNumber','sa_currency_major_long',1050763783,'Dirham','Dirham','Dirham','Dirham','Dirham','Dirham','Dirham');
INSERT INTO `flp_translator` VALUES ('lang_classFormatNumber','sa_currency_major_short',1050763783,'SAR','SAR','SAR','SAR','SAR','SAR','SAR');
INSERT INTO `flp_translator` VALUES ('lang_classFormatNumber','sa_currency_major_symbol',1050763783,'SAR','SAR','SAR','SAR','SAR','SAR','SAR');
INSERT INTO `flp_translator` VALUES ('lang_classFormatNumber','sa_currency_minor_long',1050763783,'Fils','Fils','Fils','Fils','Fils','Fils','Fils');
INSERT INTO `flp_translator` VALUES ('lang_classFormatNumber','us_currency_major_long',1050763783,'Dollar','Dollar','Dollar','Dollar','Dólar','Dollaro','&#1044;&#1086;&#1083;&#1083;&#1072;&#1088;');
INSERT INTO `flp_translator` VALUES ('lang_classFormatNumber','us_currency_major_short',1050763783,'USD','USD','USD','USD','USD','USD','USD');
INSERT INTO `flp_translator` VALUES ('lang_classFormatNumber','us_currency_major_symbol',1050763783,'$','$','$','$','$','$','$');
INSERT INTO `flp_translator` VALUES ('lang_classFormatNumber','us_currency_minor_long',1050763783,'Cent','Cent','Cent','Cent','Centavo','Centesimo','&#1062;&#1077;&#1085;&#1090;');
INSERT INTO `flp_translator` VALUES ('lang_classFormatNumber','us_currency_minor_short',1050763783,'c','c','c','c','c','c','c');
INSERT INTO `flp_translator` VALUES ('lang_classFormatNumber','us_currency_minor_symbol',1050763783,'¢','¢','¢','¢','¢','¢','¢');
INSERT INTO `flp_translator` VALUES ('lang_classMeasure','acrefd_long',1050763783,'acre feet','Acre feet','Acre feet','acre feet','acre feet','acre feet',NULL);
INSERT INTO `flp_translator` VALUES ('lang_classMeasure','acrefd_short',1050763783,'acre ft','Acre ft','Acre ft','acre ft','acre ft','acre ft',NULL);
INSERT INTO `flp_translator` VALUES ('lang_classMeasure','acre_long',1050763783,'Acre','Acre','Acre','Acre','Acre','Acre','&#1040;&#1082;&#1088;');
INSERT INTO `flp_translator` VALUES ('lang_classMeasure','acre_short',1050763783,'Acre','Acre','Acre','Acre','Acre','Acre','&#1040;&#1082;&#1088;');
INSERT INTO `flp_translator` VALUES ('lang_classMeasure','a_long',1050763783,'a','a','a','a','a','a',NULL);
INSERT INTO `flp_translator` VALUES ('lang_classMeasure','a_short',1050763783,'?Are?','?Are?','?Are?','?Are?','?Are?','?Are?',NULL);
INSERT INTO `flp_translator` VALUES ('lang_classMeasure','barrel_long',1050763783,'barrels','Barrels','Barrels','barrels','barrels','barrels',NULL);
INSERT INTO `flp_translator` VALUES ('lang_classMeasure','barrel_short',1050763783,'barrels','Barrels','Barrels','barrels','barrels','barrels',NULL);
INSERT INTO `flp_translator` VALUES ('lang_classMeasure','cg_long',1050763783,'centi­gram','Zenti­gramm','Zenti­gramm','centi­gram','centi­gram','centi­gram',NULL);
INSERT INTO `flp_translator` VALUES ('lang_classMeasure','cg_short',1050763783,'cg','cg','cg','cg','cg','cg',NULL);
INSERT INTO `flp_translator` VALUES ('lang_classMeasure','cl_long',1050763783,'centi­liter','Zenti­liter','Zenti­liter','centi­liter','centi­liter','centi­liter',NULL);
INSERT INTO `flp_translator` VALUES ('lang_classMeasure','cl_short',1050763783,'cl','cl','cl','cl','cl','cl',NULL);
INSERT INTO `flp_translator` VALUES ('lang_classMeasure','cm2_long',1050763783,'square centi­meters','Quatrat­zenti­meter','Quatrat­zenti­meter','square centi­meters','square centi­meters','square centi­meters','&#1082;&#1074;&#1072;&#1076;&#1088;&#1072;&#1090;&#1085;&#1099;&#1077; &#1089;&#1072;&#1085;&#1090;&#1080;&#1084;&#1077;&#1090;&#1088;&#1099;');
INSERT INTO `flp_translator` VALUES ('lang_classMeasure','cm2_short',1050763783,'cm²','cm²','cm²','cm²','cm²','cm²',NULL);
INSERT INTO `flp_translator` VALUES ('lang_classMeasure','cm3_long',1050763783,'cubic centi­meters','Kubik­zenti­meter','Kubik­zenti­meter','cubic centi­meters','cubic centi­meters','cubic centi­meters',NULL);
INSERT INTO `flp_translator` VALUES ('lang_classMeasure','cm3_short',1050763783,'cm³','cm³','cm³','cm³','cm³','cm³',NULL);
INSERT INTO `flp_translator` VALUES ('lang_classMeasure','cm_long',1050763783,'centi­meters','Zenti­meter','Zenti­meter','centi­meters','centi­meters','centi­meters','&#1089;&#1072;&#1085;&#1090;&#1080;&#1084;&#1077;&#1090;&#1088;&#1099;');
INSERT INTO `flp_translator` VALUES ('lang_classMeasure','cm_short',1050763783,'cm','cm','cm','cm','cm','cm',NULL);
INSERT INTO `flp_translator` VALUES ('lang_classMeasure','cuft_long',1050763783,'cubic feet','Cubic feet','Cubic feet','cubic feet','cubic feet','cubic feet',NULL);
INSERT INTO `flp_translator` VALUES ('lang_classMeasure','cuft_short',1050763783,'cu ft','cu ft','cu ft','cu ft','cu ft','cu ft',NULL);
INSERT INTO `flp_translator` VALUES ('lang_classMeasure','cuin_long',1050763783,'cubic inches','Cubic inches','Cubic inches','cubic inches','cubic inches','cubic inches',NULL);
INSERT INTO `flp_translator` VALUES ('lang_classMeasure','cuin_short',1050763783,'cu in','cu in','cu in','cu in','cu in','cu in',NULL);
INSERT INTO `flp_translator` VALUES ('lang_classMeasure','cumi_long',1050763783,'cubic miles','Cubic miles','Cubic miles','cubic miles','cubic miles','cubic miles',NULL);
INSERT INTO `flp_translator` VALUES ('lang_classMeasure','cumi_short',1050763783,'cu mi','cu mi','cu mi','cu mi','cu mi','cu mi',NULL);
INSERT INTO `flp_translator` VALUES ('lang_classMeasure','cup_long',1050763783,'cup','cup','cup','cup','cup','cup',NULL);
INSERT INTO `flp_translator` VALUES ('lang_classMeasure','cup_short',1050763783,'cup','cup','cup','cup','cup','cup',NULL);
INSERT INTO `flp_translator` VALUES ('lang_classMeasure','cuyd_long',1050763783,'cubic yards','Cubic yards','Cubic yards','cubic yards','cubic yards','cubic yards',NULL);
INSERT INTO `flp_translator` VALUES ('lang_classMeasure','cuyd_short',1050763783,'cu yd','cu yd','cu yd','cu yd','cu yd','cu yd',NULL);
INSERT INTO `flp_translator` VALUES ('lang_classMeasure','cwt_us_long',1050763783,'hundred­weight','hundred­weight','hundred­weight','hundred­weight','hundred­weight','hundred­weight',NULL);
INSERT INTO `flp_translator` VALUES ('lang_classMeasure','cwt_us_short',1050763783,'cwt','cwt','cwt','cwt','cwt','cwt',NULL);
INSERT INTO `flp_translator` VALUES ('lang_classMeasure','C_long',1050763783,'degrees celsius','Grad Celsius','Grad Celsius','degrees celsius','degrees celsius','degrees celsius',NULL);
INSERT INTO `flp_translator` VALUES ('lang_classMeasure','C_short',1050763783,'°C','°C','°C','°C','°C','°C',NULL);
INSERT INTO `flp_translator` VALUES ('lang_classMeasure','dag_long',1050763783,'deka­gram','Deka­gramm','Deka­gramm','deka­gram','deka­gram','deka­gram',NULL);
INSERT INTO `flp_translator` VALUES ('lang_classMeasure','dag_short',1050763783,'dag','dag','dag','dag','dag','dag',NULL);
INSERT INTO `flp_translator` VALUES ('lang_classMeasure','dal_long',1050763783,'deka­liter','Deka­liter','Deka­liter','deka­liter','deka­liter','deka­liter',NULL);
INSERT INTO `flp_translator` VALUES ('lang_classMeasure','dal_short',1050763783,'dal','dal','dal','dal','dal','dal',NULL);
INSERT INTO `flp_translator` VALUES ('lang_classMeasure','dg_long',1050763783,'dezi­gram','Dezi­gramm','Dezi­gramm','dezi­gram','dezi­gram','dezi­gram',NULL);
INSERT INTO `flp_translator` VALUES ('lang_classMeasure','dg_short',1050763783,'dg','dg','dg','dg','dg','dg',NULL);
INSERT INTO `flp_translator` VALUES ('lang_classMeasure','dl_long',1050763783,'dezi­liter','Dezi­liter','Dezi­liter','dezi­liter','dezi­liter','dezi­liter',NULL);
INSERT INTO `flp_translator` VALUES ('lang_classMeasure','dl_short',1050763783,'dl','dl','dl','dl','dl','dl',NULL);
INSERT INTO `flp_translator` VALUES ('lang_classMeasure','dm2_long',1050763783,'square deci­meters','Quatrat­dezi­meter','Quatrat­dezi­meter','square deci­meters','square deci­meters','square deci­meters',NULL);
INSERT INTO `flp_translator` VALUES ('lang_classMeasure','dm2_short',1050763783,'dm²','dm²','dm²','dm²','dm²','dm²',NULL);
INSERT INTO `flp_translator` VALUES ('lang_classMeasure','dm3_long',1050763783,'cubic dezi­meters','Kubik­dezi­meter','Kubik­dezi­meter','cubic dezi­meters','cubic dezi­meters','cubic dezi­meters',NULL);
INSERT INTO `flp_translator` VALUES ('lang_classMeasure','dm3_short',1050763783,'dm³','dm³','dm³','dm³','dm³','dm³',NULL);
INSERT INTO `flp_translator` VALUES ('lang_classMeasure','dm_long',1050763783,'deci­meters','Dezi­meter','Dezi­meter','deci­meters','deci­meters','deci­meters',NULL);
INSERT INTO `flp_translator` VALUES ('lang_classMeasure','dm_short',1050763783,'dm','dm','dm','dm','dm','dm',NULL);
INSERT INTO `flp_translator` VALUES ('lang_classMeasure','dr_long',1050763783,'dram','Dram','Dram','dram','dram','dram',NULL);
INSERT INTO `flp_translator` VALUES ('lang_classMeasure','dr_short',1050763783,'dr','dr','dr','dr','dr','dr',NULL);
INSERT INTO `flp_translator` VALUES ('lang_classMeasure','fldr_long',1050763783,'fluid dram','Fluid dram','Fluid dram','fluid dram','fluid dram','fluid dram',NULL);
INSERT INTO `flp_translator` VALUES ('lang_classMeasure','fldr_short',1050763783,'fl dr','fl dr','fl dr','fl dr','fl dr','fl dr',NULL);
INSERT INTO `flp_translator` VALUES ('lang_classMeasure','floz_long',1050763783,'fluid Ounces','Fluid Ounces','Fluid Ounces','fluid Ounces','fluid Ounces','fluid Ounces',NULL);
INSERT INTO `flp_translator` VALUES ('lang_classMeasure','floz_short',1050763783,'fl oz','fl oz','fl oz','fl oz','fl oz','fl oz',NULL);
INSERT INTO `flp_translator` VALUES ('lang_classMeasure','ft_long',1050763783,'feet','feet','feet','feet','feet','feet','&#1085;&#1086;&#1075;&#1080;');
INSERT INTO `flp_translator` VALUES ('lang_classMeasure','ft_short',1050763783,'ft','ft','ft','ft','ft','ft',NULL);
INSERT INTO `flp_translator` VALUES ('lang_classMeasure','fur_long',1050763783,'fur­longs','Fur­longs','Fur­longs','fur­longs','fur­longs','fur­longs',NULL);
INSERT INTO `flp_translator` VALUES ('lang_classMeasure','fur_short',1050763783,'fur','fur','fur','fur','fur','fur',NULL);
INSERT INTO `flp_translator` VALUES ('lang_classMeasure','F_long',1050763783,'degrees fahren­heit','Grad Fahren­heit','Grad Fahren­heit','degrees fahren­heit','degrees fahren­heit','degrees fahren­heit',NULL);
INSERT INTO `flp_translator` VALUES ('lang_classMeasure','F_short',1050763783,'°F','°F','°F','°F','°F','°F',NULL);
INSERT INTO `flp_translator` VALUES ('lang_classMeasure','gal_long',1050763783,'gallons','Gallons','Gallons','gallons','gallons','gallons',NULL);
INSERT INTO `flp_translator` VALUES ('lang_classMeasure','gal_short',1050763783,'gal','gal','gal','gal','gal','gal',NULL);
INSERT INTO `flp_translator` VALUES ('lang_classMeasure','gi_long',1050763783,'gill','Gill','Gill','gill','gill','gill',NULL);
INSERT INTO `flp_translator` VALUES ('lang_classMeasure','gi_short',1050763783,'gi','gi','gi','gi','gi','gi',NULL);
INSERT INTO `flp_translator` VALUES ('lang_classMeasure','grain_long',1050763783,'grain','Grain','Grain','grain','grain','grain',NULL);
INSERT INTO `flp_translator` VALUES ('lang_classMeasure','grain_short',1050763783,'grain','Grain','Grain','grain','grain','grain',NULL);
INSERT INTO `flp_translator` VALUES ('lang_classMeasure','g_long',1050763783,'gram','Gramm','Gramm','gram','gram','gram',NULL);
INSERT INTO `flp_translator` VALUES ('lang_classMeasure','g_short',1050763783,'g','g','g','g','g','g',NULL);
INSERT INTO `flp_translator` VALUES ('lang_classMeasure','ha_long',1050763783,'hectares','Hektar','Hektar','hectares','hectares','hectares','&#1075;&#1077;&#1082;&#1090;&#1072;&#1088;&#1099;');
INSERT INTO `flp_translator` VALUES ('lang_classMeasure','ha_short',1050763783,'ha','ha','ha','ha','ha','ha',NULL);
INSERT INTO `flp_translator` VALUES ('lang_classMeasure','hl_long',1050763783,'hekto­liter','Hekto­liter','Hekto­liter','hekto­liter','hekto­liter','hekto­liter',NULL);
INSERT INTO `flp_translator` VALUES ('lang_classMeasure','hl_short',1050763783,'hl','hl','hl','hl','hl','hl',NULL);
INSERT INTO `flp_translator` VALUES ('lang_classMeasure','in_long',1050763783,'inches','Inches','Inches','inches','inches','inches','&#1076;&#1102;&#1081;&#1084;&#1099;');
INSERT INTO `flp_translator` VALUES ('lang_classMeasure','in_short',1050763783,'in','in','in','in','in','in',NULL);
INSERT INTO `flp_translator` VALUES ('lang_classMeasure','kg_long',1050763783,'kilo­gram','Kilo­gramm','Kilo­gramm','kilo­gram','kilo­gram','kilo­gram',NULL);
INSERT INTO `flp_translator` VALUES ('lang_classMeasure','kg_short',1050763783,'kg','kg','kg','kg','kg','kg',NULL);
INSERT INTO `flp_translator` VALUES ('lang_classMeasure','km2_long',1050763783,'square kilo­meters','Quatrat­kilo­meter','Quatrat­kilo­meter','square kilo­meters','square kilo­meters','square kilo­meters','&#1082;&#1074;&#1072;&#1076;&#1088;&#1072;&#1090;&#1085;&#1099;&#1077; &#1082;&#1080;&#1083;&#1086;&#1084;&#1077;&#1090;&#1088;&#1099;');
INSERT INTO `flp_translator` VALUES ('lang_classMeasure','km2_short',1050763783,'km²','km²','km²','km²','km²','km²',NULL);
INSERT INTO `flp_translator` VALUES ('lang_classMeasure','km3_long',1050763783,'cubic kilo­meters','Kubik­kilo­meter','Kubik­kilo­meter','cubic kilo­meters','cubic kilo­meters','cubic kilo­meters',NULL);
INSERT INTO `flp_translator` VALUES ('lang_classMeasure','km3_short',1050763783,'km³','km³','km³','km³','km³','km³',NULL);
INSERT INTO `flp_translator` VALUES ('lang_classMeasure','km_long',1050763783,'kilo­meters','Kilo­meter','Kilo­meter','kilo­meters','kilo­meters','kilo­meters','&#1082;&#1080;&#1083;&#1086;&#1084;&#1077;&#1090;&#1088;&#1099;');
INSERT INTO `flp_translator` VALUES ('lang_classMeasure','km_short',1050763783,'km','km','km','km','km','km',NULL);
INSERT INTO `flp_translator` VALUES ('lang_classMeasure','lb_long',1050763783,'pounds','Pounds','Pounds','pounds','pounds','pounds',NULL);
INSERT INTO `flp_translator` VALUES ('lang_classMeasure','lb_short',1050763783,'lb','lb','lb','lb','lb','lb',NULL);
INSERT INTO `flp_translator` VALUES ('lang_classMeasure','l_long',1050763783,'liter','Liter','Liter','liter','liter','liter',NULL);
INSERT INTO `flp_translator` VALUES ('lang_classMeasure','l_short',1050763783,'l','l','l','l','l','l',NULL);
INSERT INTO `flp_translator` VALUES ('lang_classMeasure','m2_long',1050763783,'square meters','Quatrat­meter','Quatrat­meter','square meters','square meters','square meters','&#1082;&#1074;&#1072;&#1076;&#1088;&#1072;&#1090;&#1085;&#1099;&#1077; &#1084;&#1077;&#1090;&#1088;&#1099;');
INSERT INTO `flp_translator` VALUES ('lang_classMeasure','m2_short',1050763783,'m²','m²','m²','m²','m²','m²',NULL);
INSERT INTO `flp_translator` VALUES ('lang_classMeasure','m3_long',1050763783,'cubic meters','Kubik­meter','Kubik­meter','cubic meters','cubic meters','cubic meters',NULL);
INSERT INTO `flp_translator` VALUES ('lang_classMeasure','m3_short',1050763783,'m³','m³','m³','m³','m³','m³',NULL);
INSERT INTO `flp_translator` VALUES ('lang_classMeasure','mg_long',1050763783,'milli­gram','Milli­gramm','Milli­gramm','milli­gram','milli­gram','milli­gram',NULL);
INSERT INTO `flp_translator` VALUES ('lang_classMeasure','mg_short',1050763783,'mg','mg','mg','mg','mg','mg',NULL);
INSERT INTO `flp_translator` VALUES ('lang_classMeasure','min_long',1050763783,'minim','Minim','Minim','minim','minim','minim',NULL);
INSERT INTO `flp_translator` VALUES ('lang_classMeasure','min_short',1050763783,'min','min','min','min','min','min',NULL);
INSERT INTO `flp_translator` VALUES ('lang_classMeasure','mi_long',1050763783,'miles','Miles','Miles','miles','miles','miles','&#1084;&#1080;&#1083;&#1080;');
INSERT INTO `flp_translator` VALUES ('lang_classMeasure','mi_short',1050763783,'mi','mi','mi','mi','mi','mi',NULL);
INSERT INTO `flp_translator` VALUES ('lang_classMeasure','ml_long',1050763783,'milli­liter','Milli­liter','Milli­liter','milli­liter','milli­liter','milli­liter',NULL);
INSERT INTO `flp_translator` VALUES ('lang_classMeasure','ml_short',1050763783,'ml','ml','ml','ml','ml','ml',NULL);
INSERT INTO `flp_translator` VALUES ('lang_classMeasure','mm2_long',1050763783,'square milli­meters','Quatrat­milli­meter','Quatrat­milli­meter','square milli­meters','square milli­meters','square milli­meters','&#1082;&#1074;&#1072;&#1076;&#1088;&#1072;&#1090;&#1085;&#1099;&#1077; &#1084;&#1080;&#1083;&#1083;&#1080;&#1084;&#1077;&#1090;&#1088;&#1099;');
INSERT INTO `flp_translator` VALUES ('lang_classMeasure','mm2_short',1050763783,'mm²','mm²','mm²','mm²','mm²','mm²',NULL);
INSERT INTO `flp_translator` VALUES ('lang_classMeasure','mm3_long',1050763783,'cubic milli­meters','Kubik­milli­meter','Kubik­milli­meter','cubic milli­meters','cubic milli­meters','cubic milli­meters',NULL);
INSERT INTO `flp_translator` VALUES ('lang_classMeasure','mm3_short',1050763783,'mm³','mm³','mm³','mm³','mm³','mm³',NULL);
INSERT INTO `flp_translator` VALUES ('lang_classMeasure','mm_long',1050763783,'milli­meters','Milli­meter','Milli­meter','milli­meters','milli­meters','milli­meters','&#1084;&#1080;&#1083;&#1083;&#1080;&#1084;&#1077;&#1090;&#1088;&#1099;');
INSERT INTO `flp_translator` VALUES ('lang_classMeasure','mm_short',1050763783,'mm','mm','mm','mm','mm','mm',NULL);
INSERT INTO `flp_translator` VALUES ('lang_classMeasure','m_long',1050763783,'meters','Meter','Meter','meters','meters','meters','&#1084;&#1077;&#1090;&#1088;&#1099;');
INSERT INTO `flp_translator` VALUES ('lang_classMeasure','m_short',1050763783,'m','m','m','m','m','m',NULL);
INSERT INTO `flp_translator` VALUES ('lang_classMeasure','oz_long',1050763783,'ounces','Ounces','Ounces','ounces','ounces','ounces',NULL);
INSERT INTO `flp_translator` VALUES ('lang_classMeasure','oz_short',1050763783,'oz','oz','oz','oz','oz','oz',NULL);
INSERT INTO `flp_translator` VALUES ('lang_classMeasure','pt_long',1050763783,'pint','Pint','Pint','pint','pint','pint',NULL);
INSERT INTO `flp_translator` VALUES ('lang_classMeasure','pt_short',1050763783,'pt','pt','pt','pt','pt','pt',NULL);
INSERT INTO `flp_translator` VALUES ('lang_classMeasure','qt_long',1050763783,'quart','Quart','Quart','quart','quart','quart',NULL);
INSERT INTO `flp_translator` VALUES ('lang_classMeasure','qt_short',1050763783,'qt','qt','qt','qt','qt','qt',NULL);
INSERT INTO `flp_translator` VALUES ('lang_classMeasure','sqft_long',1050763783,'square feet','Square feet','Square feet','square feet','square feet','square feet','&#1082;&#1074;&#1072;&#1076;&#1088;&#1072;&#1090;&#1085;&#1099;&#1077; &#1085;&#1086;&#1075;&#1080;');
INSERT INTO `flp_translator` VALUES ('lang_classMeasure','sqft_short',1050763783,'sq ft','sq ft','sq ft','sq ft','sq ft','sq ft',NULL);
INSERT INTO `flp_translator` VALUES ('lang_classMeasure','sqin_long',1050763783,'square inches','Square inches','Square inches','square inches','square inches','square inches','&#1082;&#1074;&#1072;&#1076;&#1088;&#1072;&#1090;&#1085;&#1099;&#1077; &#1076;&#1102;&#1081;&#1084;&#1099;');
INSERT INTO `flp_translator` VALUES ('lang_classMeasure','sqin_short',1050763783,'sq in','sq in','sq in','sq in','sq in','sq in',NULL);
INSERT INTO `flp_translator` VALUES ('lang_classMeasure','sqmi_long',1050763783,'square miles','Square miles','Square miles','square miles','square miles','square miles','&#1082;&#1074;&#1072;&#1076;&#1088;&#1072;&#1090;&#1085;&#1099;&#1077; &#1084;&#1080;&#1083;&#1080;');
INSERT INTO `flp_translator` VALUES ('lang_classMeasure','sqmi_short',1050763783,'sq mi','sq mi','sq mi','sq mi','sq mi','sq mi',NULL);
INSERT INTO `flp_translator` VALUES ('lang_classMeasure','sqrd_long',1050763783,'square rod','Square rod','Square rod','square rod','square rod','square rod','&#1082;&#1074;&#1072;&#1076;&#1088;&#1072;&#1090;&#1085;&#1099;&#1077; rod');
INSERT INTO `flp_translator` VALUES ('lang_classMeasure','sqrd_short',1050763783,'sq rd','sq rd','sq rd','sq rd','sq rd','sq rd',NULL);
INSERT INTO `flp_translator` VALUES ('lang_classMeasure','sqyd_long',1050763783,'square yards','Square yards','Square yards','square yards','square yards','square yards','&#1082;&#1074;&#1072;&#1076;&#1088;&#1072;&#1090;&#1085;&#1099;&#1077; &#1103;&#1088;&#1076;&#1099;');
INSERT INTO `flp_translator` VALUES ('lang_classMeasure','sqyd_short',1050763783,'sq yd','sq yd','sq yd','sq yd','sq yd','sq yd',NULL);
INSERT INTO `flp_translator` VALUES ('lang_classMeasure','stone_long',1050763783,'stone','Stone','Stone','stone','stone','stone',NULL);
INSERT INTO `flp_translator` VALUES ('lang_classMeasure','stone_short',1050763783,'stone','Stone','Stone','stone','stone','stone',NULL);
INSERT INTO `flp_translator` VALUES ('lang_classMeasure','tbs_is_long',1050763783,'table­spoon','Ess­löffel','Ess­löffel','table­spoon','table­spoon','table­spoon',NULL);
INSERT INTO `flp_translator` VALUES ('lang_classMeasure','tbs_is_short',1050763783,'tbs','tbs','tbs','tbs','tbs','tbs',NULL);
INSERT INTO `flp_translator` VALUES ('lang_classMeasure','tbs_uscs_long',1050763783,'table­spoon','Ess­löffel','Ess­löffel','table­spoon','table­spoon','table­spoon',NULL);
INSERT INTO `flp_translator` VALUES ('lang_classMeasure','tbs_uscs_short',1050763783,'tbs','tbs','tbs','tbs','tbs','tbs',NULL);
INSERT INTO `flp_translator` VALUES ('lang_classMeasure','ton_is_long',1050763783,'tons','Tonnen','Tonnen','tons','tons','tons',NULL);
INSERT INTO `flp_translator` VALUES ('lang_classMeasure','ton_is_short',1050763783,'t','t','t','t','t','t',NULL);
INSERT INTO `flp_translator` VALUES ('lang_classMeasure','ton_us_long',1050763783,'tons','tons','tons','tons','tons','tons',NULL);
INSERT INTO `flp_translator` VALUES ('lang_classMeasure','ton_us_short',1050763783,'tons','tons','tons','tons','tons','tons',NULL);
INSERT INTO `flp_translator` VALUES ('lang_classMeasure','tsp_is_long',1050763783,'tea­spoon','Tee­löffel','Tee­löffel','tea­spoon','tea­spoon','tea­spoon',NULL);
INSERT INTO `flp_translator` VALUES ('lang_classMeasure','tsp_is_short',1050763783,'tsp','tsp','tsp','tsp','tsp','tsp',NULL);
INSERT INTO `flp_translator` VALUES ('lang_classMeasure','tsp_uscs_long',1050763783,'tea­spoon','Tee­löffel','Tee­löffel','tea­spoon','tea­spoon','tea­spoon',NULL);
INSERT INTO `flp_translator` VALUES ('lang_classMeasure','tsp_uscs_short',1050763783,'tsp','tsp','tsp','tsp','tsp','tsp',NULL);
INSERT INTO `flp_translator` VALUES ('lang_classMeasure','yd_long',1050763783,'yards','Yards','Yards','yards','yards','yards','&#1103;&#1088;&#1076;&#1099;');
INSERT INTO `flp_translator` VALUES ('lang_classMeasure','yd_short',1050763783,'y','y','y','y','y','y',NULL);
INSERT INTO `flp_translator` VALUES ('lang_main','de',1050763783,'Ger­man','Deutsch','Deutsch','Allemand','Alemán','Tedesco','&#1085;&#1077;&#1084;&#1077;&#1094;&#1082;&#1086;');
INSERT INTO `flp_translator` VALUES ('lang_main','de_at',1050763783,'Austrian','Öster­reichisch','Öster­reichisch','Autrichien','Austríaco','Austriaco','&#1072;&#1074;&#1089;&#1090;&#1088;&#1080;&#1081;&#1089;&#1082;&#1086;');
INSERT INTO `flp_translator` VALUES ('lang_main','en',1050763783,'English','Englisch','Englisch','Anglais','Inglés','Inglese','&#1072;&#1085;&#1075;&#1083;&#1080;&#1081;&#1089;&#1082;&#1086;');
INSERT INTO `flp_translator` VALUES ('lang_main','es',1050763783,'Spanish','Spanisch','Spanisch','Espagnol','Español','Spagnolo','&#1080;&#1089;&#1087;&#1072;&#1085;&#1089;&#1082;&#1086;');
INSERT INTO `flp_translator` VALUES ('lang_main','fr',1050763783,'French','Französisch','Französisch','Français','Francés','Francese','&#1092;&#1088;&#1072;&#1085;&#1094;&#1091;&#1079;&#1089;&#1082;&#1086;');
INSERT INTO `flp_translator` VALUES ('lang_main','it',1050763783,'Italian','Italienisch','Italienisch','Italien','Italiano','Italiano','&#1080;&#1090;&#1072;&#1083;&#1100;&#1103;&#1085;&#1089;&#1082;&#1086;');
INSERT INTO `flp_translator` VALUES ('lang_main','No',1050763783,'No','Nein','Nein','Non','No','No','&#1053;&#1077;&#1090;');
INSERT INTO `flp_translator` VALUES ('lang_main','no_records_found',1050763783,'No recordset found!','Kein Datensatz gefunden!','Kein Datensatz gefunden!',NULL,NULL,NULL,'&#1086;&#1090;&#1089;&#1091;&#1090;&#1089;&#1090;&#1074;&#1080;&#1077; &#1085;&#1072;&#1081;&#1076;&#1077;&#1085;&#1085;&#1099;&#1093; &#1087;&#1086;&#1082;&#1072;&#1079;&#1072;&#1090;&#1077;&#1083;&#1077;&#1081;');
INSERT INTO `flp_translator` VALUES ('lang_main','ru',1050763783,'Russian','Russisch','Russisch','Russe','Ruso','Russo','&#1088;&#1091;&#1089;&#1089;&#1082;&#1086;');
INSERT INTO `flp_translator` VALUES ('lang_main','switch_to',1050763783,'switch to','Wechsle zu','Wechsle zu','commutez à','cambie a','commuti a','&#1087;&#1077;&#1088;&#1077;&#1082;&#1083;&#1102;&#1095;&#1080;&#1090;&#1077; &#1082;');
INSERT INTO `flp_translator` VALUES ('lang_main','time',1050763783,'time','Zeit','Zeit','temps','hora','tempo','&#1074;&#1088;&#1077;&#1084;&#1103;');
INSERT INTO `flp_translator` VALUES ('lang_main','timezone_is',1050763783,'All times:','Zeit­zone:','Zeit­zone:','Toutes les fois:','Todas las veces:','Tutte le volte:','&#1074;&#1088;&#1077;&#1084;&#1103;&#1079;&#1086;&#1085;&#1072;');
INSERT INTO `flp_translator` VALUES ('lang_main','Yes',1050763783,'Yes','Ja','Ja','Oui','Sí','Sí','&#1044;&#1072;');

