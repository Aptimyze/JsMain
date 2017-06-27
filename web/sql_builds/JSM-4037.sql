use newjs;
CREATE TABLE `CRITICAL_INFO_CHANGED` (
 `ID` int(11) NOT NULL AUTO_INCREMENT,
 `PROFILEID` int(11) NOT NULL,
 `EDITED_FIELDS` varchar(50) NOT NULL,
 `DATE` datetime NOT NULL,
 `SCREENED_STATUS` enum('N','Y') DEFAULT 'Y',
 PRIMARY KEY (`ID`),
 UNIQUE KEY `PROFILEID` (`PROFILEID`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1

CREATE TABLE `CRITICAL_INFO_CHANGED_DOCS` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `PROFILEID` int(11) NOT NULL,
  `DOCUMENT_PATH` varchar(255) NOT NULL,
  `SCREENED_STATUS` enum('N','Y','F') NOT NULL DEFAULT 'N',
  `UPLOADED_ON` datetime NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `PROFILEID` (`PROFILEID`)
) ENGINE=InnoDB;

CREATE TABLE `CRITICAL_INFO_DOC_ASSIGNED` (
 `PROFILEID` int(11) NOT NULL DEFAULT '0',
 `ASSIGNED_TO` varchar(50) DEFAULT NULL,
 `ALLOTED_TIME` datetime DEFAULT NULL,
 PRIMARY KEY (`PROFILEID`),
 UNIQUE KEY `DOCID` (`PROFILEID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `CRITICAL_INFO_DOC_SCREENED_LOG` (
 `PROFILEID` int(11) NOT NULL DEFAULT '0',
 `ASSIGNED_TO` varchar(50) DEFAULT NULL,
 `ALLOTED_TIME` datetime DEFAULT NULL,
 `SCREENED_STATUS` enum('N','Y','F') DEFAULT NULL,
 `DOCUMENT_PATH` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

use jeevansathi_mailer;
INSERT INTO `EMAIL_TYPE` ( `ID` , `MAIL_ID` , `TPL_LOCATION` , `HEADER_TPL` , `FOOTER_TPL` , `TEMPLATE_EX_LOCATION` , `MAIL_GROUP` , `CUSTOM_CRITERIA` , `SENDER_EMAILID` , `DESCRIPTION` , `MEMBERSHIP_TYPE` , `GENDER` , `PHOTO_PROFILE` , `REPLY_TO_ENABLED` , `FROM_NAME` , `REPLY_TO_ADDRESS` , `MAX_COUNT_TO_BE_SENT` , `REQUIRE_AUTOLOGIN` , `FTO_FLAG` , `PRE_HEADER` , `PARTIALS` )
VALUES (
'', '1851', 'criticalInfoChangeMail.tpl', NULL , 'revamp_footer.tpl', NULL , '47', '1', 'info@jeevansathi.com  ', '', NULL , NULL , NULL , NULL , 'Jeevansathi Info', NULL , NULL , NULL , NULL , 'Please add info@jeevansathi.com to your address book to ensure delivery of this mail into you inbox', ''
);
INSERT INTO `EMAIL_TYPE` ( `ID` , `MAIL_ID` , `TPL_LOCATION` , `HEADER_TPL` , `FOOTER_TPL` , `TEMPLATE_EX_LOCATION` , `MAIL_GROUP` , `CUSTOM_CRITERIA` , `SENDER_EMAILID` , `DESCRIPTION` , `MEMBERSHIP_TYPE` , `GENDER` , `PHOTO_PROFILE` , `REPLY_TO_ENABLED` , `FROM_NAME` , `REPLY_TO_ADDRESS` , `MAX_COUNT_TO_BE_SENT` , `REQUIRE_AUTOLOGIN` , `FTO_FLAG` , `PRE_HEADER` , `PARTIALS` )
VALUES (
'', '1852', 'criticalInfoDocUpload.tpl', NULL , 'revamp_footer.tpl', NULL , '47', '1', 'info@jeevansathi.com  ', '', NULL , NULL , NULL , NULL , 'Jeevansathi Info', NULL , NULL , NULL , NULL , 'Please add info@jeevansathi.com to your address book to ensure delivery of this mail into you inbox', ''
);

INSERT INTO EMAIL_TYPE (ID, MAIL_ID, TPL_LOCATION, HEADER_TPL, FOOTER_TPL, TEMPLATE_EX_LOCATION, MAIL_GROUP, CUSTOM_CRITERIA, SENDER_EMAILID, DESCRIPTION, MEMBERSHIP_TYPE, GENDER, PHOTO_PROFILE, REPLY_TO_ENABLED, FROM_NAME, REPLY_TO_ADDRESS, MAX_COUNT_TO_BE_SENT, REQUIRE_AUTOLOGIN, FTO_FLAG, PRE_HEADER, PARTIALS) VALUES (1874, 1853, 'criticalInfoDocUploadFailed.tpl', NULL, 'revamp_footer.tpl', NULL, 47, 1, 'info@jeevansathi.com  ', '', NULL, NULL, NULL, NULL, 'Jeevansathi Info', NULL, NULL, NULL, NULL, 'Please add info@jeevansathi.com to your address book to ensure delivery of this mail into you inbox', '');
INSERT INTO EMAIL_TYPE (ID, MAIL_ID, TPL_LOCATION, HEADER_TPL, FOOTER_TPL, TEMPLATE_EX_LOCATION, MAIL_GROUP, CUSTOM_CRITERIA, SENDER_EMAILID, DESCRIPTION, MEMBERSHIP_TYPE, GENDER, PHOTO_PROFILE, REPLY_TO_ENABLED, FROM_NAME, REPLY_TO_ADDRESS, MAX_COUNT_TO_BE_SENT, REQUIRE_AUTOLOGIN, FTO_FLAG, PRE_HEADER, PARTIALS) VALUES (1873, 1852, 'criticalInfoDocUploadSuccess.tpl', NULL, 'revamp_footer.tpl', NULL, 47, 1, 'info@jeevansathi.com  ', '', NULL, NULL, NULL, NULL, 'Jeevansathi Info', NULL, NULL, NULL, NULL, 'Please add info@jeevansathi.com to your address book to ensure delivery of this mail into you inbox', '');

INSERT INTO `MAILER_SUBJECT` VALUES (1853, 'D', 'Changes to Basic Details requested by you could not be made', 'Changes to Basic Details requested by you could not be made');
INSERT INTO `MAILER_SUBJECT` VALUES (1852, 'D', 'Changes to Basic Details requested by you have been made', 'Changes to Basic Details requested by you have been made');
INSERT INTO `MAILER_SUBJECT` VALUES (1851, 'D', '~$namePG` has changed ~$hisHerfieldList`', 'Critical info changed');
