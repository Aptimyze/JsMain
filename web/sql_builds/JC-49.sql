USE jeevansathi_mailer;

INSERT INTO  `EMAIL_TYPE` (  `ID` ,  `MAIL_ID` ,  `TPL_LOCATION` ,  `HEADER_TPL` ,  `FOOTER_TPL` ,  `TEMPLATE_EX_LOCATION` ,  `MAIL_GROUP` ,  `CUSTOM_CRITERIA` ,  `SENDER_EMAILID` ,  `DESCRIPTION` , `MEMBERSHIP_TYPE` ,  `GENDER` ,  `PHOTO_PROFILE` ,  `REPLY_TO_ENABLED` ,  `FROM_NAME` ,  `REPLY_TO_ADDRESS` ,  `MAX_COUNT_TO_BE_SENT` ,  `REQUIRE_AUTOLOGIN` ,  `FTO_FLAG` ,  `PRE_HEADER` ,  `PARTIALS` ) 
VALUES (
'1785',  '1785',  'membership_mailer_7.tpl', NULL , NULL , NULL ,  '29',  '1',  'membership@jeevansathi.com',  'Paid membership mailers on 7th day after registration.',  'D', NULL , NULL , NULL ,  'Jeevansathi Membership', NULL , NULL , NULL , NULL , NULL ,  ''
);

INSERT INTO  `MAILER_SUBJECT` (  `MAIL_ID` ,  `SUBJECT_TYPE` ,  `SUBJECT_CODE` ,  `DESCRIPTION` ) 
VALUES (
'1785',  'D',  'More Value. Less price. Contact the Profiles you like',  'Paid membership mailers on 7th day after registration'
);

INSERT INTO  `EMAIL_TYPE` (  `ID` ,  `MAIL_ID` ,  `TPL_LOCATION` ,  `HEADER_TPL` ,  `FOOTER_TPL` ,  `TEMPLATE_EX_LOCATION` ,  `MAIL_GROUP` ,  `CUSTOM_CRITERIA` ,  `SENDER_EMAILID` ,  `DESCRIPTION` , `MEMBERSHIP_TYPE` ,  `GENDER` ,  `PHOTO_PROFILE` ,  `REPLY_TO_ENABLED` ,  `FROM_NAME` ,  `REPLY_TO_ADDRESS` ,  `MAX_COUNT_TO_BE_SENT` ,  `REQUIRE_AUTOLOGIN` ,  `FTO_FLAG` ,  `PRE_HEADER` ,  `PARTIALS` ) 
VALUES (
'1786',  '1786',  'membership_mailer_21.tpl', NULL , NULL , NULL ,  '29',  '1',  'membership@jeevansathi.com',  'Paid membership mailers on 21st day after registration.',  'D', NULL , NULL , NULL ,  'Jeevansathi Membership', NULL , NULL , NULL , NULL , NULL ,  ''
);

INSERT INTO  `MAILER_SUBJECT` (  `MAIL_ID` ,  `SUBJECT_TYPE` ,  `SUBJECT_CODE` ,  `DESCRIPTION` ) 
VALUES (
'1786',  'D',  'Contact the Profiles you like. Know our Membership plans',  'Paid membership mailers on 21st day after registration'
);

INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP` VALUES ('MEMBERSHIP_DETAIL', 3, 'Membership Detail Page', 255, 0, 'NA', '');

INSERT INTO `LINK_MAILERS` VALUES ('', '', 'MEMBERSHIP_DETAIL', 'profile/mem_comparison.php', 'from_source=memMailer', 'Y', 'N');
