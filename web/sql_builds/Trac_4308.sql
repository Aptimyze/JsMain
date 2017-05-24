use MATCHALERT_TRACKING;
CREATE TABLE `NEW_MATCHES_EMAILS_TRACKING` (
  `DATE` date NOT NULL,
  `PROFILES_CONSIDERED` mediumint(11) unsigned DEFAULT '0',
  `PROFILES_MAIL_SENT` mediumint(11) unsigned DEFAULT '0',
  `MAIL_OPEN` mediumint(5) unsigned DEFAULT '0',
  `UNSUBSCRIPTION` mediumint(5) unsigned DEFAULT '0',
  `1_MATCH` mediumint(5) unsigned DEFAULT '0',
  `2_MATCH` mediumint(5) unsigned DEFAULT '0',
  `3_MATCH` mediumint(5) unsigned DEFAULT '0',
  `4_MATCH` mediumint(5) unsigned DEFAULT '0',
  `5_MATCH` mediumint(5) unsigned DEFAULT '0',
  `6_MATCH` mediumint(5) unsigned DEFAULT '0',
  `7_MATCH` mediumint(5) unsigned DEFAULT '0',
  `8_MATCH` mediumint(5) unsigned DEFAULT '0',
  `9_MATCH` mediumint(5) unsigned DEFAULT '0',
  `10_MATCH` mediumint(5) unsigned DEFAULT '0',
  PRIMARY KEY (`DATE`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;



use new_matches_emails;
ALTER TABLE `MAILER` ADD `DATE` DATE;
