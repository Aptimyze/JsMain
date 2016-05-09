use matchalerts;
CREATE TABLE `SPAM_CONTROL` (
  `ID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `GMAIL` mediumint(5) unsigned DEFAULT '0',
  `GMAIL_OPEN` mediumint(5) unsigned DEFAULT '0',
  `YAHOO` mediumint(5) unsigned DEFAULT '0',
  `YAHOO_OPEN` mediumint(5) unsigned DEFAULT '0',
  `HOTMAIL` mediumint(5) unsigned DEFAULT '0',
  `HOTMAIL_OPEN` mediumint(5) unsigned DEFAULT '0',
  `REDIFF` mediumint(5) unsigned DEFAULT '0',
  `REDIFF_OPEN` mediumint(5) unsigned DEFAULT '0',
  `OTHERS` mediumint(5) unsigned DEFAULT '0',
  `OTHERS_OPEN` mediumint(5) unsigned DEFAULT '0',
  `COUNTER` smallint(5) unsigned DEFAULT '1',
  `DATE` date DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM;


ALTER TABLE `MAILER` ADD `DATE` DATE NOT NULL ;

DROP TABLE `MAILER_TEMP` ;
CREATE TABLE `MAILER_TEMP` AS (SELECT * FROM `MAILER`);
