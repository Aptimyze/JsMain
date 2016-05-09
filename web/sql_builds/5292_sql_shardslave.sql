use test;

CREATE TABLE `CONTACTS_ACTIVE` (
 `CONTACTID` int(11) NOT NULL,
 `SENDER` int(11) unsigned NOT NULL default '0',
 `RECEIVER` int(11) unsigned NOT NULL default '0',
 `TYPE` char(1) NOT NULL default '',
 `TIME` datetime NOT NULL default '0000-00-00 00:00:00',
 `COUNT` smallint(11) unsigned NOT NULL default '0',
 `MSG_DEL` char(1) NOT NULL default '',
 `SEEN` char(1) NOT NULL,
 `FILTERED` char(1) NOT NULL,
 `FOLDER` char(3) NOT NULL,
 PRIMARY KEY  (`CONTACTID`),
 UNIQUE KEY `IND1` (`SENDER`,`RECEIVER`),
 KEY `RECEIVER` (`RECEIVER`)
) ENGINE=MyISAM;


CREATE TABLE `MESSAGE_LOG_ACTIVE` (
 `SENDER` int(8) unsigned NOT NULL default '0',
 `RECEIVER` int(8) unsigned NOT NULL default '0',
 `DATE` datetime NOT NULL default '0000-00-00 00:00:00',
 `IP` int(10) unsigned default NULL,
 `RECEIVER_STATUS` char(1) NOT NULL default 'U',
 `FOLDERID` mediumint(9) NOT NULL default '0',
 `MSG_OBS_ID` int(11) NOT NULL default '0',
 `SENDER_STATUS` char(1) NOT NULL default 'U',
 `TYPE` char(1) NOT NULL default 'R',
 `ID` int(11) NOT NULL,
 `OBSCENE` char(1) NOT NULL default 'N',
 `IS_MSG` char(1) NOT NULL default 'N',
 `SEEN` char(1) NOT NULL,
 UNIQUE KEY `ID` (`ID`),
 KEY `SENDER` (`SENDER`,`RECEIVER`),
 KEY `RECEIVER` (`RECEIVER`,`FOLDERID`,`OBSCENE`)
) ENGINE=MyISAM;


CREATE TABLE `MESSAGES_ACTIVE` (
 `ID` int(11) NOT NULL,
 `MESSAGE` text NOT NULL,
 UNIQUE KEY `ID` (`ID`)
) ENGINE=MyISAM;

CREATE TABLE `EOI_VIEWED_LOG_ACTIVE` (
 `VIEWER` int(8) NOT NULL default '0',
 `VIEWED` int(8) NOT NULL default '0',
 `DATE` datetime NOT NULL default '0000-00-00 00:00:00',
 UNIQUE KEY `COMBO_INDEX` (`VIEWED`,`VIEWER`),
 KEY `VIEWER` (`VIEWER`)
) ENGINE=MyISAM;










CREATE TABLE `CONTACTS_INACTIVE` (
 `CONTACTID` int(11) NOT NULL,
 `SENDER` int(11) unsigned NOT NULL default '0',
 `RECEIVER` int(11) unsigned NOT NULL default '0',
 `TYPE` char(1) NOT NULL default '',
 `TIME` datetime NOT NULL default '0000-00-00 00:00:00',
 `COUNT` smallint(11) unsigned NOT NULL default '0',
 `MSG_DEL` char(1) NOT NULL default '',
 `SEEN` char(1) NOT NULL,
 `FILTERED` char(1) NOT NULL,
 `FOLDER` char(3) NOT NULL,
 PRIMARY KEY  (`CONTACTID`),
 UNIQUE KEY `IND1` (`SENDER`,`RECEIVER`),
 KEY `RECEIVER` (`RECEIVER`)
) ENGINE=MyISAM;


CREATE TABLE `MESSAGE_LOG_INACTIVE` (
 `SENDER` int(8) unsigned NOT NULL default '0',
 `RECEIVER` int(8) unsigned NOT NULL default '0',
 `DATE` datetime NOT NULL default '0000-00-00 00:00:00',
 `IP` int(10) unsigned default NULL,
 `RECEIVER_STATUS` char(1) NOT NULL default 'U',
 `FOLDERID` mediumint(9) NOT NULL default '0',
 `MSG_OBS_ID` int(11) NOT NULL default '0',
 `SENDER_STATUS` char(1) NOT NULL default 'U',
 `TYPE` char(1) NOT NULL default 'R',
 `ID` int(11) NOT NULL,
 `OBSCENE` char(1) NOT NULL default 'N',
 `IS_MSG` char(1) NOT NULL default 'N',
 `SEEN` char(1) NOT NULL,
 UNIQUE KEY `ID` (`ID`),
 KEY `SENDER` (`SENDER`,`RECEIVER`),
 KEY `RECEIVER` (`RECEIVER`,`FOLDERID`,`OBSCENE`)
) ENGINE=MyISAM;


CREATE TABLE `MESSAGES_INACTIVE` (
 `ID` int(11) NOT NULL,
 `MESSAGE` text NOT NULL,
 UNIQUE KEY `ID` (`ID`)
) ENGINE=MyISAM;

CREATE TABLE `EOI_VIEWED_LOG_INACTIVE` (
 `VIEWER` int(8) NOT NULL default '0',
 `VIEWED` int(8) NOT NULL default '0',
 `DATE` datetime NOT NULL default '0000-00-00 00:00:00',
 UNIQUE KEY `COMBO_INDEX` (`VIEWED`,`VIEWER`),
 KEY `VIEWER` (`VIEWER`)
) ENGINE=MyISAM;

CREATE TABLE `MESSAGE_LOG_6_TRACKING` (
  `SENDER` int(8) unsigned NOT NULL default '0',
  `RECEIVER` int(8) unsigned NOT NULL default '0',
  `ID` int(11) NOT NULL,
  UNIQUE KEY `ID` (`ID`),
  KEY `RECEIVER` (`RECEIVER`),
  KEY `SENDER` (`SENDER`)
) ENGINE=MyISAM;


