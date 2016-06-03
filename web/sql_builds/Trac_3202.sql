use jeevansathi_mailer;

INSERT INTO `EMAIL_TYPE` VALUES (1776, 1776, 'under_screening_KYC.tpl', NULL, NULL, NULL, 25, 1, 'register@jeevansathi.com', NULL, 'D', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '');

INSERT INTO `MAILER_SUBJECT` VALUES (1776, 'D', 'Your Relationship Executive will visit you for verification', 'screening mailer kyc ');

INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP` VALUES ('TOLL_NO_KYC', 1, 'toll free mobile number for kyc mailer', 13, 0, '+91-9971176314', '');

INSERT INTO  `MAILER_TEMPLATE_VARIABLES_MAP` (  `VARIABLE_NAME` ,  `VARIABLE_PROCESSING_CLASS` ,  `DESCRIPTION` ,  `MAX_LENGTH` ,  `MAX_LENGTH_SMS` ,  `DEFAULT_VALUE` ,  `TPL_FORMAT` ) 
VALUES ('KYC_PAGE',  '3',  'NA',  '1000',  '20',  'NA',  '');

INSERT INTO  `LINK_MAILERS` (  `LINKID` ,  `LINK_NAME` ,  `LINK_URL` ,  `OTHER_GET_PARAMS` ,  `REQUIRED_AUTOLOGIN` ,  `OUTER_LINK` ) 
VALUES ('', 'KYC_PAGE', '/static/agentinfo', 'source=M', 'Y', 'N');



