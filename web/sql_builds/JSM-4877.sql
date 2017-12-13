use PROFILE_VERIFICATION;

CREATE TABLE  `AADHAAR_VERIFICATION_MAILER_LOG` (
 `PROFILEID` INT( 11 ) NOT NULL ,
PRIMARY KEY (  `PROFILEID` )
);

use jeevansathi_mailer;

INSERT INTO  `EMAIL_TYPE` (  `ID` ,  `MAIL_ID` ,  `TPL_LOCATION` ,  `HEADER_TPL` ,  `FOOTER_TPL` ,  `TEMPLATE_EX_LOCATION` ,  `MAIL_GROUP` ,  `CUSTOM_CRITERIA` ,  `SENDER_EMAILID` ,  `DESCRIPTION` ,  `MEMBERSHIP_TYPE` ,  `GENDER` , `PHOTO_PROFILE` ,  `REPLY_TO_ENABLED` ,  `FROM_NAME` ,  `REPLY_TO_ADDRESS` ,  `MAX_COUNT_TO_BE_SENT` ,  `REQUIRE_AUTOLOGIN` ,  `FTO_FLAG` ,  `PRE_HEADER` ,  `PARTIALS` ) 
VALUES (
'',  '1886',  'aadhaarVerificationMailer.tpl',  'top8_dpp_Review.tpl',  'revamp_footer.tpl', NULL ,  '27',  '1',  'info@jeevansathi.com', NULL ,  'D', NULL , NULL , NULL ,  'Jeevansathi Info', NULL , NULL , NULL , NULL , 'Please add info@jeevansathi.com to your address book to ensure delivery of this mail into you inbox',  ''
);

INSERT INTO  `MAILER_SUBJECT` (  `MAIL_ID` ,  `SUBJECT_TYPE` ,  `SUBJECT_CODE` ,  `DESCRIPTION` ) 
VALUES (
'1888',  'D',  'Get more Secure on Jeevansathi with linking of Aadhaar number.',  'subject line for aadhaar verification mailer'
);

use newjs;

INSERT INTO  `SMS_TYPE` (  `ID` ,  `SMS_KEY` ,  `SMS_TYPE` ,  `PRIORITY` ,  `SUBSCRIPTION` ,  `GENDER` ,  `COUNT` ,  `TIME_CRITERIA` ,  `CUSTOM_CRITERIA` ,  `SMS_SUBSCRIPTION` ,  `STATUS` ,  `MESSAGE` ) 
VALUES (
'',  'AADHAAR_VERIFICATION',  'I',  '2',  'A',  'A',  'SINGLE',  '0',  '0',  'SERVICE',  'Y',  'We are making Jeevansathi more secure! Link Aadhaar to your Profile for Verification. Click {EDIT_BASIC_PROFILE_URL}'
);

