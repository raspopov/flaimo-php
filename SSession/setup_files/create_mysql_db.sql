/*
SQLyog Community Edition- MySQL GUI v5.2 Beta 4
Host - 5.0.24-community-nt : Database - ssession
*********************************************************************
Server version : 5.0.24-community-nt
*/

SET NAMES utf8;

SET SQL_MODE='';

create database if not exists `ssession`;

USE `ssession`;

SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO';

/*Table structure for table `ssession` */

DROP TABLE IF EXISTS `ssession`;

CREATE TABLE `ssession` (
  `id` varchar(32) NOT NULL,
  `access` int(10) unsigned default NULL,
  `data` text,
  PRIMARY KEY  (`id`),
  KEY `access` (`access`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/* Procedure structure for procedure `_clean` */

drop procedure if exists `_clean`;

DELIMITER $$

CREATE DEFINER=`root`@`localhost` PROCEDURE `_clean`(IN timelimit INT)
BEGIN
DELETE FROM `ssession` WHERE `access` < timelimit;
END$$

DELIMITER ;

/* Procedure structure for procedure `_destroy` */

drop procedure if exists `_destroy`;

DELIMITER $$

CREATE DEFINER=`root`@`localhost` PROCEDURE `_destroy`(IN sid VARCHAR(32))
BEGIN
DELETE FROM `ssession` WHERE `id` = sid;
END$$

DELIMITER ;

/* Procedure structure for procedure `_read` */

drop procedure if exists `_read`;

DELIMITER $$

CREATE DEFINER=`root`@`localhost` PROCEDURE `_read`(IN sid VARCHAR(32))
BEGIN
SELECT `data` FROM `ssession` WHERE `id` = sid;
END$$

DELIMITER ;

/* Procedure structure for procedure `_write` */

drop procedure if exists `_write`;

DELIMITER $$

CREATE DEFINER=`root`@`localhost` PROCEDURE `_write`(IN sid VARCHAR(32), IN dat TEXT)
BEGIN
REPLACE INTO `ssession` (`id`, `access`, `data`) VALUES (sid, UNIX_TIMESTAMP(), dat);
END$$

DELIMITER ;

SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
