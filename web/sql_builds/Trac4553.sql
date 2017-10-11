use jeevansathi_mailer;


INSERT INTO `EMAIL_TYPE` VALUES (1783, 1783, 'automatedResponse.tpl', NULL, NULL, NULL, 28, 1, 'info@jeevansathi.com', NULL, 'D', NULL, NULL, NULL, 'Jeevansathi Info', NULL, NULL, NULL, NULL, NULL, '');

INSERT INTO  `MAILER_SUBJECT` (  `MAIL_ID` ,  `SUBJECT_TYPE` ,  `SUBJECT_CODE` ,  `DESCRIPTION` ) 
VALUES (
'1783',  'D',  'The changes made by you in your profile are active now',  'edit screening profiles mail subject line'
);
	


INSERT INTO  `LINK_MAILERS` (  `LINKID` ,  `LINK_NAME` ,  `LINK_URL` ,  `OTHER_GET_PARAMS` ,  `REQUIRED_AUTOLOGIN` ,  `OUTER_LINK` ) 
VALUES (
'',  'ABOUT_ME',  'profile/viewprofile.php',  'ownview=1&EditWhatNew=PMF',  'Y',  'N'
);

INSERT INTO  `MAILER_TEMPLATE_VARIABLES_MAP` (  `VARIABLE_NAME` ,  `VARIABLE_PROCESSING_CLASS` ,  `DESCRIPTION` ,  `MAX_LENGTH` ,  `MAX_LENGTH_SMS` ,  `DEFAULT_VALUE` ,  `TPL_FORMAT` ) 
VALUES (
'ABOUT_ME',  '3',  'about your info layer on my profile page',  '255',  '0',  'NA',  ''
);



INSERT INTO  `LINK_MAILERS` (  `LINKID` ,  `LINK_NAME` ,  `LINK_URL` ,  `OTHER_GET_PARAMS` ,  `REQUIRED_AUTOLOGIN` ,  `OUTER_LINK` ) 
VALUES (
'',  'EDU_OCC',  'profile/viewprofile.php',  'ownview=1&EditWhatNew=EduOcc',  'Y',  'N'
);

INSERT INTO  `MAILER_TEMPLATE_VARIABLES_MAP` (  `VARIABLE_NAME` ,  `VARIABLE_PROCESSING_CLASS` ,  `DESCRIPTION` ,  `MAX_LENGTH` ,  `MAX_LENGTH_SMS` ,  `DEFAULT_VALUE` ,  `TPL_FORMAT` ) 
VALUES (
'EDU_OCC',  '3',  'about your info layer on my profile page',  '255',  '0',  'NA',  ''
);




INSERT INTO  `LINK_MAILERS` (  `LINKID` ,  `LINK_NAME` ,  `LINK_URL` ,  `OTHER_GET_PARAMS` ,  `REQUIRED_AUTOLOGIN` ,  `OUTER_LINK` ) 
VALUES (
'',  'PROFILE_RELIGION',  'profile/viewprofile.php',  'ownview=1&EditWhatNew=RelEthnic',  'Y',  'N'
);

INSERT INTO  `MAILER_TEMPLATE_VARIABLES_MAP` (  `VARIABLE_NAME` ,  `VARIABLE_PROCESSING_CLASS` ,  `DESCRIPTION` ,  `MAX_LENGTH` ,  `MAX_LENGTH_SMS` ,  `DEFAULT_VALUE` ,  `TPL_FORMAT` ) 
VALUES (
'PROFILE_RELIGION',  '3',  'about your info layer on my profile page',  '255',  '0',  'NA',  ''
);



INSERT INTO  `LINK_MAILERS` (  `LINKID` ,  `LINK_NAME` ,  `LINK_URL` ,  `OTHER_GET_PARAMS` ,  `REQUIRED_AUTOLOGIN` ,  `OUTER_LINK` ) 
VALUES (
'',  'PROFILE_FAMILY',  'profile/viewprofile.php',  'ownview=1&EditWhatNew=FamilyDetails',  'Y',  'N'
);

INSERT INTO  `MAILER_TEMPLATE_VARIABLES_MAP` (  `VARIABLE_NAME` ,  `VARIABLE_PROCESSING_CLASS` ,  `DESCRIPTION` ,  `MAX_LENGTH` ,  `MAX_LENGTH_SMS` ,  `DEFAULT_VALUE` ,  `TPL_FORMAT` ) 
VALUES (
'PROFILE_FAMILY',  '3',  'about your info layer on my profile page',  '255',  '0',  'NA',  ''
);




INSERT INTO  `LINK_MAILERS` (  `LINKID` ,  `LINK_NAME` ,  `LINK_URL` ,  `OTHER_GET_PARAMS` ,  `REQUIRED_AUTOLOGIN` ,  `OUTER_LINK` ) 
VALUES (
'',  'PROFILE_HOBBIES',  'profile/viewprofile.php',  'ownview=1&EditWhatNew=Interests',  'Y',  'N'
);

INSERT INTO  `MAILER_TEMPLATE_VARIABLES_MAP` (  `VARIABLE_NAME` ,  `VARIABLE_PROCESSING_CLASS` ,  `DESCRIPTION` ,  `MAX_LENGTH` ,  `MAX_LENGTH_SMS` ,  `DEFAULT_VALUE` ,  `TPL_FORMAT` ) 
VALUES (
'PROFILE_HOBBIES',  '3',  'about your info layer on my profile page',  '255',  '0',  'NA',  ''
);
