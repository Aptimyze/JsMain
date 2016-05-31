use newjs;
INSERT INTO `SMS_TYPE` ( `ID` , `SMS_KEY` , `SMS_TYPE` , `PRIORITY` , `SUBSCRIPTION` , `GENDER` , `COUNT` , `TIME_CRITERIA` , `CUSTOM_CRITERIA` , `SMS_SUBSCRIPTION` , `STATUS` , `MESSAGE` )
VALUES (
NULL, 'REQUEST_CALLBACK', 'D', '1', 'F', 'A', 'SINGLE', '0', '0', 'SERVICE', 'Y', 'You have {ACCEPTANCE_COUNT} acceptances now! Contact them by upgrading to the best Jeevansathi plan for you. Request a call back: {RC_URL} or call 18004196299.'
);

USE billing;
CREATE TABLE `SMS_REQUEST_CALLBACK` ( `ID` int(11) NOT NULL AUTO_INCREMENT, `PROFILEID` int(11) NOT NULL, `SMS_DATE` date DEFAULT NULL, PRIMARY KEY (`ID`), KEY `PROFILEID` (`PROFILEID`)) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1