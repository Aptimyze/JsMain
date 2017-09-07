use feedback;
CREATE INDEX REPORTEE_IDX ON REPORT_ABUSE_LOG (REPORTEE);

use `jeevansathi_mailer`;

INSERT INTO `EMAIL_TYPE` VALUES (1883, 1883, 'abuseActionSuccess.tpl', NULL, 'revamp_footer.tpl', NULL, 48, 1, 'info@jeevansathi.com', 'On User deleted using negative treatment ,All activated users who reported abuse against the said user in last 15 days.', 'D', NULL, NULL, NULL, 'Jeevansathi Info', NULL, NULL, 'Y', NULL, 'Please add info@jeevansathi.com to your address book to ensure delivery of this mail into you inbox', '');
INSERT INTO `MAILER_SUBJECT` VALUES (1883, 'D', 'Profile ~$deletedUserName` has been deleted after you reported abuse', 'On User deleted using negative treatment ,All activated users who reported abuse against the said user in last 15 days');        