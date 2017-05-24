use MAIL;
CREATE TABLE `UNRESPONDED_CONTACTS` (
 `ID` int(10) NOT NULL AUTO_INCREMENT,
 `RECEIVER` int(11) NOT NULL DEFAULT '0',
 `COUNTS` int(6) DEFAULT '0',
 `USERS` varchar(300) DEFAULT NULL,
 `SENT` char(1) DEFAULT NULL,
 `DATE` date DEFAULT '0000-00-00',
 PRIMARY KEY (`RECEIVER`),
 UNIQUE KEY `ID` (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1

use jeevansathi_mailer;
INSERT INTO `EMAIL_TYPE` ( `ID` , `MAIL_ID` , `TPL_LOCATION` , `HEADER_TPL` , `FOOTER_TPL` , `TEMPLATE_EX_LOCATION` , `MAIL_GROUP` , `CUSTOM_CRITERIA` , `SENDER_EMAILID` , `DESCRIPTION` , `MEMBERSHIP_TYPE` , `GENDER` , `PHOTO_PROFILE` , `REPLY_TO_ENABLED` , `FROM_NAME` , `REPLY_TO_ADDRESS` , `MAX_COUNT_TO_BE_SENT` , `REQUIRE_AUTOLOGIN` , `FTO_FLAG` , `PRE_HEADER` , `PARTIALS` )
VALUES (
'', '1840', 'reminderMailer.tpl', NULL , NULL , NULL , '44', '1', 'contacts@jeevansathi.com', 'Mail for sending interests to recently pending interests. ', 'D', '', '', NULL , 'Jeevansathi Contacts', NULL , NULL , NULL , '', 'Please add contacts@jeevansathi.com to your address book to ensure delivery of this mail into your inbox', ''
);
