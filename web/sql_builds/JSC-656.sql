INSERT INTO jeevansathi_mailer.`EMAIL_TYPE` ( `ID` , `MAIL_ID` , `TPL_LOCATION` , `HEADER_TPL` , `FOOTER_TPL` , `TEMPLATE_EX_LOCATION` , `MAIL_GROUP` , `CUSTOM_CRITERIA` , `SENDER_EMAILID` , `DESCRIPTION` , `MEMBERSHIP_TYPE` , `GENDER` , `PHOTO_PROFILE` , `REPLY_TO_ENABLED` , `FROM_NAME` , `REPLY_TO_ADDRESS` , `MAX_COUNT_TO_BE_SENT` , `REQUIRE_AUTOLOGIN` , `FTO_FLAG` , `PRE_HEADER` , `PARTIALS` )
VALUES (
'', '1803', 'we_talk_for_you_usage_description.tpl', NULL , NULL , NULL , '25', '1', 'membership@jeevansathi.com', 'Jeevansathi We Talk For You pack usage description', 'D', NULL , NULL , NULL , 'Jeevansathi Membership', 'NULL', NULL , NULL , NULL , NULL , ''
);

INSERT INTO jeevansathi_mailer.`MAILER_SUBJECT` ( `MAIL_ID` , `SUBJECT_TYPE` , `SUBJECT_CODE` , `DESCRIPTION` )
VALUES (
'1803', 'D', 'Details about your "We Talk for You" service', 'Jeevansathi We Talk For You usage description'
);
