use jeevansathi_mailer;

DELETE FROM `EMAIL_TYPE` WHERE `ID` = '1822' LIMIT 1;
DELETE FROM `EMAIL_TYPE` WHERE `ID` = '1819' LIMIT 1;


INSERT INTO `EMAIL_TYPE` VALUES (1822, 1822, 'inactive.tpl', 'inactive_header.tpl', 'revamp_footer.tpl', NULL, 37, 1, 'info@jeevansathi.com', 'inactive mailer', 'D', NULL, NULL, NULL, 'Jeevansathi Info', NULL, NULL, NULL, NULL, 'Please add  info@jeevansathi.com to your address book to ensure delivery of this mail into you inbox', '');

INSERT INTO `EMAIL_TYPE` VALUES (1823, 1823, 'inactive.tpl', 'inactive_header.tpl', 'revamp_footer.tpl', NULL, 38, 1, 'info@jeevansathi.com', 'inactive mailer', 'D', NULL, NULL, NULL, 'Jeevansathi Info', NULL, NULL, NULL, NULL, 'Please add  info@jeevansathi.com to your address book to ensure delivery of this mail into you inbox', '');

INSERT INTO `EMAIL_TYPE` VALUES (1824, 1824, 'inactive.tpl', 'inactive_header.tpl', 'revamp_footer.tpl', NULL, 39, 1, 'info@jeevansathi.com', 'inactive mailer', 'D', NULL, NULL, NULL, 'Jeevansathi Info', NULL, NULL, NULL, NULL, 'Please add  info@jeevansathi.com to your address book to ensure delivery of this mail into you inbox', '');

INSERT INTO `EMAIL_TYPE` VALUES (1825, 1825, 'inactive.tpl', 'inactive_header.tpl', 'revamp_footer.tpl', NULL, 40, 1, 'info@jeevansathi.com', 'inactive mailer', 'D', NULL, NULL, NULL, 'Jeevansathi Info', NULL, NULL, NULL, NULL, 'Please add  info@jeevansathi.com to your address book to ensure delivery of this mail into you inbox', '');

INSERT INTO `EMAIL_TYPE` VALUES (1826, 1826, 'inactive.tpl', 'inactive_header.tpl', 'revamp_footer.tpl', NULL, 41, 1, 'info@jeevansathi.com', 'inactive mailer', 'D', NULL, NULL, NULL, 'Jeevansathi Info', NULL, NULL, NULL, NULL, 'Please add  info@jeevansathi.com to your address book to ensure delivery of this mail into you inbox', '');


INSERT INTO `EMAIL_TYPE` VALUES (1827, 1827, 'incomplete.tpl', 'inactive_header.tpl', 'revamp_footer.tpl', NULL, 34, 1, 'info@jeevansathi.com', 'incomplete mailer', 'D', NULL, NULL, NULL, 'Jeevansathi Info', NULL, NULL, NULL, NULL, 'Please add info@jeevansathi.com to your address book to ensure delivery of this mail into you inbox', '');

INSERT INTO `EMAIL_TYPE` VALUES (1828, 1828, 'incomplete.tpl', 'inactive_header.tpl', 'revamp_footer.tpl', NULL, 35, 1, 'info@jeevansathi.com', 'incomplete mailer', 'D', NULL, NULL, NULL, 'Jeevansathi Info', NULL, NULL, NULL, NULL, 'Please add info@jeevansathi.com to your address book to ensure delivery of this mail into you inbox', '');

INSERT INTO `EMAIL_TYPE` VALUES (1829, 1829, 'incomplete.tpl', 'inactive_header.tpl', 'revamp_footer.tpl', NULL, 36, 1, 'info@jeevansathi.com', 'incomplete mailer', 'D', NULL, NULL, NULL, 'Jeevansathi Info', NULL, NULL, NULL, NULL, 'Please add info@jeevansathi.com to your address book to ensure delivery of this mail into you inbox', '');


DELETE FROM `MAILER_SUBJECT` WHERE `MAIL_ID` = '1822' AND CONVERT(`SUBJECT_TYPE` USING utf8) = 'D' AND CONVERT(`SUBJECT_CODE` USING utf8) = 'Login at least once a week to continue to receive relevant interests\r\n' AND CONVERT(`DESCRIPTION` USING utf8) = 'Inactive_SECOND' LIMIT 1;
DELETE FROM `MAILER_SUBJECT` WHERE `MAIL_ID` = '1819' AND CONVERT(`SUBJECT_TYPE` USING utf8) = 'D' AND CONVERT(`SUBJECT_CODE` USING utf8) = 'Important Information for you jhg' AND CONVERT(`DESCRIPTION` USING utf8) = 'Inactive' LIMIT 1;



INSERT INTO `MAILER_SUBJECT` ( `MAIL_ID` , `SUBJECT_TYPE` , `SUBJECT_CODE` , `DESCRIPTION` )
VALUES (
'1822', 'D', 'Login at least once a week to continue to receive relevant interests
', 'Inactive_SECOND'
);

INSERT INTO `MAILER_SUBJECT` ( `MAIL_ID` , `SUBJECT_TYPE` , `SUBJECT_CODE` , `DESCRIPTION` )
VALUES (
'1823', 'D', 'Login at least once a week to continue to receive relevant interests
', 'Inactive_SECOND'
);

INSERT INTO `MAILER_SUBJECT` ( `MAIL_ID` , `SUBJECT_TYPE` , `SUBJECT_CODE` , `DESCRIPTION` )
VALUES (
'1824', 'D', 'Login at least once a week to continue to receive relevant interests
', 'Inactive_SECOND'
);

INSERT INTO `MAILER_SUBJECT` ( `MAIL_ID` , `SUBJECT_TYPE` , `SUBJECT_CODE` , `DESCRIPTION` )
VALUES (
'1825', 'D', 'Login at least once a week to continue to receive relevant interests
', 'Inactive_SECOND'
);

INSERT INTO `MAILER_SUBJECT` ( `MAIL_ID` , `SUBJECT_TYPE` , `SUBJECT_CODE` , `DESCRIPTION` )
VALUES (
'1826', 'D', 'Login at least once a week to continue to receive relevant interests
', 'Inactive_SECOND'
);

INSERT INTO `MAILER_SUBJECT` ( `MAIL_ID` , `SUBJECT_TYPE` , `SUBJECT_CODE` , `DESCRIPTION` )
VALUES (
'1827', 'D', 'We will soon make your profile inactive, login to continue to receive recommendations
', 'Inactive_SECOND'
);

INSERT INTO `MAILER_SUBJECT` ( `MAIL_ID` , `SUBJECT_TYPE` , `SUBJECT_CODE` , `DESCRIPTION` )
VALUES (
'1828', 'D', 'We will soon make your profile inactive, login to continue to receive recommendations
', 'Inactive_SECOND'
);

INSERT INTO `MAILER_SUBJECT` ( `MAIL_ID` , `SUBJECT_TYPE` , `SUBJECT_CODE` , `DESCRIPTION` )
VALUES (
'1829', 'D', 'We will soon make your profile inactive, login to continue to receive recommendations
', 'Inactive_SECOND'
);
