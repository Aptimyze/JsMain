use REGISTER;

CREATE TABLE `TRACK_REUSAGE_EMAIL_DELETED` (
 `S_NO` int(10) unsigned NOT NULL AUTO_INCREMENT,
 `EMAIL` varchar(100) NOT NULL,
 `CHANNEL` enum('Desktop','MS','NewMS','Android','Ios','Offline') DEFAULT NULL,
 `TIME` datetime DEFAULT NULL,
 PRIMARY KEY (`S_NO`)
);

