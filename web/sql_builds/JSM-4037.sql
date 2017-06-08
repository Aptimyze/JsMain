use newjs;
CREATE TABLE `CRITICAL_INFO_CHANGED` (
`ID` INT( 11 ) NOT NULL AUTO_INCREMENT ,
`PROFILEID` INT( 11 ) NOT NULL ,
`EDITED_FIELDS` VARCHAR( 50 ) NOT NULL ,
`DATE` DATETIME NOT NULL ,
PRIMARY KEY ( `ID` ) ,
UNIQUE (
`PROFILEID`
)
);
CREATE TABLE `CRITICAL_INFO_CHANGED_DOCS` (
`ID` INT( 11 ) NOT NULL AUTO_INCREMENT ,
`PROFILEID` INT( 11 ) NOT NULL ,
`DOCUMENT_PATH` VARCHAR( 255 ) NOT NULL ,
`SCREENED_STATUS` ENUM( 'N', 'Y', 'F' ) DEFAULT 'N' NOT NULL ,
`UPLOADED_ON` DATETIME NOT NULL ,
PRIMARY KEY ( `ID` ) ,
UNIQUE (
`PROFILEID`
)
);

use jeevansathi_mailer;
INSERT INTO `EMAIL_TYPE` ( `ID` , `MAIL_ID` , `TPL_LOCATION` , `HEADER_TPL` , `FOOTER_TPL` , `TEMPLATE_EX_LOCATION` , `MAIL_GROUP` , `CUSTOM_CRITERIA` , `SENDER_EMAILID` , `DESCRIPTION` , `MEMBERSHIP_TYPE` , `GENDER` , `PHOTO_PROFILE` , `REPLY_TO_ENABLED` , `FROM_NAME` , `REPLY_TO_ADDRESS` , `MAX_COUNT_TO_BE_SENT` , `REQUIRE_AUTOLOGIN` , `FTO_FLAG` , `PRE_HEADER` , `PARTIALS` )
VALUES (
'', '1851', 'criticalInfoChangeMail.tpl', NULL , 'revamp_footer.tpl', NULL , '47', '1', 'info@jeevansathi.com  ', '', NULL , NULL , NULL , NULL , 'Jeevansathi Info', NULL , NULL , NULL , NULL , 'Please add info@jeevansathi.com to your address book to ensure delivery of this mail into you inbox', ''
);
INSERT INTO `EMAIL_TYPE` ( `ID` , `MAIL_ID` , `TPL_LOCATION` , `HEADER_TPL` , `FOOTER_TPL` , `TEMPLATE_EX_LOCATION` , `MAIL_GROUP` , `CUSTOM_CRITERIA` , `SENDER_EMAILID` , `DESCRIPTION` , `MEMBERSHIP_TYPE` , `GENDER` , `PHOTO_PROFILE` , `REPLY_TO_ENABLED` , `FROM_NAME` , `REPLY_TO_ADDRESS` , `MAX_COUNT_TO_BE_SENT` , `REQUIRE_AUTOLOGIN` , `FTO_FLAG` , `PRE_HEADER` , `PARTIALS` )
VALUES (
'', '1852', 'criticalInfoDocUpload.tpl', NULL , 'revamp_footer.tpl', NULL , '47', '1', 'info@jeevansathi.com  ', '', NULL , NULL , NULL , NULL , 'Jeevansathi Info', NULL , NULL , NULL , NULL , 'Please add info@jeevansathi.com to your address book to ensure delivery of this mail into you inbox', ''
);