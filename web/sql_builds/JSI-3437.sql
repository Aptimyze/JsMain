use jeevansathi_mailer;

UPDATE  `MAILER_SUBJECT` SET  `SUBJECT_CODE` =  '<var>{{NAME_OTHER_PROFILE:profileid=~$otherProfileId`,receiver_id=~$profileid`}}</var>  declined your interest' WHERE  `MAIL_ID` =  '1748' AND CONVERT(  `SUBJECT_TYPE` USING utf8 ) =  'P' AND CONVERT(  `DESCRIPTION` USING utf8 ) =  'Decline with photo' LIMIT 1 ;

UPDATE  `MAILER_SUBJECT` SET  `SUBJECT_CODE` =  '<var>{{NAME_OTHER_PROFILE:profileid=~$otherProfileId`,receiver_id=~$profileid`}}</var>  declined your interest' WHERE  `MAIL_ID` =  '1748' AND CONVERT(  `SUBJECT_TYPE` USING utf8 ) =  'N' AND CONVERT(  `DESCRIPTION` USING utf8 ) =  'Decline with out photo' LIMIT 1 ;

UPDATE  `MAILER_SUBJECT` SET  `SUBJECT_CODE` =  '<var>{{NAME_OTHER_PROFILE:profileid=~$otherProfileId`,receiver_id=~$profileid`}}</var> has Accepted your interest' WHERE  `MAIL_ID` =  '1742' AND CONVERT(  `SUBJECT_TYPE` USING utf8 ) =  'D' AND CONVERT(  `DESCRIPTION` USING utf8 ) =  'Acceptance Mailer' LIMIT 1 ;

UPDATE  `MAILER_SUBJECT` SET  `SUBJECT_CODE` =  '<var>{{NAME_OTHER_PROFILE:profileid=~$otherProfileId`,receiver_id=~$profileid`}}</var> has Cancelled interest in you.' WHERE  `MAIL_ID` =  '1758' AND CONVERT(  `SUBJECT_TYPE` USING utf8 ) =  'P'  AND CONVERT(  `DESCRIPTION` USING utf8 ) =  'Cancel mailer with photo' LIMIT 1 ;

UPDATE  `MAILER_SUBJECT` SET  `SUBJECT_CODE` =  '<var>{{NAME_OTHER_PROFILE:profileid=~$otherProfileId`,receiver_id=~$profileid`}}</var> has Cancelled interest in you. Add a photo to make your profile better ' WHERE  `MAIL_ID` =  '1758' AND CONVERT(  `DESCRIPTION` USING utf8 ) =  'Cancel mailer without photo' LIMIT 1 ;

UPDATE  `MAILER_SUBJECT` SET  `SUBJECT_CODE` =  '<var>{{NAME_OTHER_PROFILE:profileid=~$otherProfileId`,receiver_id=~$profileid`}}</var> has Cancelled interest in you. Please make your photo visible' WHERE  `MAIL_ID` =  '1758' AND CONVERT(  `DESCRIPTION` USING utf8 ) =  'Cancel mailer with photo visible on accept' LIMIT 1 ;

UPDATE  `MAILER_SUBJECT` SET  `SUBJECT_CODE` =  '<var>{{NAME_OTHER_PROFILE:profileid=~$otherProfileId`,receiver_id=~$profileid`}}</var> has Expressed Interest in you' WHERE  `MAIL_ID` =  '1754' AND CONVERT(  `SUBJECT_TYPE` USING utf8 ) =  'D' AND CONVERT(  `DESCRIPTION` USING utf8 ) =  'For EOI Mailer' LIMIT 1 ;

UPDATE  `MAILER_SUBJECT` SET  `SUBJECT_CODE` =  'Member <var>{{USERNAME:profileid=~$otherProfileId`}}</var> has sent you a reminder.' WHERE  `MAIL_ID` =  '1756' AND CONVERT(  `SUBJECT_TYPE` USING utf8 ) =  'D' AND CONVERT(  `DESCRIPTION` USING utf8 ) =  'Reminder subject' LIMIT 1 ;

INSERT INTO  `MAILER_TEMPLATE_VARIABLES_MAP` (  `VARIABLE_NAME` ,  `VARIABLE_PROCESSING_CLASS` ,  `DESCRIPTION` ,  `MAX_LENGTH` ,  `MAX_LENGTH_SMS` ,  `DEFAULT_VALUE` ,  `TPL_FORMAT` ) 
VALUES (
'NAME_OTHER_PROFILE',  '2',  'actual Name of the user of profile',  '30',  '6 ',  'NA',  ''
);
