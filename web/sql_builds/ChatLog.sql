/* shards master*/
use newjs;
CREATE TABLE `CHATS` (
 `ID` int(11) NOT NULL,
 `MESSAGE` text NOT NULL,
 UNIQUE KEY `ID` (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1

CREATE TABLE `CHAT_LOG` (
 `SENDER` int(8) unsigned DEFAULT '0',
 `RECEIVER` int(8) unsigned DEFAULT '0',
 `DATE` datetime DEFAULT '0000-00-00 00:00:00',
 `IP` varchar(64) DEFAULT NULL,
 `RECEIVER_STATUS` char(1) DEFAULT 'U',
 `FOLDERID` varchar(30) DEFAULT '0',
 `MSG_OBS_ID` int(11) DEFAULT '0',
 `SENDER_STATUS` char(1) DEFAULT 'U',
 `TYPE` char(1) DEFAULT 'R',
 `ID` int(11) DEFAULT NULL,
 `OBSCENE` char(1) DEFAULT 'N',
 `IS_MSG` char(1) DEFAULT 'N',
 `SEEN` char(1) DEFAULT NULL,
 UNIQUE KEY `ID` (`ID`),
 KEY `SENDER` (`SENDER`,`RECEIVER`),
 KEY `RECEIVER` (`RECEIVER`,`FOLDERID`,`OBSCENE`),
 KEY `DATE_id` (`DATE`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1
/*main master*/
use newjs;
CREATE TABLE `CHAT_LOG_GET_ID` (
 `ID` int(11) NOT NULL AUTO_INCREMENT,
 `NO_USE_VARIABLE` char(1) NOT NULL,
 PRIMARY KEY (`ID`),
 UNIQUE KEY `NO_USE_VARIABLE` (`NO_USE_VARIABLE`)
) ENGINE=MyISAM AUTO_INCREMENT=512 DEFAULT CHARSET=latin1 COMMENT='Table generate id for MESSAGE_LOG table'
