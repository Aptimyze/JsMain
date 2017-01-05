use newjs;
UPDATE  `SMS_TYPE` SET  `MESSAGE` =  'Hi, we have unverified your number {ISD_MOB} as it was reported invalid and we could not reach you. Please login and verify your number {URL_EDIT_PHONE}' WHERE  `ID` =  '16' LIMIT 1 ;

use jeevansathi_mailer;
INSERT INTO  `EMAIL_TYPE` (  `ID` ,  `MAIL_ID` ,  `TPL_LOCATION` ,  `HEADER_TPL` ,  `FOOTER_TPL` ,  `TEMPLATE_EX_LOCATION` ,  `MAIL_GROUP` ,  `CUSTOM_CRITERIA` ,  `SENDER_EMAILID` ,  `DESCRIPTION` ,  `MEMBERSHIP_TYPE` , `GENDER` ,  `PHOTO_PROFILE` ,  `REPLY_TO_ENABLED` ,  `FROM_NAME` ,  `REPLY_TO_ADDRESS` ,  `MAX_COUNT_TO_BE_SENT` ,  `REQUIRE_AUTOLOGIN` ,  `FTO_FLAG` ,  `PRE_HEADER` ,  `PARTIALS` ) 
VALUES (
'1838',  '1838',  'phoneUnverify.tpl', NULL ,  'revamp_footer.tpl', NULL ,  '43',  '1',  'info@jeevansathi.com',  'top 8 mailer to be sent after screening',  'D', NULL , NULL , NULL ,  'Jeevansathi Info', NULL , NULL , NULL , NULL , 'Please add info@jeevansathi.com to your address book to ensure delivery of this mail into you inbox',  ''
);
