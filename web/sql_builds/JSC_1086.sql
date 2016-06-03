use billing;

CREATE TABLE `EXCLUSIVE_MEMBERS` (
 `ID` int(11) NOT NULL AUTO_INCREMENT,
 `PROFILEID` int(12) NOT NULL,
 `ASSIGNED_TO` varchar(20),
 `BILLING_DT` date NOT NULL DEFAULT '0000-00-00',
 `ASSIGNED_DT` date DEFAULT '0000-00-00',
 `ASSIGNED` enum('Y','N') NOT NULL DEFAULT 'N',
  PRIMARY KEY (`ID`),
  UNIQUE KEY `PROFILEID` (`PROFILEID`),
  KEY `ASSIGNED` (`ASSIGNED`)
)  ENGINE=MyISAM DEFAULT CHARSET=latin1;

use jeevansathi_mailer;

INSERT INTO jeevansathi_mailer.`EMAIL_TYPE` ( `ID` , `MAIL_ID` , `TPL_LOCATION` , `HEADER_TPL` , `FOOTER_TPL` , `TEMPLATE_EX_LOCATION` , `MAIL_GROUP` , `CUSTOM_CRITERIA` , `SENDER_EMAILID` , `DESCRIPTION` , `MEMBERSHIP_TYPE` , `GENDER` , `PHOTO_PROFILE` , `REPLY_TO_ENABLED` , `FROM_NAME` , `REPLY_TO_ADDRESS` , `MAX_COUNT_TO_BE_SENT` , `REQUIRE_AUTOLOGIN` , `FTO_FLAG` , `PRE_HEADER` , `PARTIALS` ) VALUES (
'', '1808', 'Exclusive_Assignment_mailer.tpl', NULL , NULL , NULL , '25', '1', 'membership@jeevansathi.com', 'Jeevansathi Exclusive Service', 'D', NULL , NULL , NULL , 'Jeevansathi Membership', 'NULL', NULL , NULL , NULL , NULL , '');

INSERT INTO jeevansathi_mailer.`MAILER_SUBJECT` ( `MAIL_ID` , `SUBJECT_TYPE` , `SUBJECT_CODE` , `DESCRIPTION` ) VALUES (
'1808', 'D', 'Welcome to Jeevansathi Exclusive Membership!', 'Jeevansathi Exclusive Service');