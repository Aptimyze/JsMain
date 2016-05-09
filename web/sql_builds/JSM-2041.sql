/* NOTE: choose 82 server */
use matchalerts;

CREATE TABLE `MATCHALERTS_TO_BE_SENT` (
  `PROFILEID` int(11) unsigned NOT NULL,
  `HASTRENDS` enum('0','1') NOT NULL DEFAULT '0',
  `IS_CALCULATED` enum('N','Y') NOT NULL DEFAULT 'N',
  PRIMARY KEY (`PROFILEID`)
) ENGINE=MyISAM;

ALTER TABLE `MAILER` RENAME `MAILER_BEFORE_INT` ;
CREATE TABLE `MAILER` (
  `RECEIVER` int(11) unsigned DEFAULT '0',
  `IS_USER_ACTIVE` enum('Y','N') NOT NULL DEFAULT 'N',
  `USER1` int(11) unsigned DEFAULT '0',
  `RECOMEND_USER1` enum('H','R','N','D') NOT NULL DEFAULT 'D',
  `USER2` int(11) unsigned DEFAULT '0',
  `RECOMEND_USER2` enum('H','R','N','D') NOT NULL DEFAULT 'D',
  `USER3` int(11) unsigned DEFAULT '0',
  `RECOMEND_USER3` enum('H','R','N','D') NOT NULL DEFAULT 'D',
  `USER4` int(11) unsigned DEFAULT '0',
  `RECOMEND_USER4` enum('H','R','N','D') NOT NULL DEFAULT 'D',
  `USER5` int(11) unsigned DEFAULT '0',
  `RECOMEND_USER5` enum('H','R','N','D') NOT NULL DEFAULT 'D',
  `USER6` int(11) unsigned DEFAULT '0',
  `RECOMEND_USER6` enum('H','R','N','D') NOT NULL DEFAULT 'D',
  `USER7` int(11) unsigned DEFAULT '0',
  `RECOMEND_USER7` enum('H','R','N','D') NOT NULL DEFAULT 'D',
  `USER8` int(11) unsigned DEFAULT '0',
  `RECOMEND_USER8` enum('H','R','N','D') NOT NULL DEFAULT 'D',
  `USER9` int(11) unsigned DEFAULT '0',
  `RECOMEND_USER9` enum('H','R','N','D') NOT NULL DEFAULT 'D',
  `USER10` int(11) unsigned DEFAULT '0',
  `RECOMEND_USER10` enum('H','R','N','D') NOT NULL DEFAULT 'D',
  `USER11` int(11) unsigned DEFAULT '0',
  `RECOMEND_USER11` enum('H','R','N','D') DEFAULT 'D',
  `USER12` int(11) unsigned DEFAULT '0',
  `RECOMEND_USER12` enum('H','R','N','D') DEFAULT 'D',
  `USER13` int(11) unsigned DEFAULT '0',
  `RECOMEND_USER13` enum('H','R','N','D') DEFAULT 'D',
  `USER14` int(11) unsigned DEFAULT '0',
  `RECOMEND_USER14` enum('H','R','N','D') DEFAULT 'D',
  `USER15` int(11) unsigned DEFAULT '0',
  `RECOMEND_USER15` enum('H','R','N','D') DEFAULT 'D',
  `USER16` int(11) unsigned DEFAULT '0',
  `RECOMEND_USER16` enum('H','R','N','D') DEFAULT 'D',
  `SENT` char(1) NOT NULL,
  `LOGIC_USED` tinyint(1) NOT NULL,
  `SNO` mediumint(11) NOT NULL AUTO_INCREMENT,
  `FREQUENCY` tinyint(1) unsigned NOT NULL,
  `DATE` date DEFAULT NULL,
  PRIMARY KEY (`SNO`),
  KEY `RECEIVER` (`RECEIVER`)
) ENGINE=MyISAM;
