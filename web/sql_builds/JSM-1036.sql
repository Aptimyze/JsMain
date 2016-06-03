USE jeevasathi_mailer;

INSERT INTO  `EMAIL_TYPE` (  `ID` ,  `MAIL_ID` ,  `TPL_LOCATION` ,  `HEADER_TPL` ,  `FOOTER_TPL` ,  `TEMPLATE_EX_LOCATION` ,  `MAIL_GROUP` ,  `CUSTOM_CRITERIA` ,  `SENDER_EMAILID` ,  `DESCRIPTION` ,  `MEMBERSHIP_TYPE` ,  `GENDER` ,  `PHOTO_PROFILE` , `REPLY_TO_ENABLED` ,  `FROM_NAME` ,  `REPLY_TO_ADDRESS` ,  `MAX_COUNT_TO_BE_SENT` ,  `REQUIRE_AUTOLOGIN` ,  `FTO_FLAG` ,  `PRE_HEADER` ,  `PARTIALS` ) 
VALUES (
'1801',  '1801',  'fraudAlertFemale.tpl',  'top8_fraud_header.tpl',  'revamp_footer.tpl', NULL ,  '27',  '1',  'info@jeevansathi.com',  'fraud alert mailer',  'D', NULL , NULL , NULL ,  'Jeevansathi Info', NULL , NULL , NULL , NULL , NULL ,  ''
);


INSERT INTO  `MAILER_SUBJECT` (  `MAIL_ID` ,  `SUBJECT_TYPE` ,  `SUBJECT_CODE` ,  `DESCRIPTION` ) 
VALUES (
'1801',  'D',  'Search Safe: 6 tips to have a secure experience on Jeevansathi',  'Fraud Alert for Females'
);
