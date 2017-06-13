use jeevansathi_mailer;
INSERT INTO  `MAILER_SUBJECT` (  `MAIL_ID` ,  `SUBJECT_TYPE` ,  `SUBJECT_CODE` ,  `DESCRIPTION` ) 
VALUES (
'1841',  'D',  'Your profile on Jeevansathi.com has been marked incomplete',  'Email is sent when profile is marked incomplete on junk characters removal.'
);

INSERT INTO  `EMAIL_TYPE` (  `ID` ,  `MAIL_ID` ,  `TPL_LOCATION` ,  `HEADER_TPL` ,  `FOOTER_TPL` ,  `TEMPLATE_EX_LOCATION` ,  `MAIL_GROUP` ,  `CUSTOM_CRITERIA` ,  `SENDER_EMAILID` ,  `DESCRIPTION` ,  `MEMBERSHIP_TYPE` ,  `GENDER` ,  `PHOTO_PROFILE` , `REPLY_TO_ENABLED` ,  `FROM_NAME` ,  `REPLY_TO_ADDRESS` ,  `MAX_COUNT_TO_BE_SENT` ,  `REQUIRE_AUTOLOGIN` ,  `FTO_FLAG` ,  `PRE_HEADER` ,  `PARTIALS` ) 
VALUES (
'',  '1841',  'incompleteJunkRemoval.tpl', NULL , NULL , NULL ,  '45',  '1',  'info@jeevansathi.com',  'incomplete mailer after removal of the junk characters.',  'D', NULL , NULL , NULL ,  'Jeevansathi.com', NULL , NULL , NULL , NULL , NULL ,  ''
);

