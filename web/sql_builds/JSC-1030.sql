use incentive;

CREATE TABLE incentive.`SALES_PROCESS_WISE_TRACKING` (
 `ID` int(11) NOT NULL AUTO_INCREMENT,
 `DATE` date NOT NULL,
 `INBOUND_TELE` double NOT NULL,
 `CENTER_SALES` double NOT NULL,
 `FP_TELE` double NOT NULL,
 `CENTRAL_RENEW_TELE` double NOT NULL,
 `FIELD_SALES` double NOT NULL,
 `FRANCHISEE_SALES` double NOT NULL,
 `OUTBOUND_TELE` double NOT NULL,
 `UNASSISTED_SALES` double NOT NULL,
 PRIMARY KEY (`ID`),
 UNIQUE KEY `DATE` (`DATE`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

use MIS;
INSERT INTO  MIS.`MIS_MAINPAGE` (  `ID` ,  `NAME` ,  `MAIN_URL` ,  `JUMP_URL` ,  `PRIVILEGE` ,  `ACTIVE` ,  `PUBLIC` ) 
VALUES (
'',  'Sales process-wise tracking MIS',  '/operations.php/crmMis/salesProcessWiseTrackingMis?cid=$cid', NULL ,  'SLHDO+SLHD',  'Y',  ''
);