USE newjs;

INSERT INTO newjs.SMS_TYPE VALUES (182, 'VD1', 'D', 1, 'F', 'A', 'SINGLE', 0, 0, 'SERVICE', 'Y', 'Congrats! You are selected for upto {VD_DISCOUNT}% discount on Jeevansathi plans! LOGIN to get discount at {VD_URL} or call us at 18004196299 by {VD_END_DT}');

INSERT INTO newjs.SMS_TYPE VALUES (183, 'VD2', 'D', 1, 'F', 'A', 'SINGLE', 0, 0, 'SERVICE', 'Y', 'Congrats! You are selected for a flat {VD_DISCOUNT}% discount on all Jeevansathi plans! LOGIN to get discount at {VD_URL} or call us at 18004196299 by {VD_END_DT}');


USE billing;

CREATE TABLE `VARIABLE_DISCOUNT_SMS_LOG` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `STATUS` varchar(1) DEFAULT 'N',
  `ENTRY_DT` date NOT NULL DEFAULT '0000-00-00',
  `START_TIME` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `END_TIME` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `FLAT_COUNT` int(11) DEFAULT '0',
  `UPTO_COUNT` int(11) NOT NULL DEFAULT '0',
  `FREQUENCY` int(2) unsigned DEFAULT '0',
  `NO_OF_TIMES` int(2) unsigned DEFAULT '1',
  PRIMARY KEY (`ID`)
);