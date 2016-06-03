use bot_jeevansathi;
ALTER TABLE  `user_info` CHANGE  `jeevansathi_ID`  `jeevansathi_ID` VARCHAR( 100 ) CHARACTER SET latin1 COLLATE latin1_bin;

CREATE TABLE `gmail_invites` (
 `id` int(11) NOT NULL auto_increment,
 `profileid` varchar(100) character set latin1 collate latin1_bin default NULL,
 `gmailid` varchar(100) character set latin1 collate latin1_bin default NULL,
 PRIMARY KEY  (`id`),
 KEY `profileid` (`profileid`)
) COMMENT='Stores to whom gmail invite to send';
CREATE TABLE `user_online` (
 `USER` int(10) unsigned NOT NULL ,
 `TYPE` char(1) default NULL,
 PRIMARY KEY  (`USER`)
) COMMENT='Stores who are currently online';

CREATE TABLE `invite_send` (
`id` INT NOT NULL AUTO_INCREMENT ,
`PROFILEID` INT NOT NULL ,
`EMAIL` VARCHAR( 100 ) NOT NULL ,
PRIMARY KEY ( `id` ) ,
INDEX ( `PROFILEID` , `EMAIL` )
) COMMENT= 'Stores to whom invite send from new bot';
CREATE TABLE `SUB_UNSUB` (
`ID` INT NOT NULL AUTO_INCREMENT ,
`EMAIL` VARCHAR( 100 ) NOT NULL ,
`SUB_TAKEN` TINYINT( 1 ) NOT NULL ,
`DATE` DATE NOT NULL ,
`TYPE` CHAR( 1 ) DEFAULT 'G' NOT NULL ,
PRIMARY KEY ( `ID` ) ,
INDEX ( `EMAIL` , `DATE` )
) COMMENT = 'Stores who accepts and rejects friend request of bot';

CREATE TABLE `CUS_MES_MAIL` (
 `id` int(11) NOT NULL auto_increment,
 `TYPE` char(1) NOT NULL default 'G',
 `USERNAME` varchar(100) NOT NULL,
 `PROFILEID` int(11) NOT NULL,
 `MESSAGE` text NOT NULL,
 `SENT` char(1) NOT NULL default 'N',
 PRIMARY KEY  (`id`),
 KEY `USERNAME` (`USERNAME`),
 KEY `PROFILEID` (`PROFILEID`)
) COMMENT= 'Stores all the custom message send to our client of gtalk,yahoo,msn etc';

