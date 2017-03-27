use billing;
INSERT INTO billing.`COMPONENTS` VALUES ('','J1', 'JS Boost - 1 months', '', 1, 0, 0, 'J', 'D', 0);
INSERT INTO billing.`COMPONENTS` VALUES ('','J2', 'JS Boost - 2 months', '', 2, 0, 0, 'J', 'D', 0);
INSERT INTO billing.`COMPONENTS` VALUES ('','J3', 'JS Boost - 3 months', '', 3, 0, 0, 'J', 'D', 0);
INSERT INTO billing.`COMPONENTS` VALUES ('','J4', 'JS Boost - 4 months', '', 4, 0, 0, 'J', 'D', 0);
INSERT INTO billing.`COMPONENTS` VALUES ('','J5', 'JS Boost - 5 months', '', 5, 0, 0, 'J', 'D', 0);
INSERT INTO billing.`COMPONENTS` VALUES ('','J6', 'JS Boost - 6 months', '', 6, 0, 0, 'J', 'D', 0);
INSERT INTO billing.`COMPONENTS` VALUES ('','J7', 'JS Boost - 7 months', '', 7, 0, 0, 'J', 'D', 0);
INSERT INTO billing.`COMPONENTS` VALUES ('','J8', 'JS Boost - 8 months', '', 8, 0, 0, 'J', 'D', 0);
INSERT INTO billing.`COMPONENTS` VALUES ('','J9', 'JS Boost - 9 months', '', 9, 0, 0, 'J', 'D', 0);
INSERT INTO billing.`COMPONENTS` VALUES ('','J11', 'JS Boost - 11 months', '', 11, 0, 0, 'J', 'D', 0);
INSERT INTO billing.`COMPONENTS` VALUES ('','J12', 'JS Boost - 12 months', '', 12, 0, 0, 'J', 'D', 0);
INSERT INTO billing.`COMPONENTS` VALUES ('','JL', 'JS Boost - Unlimited Months', '', 1188, 0, 0, 'J', 'D', 0);


INSERT INTO billing.`PACK_COMPONENTS` VALUES ('', 'PNCP2', 'J2');
INSERT INTO billing.`PACK_COMPONENTS` VALUES ('', 'PNCP3', 'J3');
INSERT INTO billing.`PACK_COMPONENTS` VALUES ('', 'PNCP4', 'J4');
INSERT INTO billing.`PACK_COMPONENTS` VALUES ('', 'PNCP5', 'J5');
INSERT INTO billing.`PACK_COMPONENTS` VALUES ('', 'PNCP6', 'J6');
INSERT INTO billing.`PACK_COMPONENTS` VALUES ('', 'PNCP8', 'J8');
INSERT INTO billing.`PACK_COMPONENTS` VALUES ('', 'PNCP9', 'J9');
INSERT INTO billing.`PACK_COMPONENTS` VALUES ('', 'PNCP12', 'J12');
INSERT INTO billing.`PACK_COMPONENTS` VALUES ('', 'PNCPL', 'JL');

INSERT INTO billing.`SERVICES` VALUES ('', 'J1', 'JS Boost - 1 months', '', 1, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 'N', 'J1', '', 'Y', 6, 'N', 'Y', 'Y', 'N', ' ');
INSERT INTO billing.`SERVICES` VALUES ('', 'J2', 'JS Boost - 2 months', '', 2, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 'N', 'J2', '', 'Y', 41, 'N', 'Y', 'Y', 'N', ' ');
INSERT INTO billing.`SERVICES` VALUES ('', 'J3', 'JS Boost - 3 months', '', 3, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 'N', 'J3', '', 'Y', 31, 'N', 'Y', 'Y', 'N', ' ');
INSERT INTO billing.`SERVICES` VALUES ('', 'J4', 'JS Boost - 4 months', '', 4, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 'N', 'J4', '', 'Y', 42, 'N', 'Y', 'Y', 'N', ' ');
INSERT INTO billing.`SERVICES` VALUES ('', 'J5', 'JS Boost - 5 months', '', 5, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 'N', 'J5', '', 'Y', 51, 'N', 'Y', 'Y', 'N', ' ');
INSERT INTO billing.`SERVICES` VALUES ('', 'J6', 'JS Boost - 6 months', '', 6, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 'N', 'J6', '', 'Y', 63, 'N', 'Y', 'Y', 'N', ' ');
INSERT INTO billing.`SERVICES` VALUES ('', 'J8', 'JS Boost - 8 months', '', 8, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 'N', 'J8', '', 'Y', 104, 'N', 'Y', 'Y', 'N', ' ');
INSERT INTO billing.`SERVICES` VALUES ('', 'J9', 'JS Boost - 9 months', '', 9, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 'N', 'J9', '', 'Y', 103, 'N', 'Y', 'Y', 'N', ' ');
INSERT INTO billing.`SERVICES` VALUES ('', 'J12', 'JS Boost - 12 months', '', 12, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 'N', 'J12', '', 'Y', 138, 'N', 'Y', 'Y', 'N', ' ');
INSERT INTO billing.`SERVICES` VALUES ('', 'JL', 'JS Boost - Unlimited Months', '', 1188, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 'N', 'JL', '', 'Y', 138, 'N', 'Y', 'Y', 'N', ' ');

DELETE FROM billing.`PACK_COMPONENTS` WHERE  `PACKID` LIKE '%NCP%' AND  `COMPID` LIKE '%T%';

DELETE FROM billing.`PACK_COMPONENTS` WHERE  `PACKID` LIKE '%NCP%' AND  `COMPID` LIKE '%R%'

UPDATE  billing.`SERVICES` SET  `COMPID` =  '' WHERE  `SERVICEID` LIKE '%X%'

UPDATE billing.`SERVICES` SET SHOW_ONLINE='N' WHERE SERVICEID IN ('T3','T6');

UPDATE billing.`SERVICES` SET SHOW_ONLINE='N' WHERE SERVICEID IN ('R3','R6');