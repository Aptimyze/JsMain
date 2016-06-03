use jeevansathi_mailer;


INSERT INTO `MAILER_SUBJECT` ( `MAIL_ID` , `SUBJECT_TYPE` , `SUBJECT_CODE` , `DESCRIPTION` )
VALUES (
'1819', 'D', 'Important Information for you', 'Inactive'
);

INSERT INTO `EMAIL_TYPE` VALUES ( 
1819, 1819, 'incomplete.tpl', 'inactive_header.tpl', 'revamp_footer.tpl', NULL, 27, 1, 'info@jeevansathi.com', 'inactive mailer', 'D', NULL, NULL, NULL, 'Jeevansathi Info', NULL, NULL, NULL, NULL, NULL, '');


use newjs;


CREATE TABLE  `INACTIVE_PROFILES` (
 `PROFILEID` INT( 11 ) DEFAULT NULL ,
 `STATUS` ENUM(  'Y',  'N' ) DEFAULT  'N'
);