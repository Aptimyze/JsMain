use incentive;
CREATE TABLE `SALES_CAMPAIGN_PROFILE_DETAILS` (
 `ID` int(11) NOT NULL AUTO_INCREMENT,
 `PROFILEID` int(11) NOT NULL DEFAULT '0',
 `PHONE_NO` varchar(20) NOT NULL DEFAULT '',
 `CAMPAIGN` varchar(40) NOT NULL DEFAULT '',
 `MAIL_SENT` enum('Y','N') NOT NULL DEFAULT 'N',
 PRIMARY KEY (`ID`),
 KEY `PROFILEID` (`PROFILEID`,`CAMPAIGN`)
)ENGINE=MyISAM;

use jeevansathi_mailer;

INSERT INTO `EMAIL_TYPE` VALUES (1806, 1806, 'salesCampaignFeedbackMailer.tpl', NULL, NULL, NULL, 32, 1, 'info@jeevansathi.com', 'Jeevansathi Sales Service Feedback', 'D', NULL, NULL, NULL, 'Jeevansathi Feedback', NULL, NULL, NULL, NULL, NULL, '');

INSERT INTO `MAILER_SUBJECT` VALUES (1806, 'D', 'Feedback request from Jeevansathi.com', 'Jeevansathi Sales Service Feedback'); 
 
