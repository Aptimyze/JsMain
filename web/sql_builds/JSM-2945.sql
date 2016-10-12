use jeevansathi_mailer;	

INSERT INTO `MAILER_SUBJECT` ( `MAIL_ID` , `SUBJECT_TYPE` , `SUBJECT_CODE` , `DESCRIPTION` ) VALUES ( '1839' , 'D', 'Astro compatibility report with member <var>{{USERNAME:profileid=~$otherProfile`}}</var> ', 'astro compatibility report' );

INSERT INTO  `EMAIL_TYPE` (  `ID` ,  `MAIL_ID` ,  `TPL_LOCATION` ,  `HEADER_TPL` ,  `FOOTER_TPL` ,  `TEMPLATE_EX_LOCATION` ,  `MAIL_GROUP` ,  `CUSTOM_CRITERIA` ,  `SENDER_EMAILID` ,  `DESCRIPTION` ,  `MEMBERSHIP_TYPE` ,  `GENDER` , `PHOTO_PROFILE` ,  `REPLY_TO_ENABLED` ,  `FROM_NAME` ,  `REPLY_TO_ADDRESS` ,  `MAX_COUNT_TO_BE_SENT` ,  `REQUIRE_AUTOLOGIN` ,  `FTO_FLAG` ,  `PRE_HEADER` ,  `PARTIALS` ) 
VALUES (
'',  '1839', 'astroCompatibiltyMailer.tpl' ,  'top8_dpp_Review.tpl',  'revamp_footer.tpl', NULL ,  '27',  '1',  'info@jeevansathi.com',  'astro compatibility mail',  'D', NULL , NULL , NULL ,  'Jeevansathi Info', NULL , NULL , NULL , NULL , NULL ,  ''
);
