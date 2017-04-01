use PICTURE;
CREATE TABLE `PICTURE_NEW_BKP` (
 `PICTUREID` int(11) unsigned NOT NULL DEFAULT '0',
 `PROFILEID` int(11) unsigned DEFAULT '0',
 `ORDERING` int(1) DEFAULT NULL,
 `TITLE` varchar(40) DEFAULT NULL,
 `KEYWORD` varchar(20) DEFAULT NULL,
 `UPDATED_TIMESTAMP` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
 `MainPicUrl` varchar(200) DEFAULT NULL,
 `ProfilePicUrl` varchar(200) DEFAULT NULL,
 `ThumbailUrl` varchar(200) DEFAULT NULL,
 `Thumbail96Url` varchar(200) DEFAULT NULL,
 `PICFORMAT` varchar(10) DEFAULT NULL,
 `SearchPicUrl` varchar(200) DEFAULT NULL,
 `MobileAppPicUrl` varchar(200) DEFAULT NULL,
 `ProfilePic120Url` varchar(200) NOT NULL,
 `ProfilePic235Url` varchar(200) NOT NULL,
 `ProfilePic450Url` varchar(200) NOT NULL,
 `OriginalPicUrl` varchar(200) NOT NULL,
 `UNSCREENED_TITLE` varchar(40) NOT NULL,
 PRIMARY KEY (`PICTUREID`),
 UNIQUE KEY `PROFILEID` (`PROFILEID`,`ORDERING`)
);
