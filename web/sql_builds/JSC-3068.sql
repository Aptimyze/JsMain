use billing;
ALTER TABLE billing.`EXCLUSIVE_FOLLOWUPS` ADD `MAILER` CHAR( 1 ) DEFAULT 'Y' NOT NULL ;

use jeevansathi_mailer;
INSERT INTO jeevansathi_mailer.`EMAIL_TYPE` VALUES (1885, 1885, 'weeklyFollowupStatus.tpl', NULL, 'revamp_footer.tpl', NULL, 27, 1, '~$senderEmail`', 'Weekly Followup Status', 'D', NULL, NULL, NULL, '~$senderName`', NULL, NULL, NULL, NULL, 'Please add ~$senderEmail` to your address book to ensure delivery of this mail into your inbox.', '');

use jeevansathi_mailer;
INSERT INTO jeevansathi_mailer.`MAILER_SUBJECT` VALUES (1885, 'D', 'Weekly Followup status Mail ~$date`', 'Weekly Followup status Mail');