use MOBILE_API;

INSERT INTO MOBILE_API.`APP_NOTIFICATIONS` VALUES (48, 'UPGRADE_APP', 'You are missing on important features. Update the app now to access them.', 27, 'AND', 'Y', 'D', NULL, 0, 'SINGLE', 'Y', 86400, 'A', 'A', 'App Update Required', 'D');

CREATE TABLE MOBILE_API.`UPGRADE_APP_NOTIFICATION` ( `ID` int(11) NOT NULL AUTO_INCREMENT, `ANDROID_UPDATE_VERSION` smallint(6) NOT NULL, `CURRENT_ANDROID_MAX_VERSION` smallint(6) NOT NULL, `ENTRY_DT` datetime NOT NULL, PRIMARY KEY (`ID`)) ENGINE=InnoDB;
