use newjs

INSERT INTO  `SMS_TYPE` (  `ID` ,  `SMS_KEY` ,  `SMS_TYPE` ,  `PRIORITY` ,  `SUBSCRIPTION` ,  `GENDER` ,  `COUNT` ,  `TIME_CRITERIA` ,  `CUSTOM_CRITERIA` ,  `SMS_SUBSCRIPTION` ,  `STATUS` ,  `MESSAGE` ) 
VALUES (
'',  'PROFILECOMPLETION_2',  'D',  '3',  'A',  'A',  'SINGLE',  '0',  '0',  'SERVICE',  'Y',  'Get more interests and more acceptances. Just add details about your Family at {URL_FAMILY} now.'
);




INSERT INTO  `SMS_TYPE` (  `ID` ,  `SMS_KEY` ,  `SMS_TYPE` ,  `PRIORITY` ,  `SUBSCRIPTION` ,  `GENDER` ,  `COUNT` ,  `TIME_CRITERIA` ,  `CUSTOM_CRITERIA` ,  `SMS_SUBSCRIPTION` ,  `STATUS` ,  `MESSAGE` ) 
VALUES (
'',  'PROFILECOMPLETION_3',  'D',  '8',  'A',  'A',  'SINGLE',  '0',  '0',  'SERVICE',  'Y',  'To get more interests and more acceptances. Add details about your Education at  {URL_EDUCATION} now.'
);


INSERT INTO  `SMS_TYPE` (  `ID` ,  `SMS_KEY` ,  `SMS_TYPE` ,  `PRIORITY` ,  `SUBSCRIPTION` ,  `GENDER` ,  `COUNT` ,  `TIME_CRITERIA` ,  `CUSTOM_CRITERIA` ,  `SMS_SUBSCRIPTION` ,  `STATUS` ,  `MESSAGE` ) 
VALUES (
'',  'PROFILECOMPLETION_4',  'D',  '15',  'A',  'A',  'SINGLE',  '0',  '0',  'SERVICE',  'Y',  'Get more interests and more acceptances - Add your Career Details at {URL_CAREER} now.'
);


ALTER TABLE  `SMS_TEMP_TABLE` ADD  `VERIFY_ACTIVATED_DT` DATETIME DEFAULT NULL ;

ALTER TABLE  `SMS_TEMP_TABLE` ADD  `FAMILYINFO` TEXT DEFAULT NULL ;

ALTER TABLE  `SMS_TEMP_TABLE` ADD  `EDUCATION` TEXT DEFAULT NULL ;

ALTER TABLE  `SMS_TEMP_TABLE` ADD  `JOB_INFO` TEXT DEFAULT NULL ;
