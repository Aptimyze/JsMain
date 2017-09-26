use jeevansathi_mailer;

UPDATE  `MAILER_SUBJECT` SET  `SUBJECT_CODE` =  '<var>{{NAME_PROFILE:profileid=~$otherProfile`}}</var>  declined your interest' WHERE  `MAIL_ID` =  '1748' AND CONVERT(  `SUBJECT_TYPE` USING utf8 ) =  'P' AND CONVERT(  `SUBJECT_CODE` USING utf8 ) = '<var>{{USERNAME:profileid=~$otherProfile`}}</var>  declined your interest' AND CONVERT(  `DESCRIPTION` USING utf8 ) =  'Decline with photo' LIMIT 1 ;

UPDATE  `MAILER_SUBJECT` SET  `SUBJECT_CODE` =  '<var>{{NAME_PROFILE:profileid=~$otherProfile`}}</var>  declined your interest' WHERE  `MAIL_ID` =  '1748' AND CONVERT(  `SUBJECT_TYPE` USING utf8 ) =  'N' AND CONVERT(  `SUBJECT_CODE` USING utf8 ) = '<var>{{USERNAME:profileid=~$otherProfile`}}</var>  declined your interest' AND CONVERT(  `DESCRIPTION` USING utf8 ) =  'Decline with out photo' LIMIT 1 ;

UPDATE  `MAILER_SUBJECT` SET  `SUBJECT_CODE` =  '<var>{{NAME_PROFILE:profileid=~$otherProfile`}}</var> has Accepted your interest' WHERE  `MAIL_ID` =  '1742' AND CONVERT(  `SUBJECT_TYPE` USING utf8 ) =  'D' AND CONVERT(  `SUBJECT_CODE` USING utf8 ) = '<var>{{USERNAME:profileid=~$otherProfile`}}</var> has Accepted your interest' AND CONVERT(  `DESCRIPTION` USING utf8 ) =  'Acceptance Mailer' LIMIT 1 ;

UPDATE  `MAILER_SUBJECT` SET  `SUBJECT_CODE` =  '<var>{{NAME_PROFILE:profileid=~$otherProfile`}}</var> has Cancelled interest in you.' WHERE  `MAIL_ID` =  '1758' AND CONVERT(  `SUBJECT_TYPE` USING utf8 ) =  'P' AND CONVERT(  `SUBJECT_CODE` USING utf8 ) = '<var>{{USERNAME:profileid=~$otherProfile`}}</var> has Cancelled interest in you.' AND CONVERT(  `DESCRIPTION` USING utf8 ) =  'Cancel mailer with photo' LIMIT 1 ;

UPDATE  `MAILER_SUBJECT` SET  `SUBJECT_CODE` =  '<var>{{NAME_PROFILE:profileid=~$otherProfile`}}</var> has Cancelled interest in you. Add a photo to make your profile better ' WHERE  `MAIL_ID` =  '1758' AND CONVERT(  `SUBJECT_TYPE` USING utf8 ) =  'N' AND CONVERT(  `SUBJECT_CODE` USING utf8 ) =  '<var>{{USERNAME:profileid=~$otherProfile`}}</var> has Cancelled interest in you. Add a photo to make your profile better ' AND CONVERT(  `DESCRIPTION` USING utf8 ) =  'Cancel mailer without photo' LIMIT 1 ;

UPDATE  `MAILER_SUBJECT` SET  `SUBJECT_CODE` =  '<var>{{NAME_PROFILE:profileid=~$otherProfile`}}</var> has Cancelled interest in you. Please make your photo visible' WHERE  `MAIL_ID` =  '1758' AND CONVERT(  `SUBJECT_TYPE` USING utf8 ) =  'A' AND CONVERT( `SUBJECT_CODE` USING utf8 ) =  '<var>{{USERNAME:profileid=~$otherProfile`}}</var> has Cancelled interest in you. Please make your photo visible' AND CONVERT(  `DESCRIPTION` USING utf8 ) =  'Cancel mailer with photo visible on accept' LIMIT 1 ;

UPDATE  `MAILER_SUBJECT` SET  `SUBJECT_CODE` =  '<var>{{NAME_PROFILE:profileid=~$otherProfileId`}}</var> has Expressed Interest in you' WHERE  `MAIL_ID` =  '1754' AND CONVERT(  `SUBJECT_TYPE` USING utf8 ) =  'D' AND CONVERT(  `SUBJECT_CODE` USING utf8 ) =  '<var>{{USERNAME:profileid=~$otherProfileId`}}</var> has Expressed Interest in you' AND CONVERT(  `DESCRIPTION` USING utf8 ) =  'For EOI Mailer' LIMIT 1 ;

UPDATE  `MAILER_SUBJECT` SET  `SUBJECT_CODE` =  'Member <var>{{NAME_PROFILE:profileid=~$otherProfileId`}}</var> has sent you a reminder.' WHERE  `MAIL_ID` =  '1756' AND CONVERT(  `SUBJECT_TYPE` USING utf8 ) =  'D' AND CONVERT(  `SUBJECT_CODE` USING utf8 ) =  'Member <var>{{USERNAME:profileid=~$otherProfileId`}}</var> has sent you a reminder.' AND CONVERT(  `DESCRIPTION` USING utf8 ) =  'Reminder subject' LIMIT 1 ;

