INSERT INTO  `SMS_TYPE` (  `ID` ,  `SMS_KEY` ,  `SMS_TYPE` ,  `PRIORITY` ,  `SUBSCRIPTION` ,  `GENDER` ,  `COUNT` ,  `TIME_CRITERIA` ,  `CUSTOM_CRITERIA` ,  `SMS_SUBSCRIPTION` ,  `STATUS` ,  `MESSAGE` ) 
VALUES (
'',  'CANCEL',  'D',  '14',  'C1,C2',  'A',  'SINGLE',  '1',  '0',  'SERVICE',  'Y', 'Jeevansathi member {OTHER_USERNAME} has cancelled interest in u as yr profile has no pic. See phone/email of profiles for FREE if u mail snap to {PHOTO_EMAIL_ID} T&C Apply'
), (
'',  'CANCEL',  'D',  '14',  'C3',  'A',  'SINGLE',  '1',  '0',  'SERVICE',  'Y', 'Jeevansathi member {OTHER_USERNAME} has cancelled interest as yr profile is not verified. Verify by SMSing "Y" to {VALUEFRSTNO} and See Phone/Email of profiles. T&C Apply'
);
INSERT INTO  `SMS_TYPE` (  `ID` ,  `SMS_KEY` ,  `SMS_TYPE` ,  `PRIORITY` ,  `SUBSCRIPTION` ,  `GENDER` ,  `COUNT` ,  `TIME_CRITERIA` ,  `CUSTOM_CRITERIA` ,  `SMS_SUBSCRIPTION` ,  `STATUS` ,  `MESSAGE` ) 
VALUES (
'',  'DECLINE',  'D',  '13',  'C1',  'A',  'SINGLE',  '1',  '0',  'SERVICE',  'Y', 'Jeevansathi member {OTHER_USERNAME} has declined yr interest. Upload photo to get better response in future. You''ll also get FREE TRIAL OFFER worth Rs.1100/- T&C Apply '
), (
'',  'DECLINE',  'D',  '13',  'C2',  'A',  'SINGLE',  '1',  '0',  'SERVICE',  'Y', 'Jeevansathi member {OTHER_USERNAME} has declined yr profile as it has no photo. See phone/email of profiles for FREE if u upload photo/mail to {PHOTO_EMAIL_ID} T&C Apply'
);
INSERT INTO  `SMS_TYPE` (  `ID` ,  `SMS_KEY` ,  `SMS_TYPE` ,  `PRIORITY` ,  `SUBSCRIPTION` ,  `GENDER` ,  `COUNT` ,  `TIME_CRITERIA` ,  `CUSTOM_CRITERIA` ,  `SMS_SUBSCRIPTION` ,  `STATUS` ,  `MESSAGE` ) 
VALUES (
'',  'DECLINE',  'D',  '13',  'C3',  'A',  'SINGLE',  '1',  '0',  'SERVICE',  'Y', 'Jeevansathi member {OTHER_USERNAME} has declined yr profile as its phone is not verified. Verify by SMSing "Y" to {VALUEFRSTNO} and See Phone/Email of profiles. T&C Apply'
);
INSERT INTO  `SMS_TYPE` (  `ID` ,  `SMS_KEY` ,  `SMS_TYPE` ,  `PRIORITY` ,  `SUBSCRIPTION` ,  `GENDER` ,  `COUNT` ,  `TIME_CRITERIA` ,  `CUSTOM_CRITERIA` ,  `SMS_SUBSCRIPTION` ,  `STATUS` ,  `MESSAGE` ) 
VALUES (
'',  'FTO_SERVICE',  'D',  '29',  'C1,C2,C3,D1',  'A',  'SINGLE',  '1',  '0',  'SERVICE',  'Y', 'Dear member,Jeevansathi executive {FTO_AGENT} had spoken to you yesterday. Happy with way he/she spoke- SMS "H" to {VALUEFRSTNO}, If not satisfied then SMS "S"'
);
INSERT INTO  `SMS_TYPE` (  `ID` ,  `SMS_KEY` ,  `SMS_TYPE` ,  `PRIORITY` ,  `SUBSCRIPTION` ,  `GENDER` ,  `COUNT` ,  `TIME_CRITERIA` ,  `CUSTOM_CRITERIA` ,  `SMS_SUBSCRIPTION` ,  `STATUS` ,  `MESSAGE` ) 
VALUES (
'', 'UPLOAD_HOROSCOPE', 'D', '72' , 'D3,D4' , 'A', 'SINGLE' , '1', '0', 'SERVICE' , 'Y', 'Add horoscope on Jeevansathi & get 7 times more response. You may/may not believe in it, but for some it’s a must. SMS Date,Time,City of Birth to {HOROSCOPE_HELPLINE_NUMBER}'
);
