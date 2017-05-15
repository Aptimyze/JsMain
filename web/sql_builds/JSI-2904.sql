use MIS;
CREATE TABLE `APP_LOGINTRACKING` (
  `PROFILEID` int(12) NOT NULL,
  `REGISTRATION_ID` varchar(255) NOT NULL,
  `APP_TYPE` varchar(255) NOT NULL,
  PRIMARY KEY (`PROFILEID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

use jeevansathi_mailer;
INSERT INTO  `EMAIL_TYPE` (  `ID` ,  `MAIL_ID` ,  `TPL_LOCATION` ,  `HEADER_TPL` ,  `FOOTER_TPL` ,  `TEMPLATE_EX_LOCATION` ,  `MAIL_GROUP` ,  `CUSTOM_CRITERIA` ,  `SENDER_EMAILID` ,  `DESCRIPTION` ,  `MEMBERSHIP_TYPE` , `GENDER` ,  `PHOTO_PROFILE` ,  `REPLY_TO_ENABLED` ,  `FROM_NAME` ,  `REPLY_TO_ADDRESS` ,  `MAX_COUNT_TO_BE_SENT` ,  `REQUIRE_AUTOLOGIN` ,  `FTO_FLAG` ,  `PRE_HEADER` ,  `PARTIALS` ) 
VALUES (
'',  '1849',  'loginAlert.tpl', NULL ,  'revamp_footer.tpl', NULL ,  '27',  '1',  'info@jeevansathi.com',  'Mails for login through new devices.',  'D', NULL , NULL , NULL ,  'Jeevansathi Info', NULL , NULL , NULL , NULL ,  'Please add info@jeevansathi.com to your address book to ensure delivery of this mail into you inbox',  ''
);