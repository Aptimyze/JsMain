use jeevansathi_mailer;
UPDATE  `MAILER_SUBJECT` SET  `SUBJECT_CODE` =  'Your Jeevansathi.com Password information', `DESCRIPTION` =  'Your Jeevansathi.com Password information' WHERE  `MAIL_ID` =  '1778' AND `SUBJECT_TYPE` = 'D' AND `SUBJECT_CODE` =  'Jeevansathi Password Mail' AND `DESCRIPTION` =  'Jeevansathi Password Mail' LIMIT 1 ;
UPDATE  `EMAIL_TYPE` SET `SENDER_EMAILID` =  'info@jeevansathi.com', `FROM_NAME` =  'Jeevansathi info'WHERE  `ID` =  '1778' LIMIT 1 ;
use newjs;
ALTER TABLE  `JPROFILE`  MODIFY `PASSWORD` VARCHAR( 75 ), ADD  `VERIFY_ACTIVATED_DT` DATETIME NOT NULL ;
CREATE TABLE `PASSWORDS` (
 `PROFILEID` int(12) NOT NULL,
 `PASSWORD` varchar(75) DEFAULT NULL,
 PRIMARY KEY (`PROFILEID`)
);
CREATE TABLE `SERIES` (
 `ID` varchar(32) DEFAULT NULL,
 `HASH_ID` varchar(32) DEFAULT NULL,
 `PROFILEID` int(11) NOT NULL,
 `TIME` datetime NOT NULL,
 `USED` varchar(1) NOT NULL
);
