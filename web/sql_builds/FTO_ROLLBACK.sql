use jeevansathi_mailer;

INSERT INTO `EMAIL_TYPE` VALUES (1774, 1774, 'incomplete_mailer_no_fto.tpl', NULL, 'footer1.tpl', NULL, 3, 1, 'info@jeevansathi.com', 'This mailer will be fired to incomplete profiles.', 'D', NULL, NULL, NULL, 'Jeevansathi.com', '', 0, 'Y', 'IU,I', 'Complete your profile & contact profiles you like for free', '');

INSERT INTO `MAILER_SUBJECT` VALUES (1774, 'D', 'Complete your profile & contact profiles you like for free', NULL);

UPDATE `EMAIL_TYPE` SET `REPLY_TO_ENABLED` = NULL, `REPLY_TO_ADDRESS` = NULL, `MAX_COUNT_TO_BE_SENT` = NULL, `FTO_FLAG` = 'C1,C2,C3,D1,D2,D3,D4' WHERE `ID` = '1765' LIMIT 1;
UPDATE `EMAIL_TYPE` SET `REPLY_TO_ENABLED` = NULL, `REPLY_TO_ADDRESS` = NULL, `MAX_COUNT_TO_BE_SENT` = NULL, `FTO_FLAG` = 'C1,C2,C3,D1,D2,D3,D4,IU' WHERE `ID` = '1766' LIMIT 1;
