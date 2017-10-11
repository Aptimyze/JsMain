use MAIL;
CREATE TABLE `SHORTLISTED_PROFILES` (
 `ID` int(10) NOT NULL AUTO_INCREMENT,
 `RECEIVER` int(11) NOT NULL DEFAULT '0',
 `COUNTS` int(6) DEFAULT '0',
 `USERS` varchar(300) DEFAULT NULL,
 `SENT` char(1) DEFAULT NULL,
 `DATE` date DEFAULT '0000-00-00',
 PRIMARY KEY (`RECEIVER`),
 UNIQUE KEY `ID` (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1

