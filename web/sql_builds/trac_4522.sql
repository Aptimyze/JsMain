use jeevansathi_mailer;


INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP` VALUES ('EOI_RECEIVIED', 3, 'People awaiting response link', 1000, 1000, 'NA', '');

INSERT INTO `LINK_MAILERS` VALUES (40,  'EOI_RECEIVIED', 'profile/contacts_made_received.php', 'page=eoi&filter=R', 'Y', 'N');
INSERT INTO  `MAILER_TEMPLATE_VARIABLES_MAP` (  `VARIABLE_NAME` ,  `VARIABLE_PROCESSING_CLASS` ,  `DESCRIPTION` ,  `MAX_LENGTH` ,  `MAX_LENGTH_SMS` ,  `DEFAULT_VALUE` ,  `TPL_FORMAT` ) 
VALUES (
'PAIDSTATUS',  '2',  'paid Status ',  '50',  '50',  'NA',  ''
);


UPDATE  `MAILER_SUBJECT` SET  `SUBJECT_CODE` =  '<var>{{USERNAME:profileid=~$otherProfile`}}</var>  declined your interest' WHERE  `MAIL_ID` =  '1748';
UPDATE  `MAILER_SUBJECT` SET  `SUBJECT_CODE` =  '<var>{{USERNAME:profileid=~$otherProfile`}}</var> has Accepted your interest' WHERE  `MAIL_ID` =  '1742';
UPDATE  `MAILER_SUBJECT` SET  `SUBJECT_CODE` =  '<var>{{USERNAME:profileid=~$otherProfileId`}}</var> has Expressed Interest in you' WHERE  `MAIL_ID` =  '1754';
UPDATE  `MAILER_SUBJECT` SET  `SUBJECT_CODE` =  ' ~$count` Members Expressed interest in you' WHERE  `MAIL_ID` =  '1767';



UPDATE  `EMAIL_TYPE` SET
`FROM_NAME` =  'Jeevansathi Contacts'
WHERE  `ID` IN  ('1748','1756','1767','1742','1754');

INSERT INTO  `MAILER_TEMPLATE_VARIABLES_MAP` (  `VARIABLE_NAME` ,  `VARIABLE_PROCESSING_CLASS` ,  `DESCRIPTION` ,  `MAX_LENGTH` ,  `MAX_LENGTH_SMS` ,  `DEFAULT_VALUE` ,  `TPL_FORMAT` ) 
VALUES (
'ABOUTPROFILE',  '2',  'About Profile',  '100',  '0',  'NA',  ''
);


