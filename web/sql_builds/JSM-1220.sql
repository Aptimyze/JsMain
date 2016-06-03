use jeevansathi_mailer;

INSERT INTO  `EMAIL_TYPE` (  `ID` ,  `MAIL_ID` ,  `TPL_LOCATION` ,  `HEADER_TPL` ,  `FOOTER_TPL` ,  `TEMPLATE_EX_LOCATION` ,  `MAIL_GROUP` ,  `CUSTOM_CRITERIA` ,  `SENDER_EMAILID` ,  `DESCRIPTION` ,  `MEMBERSHIP_TYPE` , `GENDER` ,  `PHOTO_PROFILE` ,  `REPLY_TO_ENABLED` ,  `FROM_NAME` ,  `REPLY_TO_ADDRESS` ,  `MAX_COUNT_TO_BE_SENT` ,  `REQUIRE_AUTOLOGIN` ,  `FTO_FLAG` ,  `PRE_HEADER` ,  `PARTIALS` ) 
VALUES (
'1821',  '1821',  'dpp_Review_Mailer.tpl',  'top8_dpp_Review.tpl', 'revamp_footer.tpl' , NULL ,  '27',  '1',  'info@jeevansathi.com',  'mailer to review one''s dpp',  'D', NULL , NULL , NULL ,  'Jeevansathi Info', NULL , NULL , NULL , NULL , NULL ,  ''
);

INSERT INTO  `MAILER_SUBJECT` (  `MAIL_ID` ,  `SUBJECT_TYPE` ,  `SUBJECT_CODE` ,  `DESCRIPTION` ) 
VALUES (
'1821',  'D',  'Hi ~$username` , Please review your desired partner profile',  'review your desired partner profile'
);

use PROFILE;

CREATE TABLE `DPP_REVIEW_MAILER_LOG` (
 `RECEIVER` int(11) DEFAULT '0',
 `SENT` char(1) DEFAULT NULL,
 `DATE` date DEFAULT NULL,
 UNIQUE KEY `PROFILEID_DATE` (`RECEIVER`,`DATE`)
) ENGINE=MYISAM DEFAULT CHARSET=latin1