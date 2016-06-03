USE duplicates;	


CREATE TABLE  `DUPLICATE_PROFILES_MAIL_LOG` (
 `PROFILEID` INT( 11 ) UNSIGNED NOT NULL ,
UNIQUE (
 `PROFILEID`
)
);

USE jeevansathi_mailer;	

INSERT INTO  `MAILER_SUBJECT` (  `MAIL_ID` ,  `SUBJECT_TYPE` ,  `SUBJECT_CODE` ,  `DESCRIPTION` ) 
VALUES (
'1791',  'D',  'Here is some information regarding your account visibility',  'Duplicate profiles mail'
);

INSERT INTO  `EMAIL_TYPE` (  `ID` ,  `MAIL_ID` ,  `TPL_LOCATION` ,  `HEADER_TPL` ,  `FOOTER_TPL` ,  `TEMPLATE_EX_LOCATION` ,  `MAIL_GROUP` ,  `CUSTOM_CRITERIA` ,  `SENDER_EMAILID` ,  `DESCRIPTION` ,  `MEMBERSHIP_TYPE` ,  `GENDER` ,  `PHOTO_PROFILE` , `REPLY_TO_ENABLED` ,  `FROM_NAME` ,  `REPLY_TO_ADDRESS` ,  `MAX_COUNT_TO_BE_SENT` ,  `REQUIRE_AUTOLOGIN` ,  `FTO_FLAG` ,  `PRE_HEADER` ,  `PARTIALS` ) 
VALUES (
'1791',  '1791',  'duplicateProfileMailer.tpl',  'duplicate_profiles_header.tpl',  'revamp_footer.tpl', NULL ,  '27',  '1',  'info@jeevansathi.com',  'mailer for those profiles which have been marked duplicate',  'D', NULL , NULL , NULL ,  'Jeevansathi Info', NULL , NULL , NULL , NULL , NULL ,  ''
);

