use jeevansathi_mailer;


INSERT INTO `MAILER_SUBJECT` ( `MAIL_ID` , `SUBJECT_TYPE` , `SUBJECT_CODE` , `DESCRIPTION` )
VALUES (
'1788', 'D', 'Important Information for you', 'Fraud Alert'
);

INSERT INTO `EMAIL_TYPE` VALUES ( 
1788, 1788, 'fraudAlert.tpl', 'top8_fraud_header.tpl', 'revamp_footer.tpl', NULL, 27, 1, 'info@jeevansathi.com', 'fraud alert mailer', 'D', NULL, NULL, NULL, 'Jeevansathi Info', NULL, NULL, NULL, NULL, NULL, '');

INSERT INTO `MAILER_SUBJECT` ( `MAIL_ID` , `SUBJECT_TYPE` , `SUBJECT_CODE` , `DESCRIPTION` )
VALUES (
'1790', 'D', 'Update regarding your request to call ~$pog_id` as a part of your intro call service.', 'AP Intro calls comments'
);

INSERT INTO `EMAIL_TYPE` VALUES ( 
1790, 1790, 'apCommentsMailer.tpl', 'top8_fraud_header.tpl', 'revamp_footer.tpl', NULL, 27, 1, 'contacts@jeevansathi.com', 'mailer for comments added in AP Intro Calls.', 'D', NULL, NULL, NULL, 'Jeevansathi Alerts', NULL, NULL, NULL, NULL, NULL, '');