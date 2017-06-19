use jeevansathi_mailer;

INSERT INTO `EMAIL_TYPE` VALUES (1794, 1794, 'improveProfileScore.tpl', 'improveScoreHeader.tpl', 'revamp_footer.tpl', NULL, 31, 1, 'info@jeevansathi.com', 'send an Email asking the user to fill key details about his/her profile. The Email should be fired 1 day, 7 days, 14 days, 21 days and 30 days after registration if profile completion score is less than 60%.', 'D', NULL, NULL, NULL, 'Jeevansathi info', NULL, NULL, NULL, NULL, NULL, '');

INSERT INTO  `MAILER_SUBJECT` (  `MAIL_ID` ,  `SUBJECT_TYPE` ,  `SUBJECT_CODE` ,  `DESCRIPTION` ) 
VALUES (
'1794',  'D',  'Get 5 times more responses. Add important details about yourself and your family..',  'Improve Profile Score'
);


INSERT INTO `LINK_MAILERS` (`APP_SCREEN_ID`,`LINK_NAME` ,`LINK_URL`,`OTHER_GET_PARAMS`,`REQUIRED_AUTOLOGIN`,`OUTER_LINK`)   VALUES ('a12', 'EDUCATION', 'profile/viewprofile.php', 'ownview=1&EditWhatNew=EduOcc&editSec=Edu', 'Y', 'N');
INSERT INTO `LINK_MAILERS` (`APP_SCREEN_ID`,`LINK_NAME` ,`LINK_URL`,`OTHER_GET_PARAMS`,`REQUIRED_AUTOLOGIN`,`OUTER_LINK`)   VALUES ('a12', 'OCCUPATION', 'profile/viewprofile.php', 'ownview=1&EditWhatNew=EduOcc&editSec=Occ', 'Y', 'N');

INSERT INTO  `MAILER_TEMPLATE_VARIABLES_MAP` (  `VARIABLE_NAME` ,  `VARIABLE_PROCESSING_CLASS` ,  `DESCRIPTION` ,  `MAX_LENGTH` ,  `MAX_LENGTH_SMS` ,  `DEFAULT_VALUE` ,  `TPL_FORMAT` ) 
VALUES (
'EDUCATION',  '3',  'Education Layer or section',  '255',  '0',  'NA',  ''
), (
'OCCUPATION',  '3',  'Occupation Layer or section',  '255',  '0',  'NA',  ''
);