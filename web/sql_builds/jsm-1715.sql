use REGISTER;
CREATE TABLE `REGISTRATION_QUALITY` (
  `REG_DATE` date DEFAULT NULL,
  `SOURCEID` varchar(50) DEFAULT NULL,
  `TOTAL_REG` mediumint(9) DEFAULT NULL,
  `F22` mediumint(9) DEFAULT NULL COMMENT '		',
  `F22MV` mediumint(9) DEFAULT NULL,
  `F22MVCC` mediumint(9) DEFAULT NULL,
  `M26` mediumint(9) DEFAULT NULL,
  `M26MV` mediumint(9) DEFAULT NULL,
  `M26MVCC` mediumint(9) DEFAULT NULL,
  `SCREENED_SIC` mediumint(9) DEFAULT NULL,
  UNIQUE KEY `DATE_SOURCE_UNIQUE` (`REG_DATE`,`SOURCEID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

use MIS;
INSERT INTO `MIS_MAINPAGE` ( `ID` , `NAME` , `MAIN_URL` , `JUMP_URL` , `PRIVILEGE` , `ACTIVE` , `PUBLIC` )
VALUES (
'', 'Registration Quality MIS', '/operations.php/registerMis/qualityRegistration?cid=$cid', NULL , 'P+MG', 'Y', ''
);
