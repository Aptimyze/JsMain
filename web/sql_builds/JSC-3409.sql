
Use table "SALES_CAMPAIGN_PROFILE_DETAILS"

ALTER TABLE  `SALES_CAMPAIGN_PROFILE_DETAILS` ADD  `DATE` DATETIME NOT NULL AFTER  `MAIL_SENT` ;
ALTER TABLE  `SALES_CAMPAIGN_PROFILE_DETAILS` ADD  `DIALER_TIME` DATETIME DEFAULT  '0000-00-00 00:00:00' NOT NULL AFTER  `DATE` ;
ALTER TABLE  `SALES_CAMPAIGN_PROFILE_DETAILS` ADD INDEX (  `DATE` );

CREATE TABLE `SALES_CAMPAIGN_PROFILE_DETAILS_log` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `PROFILEID` int(11) DEFAULT '0',
  `PHONE_NO` varchar(20) DEFAULT NULL,
  `CAMPAIGN` varchar(40) DEFAULT NULL,
  `MAIL_SENT` enum('Y','N') DEFAULT 'N',
  `DATE` datetime DEFAULT NULL,
  `DIALER_TIME` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
