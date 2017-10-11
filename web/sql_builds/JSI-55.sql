use newjs;
INSERT INTO `SMS_TYPE` VALUES (184, 'INCOMPLETE_TASK0', 'I', 2, 'A', 'A', 'SINGLE', 0, 0, 'SERVICE', 'Y', 'Your Jeevansathi profile is incomplete. Enter few more details to start getting contacted by your matches. Complete your profile now. {INCOMP_URL}');

use MIS;
CREATE TABLE  `INCOMPLETE_SMS` (
 `PROFILEID` INT( 11 ) NOT NULL ,
 `DATE` DATETIME NOT NULL ,
 `PAGE_DISPLAY` CHAR( 1 ) NOT NULL ,
 `PAGE_SUBMIT` CHAR( 1 ) NOT NULL ,
PRIMARY KEY (  `PROFILEID` ) ,
INDEX (  `DATE` )
) COMMENT =  'For Tracking incomplete Users from SMS';
