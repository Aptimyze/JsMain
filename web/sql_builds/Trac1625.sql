use jeevansathi_mailer;

INSERT INTO `MAILER_TEMPLATE_VARIABLES_MAP` VALUES ('PROFILE_DELETION_URL', 3, 'Profile hide/deletion url', 1000, 20, 'NA', '');

INSERT INTO `LINK_MAILERS` VALUES (31, 'PROFILE_DELETION_URL', 'profile/hide_delete_revamp.php', NULL, 'Y', 'N');

INSERT INTO `MAILER_SUBJECT` VALUES (1765, 'D', 'Welcome to Jeevansathi', 'Underscreening mailer subject');
INSERT INTO `MAILER_SUBJECT` VALUES (1766, 'D', 'Welcome to Jeevansathi', 'Underscreening mailer subject');

INSERT INTO `EMAIL_TYPE` VALUES (1765, 1765, 'UnderScreeningMailOthers.tpl', 'UnderScreeningMailOthersHeader.tpl', 'UnderScreeningMailOthersFooter.tpl', 'unknown', 16, 1, 'info@jeevansathi.com', 'The new email is to be sent only if the time of profile submitting registration page 2 i.e. the time when it moves to screening queue is before 8am on that day OR is after 7pm on that day.', 'D', '', '', NULL, 'Jeevansathi.com', NULL, NULL, 'Y', 'IU,C1,C2,C3,D1,D2,D3,D4', 'Your profile is being screened', '');
INSERT INTO `EMAIL_TYPE` VALUES (1766, 1766, 'UnderScreeningMailEFG.tpl', 'UnderScreeningMailEFGHeader.tpl', 'UnderScreeningMailEFGFooter.tpl', 'unknown', 16, 1, 'info@jeevansathi.com', 'The new email is to be sent only if the time of profile submitting registration page 2 i.e. the time when it moves to screening queue is before 8am on that day OR is after 7pm on that day.', 'D', '', '', NULL, 'Jeevansathi.com', NULL, NULL, 'Y', 'E1,E2,E3,E4,E5,F,G', 'Your profile is being screened', '');

