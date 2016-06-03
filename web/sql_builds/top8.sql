use jeevansathi_mailer;


INSERT INTO `EMAIL_TYPE` VALUES (1782, 1782, 'top8.tpl', NULL, 'revamp_footer.tpl', NULL, 27, 1, 'info@jeevansathi.com', 'top 8 mailer to be sent after screening', 'D', NULL, NULL, NULL, 'Jeevansathi Info', NULL, NULL, NULL, NULL, NULL, '');


INSERT INTO  `MAILER_SUBJECT` (  `MAIL_ID` ,  `SUBJECT_TYPE` ,  `SUBJECT_CODE` ,  `DESCRIPTION` ) 
VALUES (
'1782',  'D',  'Get Started with Jeevansathi.com',  'top 8 mailer to be sent after welcome screening'
);


INSERT INTO  `MAILER_TEMPLATE_VARIABLES_MAP` (  `VARIABLE_NAME` ,  `VARIABLE_PROCESSING_CLASS` ,  `DESCRIPTION` ,  `MAX_LENGTH` ,  `MAX_LENGTH_SMS` ,  `DEFAULT_VALUE` ,  `TPL_FORMAT` ) 
VALUES (
'shortlist',  '3',  'NA',  '1000',  '255',  'NA',  ''
);

INSERT INTO  `LINK_MAILERS` (  `LINKID` ,  `LINK_NAME` ,  `LINK_URL` ,  `OTHER_GET_PARAMS` ,  `REQUIRED_AUTOLOGIN` ,  `OUTER_LINK` ) 
VALUES (
'',  'MATCH_ALERT',  'profile/contacts_made_received.php',  'page=matches&filter=R',  'Y',  'N'
);


INSERT INTO  `MAILER_TEMPLATE_VARIABLES_MAP` (  `VARIABLE_NAME` ,  `VARIABLE_PROCESSING_CLASS` ,  `DESCRIPTION` ,  `MAX_LENGTH` ,  `MAX_LENGTH_SMS` ,  `DEFAULT_VALUE` ,  `TPL_FORMAT` ) 
VALUES (
'MATCH_ALERT',  '3',  'match alert url in my contacts page',  '1000',  '255',  'NA',  ''
);

INSERT INTO  `LINK_MAILERS` (  `LINKID` ,  `LINK_NAME` ,  `LINK_URL` ,  `OTHER_GET_PARAMS` ,  `REQUIRED_AUTOLOGIN` ,  `OUTER_LINK` ) 
VALUES (
'',  'MY_DPP',  'profile/dpp', NULL ,  'Y',  'N'
);

INSERT INTO  `MAILER_TEMPLATE_VARIABLES_MAP` (  `VARIABLE_NAME` ,  `VARIABLE_PROCESSING_CLASS` ,  `DESCRIPTION` ,  `MAX_LENGTH` ,  `MAX_LENGTH_SMS` ,  `DEFAULT_VALUE` ,  `TPL_FORMAT` ) 
VALUES (
'MY_DPP',  '3',  'dpp page',  '1000',  '255',  'NA',  ''
);


INSERT INTO  `LINK_MAILERS` (  `LINKID` ,  `LINK_NAME` ,  `LINK_URL` ,  `OTHER_GET_PARAMS` ,  `REQUIRED_AUTOLOGIN` ,  `OUTER_LINK` ) 
VALUES (
'',  'MEMBERSHIP',  'profile/mem_comparison.php',  'from_source=top8Mailer',  'Y',  'N'
);

INSERT INTO  `MAILER_TEMPLATE_VARIABLES_MAP` (  `VARIABLE_NAME` ,  `VARIABLE_PROCESSING_CLASS` ,  `DESCRIPTION` ,  `MAX_LENGTH` ,  `MAX_LENGTH_SMS` ,  `DEFAULT_VALUE` ,  `TPL_FORMAT` ) 
VALUES (
'MEMBERSHIP',  '3',  'membership page',  '1000',  '255',  'NA',  ''
);
