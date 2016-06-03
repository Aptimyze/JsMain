use reg;
CREATE TABLE `TRACK_JSMS_REG` (
 `UNIQUEID` bigint(32) unsigned NOT NULL DEFAULT '0',
 `IP_ADD` varchar(16) NOT NULL,
 `S0` datetime NOT NULL,
 `S1` datetime DEFAULT NULL,
 `S2` datetime DEFAULT NULL,
 `S3` datetime DEFAULT NULL,
 `S4` datetime DEFAULT NULL,
 `S5` datetime DEFAULT NULL,
 `S6` datetime DEFAULT NULL,
 `PROFILEID` int(11) DEFAULT NULL,
 PRIMARY KEY (`UNIQUEID`)
);
