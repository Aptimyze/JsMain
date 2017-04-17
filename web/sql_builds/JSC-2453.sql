use MOBILE_API;
INSERT INTO  `APP_NOTIFICATIONS` (  `ID` ,  `NOTIFICATION_KEY` ,  `MESSAGE` ,  `LANDING_SCREEN` ,  `OS_TYPE` ,  `STATUS` ,  `FREQUENCY` ,  `TIME_CRITERIA` ,  `PRIORITY` ,  `COUNT` ,  `COLLAPSE_STATUS` ,  `TTL` ,  `GENDER` ,  `SUBSCRIPTION` ,  `TITLE` ,  `PHOTO_URL` ) VALUES ('48',  'UPGRADE_APP',  'You are missing on important features. Update the app now to access them.',  '27',  'AND',  'Y',  'D', NULL ,  '0',  'SINGLE',  'Y',  '86400',  'A',  'A',  'App Update Required',  'D');

CREATE TABLE `UPGRADE_APP_NOTIFICATION` ( `ID` int(11) NOT NULL AUTO_INCREMENT, `ANDROID_UPDATE_VERSION` smallint(6) NOT NULL, `CURRENT_ANDROID_MAX_VERSION` smallint(6) NOT NULL, `ENTRY_DT` datetime NOT NULL, PRIMARY KEY (`ID`)) ENGINE=InnoDB;
