use MOBILE_API;
CREATE TABLE `SCHEDULED_APP_NOTIFICATIONS` (
  `PROFILEID` int(12) NOT NULL,
  `NOTIFICATION_KEY` varchar(20) NOT NULL,
  `MESSAGE` varchar(200) NOT NULL,
  `LANDING_SCREEN` int(5) DEFAULT NULL,
  `OS_TYPE` varchar(3) NOT NULL,
  `PRIORITY` float NOT NULL,
  `COLLAPSE_STATUS` varchar(1) NOT NULL,
  `TTL` int(10) NOT NULL,
  `SCHEDULED_DATE` date NOT NULL,
  `SENT` varchar(1) NOT NULL,
  `TITLE` varchar(30) NOT NULL,
  `COUNT` int(11) NOT NULL,
  PRIMARY KEY (`PROFILEID`,`NOTIFICATION_KEY`,`SCHEDULED_DATE`)
);


CREATE TABLE `APP_NOTIFICATIONS` (
  `ID` int(5) NOT NULL,
  `NOTIFICATION_KEY` varchar(20) NOT NULL,
  `MESSAGE` varchar(200) NOT NULL,
  `LANDING_SCREEN` int(5) NOT NULL,
  `OS_TYPE` varchar(3) NOT NULL,
  `STATUS` varchar(1) NOT NULL,
  `FREQUENCY` varchar(3) NOT NULL,
  `TIME_CRITERIA` varchar(5) DEFAULT NULL,
  `PRIORITY` float NOT NULL,
  `COUNT` varchar(10) NOT NULL,
  `COLLAPSE_STATUS` varchar(1) DEFAULT NULL,
  `TTL` int(10) NOT NULL,
  `GENDER` varchar(1) NOT NULL,
  `SUBSCRIPTION` varchar(10) NOT NULL,
  `TITLE` varchar(30) NOT NULL,
  `PHOTO_URL` varchar(2) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


INSERT INTO `APP_NOTIFICATIONS` VALUES (1, 'MATCHALERT', '{USERNAME_OTHER_1}, {USERNAME_OTHER_2} and {MATCH_COUNT} more new members who match your criteria have joined Jeevansathi this week.', 3, 'ALL', 'Y', 'SUN', '60|15', 2, 'MUL', 'Y', 0, 'A', 'A', 'New Matches', 'D');
INSERT INTO `APP_NOTIFICATIONS` VALUES (2, 'ACCEPTANCE', '{USERNAME_OTHER_1} ({AGE_OTHER_1} yrs, {CASTE_OTHER_1}) from {CITY_RES_OTHER_1} has accepted your interest', 1, 'ALL', 'Y', 'I', '2', 0, 'SINGLE', 'Y', 1209600, 'A', 'A', 'Interest Accepted', 'O');
INSERT INTO `APP_NOTIFICATIONS` VALUES (3, 'PHOTO_REQUEST', '{USERNAME_OTHER_1} ({AGE_OTHER_1} yrs, {CASTE_OTHER_1}) from {CITY_RES_OTHER_1} has requested your photo', 2, 'ALL', 'Y', 'I', '1', 0, 'SINGLE', 'Y', 604800, 'A', 'A', 'Photo Request Received', 'O');
INSERT INTO `APP_NOTIFICATIONS` VALUES (4, 'EOI', '{USERNAME_OTHER_1} ({AGE_OTHER_1} yrs, {CASTE_OTHER_1}) from {CITY_RES_OTHER_1} has expressed interest in you', 1, 'ALL', 'Y', 'I', '3', 0, 'SINGLE', 'Y', 604800, 'A', 'A', 'Interest Received', 'O');
INSERT INTO `APP_NOTIFICATIONS` VALUES (5, 'PENDING_EOI', 'Respond to {USERNAME_OTHER_1}, {USERNAME_OTHER_2} and {EOI_COUNT} more members waiting for your response.', 4, 'ALL', 'Y', 'SUN', '15', 3, 'MUL', 'Y', 0, 'A', 'A', 'Pending Interests', 'D');
INSERT INTO `APP_NOTIFICATIONS` VALUES (6, 'MATCHALERT', '{USERNAME_OTHER_1}, {USERNAME_OTHER_2} who match your criteria have joined Jeevansathi this week.', 3, 'ALL', 'Y', 'SUN', '60|15', 2, 'DOUBLE', 'Y', 0, 'A', 'A', 'New Matches', 'D');
INSERT INTO `APP_NOTIFICATIONS` VALUES (7, 'MATCHALERT', '{USERNAME_OTHER_1} who match your criteria have joined Jeevansathi this week.', 3, 'ALL', 'Y', 'SUN', '60|15', 2, 'SINGLE', 'Y', 0, 'A', 'A', 'New Matches', 'D');
INSERT INTO `APP_NOTIFICATIONS` VALUES (8, 'PENDING_EOI', 'Respond to {USERNAME_OTHER_1}, {USERNAME_OTHER_2} members waiting for your response.', 4, 'ALL', 'Y', 'SUN', '15', 3, 'DOUBLE', 'Y', 0, 'A', 'A', 'Pending Interests', 'D');
INSERT INTO `APP_NOTIFICATIONS` VALUES (9, 'PENDING_EOI', 'Respond to {USERNAME_OTHER_1} who is waiting for your response.', 4, 'ALL', 'Y', 'SUN', '15', 3, 'SINGLE', 'Y', 0, 'A', 'A', 'Pending Interests', 'D');
INSERT INTO `APP_NOTIFICATIONS` VALUES (10, 'VISITOR', '{USERNAME_OTHER_1}, {USERNAME_OTHER_2} and {VISITOR_COUNT} more members visited your profile today', 5, 'ALL', 'Y', 'SUN', '15', 3, 'MUL', 'Y', 0, 'A', 'A', 'Recent Profile Visitors', 'D');
INSERT INTO `APP_NOTIFICATIONS` VALUES (11, 'VISITOR', '{USERNAME_OTHER_1}, {USERNAME_OTHER_2} visited your profile today', 5, 'ALL', 'Y', 'SUN', '15', 3, 'DOUBLE', 'Y', 0, 'A', 'A', 'Recent Profile Visitors', 'D');
INSERT INTO `APP_NOTIFICATIONS` VALUES (12, 'VISITOR', '{USERNAME_OTHER_1} visited your profile today', 5, 'ALL', 'Y', 'SUN', '15', 3, 'SINGLE', 'Y', 0, 'A', 'A', 'Recent Profile Visitors', 'D');


 
 
CREATE TABLE `NOTIFICATION_LOG` (
 `ID` int(12) NOT NULL AUTO_INCREMENT,
 `PROFILEID` int(12) NOT NULL,
 `FREQUENCY` char(3) DEFAULT NULL,
 `NOTIFICATION_KEY` varchar(20) DEFAULT NULL,
 `MESSAGE` text,
 `SEND_DATE` datetime DEFAULT NULL,
 `SENT` char(1) NOT NULL,
 `OS_TYPE` char(1) DEFAULT NULL,
 PRIMARY KEY (`ID`),
 KEY `PROFILEID` (`PROFILEID`),
 KEY `SEND_DATE` (`SEND_DATE`),
 KEY `NOTIFICATION_KEY` (`NOTIFICATION_KEY`)
);

CREATE TABLE `REGISTRATION_ID` (
 `REG_ID` varchar(255) NOT NULL DEFAULT '',
 `PROFILEID` int(11) DEFAULT NULL,
 `OS_TYPE` varchar(3) DEFAULT NULL,
 `NOTIFICATION_STATUS` varchar(1) DEFAULT NULL,
 `TIME` datetime NOT NULL,
 PRIMARY KEY (`REG_ID`),
 KEY `PROFILEID` (`PROFILEID`)
);
CREATE TABLE `GCM_RESPONSE_LOG` (
  `PROFILEID` int(12) NOT NULL,
  `REGISTRATION_ID` varchar(255) NOT NULL,
  `HTTP_STATUS_CODE` int(4) NOT NULL,
  `STATUS_MESSAGE` varchar(255) DEFAULT NULL,
  `DATE` datetime NOT NULL,
  `NOTIFICATION_KEY` varchar(20) NOT NULL,
  PRIMARY KEY (`PROFILEID`,`REGISTRATION_ID`,`DATE`)
);
