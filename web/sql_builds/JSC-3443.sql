use table jeevansathi_mailer;

INSERT INTO  `LINK_MAILERS` (  `LINKID` ,  `APP_SCREEN_ID` ,  `LINK_NAME` ,  `LINK_URL` ,  `OTHER_GET_PARAMS` ,  `REQUIRED_AUTOLOGIN` ,  `OUTER_LINK` ) 
VALUES (
'74', NULL ,  'KUNDLI_PROFILE_LINK',  '/P/viewprofile.php',  'ownview=1&section=kundli',  'Y',  'N'
);

INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP` VALUES ('KUNDLI_PROFILE_LINK', 3, 'NA', 30, 0, 'NA', '');
