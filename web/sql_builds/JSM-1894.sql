use newjs;
CREATE TABLE `newjs`.`PICTURE_FOR_SCREEN_NEW_BKP` (
`PICTUREID` int( 11 ) unsigned NOT NULL DEFAULT '0',
`PROFILEID` int( 11 ) unsigned DEFAULT '0',
`ORDERING` int( 1 ) DEFAULT NULL ,
`TITLE` varchar( 40 ) DEFAULT NULL ,
`KEYWORD` varchar( 20 ) DEFAULT NULL ,
`UPDATED_TIMESTAMP` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ,
`MainPicUrl` varchar( 200 ) DEFAULT NULL ,
`ProfilePicUrl` varchar( 200 ) DEFAULT NULL ,
`ThumbailUrl` varchar( 200 ) DEFAULT NULL ,
`Thumbail96Url` varchar( 200 ) DEFAULT NULL ,
`MobileAppPicUrl` varchar( 200 ) NOT NULL ,
`ProfilePic120Url` varchar( 200 ) NOT NULL ,
`ProfilePic235Url` varchar( 200 ) NOT NULL ,
`ProfilePic450Url` varchar( 200 ) NOT NULL ,
`OriginalPicUrl` varchar( 200 ) NOT NULL ,
`SCREEN_BIT` varchar( 10 ) NOT NULL DEFAULT '0000000',
`PICFORMAT` varchar( 10 ) DEFAULT NULL ,
`WATERMARK` int( 2 ) NOT NULL DEFAULT '0',
PRIMARY KEY ( `PICTUREID` ) ,
KEY `UPDATED_TIMESTAMP` ( `UPDATED_TIMESTAMP` )
) ENGINE = MYISAM DEFAULT CHARSET = latin1;
