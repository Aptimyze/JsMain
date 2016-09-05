use jeevansathi_mailer;

INSERT INTO jeevansathi_mailer.`MAILER_TEMPLATE_VARIABLES_MAP` (`VARIABLE_NAME`, `VARIABLE_PROCESSING_CLASS`, `DESCRIPTION`, `MAX_LENGTH`, `MAX_LENGTH_SMS`, `DEFAULT_VALUE`, `TPL_FORMAT`) VALUES ('CURR_MAIL_DATE', 2, 'Current date of mail', 30, 0, 'NA', '');

INSERT INTO jeevansathi_mailer.`EMAIL_TYPE` ( `ID` , `MAIL_ID` , `TPL_LOCATION` , `HEADER_TPL` , `FOOTER_TPL` , `TEMPLATE_EX_LOCATION` , `MAIL_GROUP` , `CUSTOM_CRITERIA` , `SENDER_EMAILID` , `DESCRIPTION` , `MEMBERSHIP_TYPE` , `GENDER` , `PHOTO_PROFILE` , `REPLY_TO_ENABLED` , `FROM_NAME` , `REPLY_TO_ADDRESS` , `MAX_COUNT_TO_BE_SENT` , `REQUIRE_AUTOLOGIN` , `FTO_FLAG` , `PRE_HEADER` , `PARTIALS` ) VALUES (
'', '1837', 'exclusiveServiceIIMailer.tpl', NULL , NULL , NULL , '25', '1', 'membership@jeevansathi.com', 'Jeevansathi Exclusive Service II', 'D', NULL , NULL , NULL , 'Jeevansathi Membership', 'NULL', NULL , NULL , NULL , NULL , '');

INSERT INTO jeevansathi_mailer.`MAILER_SUBJECT` ( `MAIL_ID` , `SUBJECT_TYPE` , `SUBJECT_CODE` , `DESCRIPTION` ) VALUES (
'1837', 'D', 'I have shortlisted some profiles for you, please go through them | {CURR_MAIL_DATE}', 'Jeevansathi Exclusive Service II');