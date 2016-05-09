use newjs;

INSERT INTO  `SMS_TYPE` (  `ID` ,  `SMS_KEY` ,  `SMS_TYPE` ,  `PRIORITY` ,  `SUBSCRIPTION` ,  `GENDER` ,  `COUNT` ,  `TIME_CRITERIA` ,  `CUSTOM_CRITERIA` ,  `SMS_SUBSCRIPTION` ,  `STATUS` ,  `MESSAGE` ) 
VALUES (
'',  'FVD',  'I',  '1',  'A',  'A',  'SINGLE',  '',  '0',  'SERVICE',  'Y',  'Dear {USERNAME_FULL}, it was great meeting you! Your profile has now been marked as ''Verified by personal visit'' on the website.'
);

use incentive;

CREATE TABLE  `FVD_SMS_SENT_LIST` (
 `PROFILEID` INT( 11 ) UNSIGNED DEFAULT NULL ,
UNIQUE (
`PROFILEID`
)
);
