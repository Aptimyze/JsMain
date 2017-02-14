use matchalerts_tracking;

CREATE TABLE `match_alert_feedback` (
 `SNO` int(10) unsigned NOT NULL AUTO_INCREMENT,
 `PROFILEID` int(11) unsigned DEFAULT NULL,
 `MAILSENTDATE` datetime DEFAULT '0000-00-00 00:00:00',
 `STYPE` varchar(3) DEFAULT NULL,
 `FEEDBACKVALUE` enum('Y','N') DEFAULT NULL,
 `FEEDBACKTIME` datetime DEFAULT '0000-00-00 00:00:00',
 PRIMARY KEY (`SNO`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1



use jeevansathi_mailer;
INSERT INTO  `LINK_MAILERS` (  `LINKID` ,  `APP_SCREEN_ID` ,  `LINK_NAME` ,  `LINK_URL` ,  `OTHER_GET_PARAMS` ,  `REQUIRED_AUTOLOGIN` ,  `OUTER_LINK` ) 
VALUES (
'',  '0',  'MATCHALERT_FEEDBACK',  '/mailer/feedbackMatchAlertMailer', NULL ,  'Y',  'N'
);
