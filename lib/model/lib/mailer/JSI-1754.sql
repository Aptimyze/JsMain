use jeevansathi_mailer;

INSERT INTO  `EMAIL_TYPE` (  `ID` ,  `MAIL_ID` ,  `TPL_LOCATION` ,  `HEADER_TPL` ,  `FOOTER_TPL` ,  `TEMPLATE_EX_LOCATION` ,  `MAIL_GROUP` ,  `CUSTOM_CRITERIA` ,  `SENDER_EMAILID` ,  `DESCRIPTION` ,  `MEMBERSHIP_TYPE` ,  `GENDER` , `PHOTO_PROFILE` ,  `REPLY_TO_ENABLED` ,  `FROM_NAME` ,  `REPLY_TO_ADDRESS` ,  `MAX_COUNT_TO_BE_SENT` ,  `REQUIRE_AUTOLOGIN` ,  `FTO_FLAG` ,  `PRE_HEADER` ,  `PARTIALS` ) 
VALUES (
'',  '1831',  'shortlistedMailer.tpl', NULL , NULL , NULL ,  '42',  '1',  'contacts@jeevansathi.com',  'Mail for sending interests to recently shortlisted members. ',  'D',  '',  '', NULL ,  'Jeevansathi Contacts', NULL , NULL , NULL ,  '', 'Please add contacts@jeevansathi.com to your address book to ensure delivery of this mail into your inbox',  ''
);

INSERT INTO  `LINK_MAILERS` (  `LINKID` ,  `APP_SCREEN_ID` ,  `LINK_NAME` ,  `LINK_URL` ,  `OTHER_GET_PARAMS` ,  `REQUIRED_AUTOLOGIN` ,  `OUTER_LINK` ) 
VALUES (
'', NULL ,  'SHORTLISTED',  'search/shortlisted',  '',  'Y',  'N'
);



INSERT INTO  `MAILER_TEMPLATE_VARIABLES_MAP` (  `VARIABLE_NAME` ,  `VARIABLE_PROCESSING_CLASS` ,  `DESCRIPTION` ,  `MAX_LENGTH` ,  `MAX_LENGTH_SMS` ,  `DEFAULT_VALUE` ,  `TPL_FORMAT` ) 
VALUES (
'SHORTLISTED',  '3',  'NA',  '1000',  '255',  'NA',  ''
);