use jeevansathi_mailer;
INSERT INTO  `EMAIL_TYPE` (  `ID` ,  `MAIL_ID` ,  `TPL_LOCATION` ,  `HEADER_TPL` ,  `FOOTER_TPL` ,  `TEMPLATE_EX_LOCATION` ,  `MAIL_GROUP` ,  `CUSTOM_CRITERIA` ,  `SENDER_EMAILID` ,  `DESCRIPTION` , `MEMBERSHIP_TYPE` ,  `GENDER` ,  `PHOTO_PROFILE` ,  `REPLY_TO_ENABLED` ,  `FROM_NAME` ,  `REPLY_TO_ADDRESS` ,  `MAX_COUNT_TO_BE_SENT` ,  `REQUIRE_AUTOLOGIN` ,  `FTO_FLAG` ,  `PRE_HEADER` ,  `PARTIALS` ) 
VALUES (
'',  '1848',  'sampleAstroCompatibilityMailer.tpl',  'top8_dpp_Review.tpl',  'revamp_footer.tpl', NULL ,  '27',  '1',  'info@jeevansathi.com',  'Sample Astro Compatibility Mail',  'D',  'N',  'N',  'N',  'Jeevansathi Info', NULL , NULL , NULL , NULL , NULL ,  ''
);

INSERT INTO  `MAILER_SUBJECT` (  `MAIL_ID` ,  `SUBJECT_TYPE` ,  `SUBJECT_CODE` ,  `DESCRIPTION` ) 
VALUES (
'1848',  'D',  'Sample Astro compatibility report',  'Sample Astro compatibility report'
);

use newjs;
CREATE TABLE `HOROSCOPE_DOWNLOAD_TRACKING` (
 `SNO` int(8) NOT NULL AUTO_INCREMENT,
 `DATE` datetime NOT NULL,
 `CHANNEL` char(2) NOT NULL,
 `DOWNLOADED_BY` varchar(40) NOT NULL,
 `DOWNLOADED_OF` varchar(40) NOT NULL,
 PRIMARY KEY (`SNO`)
) ENGINE=InnoDB;