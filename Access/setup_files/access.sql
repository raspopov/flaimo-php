/*
SQLyog Community Edition- MySQL GUI v5.2 Beta 4
Host - 5.0.24-community-nt : Database - access
*********************************************************************
Server version : 5.0.24-community-nt
*/

SET NAMES utf8;

SET SQL_MODE='';

create database if not exists `access`;

USE `access`;

SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO';

/*Table structure for table `aces` */

DROP TABLE IF EXISTS `aces`;

CREATE TABLE `aces` (
  `id` smallint(5) unsigned NOT NULL auto_increment,
  `acl` tinyint(3) unsigned NOT NULL,
  `group` tinyint(3) unsigned NOT NULL,
  `position` tinyint(3) unsigned NOT NULL,
  `rights` set('Read','Change','Add','Delete') NOT NULL,
  `from` int(10) unsigned NOT NULL default '0',
  `until` int(10) unsigned NOT NULL default '2147483646',
  `order` tinyint(3) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `order` (`order`),
  KEY `ace_list` (`acl`,`from`,`until`),
  CONSTRAINT `aces_ibfk_1` FOREIGN KEY (`acl`) REFERENCES `acls` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 PACK_KEYS=1 DELAY_KEY_WRITE=1;

/*Data for the table `aces` */

insert  into `aces`(`id`,`acl`,`group`,`position`,`rights`,`from`,`until`,`order`) values (1,1,4,5,'Read,Change,Add,Delete',0,2147483646,0),(2,2,4,5,'Read,Change,Add,Delete',0,2147483646,0),(3,2,2,3,'Read,Change,Add,Delete',0,2147483646,0),(4,2,3,4,'Read,Add',0,2147483646,0),(5,2,2,1,'Read,Change',0,2147483646,0),(6,1,2,3,'Read',4,5,6),(7,1,2,2,'Read,Change',0,4,5);

/*Table structure for table `acls` */

DROP TABLE IF EXISTS `acls`;

CREATE TABLE `acls` (
  `id` tinyint(3) unsigned NOT NULL auto_increment,
  `name` varchar(60) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 MAX_ROWS=300 PACK_KEYS=1 DELAY_KEY_WRITE=1;

/*Data for the table `acls` */

insert  into `acls`(`id`,`name`) values (1,'ACL für Administratives'),(2,'ACL für Finanzen');

/*Table structure for table `base` */

DROP TABLE IF EXISTS `base`;

CREATE TABLE `base` (
  `id` mediumint(8) unsigned NOT NULL auto_increment COMMENT 'Die User ID',
  `username` varchar(32) NOT NULL,
  `password` varchar(32) NOT NULL,
  `status` tinyint(3) unsigned NOT NULL default '2',
  `identifier` varchar(32) default NULL,
  `token` varchar(32) default NULL,
  `timeout` int(10) unsigned default NULL COMMENT 'Wann soll der Login ablaufen',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `login` (`status`,`username`,`password`),
  UNIQUE KEY `check_logged_in` (`status`,`identifier`,`token`),
  CONSTRAINT `base_ibfk_1` FOREIGN KEY (`status`) REFERENCES `status` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 PACK_KEYS=1 DELAY_KEY_WRITE=1 COMMENT='Basisdaten der User für den Login';

/*Data for the table `base` */

insert  into `base`(`id`,`username`,`password`,`status`,`identifier`,`token`,`timeout`) values (1,'ABCDEF','40cc62e16f1c4a0f876880fed507d1a6',1,'0d863df884b69f7b8e4c76a18bb937be','192dd1e3196fdfdf891bc17d55094a33',17100);

/*Table structure for table `extended` */

DROP TABLE IF EXISTS `extended`;

CREATE TABLE `extended` (
  `user` mediumint(8) unsigned NOT NULL,
  `firstname` varchar(200) default NULL,
  `lastname` varchar(200) default NULL,
  `email` varchar(255) NOT NULL,
  `address` varchar(200) default NULL,
  `zip` varchar(10) default NULL,
  `town` varchar(200) default NULL,
  `country` smallint(4) unsigned NOT NULL default '0' COMMENT 'see foreign table',
  `phone` varchar(50) default NULL,
  PRIMARY KEY  (`user`),
  KEY `email` (`email`),
  CONSTRAINT `extended_ibfk_1` FOREIGN KEY (`user`) REFERENCES `base` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 PACK_KEYS=1 DELAY_KEY_WRITE=1;

/*Data for the table `extended` */

insert  into `extended`(`user`,`firstname`,`lastname`,`email`,`address`,`zip`,`town`,`country`,`phone`) values (1,'AAAAAA','Min','rtgedfuoiooit7zrr99@wt3uiuuiuzizor.com','Pinselweg 12','','',0,'');

/*Table structure for table `groups` */

DROP TABLE IF EXISTS `groups`;

CREATE TABLE `groups` (
  `id` tinyint(3) unsigned NOT NULL auto_increment,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 MAX_ROWS=300 PACK_KEYS=1 DELAY_KEY_WRITE=1;

/*Data for the table `groups` */

insert  into `groups`(`id`,`name`) values (1,'All'),(2,'Finance'),(3,'Support'),(4,'Admin');

/*Table structure for table `history_base` */

DROP TABLE IF EXISTS `history_base`;

CREATE TABLE `history_base` (
  `id` mediumint(8) unsigned NOT NULL COMMENT 'Die User ID',
  `username` varchar(32) NOT NULL,
  `password` varchar(32) NOT NULL,
  `status` tinyint(3) unsigned NOT NULL default '2',
  `identifier` varchar(32) default NULL,
  `token` varchar(32) default NULL,
  `timeout` int(10) unsigned default NULL COMMENT 'Wann soll der Login ablaufen',
  `date` int(10) unsigned NOT NULL,
  `ip` int(10) unsigned NOT NULL,
  KEY `id` (`id`),
  KEY `date` (`date`),
  CONSTRAINT `history_base_ibfk_1` FOREIGN KEY (`id`) REFERENCES `base` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 PACK_KEYS=1 DELAY_KEY_WRITE=1 COMMENT='Basisdaten der User für den Login';

/*Data for the table `history_base` */

insert  into `history_base`(`id`,`username`,`password`,`status`,`identifier`,`token`,`timeout`,`date`,`ip`) values (1,'ABCDEF','40cc62e16f1c4a0f876880fed507d1a6',1,'0d863df884b69f7b8e4c76a18bb937be','192dd1e3196fdfdf891bc17d55094a33',17100,1159778789,2130706433),(1,'ABCDEF','40cc62e16f1c4a0f876880fed507d1a6',1,'0d863df884b69f7b8e4c76a18bb937be','192dd1e3196fdfdf891bc17d55094a33',17100,1159778810,2130706433);

/*Table structure for table `history_extended` */

DROP TABLE IF EXISTS `history_extended`;

CREATE TABLE `history_extended` (
  `user` mediumint(8) unsigned NOT NULL,
  `firstname` varchar(200) default NULL,
  `lastname` varchar(200) default NULL,
  `email` varchar(255) NOT NULL,
  `address` varchar(200) default NULL,
  `zip` varchar(10) default NULL,
  `town` varchar(200) default NULL,
  `country` smallint(4) unsigned NOT NULL default '0' COMMENT 'see foreign table',
  `phone` varchar(50) default NULL,
  `date` int(10) unsigned NOT NULL,
  `ip` int(10) unsigned NOT NULL,
  KEY `user` (`user`),
  KEY `date` (`date`),
  CONSTRAINT `history_extended_ibfk_1` FOREIGN KEY (`user`) REFERENCES `base` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 PACK_KEYS=1 DELAY_KEY_WRITE=1;

/*Data for the table `history_extended` */

/*Table structure for table `logins` */

DROP TABLE IF EXISTS `logins`;

CREATE TABLE `logins` (
  `user` int(10) unsigned NOT NULL,
  `date` int(10) unsigned NOT NULL default '0',
  `ip` int(10) unsigned NOT NULL default '1',
  KEY `user` (`user`),
  KEY `date` (`date`),
  KEY `ip` (`ip`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 PACK_KEYS=1 DELAY_KEY_WRITE=1;

/*Data for the table `logins` */

insert  into `logins`(`user`,`date`,`ip`) values (1,1159530463,2130706433),(1,1159530472,2130706433),(1,1159530473,2130706433),(1,1159530474,2130706433),(1,1159530505,2130706433),(1,1159530506,2130706433);

/*Table structure for table `meta` */

DROP TABLE IF EXISTS `meta`;

CREATE TABLE `meta` (
  `user` mediumint(8) unsigned NOT NULL,
  `last_login_date` int(11) unsigned NOT NULL,
  `last_login_ip` int(11) unsigned NOT NULL COMMENT 'use INET_ATON/NET_NTOA',
  `last_change_date` int(11) unsigned NOT NULL,
  `last_change_ip` int(11) unsigned NOT NULL COMMENT 'use INET_ATON/NET_NTOA',
  `registration_date` int(11) unsigned NOT NULL,
  `registration_ip` int(11) unsigned NOT NULL COMMENT 'use INET_ATON/NET_NTOA',
  `confirmation_token` varchar(32) default NULL,
  PRIMARY KEY  (`user`),
  UNIQUE KEY `confirmation_token` (`confirmation_token`),
  CONSTRAINT `meta_ibfk_1` FOREIGN KEY (`user`) REFERENCES `base` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 PACK_KEYS=1 DELAY_KEY_WRITE=1 COMMENT='Metadaten des Users';

/*Data for the table `meta` */

insert  into `meta`(`user`,`last_login_date`,`last_login_ip`,`last_change_date`,`last_change_ip`,`registration_date`,`registration_ip`,`confirmation_token`) values (1,1159530506,2130706433,1159778810,2130706433,1158749012,1,'aace77bc9ef9d13ab9806a14e08ee34a');

/*Table structure for table `positions` */

DROP TABLE IF EXISTS `positions`;

CREATE TABLE `positions` (
  `id` tinyint(3) unsigned NOT NULL auto_increment,
  `name` varchar(30) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 MAX_ROWS=300 PACK_KEYS=1 DELAY_KEY_WRITE=1;

/*Data for the table `positions` */

insert  into `positions`(`id`,`name`) values (1,'All'),(2,'Moderator'),(3,'Manager'),(4,'Member'),(5,'Administrator');

/*Table structure for table `roles` */

DROP TABLE IF EXISTS `roles`;

CREATE TABLE `roles` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `user` mediumint(8) unsigned NOT NULL,
  `group` tinyint(3) unsigned NOT NULL,
  `position` tinyint(3) unsigned NOT NULL,
  `from` int(10) unsigned NOT NULL default '0',
  `until` int(10) unsigned NOT NULL default '2147483646',
  `order` tinyint(3) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `order` (`order`),
  KEY `role_list` (`user`,`from`,`until`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 PACK_KEYS=1 DELAY_KEY_WRITE=1;

/*Data for the table `roles` */

insert  into `roles`(`id`,`user`,`group`,`position`,`from`,`until`,`order`) values (1,1,2,1,0,2000000000,0),(3,1,1,1,0,2147483646,1),(4,1,1,1,0,2147483646,1),(5,1,1,1,0,2147483646,1),(6,1,1,1,0,2147483647,1),(7,1,1,1,0,2147483647,1),(8,1,1,1,4,2147483647,1),(9,1,1,1,4,2147483647,1),(10,1,1,1,4,1147483649,1),(11,1,1,1,0,1147483649,1),(12,1,1,2,3,4,5),(13,1,1,2,3,4,5);

/*Table structure for table `status` */

DROP TABLE IF EXISTS `status`;

CREATE TABLE `status` (
  `id` tinyint(3) unsigned NOT NULL auto_increment,
  `name` varchar(20) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 MAX_ROWS=300 PACK_KEYS=1 DELAY_KEY_WRITE=1;

/*Data for the table `status` */

insert  into `status`(`id`,`name`) values (1,'Active'),(2,'Inactive'),(3,'Locked Permanently'),(4,'Locked Temporary'),(5,'Confirm Registration'),(6,'Confirm Email');

/* Procedure structure for procedure `addACE` */

drop procedure if exists `addACE`;

DELIMITER $$

CREATE DEFINER=`root`@`localhost` PROCEDURE `addACE`(IN a_id INT, IN a_group INT, IN a_pos INT, IN a_rights VARCHAR(255), IN a_from INT, IN a_until INT, IN a_order INT)
BEGIN
DECLARE ac_from, ac_until INT;
IF a_from < 0 THEN
SET ac_from = 0;
ELSE
SET ac_from = a_from;
END IF;
IF a_until > 2147483646 THEN
SET ac_until = 2147483646;
ELSE
SET ac_until = a_until;
END IF;
IF ac_from > ac_until THEN
SET ac_from = ac_until-1;
END IF;
INSERT INTO `aces` (`id`, `acl`, `group`, `position`, `rights`, `from`, `until`, `order`) VALUES (NULL, a_id, a_group, a_pos, a_rights, a_from, a_until, a_order);
END$$

DELIMITER ;

/* Procedure structure for procedure `AddRoleForUser` */

drop procedure if exists `AddRoleForUser`;

DELIMITER $$

CREATE DEFINER=`root`@`localhost` PROCEDURE `AddRoleForUser`(IN u_id INT, IN r_group INT, IN r_pos INT, IN r_from INT, IN r_until INT, IN r_order INT)
BEGIN
DECLARE rc_from, rc_until INT;
IF r_from < 0 THEN
SET rc_from = 0;
ELSE
SET rc_from = r_from;
END IF;
IF r_until > 2147483646 THEN
SET rc_until = 2147483646;
ELSE
SET rc_until = r_until;
END IF;
IF rc_from > rc_until THEN
SET rc_from = rc_until-1;
END IF;
INSERT INTO `roles` (`id`, `user`, `group`, `position`, `from`, `until`, `order`) VALUES (NULL, u_id, r_group, r_pos, r_from, r_until, r_order);
END$$

DELIMITER ;

/* Procedure structure for procedure `deleteRole` */

drop procedure if exists `deleteRole`;

DELIMITER $$

CREATE DEFINER=`root`@`localhost` PROCEDURE `deleteRole`(IN role_id INT)
BEGIN
DELETE FROM `roles` WHERE `id` = role_id;
END$$

DELIMITER ;

/* Procedure structure for procedure `doLogin` */

drop procedure if exists `doLogin`;

DELIMITER $$

CREATE DEFINER=`root`@`localhost` PROCEDURE `doLogin`(IN usern VARCHAR(32), IN passw VARCHAR(32))
BEGIN
SELECT `id`, `status` FROM `base` WHERE `username` = usern AND `password` = passw LIMIT 1;
END$$

DELIMITER ;

/* Procedure structure for procedure `getACEs` */

drop procedure if exists `getACEs`;

DELIMITER $$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getACEs`(IN acl_id INT)
BEGIN
SELECT `id`, `group`, `position`, `rights` FROM `aces` WHERE `acl` = acl_id AND UNIX_TIMESTAMP() BETWEEN `from` AND `until` ORDER BY `order` ASC;
END$$

DELIMITER ;

/* Procedure structure for procedure `getDataHistoryBase` */

drop procedure if exists `getDataHistoryBase`;

DELIMITER $$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getDataHistoryBase`(IN uid INT)
BEGIN
SELECT `username`, `password`, `status`, `identifier`, `token`, `timeout`, `date`, INET_NTOA(`ip`) as `ip` FROM `history_base` WHERE `id` = uid ORDER BY `date` DESC;
END$$

DELIMITER ;

/* Procedure structure for procedure `getRolesForUser` */

drop procedure if exists `getRolesForUser`;

DELIMITER $$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getRolesForUser`(IN uid INT)
BEGIN
SELECT `id`, `group`, `position` FROM `roles` WHERE `user` = uid AND UNIX_TIMESTAMP() BETWEEN `from` AND `until` ORDER BY `order` ASC;
END$$

DELIMITER ;

/* Procedure structure for procedure `getUserByEmail` */

drop procedure if exists `getUserByEmail`;

DELIMITER $$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getUserByEmail`(IN mail VARCHAR(255), IN stat INT)
BEGIN
SELECT b.`id` FROM `extended` as e LEFT JOIN `base` as b ON (e.`user` = b.`id`) WHERE `email` =mail AND `status` = stat LIMIT 1;
END$$

DELIMITER ;

/* Procedure structure for procedure `getUserDataBase` */

drop procedure if exists `getUserDataBase`;

DELIMITER $$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getUserDataBase`(IN uid INT)
BEGIN
SELECT `username`, `password`, `status`, `identifier`, `token`, `timeout` FROM `base` WHERE `id` = uid LIMIT 1;
END$$

DELIMITER ;

/* Procedure structure for procedure `getUserDataExtended` */

drop procedure if exists `getUserDataExtended`;

DELIMITER $$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getUserDataExtended`(IN uid INT)
BEGIN
SELECT b.`username`, b.`password`, b.`status`, b.`identifier`, b.`token`, b.`timeout`, e.`firstname`, e.`lastname`, e.`email`, e.`address`, e.`zip`, e.`town`, e.`country`, e.`phone` FROM `base` as b LEFT JOIN `extended` as e ON (b.`id` = e.`user`) WHERE b.`id` = uid LIMIT 1;
END$$

DELIMITER ;

/* Procedure structure for procedure `isLoggedIn` */

drop procedure if exists `isLoggedIn`;

DELIMITER $$

CREATE DEFINER=`root`@`localhost` PROCEDURE `isLoggedIn`(IN stat INT, IN ident VARCHAR(32), IN tok VARCHAR(32))
BEGIN
SELECT `id` FROM `base` WHERE `status` = stat AND `identifier` = ident AND `token` = tok AND `timeout` > UNIX_TIMESTAMP() LIMIT 1;
END$$

DELIMITER ;

/* Procedure structure for procedure `recordLogin` */

drop procedure if exists `recordLogin`;

DELIMITER $$

CREATE DEFINER=`root`@`localhost` PROCEDURE `recordLogin`(IN u_id INT, IN u_ip VARCHAR(255))
BEGIN
INSERT INTO `logins` VALUES (u_id,  UNIX_TIMESTAMP(), INET_ATON(u_ip)) ;
UPDATE `meta` SET `last_login_date` = UNIX_TIMESTAMP(), `last_login_ip` = INET_ATON(u_ip) WHERE `user` = u_id;
END$$

DELIMITER ;

/* Procedure structure for procedure `reviveLastDataBase` */

drop procedure if exists `reviveLastDataBase`;

DELIMITER $$

CREATE DEFINER=`root`@`localhost` PROCEDURE `reviveLastDataBase`(IN uid INT)
BEGIN
SELECT `username`, `password`, `status`, `identifier`, `token`, `timeout` FROM `history_base` WHERE `id` = uid ORDER BY `date` DESC LIMIT 2,1;
	
END$$

DELIMITER ;

/* Procedure structure for procedure `saveSnapshotBase` */

drop procedure if exists `saveSnapshotBase`;

DELIMITER $$

CREATE DEFINER=`root`@`localhost` PROCEDURE `saveSnapshotBase`(IN uid INT, IN uname VARCHAR(32), IN pw VARCHAR(32), IN stat INT, IN ident VARCHAR(32), IN tok VARCHAR(32), IN timeo INT, IN ip VARCHAR(20))
BEGIN
INSERT INTO `history_base` (`id`, `username`, `password`, `status`, `identifier`, `token`, `timeout`, `date`, `ip`)  VALUES (uid, uname, pw, stat, ident, tok, timeo, UNIX_TIMESTAMP(), INET_ATON(ip));
END$$

DELIMITER ;

/* Procedure structure for procedure `updateDataBase` */

drop procedure if exists `updateDataBase`;

DELIMITER $$

CREATE DEFINER=`root`@`localhost` PROCEDURE `updateDataBase`(IN uid INT, IN uname VARCHAR(32), IN pw VARCHAR(32), IN stat INT, IN ident VARCHAR(32), IN tok VARCHAR(32), IN timeo INT)
BEGIN
UPDATE `base` SET  `username` = uname, `password` = pw, `status` = stat, `identifier` = ident, `token` = tok, `timeout` = timeo WHERE `id` = uid;
END$$

DELIMITER ;

/* Procedure structure for procedure `updateMetadataLastChange` */

drop procedure if exists `updateMetadataLastChange`;

DELIMITER $$

CREATE DEFINER=`root`@`localhost` PROCEDURE `updateMetadataLastChange`(IN uid INT, IN ip VARCHAR(20))
BEGIN
UPDATE `meta` SET `last_change_date` = UNIX_TIMESTAMP(), `last_change_ip` = INET_ATON(ip) WHERE `user` = uid;
END$$

DELIMITER ;

/* Procedure structure for procedure `usernameExists` */

drop procedure if exists `usernameExists`;

DELIMITER $$

CREATE DEFINER=`root`@`localhost` PROCEDURE `usernameExists`(IN uid INT, IN uname VARCHAR(32))
BEGIN
SELECT COUNT(*) FROM `base` WHERE `username` = uname  AND `id` <> uid;
END$$

DELIMITER ;

SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
