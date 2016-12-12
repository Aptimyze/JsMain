use jeevansathi_mailer;
UPDATE  `MAILER_SUBJECT` SET  `SUBJECT_CODE` =  '<var>{{USERNAME:profileid=~$otherProfile`}}</var> has Cancelled interest in you.' WHERE  `MAIL_ID` =  '1758' AND CONVERT(  `SUBJECT_TYPE` USING utf8 ) =  'P' AND CONVERT( `SUBJECT_CODE` USING utf8 ) =  '<var>{{USERNAME:profileid=~$otherProfile`}}</var> has Cancelled interest in you. See suggested matches ' AND CONVERT(  `DESCRIPTION` USING utf8 ) =  'Cancel mailer with photo' LIMIT 1 ;
UPDATE  `EMAIL_TYPE` SET  `HEADER_TPL` = NULL ,
`FOOTER_TPL` = NULL ,
`TEMPLATE_EX_LOCATION` = NULL ,
`DESCRIPTION` = NULL ,
`GENDER` = NULL ,
`PHOTO_PROFILE` = NULL ,
`REPLY_TO_ENABLED` = NULL ,
`FROM_NAME` =  'Jeevansathi Contacts',
`REPLY_TO_ADDRESS` = NULL ,
`MAX_COUNT_TO_BE_SENT` = NULL ,
`REQUIRE_AUTOLOGIN` = NULL ,
`FTO_FLAG` = NULL WHERE  `ID` =  '1758' LIMIT 1 ;
