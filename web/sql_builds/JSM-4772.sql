use MIS;

CREATE TABLE `CAMPAIGN_KEYWORD_TRACKING` (
  `PROFILEID` int(11) unsigned NOT NULL,
  `CAMPAIGN` varchar(50) DEFAULT NULL,
  `ADNAME` varchar(50) DEFAULT NULL,
  `KEYWORD` varchar(50) DEFAULT NULL,
  `ADGROUP` varchar(50) DEFAULT NULL,
  `MEDIUM` varchar(50) DEFAULT NULL,
  `PHOTO_UPLOADED` enum('Y','N') DEFAULT NULL,
  `ACTIVATED_STATUS` enum('Y','H','N','D') DEFAULT NULL,
  `IS_PAID` enum('Y','N') DEFAULT NULL,
  `IS_QUALITY` enum('Y','N') NOT NULL DEFAULT 'N',
  PRIMARY KEY (`PROFILEID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

 INSERT INTO `MIS_MAINPAGE` ( `NAME` , `MAIN_URL` , `JUMP_URL` , `PRIVILEGE` , `ACTIVE` , `PUBLIC` )
VALUES ('Fetch Keyword, Adgroup, Campaign information', '/operations.php/registerMis/CampaignsRegistration?cid=$cid', NULL , 'P+MG', 'Y', ''
)