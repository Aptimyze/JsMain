use mmmjs

CREATE TABLE  `YesNoMail` (
 `ID` INT( 10 ) NOT NULL AUTO_INCREMENT ,
 `RECEIVER` INT( 11 ) DEFAULT NULL ,
 `COUNTS` INT( 6 ) DEFAULT  '0',
 `USERS` VARCHAR( 300 ) DEFAULT NULL ,
 `SENT` CHAR( 1 ) ,
 `DATE` DATE DEFAULT  '0000-00-00',
INDEX (  `RECEIVER` ) ,
UNIQUE (
`ID`
)
);

ALTER TABLE  `YesNoMail` ADD UNIQUE (
`RECEIVER`
);

use jeevansathi_mailer

INSERT INTO  `EMAIL_TYPE` (  `ID` ,  `MAIL_ID` ,  `TPL_LOCATION` ,  `HEADER_TPL` ,  `FOOTER_TPL` ,  `TEMPLATE_EX_LOCATION` ,  `MAIL_GROUP` ,  `CUSTOM_CRITERIA` ,  `SENDER_EMAILID` ,  `DESCRIPTION` ,  `MEMBERSHIP_TYPE` ,  `GENDER` ,  `PHOTO_PROFILE` , `REPLY_TO_ENABLED` ,  `FROM_NAME` ,  `REPLY_TO_ADDRESS` ,  `MAX_COUNT_TO_BE_SENT` ,  `REQUIRE_AUTOLOGIN` ,  `FTO_FLAG` ,  `PRE_HEADER` ,  `PARTIALS` ) VALUES ('',  '1796',  'eoi_mailer_yn.tpl',  'eoi_header.tpl',  'eoi_footer.tpl', NULL ,  '5',  '1',  'contacts@jeevansathi.com', NULL ,  'D', NULL , NULL , NULL ,  'Jeevansathi Contacts', NULL , NULL ,  'Y', NULL , NULL ,  '');


INSERT INTO  `MAILER_SUBJECT` (  `MAIL_ID` ,  `SUBJECT_TYPE` ,  `SUBJECT_CODE` ,  `DESCRIPTION` ) 
VALUES ('1796',  'D',  'Respond to members who are waiting for your response. ',  'yes no mailer');
