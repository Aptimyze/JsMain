use MAIL;
CREATE TABLE `EXPIRING_MAILER` (
  `ID` int(10) NOT NULL AUTO_INCREMENT,
  `RECEIVER` int(11) DEFAULT NULL,
  `COUNTS` int(6) DEFAULT '0',
  `USERS` varchar(300) DEFAULT NULL,
  `SENT` char(1) DEFAULT NULL,
  `DATE` date DEFAULT '0000-00-00',
  UNIQUE KEY `ID` (`ID`),
  UNIQUE KEY `RECEIVER_2` (`RECEIVER`),
  KEY `RECEIVER` (`RECEIVER`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=latin1 AUTO_INCREMENT=7;

use jeevansathi_mailer;
INSERT INTO  `EMAIL_TYPE` (  `ID` ,  `MAIL_ID` ,  `TPL_LOCATION` ,  `HEADER_TPL` ,  `FOOTER_TPL` ,  `TEMPLATE_EX_LOCATION` ,  `MAIL_GROUP` ,  `CUSTOM_CRITERIA` ,  `SENDER_EMAILID` ,  `DESCRIPTION` ,  `MEMBERSHIP_TYPE` , `GENDER` ,  `PHOTO_PROFILE` ,  `REPLY_TO_ENABLED` ,  `FROM_NAME` ,  `REPLY_TO_ADDRESS` ,  `MAX_COUNT_TO_BE_SENT` ,  `REQUIRE_AUTOLOGIN` ,  `FTO_FLAG` ,  `PRE_HEADER` ,  `PARTIALS` ) 
VALUES (
'',  '1843',  'eoi_mailer_ei.tpl',  'eoi_header.tpl',  'eoi_footer.tpl', NULL ,  '5',  '1',  'contacts@jeevansathi.com', NULL ,  'D', NULL , NULL , NULL ,  'Jeevansathi Contacts', NULL , NULL ,  'Y', NULL , 'Please add contacts@jeevansathi.com> to your address book to ensure delivery of this mail into you inbox',  ''
);
INSERT INTO  `LINK_MAILERS` (  `LINKID` ,  `APP_SCREEN_ID` ,  `LINK_NAME` ,  `LINK_URL` ,  `OTHER_GET_PARAMS` ,  `REQUIRED_AUTOLOGIN` ,  `OUTER_LINK` ) 
VALUES (
'', NULL ,  'EOI_EXPIRING',  '/inbox/23/1', NULL ,  'Y',  'N'
), (
'', NULL , NULL , NULL , NULL , NULL ,  'N'
);
INSERT INTO  `MAILER_TEMPLATE_VARIABLES_MAP` (  `VARIABLE_NAME` ,  `VARIABLE_PROCESSING_CLASS` ,  `DESCRIPTION` ,  `MAX_LENGTH` ,  `MAX_LENGTH_SMS` ,  `DEFAULT_VALUE` ,  `TPL_FORMAT` ) 
VALUES (
'EOI_EXPIRING',  '3',  'Interest expiring response link',  '1000',  '1000',  'NA',  ''
);