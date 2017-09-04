use newjs;

ALTER TABLE  `SMS_TYPE` CHANGE  `SMS_KEY`  `SMS_KEY` VARCHAR( 25 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL ;


INSERT INTO  `SMS_TYPE` (  `ID` ,  `SMS_KEY` ,  `SMS_TYPE` ,  `PRIORITY` ,  `SUBSCRIPTION` ,  `GENDER` ,  `COUNT` ,  `TIME_CRITERIA` ,  `CUSTOM_CRITERIA` ,  `SMS_SUBSCRIPTION` ,  `STATUS` ,  `MESSAGE` ) 
VALUES (
'',  'EXCLUSIVE_PROPOSAL_SMS',  'I',  '',  'P',  'A',  'SINGLE',  '0',  '0',  'SERVICE',  'Y',  'Hi, JS Exclusive client {PROFILE_ID} likes your profile.We will call you shortly to know your response. View the profile here {DESCRIPTION_LINK}.'
);
