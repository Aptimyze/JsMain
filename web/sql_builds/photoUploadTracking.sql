use MOBILE_API;
CREATE TABLE `PHOTO_UPLOAD_APP_TRACKING` (
  `ID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `PROFILEID` int(11) unsigned DEFAULT NULL,
  `DATE` datetime NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `PROFILEID` (`PROFILEID`)
) ENGINE=MYISAM;