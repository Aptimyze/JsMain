ALTER TABLE  jsadmin.PSWRDS ADD  `FIRST_NAME` VARCHAR( 50 ) NOT NULL ,
ADD  `LAST_NAME` VARCHAR( 50 ) NOT NULL ;

INSERT INTO  newjs.SMS_TYPE (  `ID` ,  `SMS_KEY` ,  `SMS_TYPE` ,  `PRIORITY` ,  `SUBSCRIPTION` ,  `GENDER` ,  `COUNT` ,  `TIME_CRITERIA` ,  `CUSTOM_CRITERIA` ,  `SMS_SUBSCRIPTION` ,  `STATUS` ,  `MESSAGE` ) 
VALUES (
'178',  'AGENT_KYC',  'I',  '1',  'A',  'A',  'SINGLE',  '0',  '0',  'SERVICE',  'Y',  'Your Relationship Executive {SANAME} , cell: {SAPHONE} will call you to schedule a verification visit at your home / office. More info call 9971176314'
);

UPDATE  newjs.SMS_TYPE SET  `STATUS` =  'N' WHERE  `SMS_KEY` =  'REGISTER_KYC';
