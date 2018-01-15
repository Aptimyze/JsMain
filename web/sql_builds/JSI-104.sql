use MAIL

CREATE TABLE  `FilterEOI` (
 `ID` INT( 10 ) NOT NULL AUTO_INCREMENT ,
 `RECEIVER` INT( 11 ) DEFAULT NULL ,
 `COUNTS` INT( 6 ) DEFAULT  '0',
 `SENDER` VARCHAR( 300 ) DEFAULT NULL ,
 `SENT` CHAR( 1 ) ,
 `DATE` DATE DEFAULT  '0000-00-00',
INDEX (  `RECEIVER` ) ,
UNIQUE (
`ID`
)
);

ALTER TABLE  `FilterEOI` ADD UNIQUE (
`RECEIVER`
);

use jeevansathi_mailer

INSERT INTO  `EMAIL_TYPE` (  `ID` ,  `MAIL_ID` ,  `TPL_LOCATION` ,  `HEADER_TPL` ,  `FOOTER_TPL` ,  `TEMPLATE_EX_LOCATION` ,  `MAIL_GROUP` ,  `CUSTOM_CRITERIA` ,  `SENDER_EMAILID` ,  `DESCRIPTION` ,  `MEMBERSHIP_TYPE` ,  `GENDER` ,  `PHOTO_PROFILE` , `REPLY_TO_ENABLED` ,  `FROM_NAME` ,  `REPLY_TO_ADDRESS` ,  `MAX_COUNT_TO_BE_SENT` ,  `REQUIRE_AUTOLOGIN` ,  `FTO_FLAG` ,  `PRE_HEADER` ,  `PARTIALS` ) VALUES ('',  '1802',  'eoi_mailer_filter.tpl',  'eoi_header.tpl',  'eoi_footer.tpl', NULL ,  '5',  '1',  'contacts@jeevansathi.com', NULL ,  'D', NULL , NULL , NULL ,  'Jeevansathi Contacts', NULL , NULL ,  'Y', NULL , NULL ,  '');


INSERT INTO  `MAILER_SUBJECT` (  `MAIL_ID` ,  `SUBJECT_TYPE` ,  `SUBJECT_CODE` ,  `DESCRIPTION` ) 
VALUES ('1802',  'D',  'Did you see the interests received in your filtered folder?',  'eoi mailer filter');


INSERT INTO  `LINK_MAILERS` (  `LINKID` ,  `APP_SCREEN_ID` ,  `LINK_NAME` ,  `LINK_URL` ,  `OTHER_GET_PARAMS` ,  `REQUIRED_AUTOLOGIN` ,  `OUTER_LINK` ) 
VALUES (
'51',  'a15',  'EOI_FILTER',  'profile/contacts_made_received.php',  'page=filtered_eoi&filter=R',  'Y',  'N'
);


INSERT INTO  `MAILER_TEMPLATE_VARIABLES_MAP` (  `VARIABLE_NAME` ,  `VARIABLE_PROCESSING_CLASS` ,  `DESCRIPTION` ,  `MAX_LENGTH` ,  `MAX_LENGTH_SMS` ,  `DEFAULT_VALUE` ,  `TPL_FORMAT` ) 
VALUES (
'EOI_FILTER',  '3',  'People waiting response filter link',  '1000',  '1000',  'NA',  ''
);


