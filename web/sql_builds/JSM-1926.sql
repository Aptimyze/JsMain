use REGISTER;
CREATE TABLE `REGISTRATION_MONITORING_DATA` (
 `HOUR` tinyint(2) unsigned NOT NULL,
 `CHANNEL` varchar(20) NOT NULL,
 `MIN` smallint(5) unsigned NOT NULL,
 `MAX` smallint(5) unsigned NOT NULL,
 `AVG` smallint(5) unsigned NOT NULL,
 `STD` decimal(7,4) DEFAULT NULL,
 UNIQUE KEY `HOUR` (`HOUR`,`CHANNEL`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1