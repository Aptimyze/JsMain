use jeevansathi_mailer;


INSERT INTO `MAILER_SUBJECT` ( `MAIL_ID` , `SUBJECT_TYPE` , `SUBJECT_CODE` , `DESCRIPTION` )
VALUES (
'1822', 'D', 'Login at least once a week to continue to receive relevant interests
', 'Inactive_SECOND'
);

INSERT INTO `EMAIL_TYPE` VALUES ( 
1822, 1822, 'inactive.tpl', 'inactive_header.tpl', 'revamp_footer.tpl', NULL, 28, 1, 'info@jeevansathi.com', 'inactive mailer', 'D', NULL, NULL, NULL, 'Jeevansathi Info', NULL, NULL, NULL, NULL, NULL, '');


UPDATE  `MAILER_SUBJECT` SET  `SUBJECT_CODE` =  'We will soon make your profile inactive, login to continue to receive recommendations' WHERE  `MAIL_ID` =  '1819' AND CONVERT(  `SUBJECT_TYPE` USING utf8 ) =  'D' AND CONVERT(  `SUBJECT_CODE` USING utf8 ) = 'Important Information for you' AND CONVERT(  `DESCRIPTION` USING utf8 ) =  'Inactive' LIMIT 1 ;

use newjs;
ALTER TABLE  `INACTIVE_PROFILES` ADD  `TIME_INTERVAL` INT( 11 ) DEFAULT  '0' NOT NULL ;

ALTER TABLE  `INACTIVE_PROFILES` ADD PRIMARY KEY (  `PROFILEID` );
