use newjs;
INSERT INTO `SMS_TYPE` VALUES ('', 'REQ_CRM_DEL_SELF', 'I', 2, 'A', 'A', 'SINGLE', 0, 0, 'SERVICE', 'Y', 'Dear {USERNAME}, to proceed with deleting your profile on Jeevansathi, click the following link:{LINK_DEL}');

INSERT INTO `SMS_TYPE` VALUES ('', 'REQ_CRM_DEL_OTHER', 'I', 2, 'A', 'A', 'SINGLE', 0, 0, 'SERVICE', 'Y', 'Someone on Jeevansathi has indicated that you are already married/engaged. If so, please click this link to delete your profile:{LINK_DEL}');

use MIS;
CREATE TABLE `REQUEST_DELETIONS_LOG` (
  `DATE` datetime NOT NULL,
  `CRM_USER` char(50) NOT NULL,
  `REPORTEE` int(11) NOT NULL,
  `REQUESTED_BY` char(15) NOT NULL,
  KEY `DATE` (`DATETIME`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

use jeevansathi_mailer;
INSERT INTO `EMAIL_TYPE` VALUES (1854, 1846, 'requestDeletionForUser.tpl', 'header.tpl', 'footer.tpl', 'NULL', 27, 1, 'info@jeevansathi.com', 'Mailer requesting User to delete its profile.', 'D', NULL, NULL, NULL, 'Jeevansathi Info', 'NULL', 0, 'Y', NULL, NULL, '');
