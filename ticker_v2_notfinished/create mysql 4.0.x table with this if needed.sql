# phpMyAdmin MySQL-Dump
# version 2.5.1-rc1
# http://www.phpmyadmin.net/ (download page)
#
# Host: 127.0.0.1
# Erstellungszeit: 27. Mai 2003 um 01:36
# Server Version: 4.0.12
# PHP-Version: 4.3.0
# Datenbank: `ticker`
# --------------------------------------------------------

#
# Tabellenstruktur für Tabelle `ticker_backup`
#
# Erzeugt am: 27. Mai 2003 um 00:56
# Aktualisiert am: 27. Mai 2003 um 01:17
#

CREATE TABLE 'ticker_backup' (
  'id' int(10) NOT NULL,
  'date' int(10) default NULL,
  'message' varchar(255) default NULL,
  PRIMARY KEY  ('id')
);

