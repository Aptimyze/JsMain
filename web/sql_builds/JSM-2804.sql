use kundli_alert;
CREATE TABLE `KUNDLI_MATCHES_MAILER` (
 `SNO` mediumint(11) NOT NULL AUTO_INCREMENT,
 `RECEIVER` int(11) unsigned DEFAULT '0',
 `USER1` int(11) unsigned DEFAULT '0',
 `USER2` int(11) unsigned DEFAULT '0',
 `USER3` int(11) unsigned DEFAULT '0',
 `USER4` int(11) unsigned DEFAULT '0',
 `USER5` int(11) unsigned DEFAULT '0',
 `USER6` int(11) unsigned DEFAULT '0',
 `USER7` int(11) unsigned DEFAULT '0',
 `USER8` int(11) unsigned DEFAULT '0',
 `USER9` int(11) unsigned DEFAULT '0',
 `USER10` int(11) unsigned DEFAULT '0',
 `USER11` int(11) unsigned DEFAULT '0',
 `USER12` int(11) unsigned DEFAULT '0',
 `USER13` int(11) unsigned DEFAULT '0',
 `USER14` int(11) unsigned DEFAULT '0',
 `USER15` int(11) unsigned DEFAULT '0',
 `USER16` int(11) unsigned DEFAULT '0',
 `GUNA_U1` tinyint(2) unsigned NOT NULL DEFAULT '0',
 `GUNA_U2` tinyint(2) unsigned NOT NULL DEFAULT '0',
 `GUNA_U3` tinyint(2) unsigned NOT NULL DEFAULT '0',
 `GUNA_U4` tinyint(2) unsigned NOT NULL DEFAULT '0',
 `GUNA_U5` tinyint(2) unsigned NOT NULL DEFAULT '0',
 `GUNA_U6` tinyint(2) unsigned NOT NULL DEFAULT '0',
 `GUNA_U7` tinyint(2) unsigned NOT NULL DEFAULT '0',
 `GUNA_U8` tinyint(2) unsigned NOT NULL DEFAULT '0',
 `GUNA_U9` tinyint(2) unsigned NOT NULL DEFAULT '0',
 `GUNA_U10` tinyint(2) unsigned NOT NULL DEFAULT '0',
 `GUNA_U11` tinyint(2) unsigned NOT NULL DEFAULT '0',
 `GUNA_U12` tinyint(2) unsigned NOT NULL DEFAULT '0',
 `GUNA_U13` tinyint(2) unsigned NOT NULL DEFAULT '0',
 `GUNA_U14` tinyint(2) unsigned NOT NULL DEFAULT '0',
 `GUNA_U15` tinyint(2) unsigned NOT NULL DEFAULT '0',
 `GUNA_U16` tinyint(2) unsigned NOT NULL DEFAULT '0',
 `SENT` varchar(1) NOT NULL DEFAULT 'N',
 PRIMARY KEY (`SNO`)
) ENGINE=MyISAM


/*check value to be added in linkID*/
use jeevansathi_mailer;
INSERT INTO  `LINK_MAILERS` (  `LINKID` ,  `APP_SCREEN_ID` ,  `LINK_NAME` ,  `LINK_URL` ,  `OTHER_GET_PARAMS` ,  `REQUIRED_AUTOLOGIN` ,  `OUTER_LINK` ) 
VALUES (
'66', NULL ,  'KUNDLI_ALERTS',  '/search/kundlialerts', NULL ,  'Y',  'N'
);



-- change dates for partitions when it goes live. Please contact Sanyam/Reshu since the time given in the partitions needs to be changed
use kundli_alert;
CREATE TABLE `LOG` (
 `RECEIVER` int(11) unsigned NOT NULL DEFAULT '0',
 `USER` int(11) unsigned NOT NULL DEFAULT '0',
 `DATE` smallint(6) NOT NULL DEFAULT '0',
 KEY `RECEIVER` (`RECEIVER`),
 KEY `USER` (`USER`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1
/*!50100 PARTITION BY RANGE (DATE)
(PARTITION p1 VALUES LESS THAN (4268) ENGINE = InnoDB,
PARTITION p2 VALUES LESS THAN (4298) ENGINE = InnoDB,
PARTITION p3 VALUES LESS THAN (4328) ENGINE = InnoDB,
PARTITION p4 VALUES LESS THAN (4358) ENGINE = InnoDB)*/

CREATE TABLE  `LAST_ACTIVE_LOG1` (
 `NO` INT( 11 ) NOT NULL ,
 `DATE` DATE NOT NULL
) ENGINE = MYISAM DEFAULT CHARSET = latin1


INSERT INTO  `LAST_ACTIVE_LOG1` (  `NO` ,  `DATE` ) 
VALUES (
'2',  '<<DATE WHEN GOES LIVE>>' 
);
-- date in YYYY-MM-DD format
-- there has to be an insert query in last_active_log1. check with Reshu